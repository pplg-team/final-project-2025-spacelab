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
use App\Services\QueryOptimizationService;
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

        $activeTerm = QueryOptimizationService::getActiveTerm();

        // Optimize: Combine all counts into single query
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => Classroom::count(),
            'total_majors' => Major::count(),
            'total_rooms' => Room::where('is_active', true)->count(),
            'total_subjects' => Subject::count(),
        ];

        $majors = Major::select('id', 'code', 'name')->orderBy('code')->paginate(50);

        $studentDistribution = Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
            ->withCount(['classHistories' => function ($query) use ($activeTerm) {
                if ($activeTerm) {
                    $query->whereHas('block', function ($q) use ($activeTerm) {
                        $q->where('terms_id', $activeTerm->id);
                    });
                }
            }])
            ->with('major:id,code,name')
            ->orderBy('level')
            ->paginate(50)
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

        $majors = Major::select('id', 'code', 'name')->orderBy('code')->paginate(50);
        $selectedMajorId = $request->get('major_id');
        $selectedClassId = $request->get('class_id');

        $classes = collect();
        $students = collect();

        if ($selectedMajorId) {
            $classes = Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
                ->where('major_id', $selectedMajorId)
                ->orderBy('level')
                ->orderBy('rombel')
                ->paginate(50);
        }

        if ($selectedClassId) {
            $students = ClassHistory::select('id', 'student_id', 'class_id', 'block_id')
                ->with(['student.user:id,name,email', 'block.term:id,is_active'])
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

        $selectedClass = $selectedClassId ? Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
            ->with('major:id,name,code')
            ->find($selectedClassId) : null;

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

        $teachers = Teacher::select('id', 'user_id', 'code', 'phone')
            ->with(['user:id,name,email', 'teacherSubjects.subject:id,name'])
            ->whereHas('user')
            ->get()
            ->sortBy('user.name');

        // Optimize: Use single query with count instead of loop
        $teachingLoads = TimetableEntry::select('teacher_id')
            ->whereIn('teacher_id', $teachers->pluck('id'))
            ->whereHas('template', function ($query) {
                $query->where('is_active', true);
            })
            ->groupBy('teacher_id')
            ->selectRaw('teacher_id, count(*) as count')
            ->pluck('count', 'teacher_id')
            ->toArray();

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

        $majors = Major::select('id', 'code', 'name')->orderBy('code')->paginate(50);
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
            $classes = Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
                ->where('major_id', $selectedMajorId)
                ->orderBy('level')
                ->orderBy('rombel')
                ->paginate(50);
        }

        if ($selectedClassId) {
            $template = TimetableTemplate::select('id', 'class_id', 'is_active')
                ->where('class_id', $selectedClassId)
                ->where('is_active', true)
                ->first();

            if ($template) {
                $scheduleData = TimetableEntry::select('id', 'template_id', 'day_of_week', 'period_id', 'teacher_subject_id', 'room_history_id')
                    ->with([
                        'period:id,ordinal,start_time,end_time',
                        'teacherSubject.teacher.user:id,name',
                        'teacherSubject.subject:id,name',
                        'roomHistory.room:id,name'
                    ])
                    ->where('template_id', $template->id)
                    ->get()
                    ->groupBy(function ($entry) {
                        return $entry->day_of_week . '-' . $entry->period_id;
                    });

                $periods = \App\Models\Period::select('id', 'ordinal', 'start_time', 'end_time')
                    ->orderBy('ordinal')
                    ->paginate(50);
            }
        }

        $selectedClass = $selectedClassId ? Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
            ->with('major:id,name,code')
            ->find($selectedClassId) : null;

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

        $rooms = Room::select('id', 'building_id', 'name', 'is_active')
            ->with('building:id,name')
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(50);

        // Optimize: Use single query with count instead of loop
        $roomUsage = TimetableEntry::select('room_history_id')
            ->whereHas('roomHistory', function ($query) use ($rooms) {
                $query->whereIn('room_id', $rooms->pluck('id'));
            })
            ->whereHas('template', function ($query) {
                $query->where('is_active', true);
            })
            ->groupBy('room_history_id')
            ->selectRaw('room_history_id, count(*) as count')
            ->pluck('count', 'room_history_id')
            ->toArray();

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

        $classroom = Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
            ->with('major:id,name')
            ->find($classId);
        
        $students = ClassHistory::select('id', 'student_id', 'class_id', 'block_id')
            ->with(['student.user:id,name,email', 'block.term:id,is_active'])
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
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
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
        $teachers = Teacher::select('id', 'user_id', 'code', 'phone')
            ->with(['user:id,name,email', 'teacherSubjects.subject:id,name'])
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
