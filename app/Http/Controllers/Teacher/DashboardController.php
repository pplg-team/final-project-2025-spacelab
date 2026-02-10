<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use App\Models\Period;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show teacher dashboard with schedules for the current day.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $user->loadMissing('teacher.user');
        $teacher = $user->teacher ?? null;

        $currentTime = Carbon::now();
        $currentDayIndex = (int) $currentTime->format('N');

        // =========================
        // Ambil jadwal mengajar hari ini
        // =========================
        $schedulesTodayRaw = TimetableEntry::with([
                'period',
                'template.class.major',
                'teacherSubject.subject',
                'teacherSubject.teacher.user',
                'roomHistory.room.building',
            ])
            ->where('day_of_week', $currentDayIndex)
            ->whereHas('teacherSubject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->orderBy('period_id', 'asc')
            ->get();

        // =========================
        // JIKA TIDAK ADA JADWAL → LIBUR
        // =========================
        if ($schedulesTodayRaw->isEmpty()) {
            return view('teacher.dashboard', [
                'teacher' => $teacher,
                'schedulesToday' => collect(),
                'currentTime' => $currentTime,
                'currentDayIndex' => $currentDayIndex,
                'lessonsCount' => 0,
                'roomsCount' => 0,
                'uniqueSubjectsCount' => 0,
                'title' => 'Dashboard Guru',
                'description' => 'Halaman dashboard guru',
            ]);
        }

        // =========================
        // Non-teaching periods (hanya jika lengkap)
        // =========================
        $nonTeachingPeriods = Period::where('is_teaching', false)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->orderBy('ordinal', 'asc')
            ->get();

        $periodEntriesForDay = $nonTeachingPeriods->map(function ($p) use ($currentDayIndex) {
            return new class($p, $currentDayIndex) {
                public $period;
                public $template = null;
                public $teacherSubject = null;
                public $teacher = null;
                public $roomHistory = null;
                public $is_period_only = true;
                public $day_of_week;

                public function __construct($period, $day)
                {
                    $this->period = $period;
                    $this->day_of_week = $day;
                }

                public function isOngoing($now = null)
                {
                    $now = $now ?: Carbon::now();
                    return method_exists($this->period, 'isOngoing')
                        ? $this->period->isOngoing($now)
                        : false;
                }

                public function isPast($now = null)
                {
                    $now = $now ?: Carbon::now();
                    return method_exists($this->period, 'isPast')
                        ? $this->period->isPast($now)
                        : false;
                }
            };
        });

        // =========================
        // Merge & sort berdasarkan waktu
        // =========================
        $schedulesToday = $schedulesTodayRaw
            ->concat($periodEntriesForDay)
            ->sortBy(function ($item) use ($currentTime) {
                if (! $item->period) return PHP_INT_MAX;

                if ($item->period->start_time) {
                    try {
                        return Carbon::parse(
                            $item->period->start_time,
                            $currentTime->getTimezone()
                        )->timestamp;
                    } catch (\Exception $e) {}
                }

                return $item->period->ordinal
                    ? $item->period->ordinal * 1000
                    : PHP_INT_MAX;
            })
            ->values();

        // =========================
        // Statistik dashboard
        // =========================
        $lessonsCount = $schedulesTodayRaw->count();
        $uniqueSubjectsCount = $schedulesTodayRaw
            ->pluck('teacherSubject.subject.id')
            ->filter()
            ->unique()
            ->count();

        $roomsCount = $schedulesTodayRaw
            ->pluck('roomHistory.room.id')
            ->filter()
            ->unique()
            ->count();

        // Cek apakah ada session absensi aktif
        $activeSessions = \App\Models\AttendanceSession::where('is_active', true)->exists();
        $isAbsensiActive = $activeSessions;

        // Cek attendance record guru hari ini
        $attendanceRecord = null;
        if ($isAbsensiActive) {
            $attendanceRecord = \App\Models\AttendanceRecord::where('user_id', $user->id)
                ->whereDate('scanned_at', $currentTime->toDateString())
                ->first();
        }

        return view('teacher.dashboard', [
            'teacher' => $teacher,
            'schedulesToday' => $schedulesToday,
            'currentTime' => $currentTime,
            'currentDayIndex' => $currentDayIndex,
            'lessonsCount' => $lessonsCount,
            'roomsCount' => $roomsCount,
            'uniqueSubjectsCount' => $uniqueSubjectsCount,
            'isAbsensiActive' => $isAbsensiActive,
            'attendanceRecord' => $attendanceRecord,
            'title' => 'Dashboard Guru',
            'description' => 'Halaman dashboard guru',
        ]);
    }
}
