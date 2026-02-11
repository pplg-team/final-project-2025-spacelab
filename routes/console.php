<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('attendance:close-sessions', function () {
    $this->comment('Closing all active attendance sessions...');

    $closedCount = \App\Models\AttendanceSession::where('is_active', true)
        ->update(['is_active' => false]);

    $this->info("Closed {$closedCount} active attendance sessions.");
})->purpose('Close all active attendance sessions at the end of the day');