<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::query()
            ->with(['allowedMajors.major', 'teachers.user'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        $majors = Major::orderBy('name')->get();

        $teachers = Teacher::with('user')->get()->sortBy(function($teacher) {
            return $teacher->user->name;
        });

        return view('admin.subjects.index', compact('subjects', 'majors', 'teachers'));
    }

    public function fetch(Request $request)
    {
        $query = Subject::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        return response()->json($query->limit(20)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:subjects,code|max:20',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:teori,praktikum,lainnya',
            'description' => 'nullable|string',
            'allowed_majors' => 'array',
            'allowed_majors.*' => 'exists:majors,id',
        ]);

        $allowedMajors = $validated['allowed_majors'] ?? [];
        unset($validated['allowed_majors']);

        $subject = Subject::create($validated);

        // Create allowed major records
        foreach ($allowedMajors as $majorId) {
            $subject->allowedMajors()->create([
                'major_id' => $majorId,
                'is_allowed' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects')->ignore($subject->id)],
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:teori,praktikum,lainnya',
            'description' => 'nullable|string',
            'allowed_majors' => 'array',
            'allowed_majors.*' => 'exists:majors,id',
        ]);

        $allowedMajors = $validated['allowed_majors'] ?? [];
        unset($validated['allowed_majors']);

        $subject->update($validated);

        // Update allowed major records
        $subject->allowedMajors()->delete();
        foreach ($allowedMajors as $majorId) {
            $subject->allowedMajors()->create([
                'major_id' => $majorId,
                'is_allowed' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->back()->with('success', 'Pelajaran berhasil dihapus.');
    }

    public function updateMajors(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'majors' => 'array',
            'majors.*' => 'exists:majors,id',
        ]);

        // Update subject_major_allowed
        $subject->allowedMajors()->delete();
        foreach ($request->majors ?? [] as $majorId) {
            $subject->allowedMajors()->create([
                'major_id' => $majorId,
                'is_allowed' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Jurusan pelajaran berhasil diperbarui.');
    }

    public function updateTeachers(Request $request, Subject $subject)
    {

        $validated = $request->validate([
            'teachers' => 'array',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $subject->teachers()->sync($request->teachers ?? []);

        return redirect()->back()->with('success', 'Guru pelajaran berhasil diperbarui.');
    }
}
