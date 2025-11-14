<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::withCount(['floors', 'rooms'])->get();

        // Calculate AP stats for each building
        foreach ($buildings as $building) {
            $building->load('floors.rooms.accessPoints');
        }

        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        $existingBuildings = Building::all();

        return view('admin.buildings.create', [
            'existingBuildings' => $existingBuildings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_floors' => 'required|integer|min:1|max:20',
            'shape_type' => 'required|in:rectangle,square,l_shape,u_shape,custom',
            'svg_path' => 'nullable|string',
            'width' => 'required|integer|min:50|max:500',
            'height' => 'required|integer|min:50|max:500',
            'position_x' => 'required|integer|min:0|max:1200',
            'position_y' => 'required|integer|min:0|max:600',
            'color' => 'nullable|string',
        ]);

        $validated['color'] = $validated['color'] ?? '#5eead4';

        $building = Building::create($validated);

        // Create floors automatically
        for ($i = 1; $i <= $building->total_floors; $i++) {
            Floor::create([
                'building_id' => $building->id,
                'floor_number' => $i,
                'name' => "Lantai {$i}",
            ]);
        }

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil ditambahkan!');
    }

    public function show(Building $building)
    {
        $building->load(['floors.rooms.accessPoints']);
        return view('admin.buildings.show', compact('building'));
    }

    public function edit(Building $building)
    {
        $existingBuildings = Building::where('id', '!=', $building->id)->get();

        return view('admin.buildings.edit', [
            'building' => $building,
            'existingBuildings' => $existingBuildings
        ]);
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_floors' => 'required|integer|min:1|max:20',
            'shape_type' => 'required|in:rectangle,square,l_shape,u_shape,custom',
            'rotation' => 'nullable|integer|in:0,90,180,270',
            'svg_path' => 'nullable|string',
            'width' => 'required|integer|min:50|max:500',
            'height' => 'required|integer|min:50|max:500',
            'position_x' => 'required|integer|min:0|max:1200',
            'position_y' => 'required|integer|min:0|max:600',
            'color' => 'nullable|string',
        ]);

        $oldFloors = $building->total_floors;
        $building->update($validated);

        // Adjust floors if needed
        if ($validated['total_floors'] > $oldFloors) {
            for ($i = $oldFloors + 1; $i <= $validated['total_floors']; $i++) {
                Floor::create([
                    'building_id' => $building->id,
                    'floor_number' => $i,
                    'name' => "Lantai {$i}",
                ]);
            }
        } elseif ($validated['total_floors'] < $oldFloors) {
            Floor::where('building_id', $building->id)
                ->where('floor_number', '>', $validated['total_floors'])
                ->delete();
        }

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil diupdate!');
    }

    public function destroy(Building $building)
    {
        $building->delete();

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil dihapus!');
    }

    public function preview(Request $request, Building $building = null)
    {
        // For AJAX preview
        $data = $request->all();

        $tempBuilding = new Building($data);
        $path = $tempBuilding->generateSvgPath();

        return response()->json([
            'svg_path' => $path,
            'position_x' => $data['position_x'],
            'position_y' => $data['position_y'],
            'width' => $data['width'],
            'height' => $data['height'],
            'color' => $data['color'] ?? '#5eead4',
        ]);
    }

    public function getFloors(Building $building)
    {
        return response()->json(
            $building->floors()
                ->orderBy('floor_number')
                ->get()
                ->map(function ($floor) {
                    return [
                        'id' => $floor->id,
                        'display_name' => $floor->display_name,
                    ];
                })
        );
    }
}
