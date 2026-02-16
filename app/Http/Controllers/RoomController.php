<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\TimetableEntry;
use App\Models\AttendanceSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeekIso; // 1=Monday ... 7=Sunday

        // Get all buildings with their rooms
        $buildingsQuery = Building::with(['rooms' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }])->orderBy('name');

        $buildings = $buildingsQuery->get();

        // Get current period's timetable entries for all rooms today
        $now = Carbon::now();

        $currentEntries = TimetableEntry::where('day_of_week', $dayOfWeek)
            ->whereHas('template', function ($q) {
                $q->where('is_active', true);
            })
            ->with([
                'period',
                'teacherSubject.teacher.user',
                'teacherSubject.subject',
                'template.class.major',
                'roomHistory.room',
            ])
            ->get()
            ->filter(function ($entry) use ($now) {
                return $entry->isOngoing($now);
            });

        // Map: room_id => current entry
        $roomUsageMap = [];
        foreach ($currentEntries as $entry) {
            $roomId = $entry->roomHistory?->room_id;
            if ($roomId) {
                $roomUsageMap[$roomId] = $entry;
            }
        }

        // Get filter values
        $selectedBuilding = $request->query('building');
        $selectedType = $request->query('type');
        $search = $request->query('search');

        // Get unique room types for filter
        $roomTypes = Room::where('is_active', true)
            ->whereNotNull('type')
            ->distinct()
            ->pluck('type')
            ->sort()
            ->values();

        return view('pages.rooms.index', compact(
            'buildings',
            'roomUsageMap',
            'selectedBuilding',
            'selectedType',
            'search',
            'roomTypes',
            'today',
            'dayOfWeek'
        ));
    }

    public function show(Room $room)
    {
        $room->load('building');

        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeekIso;
        $now = Carbon::now();

        // Get all today's timetable entries for this room
        $todayEntries = TimetableEntry::where('day_of_week', $dayOfWeek)
            ->whereHas('template', function ($q) {
                $q->where('is_active', true);
            })
            ->whereHas('roomHistory', function ($q) use ($room) {
                $q->where('room_id', $room->id);
            })
            ->with([
                'period',
                'teacherSubject.teacher.user',
                'teacherSubject.subject',
                'template.class.major',
            ])
            ->get()
            ->sortBy(function ($entry) {
                return $entry->period?->start_time ?? '99:99';
            })
            ->values();

        // Determine ongoing entry
        $ongoingEntry = $todayEntries->first(function ($entry) use ($now) {
            return $entry->isOngoing($now);
        });

        // Check for active attendance session in this room
        $activeSession = null;
        if ($ongoingEntry) {
            $activeSession = AttendanceSession::where('timetable_entry_id', $ongoingEntry->id)
                ->where('is_active', true)
                ->first();
        }

        $dayName = match ($dayOfWeek) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => "Jum'at",
            6 => 'Sabtu',
            7 => 'Minggu',
            default => 'Unknown',
        };

        return view('pages.rooms.show', compact(
            'room',
            'todayEntries',
            'ongoingEntry',
            'activeSession',
            'today',
            'dayName',
            'now'
        ));
    }
}
