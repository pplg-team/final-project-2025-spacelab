<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\AttendanceAttachment;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        // Detailed validation
        $validated = $request->validate([
            'token' => 'required|string|min:1',
            'status' => 'required|in:hadir,izin,sakit',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'selfie_photo' => 'nullable|image|max:5120',
            'selfie_photo_base64' => 'nullable|string',
            'note' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'token.required' => 'QR Code sesi belum di-scan',
            'token.min' => 'QR Code tidak valid',
            'status.required' => 'Status kehadiran harus dipilih',
            'status.in' => 'Status kehadiran tidak valid',
            'latitude.required' => 'Lokasi (latitude) tidak terdeteksi. Aktifkan GPS',
            'latitude.numeric' => 'Lokasi (latitude) tidak valid',
            'longitude.required' => 'Lokasi (longitude) tidak terdeteksi. Aktifkan GPS',
            'longitude.numeric' => 'Lokasi (longitude) tidak valid',
            'selfie_photo.image' => 'Selfie harus berupa file gambar',
            'selfie_photo.max' => 'Ukuran selfie maksimal 5MB',
            'attachment.file' => 'Lampiran harus berupa file',
            'attachment.mimes' => 'Lampiran harus format: JPG, JPEG, PNG, atau PDF',
            'attachment.max' => 'Ukuran lampiran maksimal 2MB',
        ]);

        $user = Auth::user();
        
        // Find session by token
        $session = AttendanceSession::where('token', $request->token)->first();

        if (!$session) {
            return redirect()->back()->withInput()->with('error', 'Token sesi tidak valid atau sudah kadaluarsa.');
        }

        // Validate attendance Logic (active, time, duplicate)
        $validation = $this->attendanceService->validateAttendance($session, $user);
        if (!$validation['valid']) {
            return redirect()->back()->withInput()->with('error', $validation['message']);
        }

        // Process Selfie - Priority: file upload > base64
        $selfiePath = null;
        if ($request->hasFile('selfie_photo')) {
            // Priority 1: File Upload from fallback
            $file = $request->file('selfie_photo');
            $filename = 'selfie_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $selfiePath = $file->storeAs('attendance/selfies', $filename, 'public');
        } elseif ($request->filled('selfie_photo_base64')) {
            // Priority 2: Base64 from Canvas
            try {
                $base64String = $request->selfie_photo_base64;
                
                // Remove the data URL prefix if present
                if (strpos($base64String, 'data:image') === 0) {
                    $imageParts = explode(";base64,", $base64String);
                    if (!isset($imageParts[1])) {
                        return redirect()->back()->withInput()->with('error', 'Format selfie base64 tidak valid.');
                    }
                    $base64String = $imageParts[1];
                }
                
                $imageData = base64_decode($base64String, true);
                if ($imageData === false) {
                    return redirect()->back()->withInput()->with('error', 'Selfie base64 corrupted atau tidak valid.');
                }
                
                $filename = 'selfie_' . time() . '_' . $user->id . '.jpg';
                $selfiePath = 'attendance/selfies/' . $filename;
                Storage::disk('public')->put($selfiePath, $imageData);
            } catch (\Exception $e) {
                Log::error('Selfie base64 processing error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal memproses selfie: ' . $e->getMessage());
            }
        }

        if (!$selfiePath) {
            return redirect()->back()->withInput()->with('error', 'Selfie wajib diambil melalui kamera atau upload file.');
        }

        // Create Record
        try {
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
        } catch (\Exception $e) {
            Log::error('Attendance store error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }
}
