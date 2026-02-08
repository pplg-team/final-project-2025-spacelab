<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display a listing of buildings with their rooms.
     */
    public function index()
    {
        $buildings = Building::withCount('rooms')
            ->with(['rooms' => function ($query) {
                $query->orderBy('floor')->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.room.index', [
            'title' => 'Gedung & Ruangan',
            'description' => 'Kelola data gedung dan ruangan',
            'buildings' => $buildings,
            'roomTypes' => ['kelas', 'lab', 'aula', 'lainnya'],
        ]);
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:32|unique:rooms,code',
            'name' => 'required|string|max:128',
            'building_id' => 'required|uuid|exists:building,id',
            'floor' => 'nullable|integer|min:0',
            'capacity' => 'nullable|integer|min:0',
            'type' => 'required|in:kelas,lab,aula,lainnya',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Get room details for API/modal.
     */
    public function show(Room $room)
    {
        $room->load('building');

        return response()->json([
            'id' => $room->id,
            'code' => $room->code,
            'name' => $room->name,
            'building_id' => $room->building_id,
            'building_name' => $room->building?->name,
            'floor' => $room->floor,
            'capacity' => $room->capacity,
            'type' => $room->type,
            'is_active' => $room->is_active,
            'notes' => $room->notes,
        ]);
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:32', Rule::unique('rooms', 'code')->ignore($room->id)],
            'name' => 'required|string|max:128',
            'building_id' => 'required|uuid|exists:building,id',
            'floor' => 'nullable|integer|min:0',
            'capacity' => 'nullable|integer|min:0',
            'type' => 'required|in:kelas,lab,aula,lainnya',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * Remove the specified room.
     */
    public function destroy(Room $room)
    {
        // Check if room is being used in timetable
        if ($room->directTimetableEntries()->exists()) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Ruangan tidak dapat dihapus karena sedang digunakan dalam jadwal.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}
