<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Classroom;
use App\Models\Major;
use App\Models\Teacher;
use App\Models\GuardianClassHistory;
use App\Models\Term;
use App\Models\Block;
use App\Models\Student;
use App\Models\ClassHistory;
use App\Services\QueryOptimizationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    public function index()
    {
        $majors = Major::select('id', 'name', 'code')
            ->with(['classes' => function ($query) {
                $query->select('id', 'major_id', 'level', 'rombel', 'full_name')
                    ->orderBy('level', 'asc')
                    ->orderBy('rombel', 'asc');
            }])
            ->paginate(50);

        return view('admin.classroom.index', [
            'title' => 'Kelas',
            'description' => 'Halaman kelas',
            'majors' => $majors,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'level' => 'required|in:10,11,12',
            'rombel' => 'required|in:1,2,3,4,5',
        ]);

        // Check if classroom already exists
        $exists = Classroom::where('major_id', $validated['major_id'])
            ->where('level', $validated['level'])
            ->where('rombel', $validated['rombel'])
            ->exists();

        if ($exists) {
            return redirect()->route('admin.classrooms.index')
                ->with('error', 'Kelas sudah ada untuk jurusan, level, dan rombel ini.');
        }

        Classroom::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => $validated['major_id'] . '-' . $validated['level'] . '-' . $validated['rombel'],
            'action' => 'create_classroom',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' membuat kelas baru pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show($id)
    {
        $classroom = Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
            ->with('major:id,name,code')
            ->findOrFail($id);
        
        $activeTerm = QueryOptimizationService::getActiveTerm();

        // Get current guardian
        $guardian = GuardianClassHistory::select('id', 'class_id', 'teacher_id', 'started_at', 'ended_at')
            ->where('class_id', $classroom->id)
            ->where(function ($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>=', Carbon::now());
            })
            ->orderByDesc('started_at')
            ->with('teacher.user:id,name')
            ->first();

        // Get students in this class for the active term
        $students = collect();
        if ($activeTerm) {
            $students = ClassHistory::select('id', 'student_id', 'class_id', 'terms_id')
                ->where('class_id', $classroom->id)
                ->where('terms_id', $activeTerm->id)
                ->with('student.user:id,name,email')
                ->get()
                ->map(function ($history) {
                    return $history->student;
                })
                ->sortBy(function ($student) {
                    return $student->user->name;
                })
                ->values();
        }

        $teachers = Teacher::select('id', 'user_id', 'code')
            ->with('user:id,name')
            ->paginate(50);

        $availableStudents = collect();
        if ($activeTerm) {
            $assignedStudentIds = ClassHistory::select('student_id')
                ->where('terms_id', $activeTerm->id)
                ->pluck('student_id');

            $availableStudents = Student::select('id', 'users_id', 'nis', 'nisn')
                ->with('user:id,name,email')
                ->whereNotIn('id', $assignedStudentIds)
                ->paginate(50);
        }

        return view('admin.classroom.show', [
            'title' => 'Detail Kelas',
            'description' => 'Detail informasi kelas',
            'classroom' => $classroom,
            'guardian' => $guardian,
            'activeTerm' => $activeTerm,
            'students' => $students,
            'teachers' => $teachers,
            'availableStudents' => $availableStudents,
        ]);
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $validated = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'level' => 'required|in:10,11,12',
            'rombel' => 'required|in:1,2,3,4,5',
        ]);

        // Check if classroom already exists (excluding current classroom)
        $exists = Classroom::where('major_id', $validated['major_id'])
            ->where('level', $validated['level'])
            ->where('rombel', $validated['rombel'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->route('admin.classrooms.index')
                ->with('error', 'Kelas sudah ada untuk jurusan, level, dan rombel ini.');
        }

        $classroom->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => $classroom->id,
            'action' => 'update_classroom',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' memperbarui kelas pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => $classroom->id,
            'action' => 'delete_classroom',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' menghapus kelas pada ' . now()->toDateTimeString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
