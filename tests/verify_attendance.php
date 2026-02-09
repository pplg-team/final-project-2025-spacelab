<?php

$user = App\Models\User::whereHas('role', fn($q) => $q->where('name', 'Siswa'))->first();
$entry = App\Models\TimetableEntry::first();
$teacher = App\Models\User::whereHas('role', fn($q) => $q->where('name', 'Guru'))->first();
$service = new App\Services\AttendanceService();

if (!$user || !$entry || !$teacher) {
    echo "Missing data for test.\n";
    exit;
}

echo "Opening Session...\n";
$session = $service->openSession($entry, $teacher);
echo "Session Token: " . $session->token . "\n";
echo "End Time: " . $session->end_time . "\n";

echo "Validating First Attendance...\n";
$result = $service->validateAttendance($session, $user);
echo "Result 1: " . json_encode($result) . "\n";

if ($result['valid']) {
    echo "Creating Record...\n";
    App\Models\AttendanceRecord::create([
        'attendance_session_id' => $session->id,
        'user_id' => $user->id,
        'status' => 'hadir',
        'scanned_at' => now(),
    ]); // Simulate creation
}

echo "Validating Second Attendance (Same Day)...\n";
$result2 = $service->validateAttendance($session, $user);
echo "Result 2: " . json_encode($result2) . "\n";

// Test Mark Alpha
echo "Testing Mark Alpha Logic...\n";
$session->end_time = now()->subMinute(); // expire it
$session->save();

// We need a student in this class who hasn't attended.
// Finding another student.
$studentRole = App\Models\Role::where('name', 'Siswa')->first();
$otherUser = App\Models\User::where('role_id', $studentRole->id)->where('id', '!=', $user->id)->first();

if ($otherUser) {
    // Ensure this student is in the class
    // This part is tricky to mock without full factory setup.
    // We'll just run the command and check output.
    echo "Running MarkAlphaAttendance command...\n";
    Artisan::call('app:mark-alpha-attendance');
    echo Artisan::output();
} else {
    echo "No other student found for Alpha test.\n";
}
