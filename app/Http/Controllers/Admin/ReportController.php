<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Major;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Term;
use App\Models\TimetableEntry;
use App\Models\TimetableTemplate;
use App\Models\ClassHistory;
use App\Models\TeacherSubject;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the main reports dashboard
     */
    public function index()
    {
        $title = 'Laporan';
        $description = 'Pusat Laporan dan Statistik';

        // Get active term
        $activeTerm = Term::where('is_active', true)->first();

        // Statistics
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => Classroom::count(),
            'total_majors' => Major::count(),
            'total_rooms' => Room::where('is_active', true)->count(),
            'total_subjects' => Subject::count(),
        ];

        // Get majors for filters
        $majors = Major::orderBy('code')->get();

        // Get recent class histories for student distribution
        $studentDistribution = Classroom::withCount(['classHistories' => function ($query) use ($activeTerm) {
            if ($activeTerm) {
                $query->whereHas('block', function ($q) use ($activeTerm) {
                    $q->where('terms_id', $activeTerm->id);
                });
            }
        }])
        ->with('major')
        ->orderBy('level')
        ->get()
        ->groupBy('major.code');

        return view('admin.reports.index', compact(
            'title',
            'description',
            'stats',
            'majors',
            'studentDistribution',
            'activeTerm'
        ));
    }

    /**
     * Generate student report per class
     */
    public function students(Request $request)
    {
        $title = 'Laporan Siswa';
        $description = 'Laporan Data Siswa per Kelas';

        $majors = Major::orderBy('code')->get();
        $selectedMajorId = $request->get('major_id');
        $selectedClassId = $request->get('class_id');

        $classes = collect();
        $students = collect();

        if ($selectedMajorId) {
            $classes = Classroom::where('major_id', $selectedMajorId)
                ->orderBy('level')
                ->orderBy('rombel')
                ->get();
        }

        if ($selectedClassId) {
            $students = ClassHistory::with(['student.user', 'block.term'])
                ->where('class_id', $selectedClassId)
                ->whereHas('block.term', function ($query) {
                    $query->where('is_active', true);
                })
                ->get()
                ->map(function ($history) {
                    return $history->student;
                })
                ->filter()
                ->unique('id')
                ->sortBy('user.name');
        }

        $selectedClass = $selectedClassId ? Classroom::with('major')->find($selectedClassId) : null;

        return view('admin.reports.students', compact(
            'title',
            'description',
            'majors',
            'classes',
            'students',
            'selectedMajorId',
            'selectedClassId',
            'selectedClass'
        ));
    }

    /**
     * Generate teacher report
     */
    public function teachers(Request $request)
    {
        $title = 'Laporan Guru';
        $description = 'Laporan Data Guru dan Mata Pelajaran';

        $teachers = Teacher::with(['user', 'teacherSubjects.subject'])
            ->whereHas('user')
            ->get()
            ->sortBy('user.name');

        // Get teaching load per teacher
        $teachingLoads = [];
        foreach ($teachers as $teacher) {
            $scheduleCount = TimetableEntry::where('teacher_id', $teacher->id)
                ->whereHas('template', function ($query) {
                    $query->where('is_active', true);
                })
                ->count();
            $teachingLoads[$teacher->id] = $scheduleCount;
        }

        return view('admin.reports.teachers', compact(
            'title',
            'description',
            'teachers',
            'teachingLoads'
        ));
    }

    /**
     * Generate schedule report per class
     */
    public function schedules(Request $request)
    {
        $title = 'Laporan Jadwal';
        $description = 'Laporan Jadwal Pelajaran per Kelas';

        $majors = Major::orderBy('code')->get();
        $selectedMajorId = $request->get('major_id');
        $selectedClassId = $request->get('class_id');

        $classes = collect();
        $scheduleData = null;
        $periods = collect();
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => "Jum'at",
            6 => 'Sabtu',
        ];

        if ($selectedMajorId) {
            $classes = Classroom::where('major_id', $selectedMajorId)
                ->orderBy('level')
                ->orderBy('rombel')
                ->get();
        }

        if ($selectedClassId) {
            // Get active template for this class
            $template = TimetableTemplate::where('class_id', $selectedClassId)
                ->where('is_active', true)
                ->first();

            if ($template) {
                $scheduleData = TimetableEntry::with([
                    'period',
                    'teacherSubject.teacher.user',
                    'teacherSubject.subject',
                    'roomHistory.room'
                ])
                ->where('template_id', $template->id)
                ->get()
                ->groupBy(function ($entry) {
                    return $entry->day_of_week . '-' . $entry->period_id;
                });

                // Get periods
                $periods = \App\Models\Period::orderBy('ordinal')->get();
            }
        }

        $selectedClass = $selectedClassId ? Classroom::with('major')->find($selectedClassId) : null;

        return view('admin.reports.schedules', compact(
            'title',
            'description',
            'majors',
            'classes',
            'scheduleData',
            'periods',
            'days',
            'selectedMajorId',
            'selectedClassId',
            'selectedClass'
        ));
    }

    /**
     * Generate room usage report
     */
    public function rooms(Request $request)
    {
        $title = 'Laporan Ruangan';
        $description = 'Laporan Penggunaan Ruangan';

        $rooms = Room::with('building')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Calculate usage for each room
        $roomUsage = [];
        foreach ($rooms as $room) {
            $usage = TimetableEntry::whereHas('roomHistory', function ($query) use ($room) {
                $query->where('room_id', $room->id);
            })
            ->whereHas('template', function ($query) {
                $query->where('is_active', true);
            })
            ->count();

            $roomUsage[$room->id] = [
                'count' => $usage,
                'percentage' => min(100, round(($usage / 30) * 100)) // Assuming max 30 slots per week
            ];
        }

        return view('admin.reports.rooms', compact(
            'title',
            'description',
            'rooms',
            'roomUsage'
        ));
    }

    /**
     * Export students report to CSV
     */
    public function exportStudents(Request $request)
    {
        $classId = $request->get('class_id');
        
        if (!$classId) {
            return back()->with('error', 'Pilih kelas terlebih dahulu');
        }

        $classroom = Classroom::with('major')->find($classId);
        $students = ClassHistory::with(['student.user'])
            ->where('class_id', $classId)
            ->whereHas('block.term', function ($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->map(function ($history) {
                return $history->student;
            })
            ->filter()
            ->unique('id')
            ->sortBy('user.name');

        $filename = 'siswa_' . str_replace(' ', '_', $classroom->full_name) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($students, $classroom) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['Laporan Siswa Kelas ' . $classroom->full_name]);
            fputcsv($file, ['Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'NIS', 'NISN', 'Nama', 'Email']);

            $no = 1;
            foreach ($students as $student) {
                fputcsv($file, [
                    $no++,
                    $student->nis ?? '-',
                    $student->nisn ?? '-',
                    $student->user->name ?? '-',
                    $student->user->email ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export teachers report to CSV
     */
    public function exportTeachers()
    {
        $teachers = Teacher::with(['user', 'teacherSubjects.subject'])
            ->whereHas('user')
            ->get()
            ->sortBy('user.name');

        $filename = 'guru_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($teachers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Laporan Data Guru']);
            fputcsv($file, ['Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'Kode', 'Nama', 'Email', 'Telepon', 'Mata Pelajaran']);

            $no = 1;
            foreach ($teachers as $teacher) {
                $subjects = $teacher->teacherSubjects->map(function ($ts) {
                    return $ts->subject->name ?? '';
                })->filter()->implode(', ');

                fputcsv($file, [
                    $no++,
                    $teacher->code ?? '-',
                    $teacher->user->name ?? '-',
                    $teacher->user->email ?? '-',
                    $teacher->phone ?? '-',
                    $subjects ?: '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
