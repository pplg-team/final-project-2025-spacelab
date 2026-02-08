<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BuildingController extends Controller
{
    /**
     * Store a newly created building.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:32|unique:building,code',
            'description' => 'nullable|string',
            'total_floors' => 'required|integer|min:1',
        ]);

        Building::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Gedung berhasil ditambahkan.');
    }

    /**
     * Get building details for API/modal.
     */
    public function show(Building $building)
    {
        return response()->json([
            'id' => $building->id,
            'name' => $building->name,
            'code' => $building->code,
            'description' => $building->description,
            'total_floors' => $building->total_floors,
            'rooms_count' => $building->rooms()->count(),
        ]);
    }

    /**
     * Update the specified building.
     */
    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:32', Rule::unique('building', 'code')->ignore($building->id)],
            'description' => 'nullable|string',
            'total_floors' => 'required|integer|min:1',
        ]);

        $building->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Gedung berhasil diperbarui.');
    }

    /**
     * Remove the specified building.
     */
    public function destroy(Building $building)
    {
        // Check if building has rooms
        if ($building->rooms()->exists()) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Gedung tidak dapat dihapus karena masih memiliki ruangan.');
        }

        $building->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Gedung berhasil dihapus.');
    }
}
