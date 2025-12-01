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
        // Superadmin only
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

        return view('tickets.create', compact('accessPoint'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'access_point_id' => 'required|exists:access_points,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $accessPoint = AccessPoint::findOrFail($request->access_point_id);

        if ($accessPoint->status === 'maintenance') {
            return redirect()->back()
                ->withErrors(['access_point_id' => 'Access Point ini sedang dalam maintenance. Tidak dapat membuat ticket baru.'])
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
        $accessPoint->update(['status' => 'maintenance']);

        // Load relationships for notification
        $ticket->load(['accessPoint.room.floor.building', 'admin']);

        // Create notification for all superadmins
        $superadmins = User::where('role', 'superadmin')->get();
        foreach ($superadmins as $superadmin) {
            Notification::createForTicket($ticket, 'new_ticket', $superadmin);
        }

        //  SEND EMAIL NOTIFICATION TO SUPERADMINS
        try {
            foreach ($superadmins as $superadmin) {
                Mail::to($superadmin->email)->send(new TicketCreatedMail($ticket));
            }

            Log::info('Email notification sent for ticket', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'recipients' => $superadmins->pluck('email')->toArray()
            ]);
        } catch (\Exception $e) {
            // Log error tapi jangan fail ticket creation
            Log::error('Failed to send email notification', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Opsional: Kirim flash message ke user
            session()->flash('warning', 'Ticket berhasil dibuat, tapi notifikasi email gagal dikirim.');
        }

        try {
            $telegramNotification = new TelegramTicketNotification($ticket, 'created');
            $telegramNotification->toTelegram(null);

            Log::info('Telegram notification sent for new ticket', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket berhasil dibuat dengan nomor: ' . $ticket->ticket_number);
    }

    public function show(Ticket $ticket)
    {
        // Check access
        if (!auth()->user()->isSuperAdmin() && $ticket->admin_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);

        return view('tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Superadmin only
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

        if (in_array($request->status, ['resolved', 'closed'])) {
            $accessPoint = $ticket->accessPoint;

            if (!$accessPoint->hasOpenTicket()) {
                $accessPoint->update(['status' => 'active']);

                Log::info('Access Point status updated to active via updateStatus', [
                    'ap_id' => $accessPoint->id,
                    'ap_name' => $accessPoint->name,
                    'ticket_id' => $ticket->id,
                    'new_ticket_status' => $request->status
                ]);
            } else {
                Log::info('Access Point still has open tickets via updateStatus', [
                    'ap_id' => $accessPoint->id,
                    'open_tickets_count' => $accessPoint->openTickets()->count()
                ]);
            }
        }

        // Notify ticket creator about status change
        if ($oldStatus !== $request->status) {
            // In-app notification
            Notification::createForTicket($ticket, 'status_changed', $ticket->admin);

            //  EMAIL NOTIFICATION
            try {
                $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);
                Mail::to($ticket->admin->email)->send(new \App\Mail\TicketStatusUpdatedMail($ticket, $oldStatus));

                Log::info('Status update email sent', [
                    'ticket_id' => $ticket->id,
                    'old_status' => $oldStatus,
                    'new_status' => $ticket->status,
                    'recipient' => $ticket->admin->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send status update email', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }

            try {
                $telegramNotification = new TelegramTicketNotification($ticket, 'status_changed', $oldStatus);
                $telegramNotification->toTelegram(null);

                Log::info('Telegram status update sent', [
                    'ticket_id' => $ticket->id,
                    'old_status' => $oldStatus,
                    'new_status' => $ticket->status
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send Telegram status update', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
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

            Log::info('Access Point status updated to active', [
                'ap_id' => $accessPoint->id,
                'ap_name' => $accessPoint->name,
                'ticket_id' => $ticket->id
            ]);
        } else {
            Log::info('Access Point still has open tickets', [
                'ap_id' => $accessPoint->id,
                'open_tickets_count' => $accessPoint->openTickets()->count()
            ]);
        }

        // In-app notification
        Notification::createForTicket($ticket, 'ticket_resolved', $ticket->admin);

        //  EMAIL NOTIFICATION
        try {
            $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);
            Mail::to($ticket->admin->email)->send(new \App\Mail\TicketStatusUpdatedMail($ticket, $oldStatus));

            Log::info('Ticket resolved email sent', [
                'ticket_id' => $ticket->id,
                'recipient' => $ticket->admin->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send resolved email', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }

        try {
            $telegramNotification = new TelegramTicketNotification($ticket, 'status_changed', $oldStatus);
            $telegramNotification->toTelegram(null);

            Log::info('Telegram status update sent', [
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => $ticket->status
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram status update', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()
            ->with('success', 'Ticket berhasil diselesaikan.');
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

            Log::info('Access Point status updated to active', [
                'ap_id' => $accessPoint->id,
                'ap_name' => $accessPoint->name,
                'ticket_id' => $ticket->id
            ]);
        } else {
            Log::info('Access Point still has open tickets', [
                'ap_id' => $accessPoint->id,
                'open_tickets_count' => $accessPoint->openTickets()->count()
            ]);
        }

        // in app notification
        Notification::createForTicket($ticket, 'ticket_closed', $ticket->admin);

        // email notification
        try {
            $ticket->load(['accessPoint.room.floor.building', 'admin', 'resolver']);
            Mail::to($ticket->admin->email)->send(new \App\Mail\TicketStatusUpdatedMail($ticket, $oldStatus));

            Log::info('Ticket closed email sent', [
                'ticket_id' => $ticket->id,
                'recipient' => $ticket->admin->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send closed email', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }

        try {
            $telegramNotification = new TelegramTicketNotification($ticket, 'status_changed', $oldStatus);
            $telegramNotification->toTelegram(null);

            Log::info('Telegram status update sent', [
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => $ticket->status
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram status update', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()
            ->with('success', 'Ticket berhasil ditutup.');
    }
}
