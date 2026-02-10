<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\TimetableEntry;
use App\Models\Classroom;
use App\Models\Period;
use App\Models\Term;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $currentTime = Carbon::now();
        $dayIndex = (int) $currentTime->format('N');

        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => "Jum'at",
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        $dayName = $dayNames[$dayIndex] ?? $currentTime->isoFormat('dddd');
        $activeTerm = Term::where('is_active', true)->first();

        $studentRecord = $student->student;
        $classIds = collect();

        if ($studentRecord) {
            $classHistoryQuery = ClassHistory::where('student_id', $studentRecord->id);
            if ($activeTerm) {
                $classHistoryQuery->where('terms_id', $activeTerm->id);
            }
            $classIds = $classHistoryQuery->pluck('class_id')->unique()->values();
        }

        $studentClassFullName = '-';
        $periodEntries = collect();

        if ($classIds->isEmpty()) {
            $schedules = collect();
        } else {

            // Jadwal hari ini
            $schedules = TimetableEntry::whereHas('template', function ($q) use ($classIds) {
                    $q->whereIn('class_id', $classIds);
                })
                ->where('day_of_week', $dayIndex)
                ->with([
                    'period',
                    'template.class.major',
                    'teacherSubject.subject',
                    'teacherSubject.teacher.user',
                    'roomHistory.room',
                ])
                ->orderBy('period_id', 'asc')
                ->get();

            // === CEK KELENGKAPAN ORDINAL ===

            $teachingPeriods = Period::where('is_teaching', true)
                ->whereNotNull('ordinal')
                ->orderBy('ordinal', 'asc')
                ->get();

            $teachingOrdinals = $teachingPeriods->pluck('ordinal')->sort()->values();

            $existingOrdinals = $schedules
                ->pluck('period.ordinal')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            $isComplete = false;

            if ($existingOrdinals->isNotEmpty() && $teachingOrdinals->isNotEmpty()) {
                $isComplete = $teachingOrdinals->diff($existingOrdinals)->isEmpty();
            }

            // Jika tidak lengkap → libur
            if (! $isComplete) {
                $schedules = collect();
            } else {
                // Non-teaching period hanya jika lengkap
                $nonTeachingPeriods = Period::where('is_teaching', false)
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->orderBy('ordinal', 'asc')
                    ->get();

                $periodEntries = $nonTeachingPeriods->map(function ($p) use ($dayIndex) {
                    return new class($p, $dayIndex) {
                        public $period;
                        public $template;
                        public $teacherSubject;
                        public $teacher;
                        public $roomHistory;
                        public $is_period_only = true;
                        public $day_of_week;

                        public function __construct($period, $day)
                        {
                            $this->period = $period;
                            $this->day_of_week = $day;
                        }

                        public function isOngoing($now = null)
                        {
                            return $this->period?->isOngoing($now);
                        }

                        public function isPast($now = null)
                        {
                            return $this->period?->isPast($now);
                        }
                    };
                });

                $schedules = $schedules
                    ->concat($periodEntries)
                    ->sortBy(function ($item) {
                        if (! $item->period) return PHP_INT_MAX;
                        if ($item->period->start_time) {
                            return Carbon::parse($item->period->start_time)->timestamp;
                        }
                        return $item->period->ordinal ? $item->period->ordinal * 1000 : PHP_INT_MAX;
                    })
                    ->values();
            }

            $classroom = Classroom::with('major')->find($classIds->first());
            $studentClassFullName = $classroom?->full_name ?? ($classroom?->name ?? '-');
        }
        // cek apakah ada sesi absen yang dibuka
        $activeSession = AttendanceSession::where('is_active', true)->first();
        $isAbsensiActive = $activeSession !== null;
        $activeSessionToken = $activeSession?->token;

        $attendanceRecord = null;
        if ($isAbsensiActive) {
            $attendanceRecord = AttendanceRecord::where('attendance_session_id', $activeSession->id)
                ->where('user_id', $student->id)
                ->first();
        }

        return view('student.dashboard', [
            'student'        => $student,
            'schedulesToday' => $schedules,
            'countToday'     => $schedules->count() - $periodEntries->count(),
            'today'          => $dayName,
            'currentTime'    => $currentTime,
            'currentDayIndex'=> $dayIndex,
            'studentClassFullName' => $studentClassFullName,
            'title' => 'Dashboard',
            'description' => 'Halaman dashboard',
            'isAbsensiActive' => $isAbsensiActive,
            'activeSessionToken' => $activeSessionToken,
            'attendanceRecord' => $attendanceRecord,
        ]);
    }
}
