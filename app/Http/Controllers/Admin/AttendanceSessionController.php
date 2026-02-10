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
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Statistics Cards
        // We need counts of UNIQUE users who attended today by role
        // Assuming roles are named 'Guru', 'Staff', 'Siswa'
        $stats = [
            'total' => AttendanceRecord::whereDate('scanned_at', $today)->count(), // Unique by user_id logic enforced at creation
            'staff' => AttendanceRecord::whereDate('scanned_at', $today)->whereHas('user.role', fn($q) => $q->where('name', 'Staff'))->count(),
            'guru' => AttendanceRecord::whereDate('scanned_at', $today)->whereHas('user.role', fn($q) => $q->where('name', 'Guru'))->count(),
            'siswa' => AttendanceRecord::whereDate('scanned_at', $today)->whereHas('user.role', fn($q) => $q->where('name', 'Siswa'))->count(),
        ];
        
        // Also need total counts to show "18 / 25"
        // This might be heavy if users table is huge, but for now:
        $counts = [
            'staff' => User::whereHas('role', fn($q) => $q->where('name', 'Staff'))->count(),
            'guru' => User::whereHas('role', fn($q) => $q->where('name', 'Guru'))->count(),
            'siswa' => User::whereHas('role', fn($q) => $q->where('name', 'Siswa'))->count(),
        ];

        // 2. Weekly Chart Data (Mon-Fri)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklyData = AttendanceRecord::select(DB::raw('DATE(scanned_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('scanned_at', [$startOfWeek, $endOfWeek])
            ->groupBy('date')
            ->get()
            ->keyBy('date');
            
        $chartData = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            if ($date->isWeekend()) continue; // Skip weekend if desired
            $dayDate = $date->format('Y-m-d');
            $chartData[] = [
                'day' => $date->locale('id')->dayName,
                'total' => $weeklyData[$dayDate]->total ?? 0
            ];
        }

        // 3. Activation Status
        $activeSessions = AttendanceSession::where('is_active', true)->get();
        $activeSessionsCount = $activeSessions->count();
        $isAbsensiActive = $activeSessionsCount > 0;
        $activeSessionToken = $activeSessions->first()?->token;

        // Cek apakah sudah ada session yang dibuat hari ini
        $sessionTodayExists = AttendanceSession::whereDate('created_at', $today)->exists();

        // 4. Monitoring Table
        $query = AttendanceRecord::with(['user.role', 'attendanceSession.timetableEntry.template.class'])
            ->whereDate('scanned_at', $request->date ?? $today);

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
            'stats', 'counts', 'chartData', 'isAbsensiActive', 'activeSessionsCount', 'records', 'activeSessionToken', 'sessionTodayExists'
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
                ->with(['timetableEntry.template' => function($q) {
                    $q->with('class');
                }])
                ->get();
        } else {
            $session = AttendanceSession::with(['timetableEntry.template' => function($q) {
                    $q->with('class');
                }])->find($id);
            if ($session && $session->is_active) {
                $sessions = [$session];
            }
        }

        $now = Carbon::now();
        $count = 0;

        foreach ($sessions as $session) {
            // 1. Update End Time to Now
            $session->update(['end_time' => $now]);

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

            // 3. Deactivate Session
            $session->update(['is_active' => false]);
        }

        return redirect()->back()->with('success', "Semua sesi absensi ditutup. {$count} siswa ditandai Alpa.");
    }
}
