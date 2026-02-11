<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class PagesController extends Controller
{
    //
    public function index() {
        return view('pages.home',
            [
                'title' => 'Welcome to Spacelab',
                'description' => 'SpaceLab is an all-in-one academic schedule and facility management system designed to streamline school operations and enhance productivity.'
            ]
        );
    }

    public function attendanceQr() {

        $today = Carbon::today();
        
        //  ambil sesi aktif hari ini di database dari tabel attendance_sessions
        $activeSessionToken = \App\Models\AttendanceSession::where('is_active', true)
            ->whereDate('created_at', $today->toDateString())
            ->first();

        return view('attendance.show',
            [
                'title' => 'Attendance QR Code',
                'description' => 'Generate and manage QR codes for attendance sessions.',
                'activeSessionToken' => $activeSessionToken,
                'today' => $today,
                'dayName' => $today->translatedFormat('l'),
                'dateFormatted' => $today->translatedFormat('d F Y')
            ]
        );
    }
}
