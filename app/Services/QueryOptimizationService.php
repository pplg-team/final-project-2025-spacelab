<?php

namespace App\Services;

use App\Models\Term;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class QueryOptimizationService
{
    /**
     * Get active term with caching to avoid duplicate queries
     */
    public static function getActiveTerm()
    {
        return Cache::remember('active_term', now()->addHours(1), function () {
            return Term::where('is_active', true)->first();
        });
    }

    /**
     * Get active attendance session for today
     */
    public static function getActiveAttendanceSession()
    {
        $today = Carbon::today()->toDateString();
        return Cache::remember("active_attendance_session_{$today}", now()->addHours(1), function () {
            return AttendanceSession::where('is_active', true)
                ->whereDate('created_at', Carbon::today())
                ->first();
        });
    }

    /**
     * Clear cache when term changes
     */
    public static function clearTermCache()
    {
        Cache::forget('active_term');
    }

    /**
     * Clear cache when attendance session changes
     */
    public static function clearAttendanceSessionCache()
    {
        Cache::forget('active_attendance_session_' . Carbon::today()->toDateString());
    }
}
