<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Floor $floor)
    {
        $floor->load(['building', 'rooms.accessPoints']);

        return view('admin.rooms.index', compact('floor'));
    }

    public function create(Floor $floor)
    {
        $floor->load(['building','rooms']);

        return view('admin.rooms.create', [
            'floor' => $floor,
            'existingRooms' => $floor->rooms
        ]);
    }

    public function store(Request $request, Floor $floor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shape_type' => 'required|in:rectangle,square,l_shape,u_shape,custom',
            'svg_path' => 'nullable|string',
            'width' => 'required|integer|min:50|max:500',
            'height' => 'required|integer|min:50|max:500',
            'position_x' => 'required|integer|min:0|max:1000',
            'position_y' => 'required|integer|min:0|max:600',
            'color' => 'nullable|string',
        ]);

        $validated['floor_id'] = $floor->id;
        $validated['color'] = $validated['color'] ?? '#bfdbfe';

        Room::create($validated);

        return redirect()->route('admin.floors.rooms.index', $floor)
            ->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function edit(Room $room)
    {
        $room->load('floor.building');

        $existingRooms = $room->floor->rooms()->where('id', '!=', $room->id)->get();

        return view('admin.rooms.edit', [
            'room' => $room,
            'existingRooms' => $existingRooms
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shape_type' => 'required|in:rectangle,square,l_shape,u_shape,custom',
            'svg_path' => 'nullable|string',
            'width' => 'required|integer|min:50|max:500',
            'height' => 'required|integer|min:50|max:500',
            'position_x' => 'required|integer|min:0|max:1000',
            'position_y' => 'required|integer|min:0|max:600',
            'color' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('admin.floors.rooms.index', $room->floor)
            ->with('success', 'Ruangan berhasil diupdate!');
    }

    public function destroy(Room $room)
    {
        $floor = $room->floor;
        $room->delete();

        return redirect()->route('admin.floors.rooms.index', $floor)
            ->with('success', 'Ruangan berhasil dihapus!');
    }

    public function indexAll(Request $request)
    {
        $query = Room::with(['floor.building', 'accessPoints']);

        // Filter by building
        if ($request->filled('building_id')) {
            $query->whereHas('floor', function ($q) use ($request) {
                $q->where('building_id', $request->building_id);
            });
        }

        // Filter by floor
        if ($request->filled('floor_id')) {
            $query->where('floor_id', $request->floor_id);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $rooms = $query->orderBy('created_at', 'desc')->paginate(12);

        // Data untuk dropdown filter
        $buildings = \App\Models\Building::orderBy('name')->get();
        $floors = [];

        if ($request->filled('building_id')) {
            $floors = \App\Models\Floor::where('building_id', $request->building_id)
                ->orderBy('floor_number')
                ->get();
        }

        return view('admin.rooms.index-all', compact('rooms', 'buildings', 'floors'));
    }
}
