<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => Auth::id(),
            'action' => 'create_building',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' membuat gedung baru pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

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

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => $building->id,
            'action' => 'update_building',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' memperbarui gedung pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Gedung berhasil diperbarui.');
    }

    /**
     * Remove the specified building.
     */
    public function destroy(Request $request, Building $building)
    {
        // Check if building has rooms
        if ($building->rooms()->exists()) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Gedung tidak dapat dihapus karena masih memiliki ruangan.');
        }

        $building->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => $building->id,
            'action' => 'delete_building',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' menghapus gedung pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Gedung berhasil dihapus.');
    }
}
