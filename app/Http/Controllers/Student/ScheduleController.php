<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\TimetableEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        $activeTerm = \App\Models\Term::where('is_active', true)->first();

        // Ambil class history user dengan relasi model
        $classIds = collect();
        if ($student) {
            $classHistoryQuery = \App\Models\ClassHistory::where('student_id', $student->id);
            if ($activeTerm) {
                $classHistoryQuery->where('terms_id', $activeTerm->id);
            }
            $classIds = $classHistoryQuery->pluck('class_id')->unique()->values();
        }

        if ($classIds->isEmpty()) {
            Log::warning('⚠️ Tidak ada class history untuk user; tidak menampilkan jadwal.');
            return view('student.schedules', [
                'student' => $user,
                'allSchedules' => collect(),
                'studentClassFullName' => '-',
                'title' => 'Jadwal',
                'description' => 'Halaman jadwal',
            ]);
        }

        // Ambil semua jadwal yang memiliki template aktif  dengan relasi model
        $allSchedulesRaw = \App\Models\TimetableEntry::whereHas('template', function($q) use ($classIds) {
                $q->whereIn('class_id', $classIds)
                ->where('is_active', true);
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

        // Ambil semua period yang is_teaching = false
        $nonTeachingPeriods = \App\Models\Period::where('is_teaching', false)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->orderBy('ordinal', 'asc')
            ->get();

        // Ambil semua period yang is_teaching = true untuk validasi kelengkapan
        $teachingPeriods = \App\Models\Period::where('is_teaching', true)
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

            // Merge and sort by start_time (fallback to ordinal)
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

        // Nama kelas siswa
        $classId = $classIds->first();
        $classroom = \App\Models\Classroom::with('major')->find($classId);
        $studentClassFullName = $classroom?->full_name ?? ($classroom?->name ?? '-');

        return view('student.schedules', [
            'student'              => $student,
            'allSchedules'         => $allSchedules,
            'studentClassFullName' => $studentClassFullName,
            'title' => 'Jadwal',
            'description' => 'Halaman jadwal',
        ]);
    }
}
