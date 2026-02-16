<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user?->teacher;

        if (! $teacher) {
            Log::warning('⚠️ User has no teacher relationship; not showing timetable for teacher.');
            return view('teacher.schedules', [
                'teacher' => null,
                'allSchedules' => collect(),
                'teacherFullName' => $user?->name ?? '-',   
                'subjects' => collect(),
                'totalSubjects' => 0,
                'title' => 'Jadwal',
                'description' => 'Halaman jadwal',
            ]);
        }

        $activeTerm = DB::table('terms')->where('is_active', true)->first();

        // Ambil semua jadwal dengan relasi model
        $allSchedulesRaw = TimetableEntry::whereHas('teacherSubject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->whereBetween('day_of_week', [1, 7])
            ->with([
                'period',
                'template.class.major',
                'teacherSubject.subject',
                'teacherSubject.teacher.user',
                'roomHistory.room.building',
            ])
            ->orderBy('day_of_week', 'asc')
            ->orderBy('period_id', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // Ambil semua period yang is_teaching = false (break/istirahat)
        $nonTeachingPeriods = Period::where('is_teaching', false)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->orderBy('ordinal', 'asc')
            ->get();

        // Ambil semua period yang is_teaching = true untuk validasi kelengkapan
        $teachingPeriods = Period::where('is_teaching', true)
            ->whereNotNull('ordinal')
            ->orderBy('ordinal', 'asc')
            ->get();

        $teachingOrdinals = $teachingPeriods->pluck('ordinal')->sort()->values();

        $groupedOrdered = collect();

        for ($d = 1; $d <= 7; $d++) {
            $dayCollection = $allSchedulesRaw->get($d, collect());

            // Cek kelengkapan ordinal untuk hari ini
            $existingOrdinals = $dayCollection->pluck('period.ordinal')->filter()->unique()->sort()->values();

            $shouldShowPeriods = false;

            if ($existingOrdinals->isNotEmpty() && $teachingOrdinals->isNotEmpty()) {
                // Bandingkan ordinal yang ada dengan semua teaching ordinals
                // Jika semua teaching ordinals ada di existing ordinals, maka lengkap
                $shouldShowPeriods = $teachingOrdinals->diff($existingOrdinals)->isEmpty();
            }

            // Hanya tambahkan period entries jika jadwal lengkap
            $periodEntriesForDay = collect();
            if ($shouldShowPeriods) {
                $periodEntriesForDay = $nonTeachingPeriods->map(function($p) use ($d) {
                    return new class($p, $d) {
                        public $period;
                        public $template;
                        public $teacherSubject;
                        public $teacher;
                        public $roomHistory;
                        public $is_period_only;
                        public $day_of_week;

                        public function __construct($period, $day)
                        {
                            $this->period = $period;
                            $this->template = null;
                            $this->teacherSubject = null;
                            $this->teacher = null;
                            $this->roomHistory = null;
                            $this->is_period_only = true;
                            $this->day_of_week = $day;
                        }

                        public function isOngoing($now = null)
                        {
                            return $this->period ? $this->period->isOngoing($now) : false;
                        }

                        public function isPast($now = null)
                        {
                            return $this->period ? $this->period->isPast($now) : false;
                        }
                    };
                });
            }

            // Merge dan sort by start_time (fallback ke ordinal)
            $now = Carbon::now();
            $merged = $dayCollection->concat($periodEntriesForDay)->sortBy(function($item) use ($now) {
                $period = $item->period ?? null;
                if (! $period) return PHP_INT_MAX;

                $start = $period->start_time ?? ($period->start_date?->format('H:i:s') ?? null);
                if ($start) {
                    try {
                        $c = Carbon::createFromFormat('H:i:s', $start, $now->getTimezone());
                    } catch (\Exception $e) {
                        try {
                            $c = Carbon::parse($start, $now->getTimezone());
                        } catch (\Exception $e2) {
                            $c = null;
                        }
                    }
                    if ($c) return $c->timestamp;
                }

                if (isset($period->ordinal)) return (int) $period->ordinal * 1000;
                return PHP_INT_MAX;
            })->values();

            $groupedOrdered[$d] = $merged;
        }

        $allSchedules = $groupedOrdered;

        $teacherFullName = $teacher->user?->name ?? $teacher->name ?? '-';

        // Subjects the teacher teaches (via teacher_subjects pivot)
        $subjects = $teacher->subjects()->orderBy('name')->get();
        $totalSubjects = $subjects->count();

        return view('teacher.schedules', [
            'teacher' => $teacher,
            'teacherFullName' => $teacherFullName,
            'allSchedules' => $allSchedules,
            'subjects' => $subjects,
            'totalSubjects' => $totalSubjects,
            'title' => 'Jadwal',
            'description' => 'Halaman jadwal',
        ]);
    }
}
