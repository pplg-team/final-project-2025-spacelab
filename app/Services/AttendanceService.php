<?php

namespace App\Services;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Open a new attendance session for a timetable entry.
     *
     * @param TimetableEntry $entry
     * @param User $openedBy
     * @return AttendanceSession
     */
    public function openSession(TimetableEntry $entry, User $openedBy)
    {
        // Close other sessions for this entry if any (though we are moving to daily, let's keep it clean)
        AttendanceSession::where('timetable_entry_id', $entry->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return AttendanceSession::create([
            'timetable_entry_id' => $entry->id,
            'user_id' => $openedBy->id,
            'token' => Str::random(32),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHours(3), // 3 Jam durasi default
            'is_active' => true,
        ]);
    }

    /**
     * Close an attendance session.
     *
     * @param AttendanceSession $session
     * @return bool
     */
    public function closeSession(AttendanceSession $session)
    {
        $session->update(['is_active' => false]);
    }

    /**
     * Validate if a user can mark attendance for a session.
     *
     * @param AttendanceSession $session
     * @param User $user
     * @return array ['valid' => bool, 'message' => string]
     */
    public function validateAttendance(AttendanceSession $session, User $user)
    {
        if (!$session->is_active) {
            return ['valid' => false, 'message' => 'Sesi absensi sudah ditutup.'];
        }

        // Check if session has expired based on time
        if (Carbon::now()->gt(Carbon::parse($session->end_time))) {
            return ['valid' => false, 'message' => 'Waktu absensi telah habis.'];
        }

        // Cek apakah user sudah absen HARI INI (untuk mapel apapun/sesi apapun)
        // Aturan baru: Absen hanya sekali per hari
        $alreadyAttendedToday = AttendanceRecord::where('user_id', $user->id)
            ->whereDate('scanned_at', Carbon::today())
            ->exists();

        if ($alreadyAttendedToday) {
            return ['valid' => false, 'message' => 'Anda sudah melakukan absensi hari ini.'];
        }

        return ['valid' => true];
    }
}
