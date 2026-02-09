<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\AttendanceAttachment;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        // Show scanner and form
        // We can pass user's name or other info if needed for the view
        return view('attendance.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'status' => 'required|in:hadir,izin,sakit',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'selfie_photo' => 'nullable|image|max:5120',
            'selfie_photo_base64' => 'nullable|string',
            'note' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // For sick/permit proof
        ]);

        $user = Auth::user();
        
        // Find session by token
        $session = AttendanceSession::where('token', $request->token)->first();

        if (!$session) {
            return redirect()->back()->with('error', 'Token sesi tidak valid.');
        }

        // Validate attendance Logic (active, time, duplicate)
        $validation = $this->attendanceService->validateAttendance($session, $user);
        if (!$validation['valid']) {
            return redirect()->back()->with('error', $validation['message']);
        }

        // Process Selfie
        $selfiePath = null;
        if ($request->hasFile('selfie_photo')) {
            // Priority 1: File Upload
            $file = $request->file('selfie_photo');
            $filename = 'selfie_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $selfiePath = $file->storeAs('attendance/selfies', $filename, 'public');
        } elseif ($request->selfie_photo_base64) {
            // Priority 2: Base64 from Canvas
            $imageParts = explode(";base64,", $request->selfie_photo_base64);
            if (isset($imageParts[1])) {
                $imageBase64 = base64_decode($imageParts[1]);
                $filename = 'selfie_' . time() . '_' . $user->id . '.png';
                $selfiePath = 'attendance/selfies/' . $filename;
                Storage::disk('public')->put($selfiePath, $imageBase64);
            }
        }

        if (!$selfiePath) {
            return redirect()->back()->with('error', 'Selfie wajib diambil.');
        }

        // Create Record
        $record = AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'user_id' => $user->id,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'selfie_photo' => $selfiePath,
            'note' => $request->note,
            'scanned_at' => now(),
        ]);

        // Process Attachment if exists (izin/sakit)
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attendance/attachments', 'public');
            
            AttendanceAttachment::create([
                'attendance_record_id' => $record->id,
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
            ]);
        }

        $rolePrefix = $user->role->lower_name;
        return redirect()->route($rolePrefix . '.attendance.index')->with('success', 'Absensi berhasil dikirim!');
    }
}
