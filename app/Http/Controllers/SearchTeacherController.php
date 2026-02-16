<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TimetableEntry;
use App\Models\AttendanceSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SearchTeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->query('q', ''));
        $results = collect();

        if (strlen($query) >= 2) {
            $today = Carbon::today();
            $now = Carbon::now();
            $dayOfWeek = $today->dayOfWeekIso;

            // Search teachers by name or code
            $teachers = Teacher::with('user')
                ->whereHas('user', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('code', 'LIKE', "%{$query}%")
                ->limit(20)
                ->get();

            foreach ($teachers as $teacher) {
                if (!$teacher->user) continue;

                $result = [
                    'teacher' => $teacher,
                    'user' => $teacher->user,
                    'currentEntry' => null,
                    'currentRoom' => null,
                    'currentSubject' => null,
                    'currentClassroom' => null,
                    'attendanceStatus' => null,
                ];

                // Find today's ongoing timetable entry for this teacher
                $currentEntry = TimetableEntry::where('day_of_week', $dayOfWeek)
                    ->where(function ($q) use ($teacher) {
                        $q->where('teacher_id', $teacher->id)
                          ->orWhereHas('teacherSubject', function ($q2) use ($teacher) {
                              $q2->where('teacher_id', $teacher->id);
                          });
                    })
                    ->whereHas('template', function ($q) {
                        $q->where('is_active', true);
                    })
                    ->with([
                        'period',
                        'teacherSubject.subject',
                        'template.class.major',
                        'roomHistory.room.building',
                    ])
                    ->get()
                    ->first(function ($entry) use ($now) {
                        return $entry->isOngoing($now);
                    });

                if ($currentEntry) {
                    $result['currentEntry'] = $currentEntry;
                    $result['currentRoom'] = $currentEntry->roomHistory?->room;
                    $result['currentSubject'] = $currentEntry->teacherSubject?->subject;
                    $result['currentClassroom'] = $currentEntry->template?->class;
                }

                // Check if teacher has opened any attendance session today
                $hasSession = AttendanceSession::whereDate('created_at', $today)
                    ->where('user_id', $teacher->user->id)
                    ->exists();

                $result['attendanceStatus'] = $hasSession ? 'hadir' : 'belum';

                $results->push($result);
            }
        }

        return view('pages.search-teacher.index', compact('query', 'results'));
    }
}
