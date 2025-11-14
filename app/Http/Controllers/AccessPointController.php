<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\AccessPoint;
use Illuminate\Http\Request;

class AccessPointController extends Controller
{
    public function index(Room $room)
    {
        $room->load(['floor.building', 'accessPoints.tickets']);

        return view('admin.access-points.index', compact('room'));
    }

    public function create(Room $room)
    {
        $room->load('floor.building');

        return view('admin.access-points.create', compact('room'));
    }

    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,offline,maintenance',
            'position_x' => 'required|integer|min:0|max:' . $room->width,
            'position_y' => 'required|integer|min:0|max:' . $room->height,
            'notes' => 'nullable|string',
        ], [
            'position_x.max' => "Posisi X tidak boleh lebih dari {$room->width} (Lebar Ruangan)",
            'position_y.max' => "Posisi Y tidak boleh lebih dari {$room->height} (Tinggi Ruangan)",
        ]);

        $validated['room_id'] = $room->id;

        AccessPoint::create($validated);

        return redirect()->route('admin.rooms.access-points.index', $room)
            ->with('success', 'Access Point berhasil ditambahkan!');
    }

    public function edit(AccessPoint $accessPoint)
    {
        $accessPoint->load('room.floor.building');

        return view('admin.access-points.edit', compact('accessPoint'));
    }

    public function update(Request $request, AccessPoint $accessPoint)
    {
        $room = $accessPoint->room;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,offline,maintenance',
            'position_x' => 'required|integer|min:0|max:' . $room->width,
            'position_y' => 'required|integer|min:0|max:' . $room->height,
            'notes' => 'nullable|string',
        ], [
            'position_x.max' => "Posisi X tidak boleh lebih dari {$room->width} (lebar ruangan)",
            'position_y.max' => "Posisi Y tidak boleh lebih dari {$room->height} (tinggi ruangan)",
        ]);

        $accessPoint->update($validated);

        return redirect()->route('admin.rooms.access-points.index', $accessPoint->room)
            ->with('success', 'Access Point berhasil diupdate!');
    }

    public function destroy(AccessPoint $accessPoint)
    {
        $room = $accessPoint->room;
        $accessPoint->delete();

        return redirect()->route('admin.rooms.access-points.index', $room)
            ->with('success', 'Access Point berhasil dihapus!');
    }

    public function updateStatus(Request $request, AccessPoint $accessPoint)
    {
        $request->validate([
            'status' => 'required|in:active,offline,maintenance',
        ]);

        $accessPoint->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status Access Point berhasil diubah!');
    }
}
