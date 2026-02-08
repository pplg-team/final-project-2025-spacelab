<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Major;
use App\Models\Teacher;
use App\Models\GuardianClassHistory;
use App\Models\Term;
use App\Models\Block;
use App\Models\Student;
use App\Models\ClassHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $majors = Major::with(['classes' => function ($query) {
            $query->orderBy('level', 'asc')
                ->orderBy('rombel', 'asc');
        }])->get();

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

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }



    public function show($id)
    {
        $classroom = Classroom::with('major')->findOrFail($id);
        $activeTerm = Term::where('is_active', true)->first();

        // Get current guardian
        $guardian = GuardianClassHistory::where('class_id', $classroom->id)
            ->where(function ($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>=', Carbon::now());
            })
            ->orderByDesc('started_at')
            ->with('teacher.user')
            ->first();

        // Get students in this class for the active term
        $students = collect();
        if ($activeTerm) {
            $students = ClassHistory::where('class_id', $classroom->id)
                ->where('terms_id', $activeTerm->id)
                ->with('student.user')
                ->get()
                ->map(function ($history) {
                    return $history->student;
                })
                ->sortBy(function ($student) {
                    return $student->user->name;
                })
                ->values();
        }

        $teachers = Teacher::with('user')->get();

        $availableStudents = collect();
        if ($activeTerm) {
            $assignedStudentIds = ClassHistory::where('terms_id', $activeTerm->id)
                ->pluck('student_id');

            $availableStudents = Student::with('user')
                ->whereNotIn('id', $assignedStudentIds)
                ->get();
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

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
