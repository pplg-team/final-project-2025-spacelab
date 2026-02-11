<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\TimetableEntry;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceSessionController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        $today = Carbon::today();

        // 1. Statistics Cards
        // Unique user counts who attended today by role (use distinct user_id)
        $statsDate = $today->toDateString();
        $baseQuery = AttendanceRecord::whereDate('scanned_at', $statsDate)->where('status', 'hadir');

        $stats = [
            'total' => (clone $baseQuery)->distinct()->count('user_id'),
            'staff' => (clone $baseQuery)->whereHas('user', fn($q) => $q->whereHas('role', fn($r) => $r->where('name', 'Staff')))->distinct()->count('user_id'),
            'guru' => (clone $baseQuery)->whereHas('user', fn($q) => $q->whereHas('role', fn($r) => $r->where('name', 'Guru')))->distinct()->count('user_id'),
            'siswa' => (clone $baseQuery)->whereHas('user', fn($q) => $q->whereHas('role', fn($r) => $r->where('name', 'Siswa')))->distinct()->count('user_id'),
        ];
        
        // Also need total counts to show "18 / 25"
        // This might be heavy if users table is huge, but for now:
        $counts = [
            'total' => User::count(),
            'staff' => User::whereHas('role', fn($q) => $q->where('name', 'Staff'))->count(),
            'guru' => User::whereHas('role', fn($q) => $q->where('name', 'Guru'))->count(),
            'siswa' => User::whereHas('role', fn($q) => $q->where('name', 'Siswa'))->count(),
        ];

        // 2. Weekly Chart Data (all statuses + filters)
        $allowedChartRoles = ['Staff', 'Guru', 'Siswa'];
        $chartStatusMeta = [
            'hadir' => ['label' => 'Hadir', 'color' => '#22C55E'],
            'izin' => ['label' => 'Izin', 'color' => '#F59E0B'],
            'sakit' => ['label' => 'Sakit', 'color' => '#EF4444'],
            'alpa' => ['label' => 'Alpa', 'color' => '#6B7280'],
        ];
        $allowedChartStatuses = array_keys($chartStatusMeta);

        $chartReferenceDate = $today->copy();
        if ($request->filled('chart_week')) {
            try {
                $chartReferenceDate = Carbon::parse($request->chart_week);
            } catch (\Throwable $e) {
                $chartReferenceDate = $today->copy();
            }
        }

        $chartRole = $request->input('chart_role');
        if (!in_array($chartRole, $allowedChartRoles, true)) {
            $chartRole = null;
        }

        $chartStatus = $request->input('chart_status');
        if (!in_array($chartStatus, $allowedChartStatuses, true)) {
            $chartStatus = null;
        }

        $selectedChartStatuses = $chartStatus ? [$chartStatus] : $allowedChartStatuses;
        $startOfWeek = $chartReferenceDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $endOfWeek = $chartReferenceDate->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

        $weeklyDataQuery = AttendanceRecord::select(
            DB::raw('DATE(scanned_at) as date'),
            'status',
            DB::raw('COUNT(DISTINCT user_id) as total')
        )
            ->whereBetween('scanned_at', [$startOfWeek, $endOfWeek])
            ->whereIn('status', $selectedChartStatuses);

        if ($chartRole) {
            $weeklyDataQuery->whereHas('user.role', fn($q) => $q->where('name', $chartRole));
        }

        $weeklyData = $weeklyDataQuery
            ->groupBy(DB::raw('DATE(scanned_at)'), 'status')
            ->get()
            ->groupBy('date')
            ->map(fn($items) => $items->keyBy('status'));

        $chartLabels = [];
        $chartSeries = [];
        foreach ($selectedChartStatuses as $statusKey) {
            $chartSeries[$statusKey] = [];
        }

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $dayDate = $date->toDateString();
            $chartLabels[] = $date->locale('id')->isoFormat('ddd');
            $dailyData = $weeklyData->get($dayDate, collect());

            foreach ($selectedChartStatuses as $statusKey) {
                $chartSeries[$statusKey][] = $dailyData->has($statusKey)
                    ? (int) $dailyData->get($statusKey)->total
                    : 0;
            }
        }

        $chartData = [
            'labels' => $chartLabels,
            'datasets' => collect($selectedChartStatuses)->map(fn($statusKey) => [
                'label' => $chartStatusMeta[$statusKey]['label'],
                'data' => $chartSeries[$statusKey],
                'backgroundColor' => $chartStatusMeta[$statusKey]['color'],
                'borderRadius' => 4,
            ])->values(),
        ];

        $chartWeekLabel = $startOfWeek->locale('id')->isoFormat('D MMMM Y')
            . ' - '
            . $endOfWeek->locale('id')->isoFormat('D MMMM Y');

        $chartFilters = [
            'week' => $chartReferenceDate->toDateString(),
            'role' => $chartRole,
            'status' => $chartStatus,
        ];

        // 3. Activation Status
        // ambil semua sesi aktif hari ini saja
        $activeSessions = AttendanceSession::where('is_active', true)->whereDate('created_at', Carbon::today())->get();
        $activeSessionsCount = $activeSessions->count();
        $isAbsensiActive = $activeSessionsCount > 0;
        $activeSessionToken = $activeSessions->first()?->token;

        // Check whether a session was created today
        $sessionTodayExists = AttendanceSession::whereDate('created_at', $today->toDateString())->exists();

        // Safely get first opened/closed times for today (string or '-')
        $sessionOpenedToday = AttendanceSession::whereDate('start_time', $today->toDateString())->orderBy('start_time')->first()?->start_time ?? '-';
        $sessionClosedToday = AttendanceSession::whereDate('end_time', $today->toDateString())->orderByDesc('end_time')->first()?->end_time ?? '-';

        // 4. Monitoring Table
        $filterDate = $request->filled('date') ? Carbon::parse($request->date)->toDateString() : $today->toDateString();

        $query = AttendanceRecord::with(['user.role', 'attendanceSession.timetableEntry.template.class'])
            ->whereDate('scanned_at', $filterDate);

        // Filters
        if ($request->filled('role')) {
            $query->whereHas('user.role', fn($q) => $q->where('name', $request->role));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $records = $query->latest()->paginate(20);

        return view('admin.attendance.sessions', compact(
            'stats',
            'counts',
            'chartData',
            'chartWeekLabel',
            'chartFilters',
            'isAbsensiActive',
            'activeSessionsCount',
            'records',
            'activeSessionToken',
            'sessionTodayExists',
            'sessionOpenedToday',
            'sessionClosedToday'
        ));
    }

    public function store(Request $request)
    {
        // Cek apakah sudah ada session yang dibuat hari ini
        $sessionTodayExists = AttendanceSession::whereDate('created_at', Carbon::today())->exists();
        if ($sessionTodayExists) {
            return redirect()->back()->with('error', 'Sesi absensi untuk hari ini sudah dibuat. Hanya dapat membuat 1 sesi per hari.');
        }

        // "Buka Absensi" -> Open sessions for ALL timetable entries of TODAY
        $entries = TimetableEntry::where('day_of_week', Carbon::now()->dayOfWeekIso)
            ->get();

        if ($entries->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada jadwal pelajaran hari ini.');
        }

        $count = 0;
        foreach ($entries as $entry) {
            // Check if active session already exists
            $exists = AttendanceSession::where('timetable_entry_id', $entry->id)
                ->where('is_active', true)
                ->exists();
            
            if (!$exists) {
                $this->attendanceService->openSession($entry, Auth::user());
                $count++;
            }
        }

        return redirect()->back()->with('success', "Berhasil membuka {$count} sesi absensi untuk hari ini.");
    }

    public function destroy($id)
    {
        $sessions = [];
        if ($id === 'bulk') {
            $sessions = AttendanceSession::where('is_active', true)
                ->with('timetableEntry.template.class')
                ->get();
        } else {
            $session = AttendanceSession::with('timetableEntry.template.class')->find($id);
            if ($session && $session->is_active) {
                $sessions = [$session];
            }
        }

        $now = Carbon::now();
        $count = 0;

        foreach ($sessions as $session) {
            // 1. Update End Time and deactivate in a single query
            $session->update(['end_time' => $now, 'is_active' => false]);

            // 2. Find Absent Students & Mark Alpha
            $timetableEntry = $session->timetableEntry;
            if ($timetableEntry && $timetableEntry->template && $timetableEntry->template->class) {
                $classroom = $timetableEntry->template->class;
                
                // Get students in this class
                // Using the same logic as MarkAlphaAttendance command
                $historyRecords = \App\Models\ClassHistory::where('class_id', $classroom->id)
                    ->with('student.user')
                    ->get();

                foreach ($historyRecords as $record) {
                    $user = $record->student?->user;
                    if (!$user) continue;

                    // Check if they have attended TODAY (Daily Attendance Rule)
                    $hasAttendedToday = AttendanceRecord::where('user_id', $user->id)
                        ->whereDate('scanned_at', Carbon::today())
                        ->exists();

                    if (!$hasAttendedToday) {
                        AttendanceRecord::create([
                            'attendance_session_id' => $session->id,
                            'user_id' => $user->id,
                            'status' => 'alpa',
                            'scanned_at' => $now,
                            'note' => 'Auto-generated by system (Session Closed)',
                        ]);
                        $count++;
                    }
                }
            }

            // session already deactivated above
        }

        return redirect()->back()->with('success', "Semua sesi absensi ditutup. {$count} siswa ditandai Alpa.");
    }
}
