<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\AccessPoint;
use App\Models\Ticket;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {

        $stats = [
            'total_ap' => AccessPoint::count(),
            'active_ap' => AccessPoint::where('status', 'active')->count(),
            'total_buildings' => Building::count(),
        ];

        return view('landing', compact('stats'));
    }

    public function checkTicket(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string|exists:tickets,ticket_number',
        ], [
            'ticket_number.exists' => 'Nomor tiket tidak ditemukan.',
        ]);

        $ticket = Ticket::where('ticket_number', $request->ticket_number)
            ->with(['accessPoint.room.floor.building']) 
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'ticket_number' => $ticket->ticket_number,
                'status' => $ticket->status, 
                'created_at' => $ticket->created_at->format('d M Y H:i'),
                'category' => $ticket->category,
                'location' => $ticket->accessPoint->room->floor->building->name ?? 'Lokasi tidak diketahui',
            ]
        ]);
    }
}
