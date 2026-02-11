<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [PagesController::class, 'index'])->name('welcome');
Route::get('/attendance-qr', [PagesController::class, 'attendanceQr'])->name('attendance.qr');

// Redirect Login
Route::get('/redirect', function () {
    $role = Auth::user()->role->lower_name;
    return match ($role) {
        'admin' => redirect()->route('admin.index'),
        'guru' => redirect()->route('guru.index'),
        'siswa' => redirect()->route('siswa.index'),
        'staff' => redirect()->route('staff.index'),
        default => abort(403, 'Unauthorized role'),
    };
})->middleware('auth')->name('redirect');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Import route role
require __DIR__.'/roles/admin.php';
require __DIR__.'/roles/teacher.php';
require __DIR__.'/roles/student.php';
require __DIR__.'/roles/staff.php';

// Auth routes
require __DIR__.'/auth.php';
