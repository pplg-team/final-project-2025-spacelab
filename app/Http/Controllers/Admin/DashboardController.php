<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\TimetableEntry;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Room;
use App\Models\Subject;
use App\Models\AuditLog;
use App\Models\Term;
use App\Models\AttendanceSession;
use App\Services\QueryOptimizationService;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $dayOfWeek = $today->dayOfWeekIso;

        // Optimize: Select only needed columns and eager load with specific columns
        $entries = TimetableEntry::select('id', 'day_of_week', 'period_id', 'teacher_subject_id', 'room_history_id', 'template_id')
            ->with([
                'period:id,ordinal,start_time,end_time,is_teaching',
                'teacherSubject:id,teacher_id,subject_id',
                'teacherSubject.subject:id,name',
                'teacherSubject.teacher.user:id,name',
                'roomHistory.room:id,name',
                'template.class:id,name',
            ])
            ->where('day_of_week', $dayOfWeek)
            ->get();

        $upcoming = $entries->filter(function ($entry) use ($today) {
            return !$entry->isPast($today);
        })->sortBy(function ($entry) {
            return $entry->period?->ordinal ?? 0;
        })->values()->take(3);

        $totalToday = $entries->count();

        $todayEntries = $upcoming->map(function ($entry) {
            $period = $entry->period;
            $start = $period?->start_time;
            $end = $period?->end_time;

            $startFormatted = $start ? Carbon::createFromFormat('H:i:s', $start)->format('H:i') : null;
            $endFormatted = $end ? Carbon::createFromFormat('H:i:s', $end)->format('H:i') : null;

            $subject = $entry->teacherSubject?->subject?->name ?? null;
            $teacher = $entry->teacherSubject?->teacher?->user?->name ?? null;
            $className = $entry->template?->class?->name ?? null;
            $room = $entry->roomHistory?->room?->name ?? null;

            return [
                'start' => $startFormatted,
                'end' => $endFormatted,
                'subject' => $subject,
                'teacher' => $teacher,
                'class' => $className,
                'room' => $room,
                'ongoing' => $entry->isOngoing(),
            ];
        });

        // Optimize: Use single query with counts instead of 5 separate queries
        $stats = [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalClasses' => Classroom::count(),
            'totalRooms' => Room::count(),
            'totalSubjects' => Subject::count(),
        ];

        // Optimize: Select only needed columns
        $recentActivities = AuditLog::select('id', 'user_id', 'action', 'entity', 'created_at')
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($log) {
                $actionLabel = match ($log->action) {
                    'created' => 'ditambahkan',
                    'updated' => 'diperbarui',
                    'deleted' => 'dihapus',
                    default => $log->action,
                };

                $entityLabel = match ($log->entity) {
                    'Student' => 'Siswa',
                    'Teacher' => 'Guru',
                    'Classroom' => 'Kelas',
                    'TimetableEntry' => 'Jadwal',
                    default => $log->entity,
                };

                return [
                    'message' => $entityLabel . ' ' . $actionLabel,
                    'time' => $log->created_at,
                ];
            });

        // Optimize: Use cached query
        $activeTerm = QueryOptimizationService::getActiveTerm();
        $termLabel = $activeTerm ? $activeTerm->tahun_ajaran : 'Tidak ada semester aktif';
        $termPeriod = $activeTerm ? 'Periode: ' . $activeTerm->start_date->format('M d, Y') . ' - ' . $activeTerm->end_date->format('M d, Y') : '';

        // Optimize: Use exists() instead of checking if result exists
        $attendanceToday = AttendanceSession::select('id')
            ->whereDate('created_at', Carbon::today())
            ->exists();

        return view('admin.dashboard', [
            'todayEntries' => $todayEntries,
            'totalToday' => $totalToday,
            'totalStudents' => $stats['totalStudents'],
            'totalTeachers' => $stats['totalTeachers'],
            'totalClasses' => $stats['totalClasses'],
            'totalRooms' => $stats['totalRooms'],
            'totalSubjects' => $stats['totalSubjects'],
            'recentActivities' => $recentActivities,
            'activeTerm' => $activeTerm,
            'termLabel' => $termLabel,
            'termPeriod' => $termPeriod,
            'attendanceToday' => $attendanceToday,
            'title' => 'Dashboard',
            'description' => 'Halaman dashboard',
        ]);
    }
}
