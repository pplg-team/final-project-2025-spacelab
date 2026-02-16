<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassHistory;
use App\Models\TimetableEntry;
use App\Models\AttendanceRecord;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SearchStudentController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->query('q', ''));
        $results = collect();

        if (strlen($query) < 2) {
            return view('pages.search-student.index', compact('query', 'results'));
        }

        $today = Carbon::today();
        $now = Carbon::now();
        $dayOfWeek = $today->dayOfWeekIso;

        // 1️⃣ Active term
        $activeTerm = Term::where('is_active', true)->first();

        // 2️⃣ Search students (proper grouping)
        $students = Student::with('user')
            ->where(function ($q) use ($query) {
                $q->whereHas('user', function ($q2) use ($query) {
                    $q2->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('nis', 'LIKE', "%{$query}%")
                ->orWhere('nisn', 'LIKE', "%{$query}%");
            })
            ->limit(20)
            ->get();

        if ($students->isEmpty()) {
            return view('pages.search-student.index', compact('query', 'results'));
        }

        $studentIds = $students->pluck('id');
        $userIds = $students->pluck('user.id')->filter();

        // 3️⃣ Get class histories in ONE query
        $classHistories = ClassHistory::whereIn('student_id', $studentIds)
            ->when($activeTerm, fn($q) => $q->where('terms_id', $activeTerm->id))
            ->with('classroom.major')
            ->latest()
            ->get()
            ->groupBy('student_id');

        // 4️⃣ Get all classes involved
        $classIds = $classHistories
            ->flatten()
            ->pluck('class_id')
            ->unique()
            ->values();

        // 5️⃣ Get ongoing timetable entries in ONE query
        $ongoingEntries = collect();

        if ($classIds->isNotEmpty()) {
            $ongoingEntries = TimetableEntry::where('day_of_week', $dayOfWeek)
                ->whereHas('template', function ($q) use ($classIds) {
                    $q->whereIn('class_id', $classIds)
                      ->where('is_active', true);
                })
                ->whereHas('period', function ($q) use ($now) {
                    $q->whereTime('start_time', '<=', $now)
                      ->whereTime('end_time', '>=', $now);
                })
                ->with([
                    'period',
                    'teacherSubject.teacher.user',
                    'teacherSubject.subject',
                    'roomHistory.room.building',
                    'template'
                ])
                ->get()
                ->keyBy(fn($entry) => $entry->template->class_id);
        }

        // 6️⃣ Attendance in ONE query
        $attendanceUserIds = AttendanceRecord::whereIn('user_id', $userIds)
            ->whereDate('scanned_at', $today)
            ->whereIn('status', ['hadir', 'telat'])
            ->pluck('user_id')
            ->toArray();

        // 7️⃣ Build result in memory (no query inside loop)
        foreach ($students as $student) {

            $classHistory = $classHistories[$student->id]->first() ?? null;
            $classroom = $classHistory?->classroom;

            $currentEntry = null;
            $currentRoom = null;
            $currentSubject = null;
            $currentTeacher = null;

            if ($classHistory) {
                $currentEntry = $ongoingEntries[$classHistory->class_id] ?? null;

                if ($currentEntry) {
                    $currentRoom = $currentEntry->roomHistory?->room;
                    $currentSubject = $currentEntry->teacherSubject?->subject;
                    $currentTeacher = $currentEntry->teacherSubject?->teacher;
                }
            }

            $results->push([
                'student' => $student,
                'user' => $student->user,
                'classroom' => $classroom,
                'currentEntry' => $currentEntry,
                'currentRoom' => $currentRoom,
                'currentSubject' => $currentSubject,
                'currentTeacher' => $currentTeacher,
                'attendanceStatus' => in_array($student->user?->id, $attendanceUserIds)
                    ? 'hadir'
                    : 'belum',
            ]);
        }

        return view('pages.search-student.index', compact('query', 'results'));
    }
}
