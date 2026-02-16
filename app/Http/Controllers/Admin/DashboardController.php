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

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Ambil semua entri jadwal untuk hari ini beserta relasinya
        $today = Carbon::now();
        $dayOfWeek = $today->dayOfWeekIso; // 1 (Mon) - 7 (Sun)

        $entries = TimetableEntry::with([
            'period',
            'teacherSubject.subject',
            'teacherSubject.teacher.user',
            'teacher.user',
            'roomHistory.room',
            'template.class',
        ])
            ->where('day_of_week', $dayOfWeek)
            ->get();

        // Filter yang belum lewat (bisa berlangsung atau nanti), urutkan berdasarkan ordinal period, ambil 3 teratas
        $upcoming = $entries->filter(function ($entry) use ($today) {
            return ! $entry->isPast($today);
        })->sortBy(function ($entry) {
            return $entry->period?->ordinal ?? 0;
        })->values()->take(3);

        $totalToday = $entries->count();

        // Transform menjadi array ringkas untuk view supaya blade lebih sederhana
        $todayEntries = $upcoming->map(function ($entry) {
            $period = $entry->period;
            $start = $period?->start_time;
            $end = $period?->end_time;

            $startFormatted = $start ? Carbon::createFromFormat('H:i:s', $start)->format('H:i') : null;
            $endFormatted = $end ? Carbon::createFromFormat('H:i:s', $end)->format('H:i') : null;

            // Ambil subject dari teacher_subject relationship, atau fallback ke direct subject
            $subject = $entry->teacherSubject?->subject?->name ?? null;

            // Ambil teacher name dari teacher_subject.teacher.user, atau fallback ke teacher.user
            $teacher = $entry->teacherSubject?->teacher?->user?->name ??
                       $entry->teacher?->user?->name ?? null;

            // Ambil class name dari template.class
            $className = $entry->template?->class?->name ?? null;

            // Ambil room name via roomHistory
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

        // Hitung statistik dashboard
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = Classroom::count();
        $totalRooms = Room::count();
        $totalSubjects = Subject::count();

        // Ambil aktivitas terbaru dari audit log
        $recentActivities = AuditLog::with('user')
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

        // Ambil semester aktif
        $activeTerm = Term::where('is_active', true)->first();
        $termLabel = $activeTerm ? $activeTerm->tahun_ajaran : 'Tidak ada semester aktif';
        $termPeriod = $activeTerm ? 'Periode: ' . $activeTerm->start_date->format('M d, Y') . ' - ' . $activeTerm->end_date->format('M d, Y') : '';

        // Absensi hari ini
        // ubah ke true false
        $attendanceToday = AttendanceSession::where('created_at', '=', Carbon::now()->format('Y-m-d'))->first();
        $attendanceToday = $attendanceToday ? true : false;

        return view('admin.dashboard', [
            'todayEntries' => $todayEntries,
            'totalToday' => $totalToday,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalClasses' => $totalClasses,
            'totalRooms' => $totalRooms,
            'totalSubjects' => $totalSubjects,
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
