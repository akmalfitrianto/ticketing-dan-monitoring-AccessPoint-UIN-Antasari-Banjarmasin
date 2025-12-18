<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\AccessPoint;
use App\Models\Notification;
use App\Models\User;
use App\Mail\TicketCreatedMail;
use App\Notifications\TelegramTicketNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {

        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('tickets.my');
        }

        $tickets = Ticket::with(['accessPoint.room.floor.building', 'admin'])
            ->latest()
            ->paginate(20);

        return view('tickets.index', compact('tickets'));
    }

    public function myTickets()
    {
        $tickets = Ticket::with(['accessPoint.room.floor.building'])
            ->where('admin_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('tickets.my-tickets', compact('tickets'));
    }

    public function create(AccessPoint $accessPoint)
    {
        $accessPoint->load('room.floor.building');

        $building = $accessPoint->room->floor->building;

        if (!auth()->user()->hasAccessToBuilding($building->id)) {
            abort(403, 'Anda tidak memiliki akses untuk membuat tiket di gedung ini.');
        }

        return view('tickets.create', compact('accessPoint'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'access_point_id' => 'required|exists:access_points,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $accessPoint = AccessPoint::with('room.floor.building')->findOrFail($request->access_point_id);
        $building = $accessPoint->room->floor->building;

        if (!auth()->user()->hasAccessToBuilding($building->id)) {
            return redirect()->back()
                ->withErrors(['access_point_id' => 'Anda tidak memiliki akses untuk membuat tiket digedung ini.'])
                ->withInput();
        }

        $ticket = Ticket::create([
            'access_point_id' => $request->access_point_id,
            'admin_id' => auth()->id(),
            'category' => $request->category,
            'description' => $request->description,
            'status' => 'open',
        ]);

        $accessPoint = AccessPoint::find($request->access_point_id);
        $accessPoint->update(['status' => 'offline']);

        $ticket->load(['accessPoint.room.floor.building', 'admin']);

        $superadmins = User::where('role', 'superadmin')->get();
        foreach ($superadmins as $superadmin) {
            Notification::createForTicket($ticket, 'new_ticket', $superadmin);
        }

        try {
            foreach ($superadmins as $superadmin) {
                Mail::to($superadmin->email)->send(new TicketCreatedMail($ticket));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
            session()->flash('warning', 'Ticket berhasil dibuat, tapi notifikasi email gagal dikirim.');
        }

        try {
            $telegramNotification = new TelegramTicketNotification($ticket, 'created');
            $telegramNotification->toTelegram(null);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket berhasil dibuat. Status AP berubah menjadi Offline.');
    }

    public function show(Ticket $ticket)
    {
        if (!auth()->user()->isSuperAdmin() && $ticket->admin_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);

        return view('tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {

        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;

        if ($request->status === 'resolved') {
            $ticket->resolved_at = now();
            $ticket->resolved_by = auth()->id();
        }

        if ($request->status === 'closed') {
            $ticket->closed_at = now();
        }

        if ($request->filled('resolution_notes')) {
            $ticket->resolution_notes = $request->resolution_notes;
        }

        $ticket->save();

        $accessPoint = $ticket->accessPoint;

        if ($request->status === 'in_progress') {

            $accessPoint->update(['status' => 'maintenance']);

            Log::info('Access Point set to Maintenance because ticket is In Progress', ['ap_id' => $accessPoint->id]);
        } elseif ($request->status === 'open') {

            $accessPoint->update(['status' => 'offline']);

        } elseif (in_array($request->status, ['resolved', 'closed'])) {

            if (!$accessPoint->hasOpenTicket()) {
                $accessPoint->update(['status' => 'active']);

                Log::info('Access Point status updated to active via updateStatus', [
                    'ap_id' => $accessPoint->id,
                    'new_ticket_status' => $request->status
                ]);
            }
        }

        
        if ($oldStatus !== $request->status) {
            Notification::createForTicket($ticket, 'status_changed', $ticket->admin);

            try {
                $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);
                Mail::to($ticket->admin->email)->send(new \App\Mail\TicketStatusUpdatedMail($ticket, $oldStatus));
            } catch (\Exception $e) {
                Log::error('Failed to send status update email', ['error' => $e->getMessage()]);
            }

            try {
                $telegramNotification = new TelegramTicketNotification($ticket, 'status_changed', $oldStatus);
                $telegramNotification->toTelegram(null);
            } catch (\Exception $e) {
                Log::error('Failed to send Telegram status update', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->back()
            ->with('success', 'Status ticket berhasil diubah menjadi: ' . $ticket->status_label);
    }

    public function resolve(Request $request, Ticket $ticket)
    {
        $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'resolution_notes' => $request->resolution_notes,
        ]);

        $accessPoint = AccessPoint::find($ticket->access_point_id);

        if (!$accessPoint->hasOpenTicket()) {
            $accessPoint->update(['status' => 'active']); 

            Log::info('Access Point status updated to active via resolve', ['ap_id' => $accessPoint->id]);
        }

        // Notifications
        Notification::createForTicket($ticket, 'ticket_resolved', $ticket->admin);

        try {
            $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);
            Mail::to($ticket->admin->email)->send(new \App\Mail\TicketStatusUpdatedMail($ticket, $oldStatus));
        } catch (\Exception $e) {
            Log::error('Failed to send resolved email', ['error' => $e->getMessage()]);
        }

        try {
            $telegramNotification = new TelegramTicketNotification($ticket, 'status_changed', $oldStatus);
            $telegramNotification->toTelegram(null);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram status update', ['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Ticket berhasil diselesaikan.');
    }

    public function close(Ticket $ticket)
    {
        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $accessPoint = AccessPoint::find($ticket->access_point_id);

        if (!$accessPoint->hasOpenTicket()) {
            $accessPoint->update(['status' => 'active']);

            Log::info('Access Point status updated to active via close', ['ap_id' => $accessPoint->id]);
        }

        // Notifications
        Notification::createForTicket($ticket, 'ticket_closed', $ticket->admin);

        try {
            $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);
            Mail::to($ticket->admin->email)->send(new \App\Mail\TicketStatusUpdatedMail($ticket, $oldStatus));
        } catch (\Exception $e) {
            Log::error('Failed to send closed email', ['error' => $e->getMessage()]);
        }

        try {
            $telegramNotification = new TelegramTicketNotification($ticket, 'status_changed', $oldStatus);
            $telegramNotification->toTelegram(null);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram status update', ['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Ticket berhasil ditutup.');
    }
}
