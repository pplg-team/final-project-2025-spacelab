<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MarkAlphaAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-alpha-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark students as alpha when attendance session expires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting auto-alpha check...');

        // Get all active sessions that have expired
        $expiredSessions = \App\Models\AttendanceSession::where('is_active', true)
            ->where('end_time', '<', \Carbon\Carbon::now())
            ->with(['timetableEntry.template' => function($q) {
                $q->with('class');
            }])
            ->get();

        if ($expiredSessions->isEmpty()) {
            $this->info('No expired sessions found.');
            return;
        }

        $totalAlphaCount = 0;

        foreach ($expiredSessions as $session) {
            try {
                $alphaCount = $session->markAlphaIfExpired();
                if ($alphaCount !== false) {
                    $this->info("Session {$session->id} closed. Marked {$alphaCount} students as Alpha.");
                    $totalAlphaCount += $alphaCount;
                }
            } catch (\Exception $e) {
                $this->error("Error processing session {$session->id}: {$e->getMessage()}");
            }
        }

        $this->info("Total: Marked {$totalAlphaCount} students as Alpha.");
    }
}
