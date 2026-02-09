<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\{
    ClassroomController as StudentClassroomController,
    DashboardController as StudentDashboardController,
    ScheduleController as StudentScheduleController,
    ProfileController as StudentProfileController,
    RoomController as StudentRoomController,
};
use App\Http\Controllers\AttendanceController;

Route::middleware(['auth', 'role:Siswa'])
    ->prefix('student')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('index');
        Route::get('/schedules', [StudentScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/rooms', [StudentRoomController::class, 'index'])->name('rooms.index');
        Route::get('/classes', [StudentClassroomController::class, 'index'])->name('classroom.index');
        Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');

        // Attendance
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    });
