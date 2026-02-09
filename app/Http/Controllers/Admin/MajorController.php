<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ClassHistory;
use App\Models\Major;
use App\Models\RoleAssignment;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\Company;
use App\Models\CompanyRelation;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = Major::withCount(['classes', 'roleAssignments'])
            ->orderBy('code')
            ->paginate(9);

        return view('admin.major.index', [
            'majors' => $majors,
            'title' => 'Jurusan',
            'description' => 'Halaman manajemen jurusan',
        ]);
    }

    public function show(Major $major)
    {
        // $major is already injected via route binding

        $activeTerm = Term::where('is_active', true)->first();

        // Initialize variables based on the requested $major

        // 1. Classes
        $classQuery = $major->classes();
        if ($activeTerm) {
            // We can eager load student count filtered by active term
            $classQuery->withCount(['classHistories as students_count' => function ($q) use ($activeTerm) {
                $q->where('terms_id', $activeTerm->id);
            }]);
        } else {
            // If no active term, still count students but without term filter
            $classQuery->withCount(['classHistories as students_count']);
        }
        $classes = $classQuery->paginate(10, ['*'], 'classes_page');

        // 2. Teachers (Head, Coordinator, and Subject Teachers)
        // Fetch Assignment for this Major (Head & Coordinator)
        $assignmentQuery = RoleAssignment::with(['head.user', 'programCoordinator.user'])
            ->where('major_id', $major->id);

        if ($activeTerm) {
            $assignmentQuery->where('terms_id', $activeTerm->id);
        } else {
            $assignmentQuery->latest();
        }
        $assignment = $assignmentQuery->first();

        // Fetch Subject Teachers
        $majorSubjects = $major->majorSubjects()->with('subject')->get();
        $subjectIds = $majorSubjects->pluck('subject_id')->all();

        $teacherIds = [];
        if (!empty($subjectIds)) {
            $teacherIds = TeacherSubject::whereIn('subject_id', $subjectIds)->pluck('teacher_id')->unique()->all();
        }

        // Add Head and Coordinator to teacher list
        if ($assignment) {
            if ($assignment->head) $teacherIds[] = $assignment->head->id;
            if ($assignment->programCoordinator) $teacherIds[] = $assignment->programCoordinator->id;
        }
        $teacherIds = array_values(array_unique($teacherIds));
        $teacherCount = count($teacherIds);

        $teachers = collect();
        if (!empty($teacherIds)) {
            $teachers = Teacher::whereIn('id', $teacherIds)->with('user')->paginate(10, ['*'], 'teachers_page');
        }

        // 3. Stats
        $stats = [
            'class_count' => $major->classes()->count(),
            'room_count' => 0, // Logic for room count seems redundant in original code (same as classes), keeping it simple or fixing it?
            // Original: $stats['room_count'] = $major->classes()->count();
            // Let's keep it consistent with original for now but maybe it refers to homerooms?
            // Actually major doesn't directly own rooms usually, accessing rooms via classes -> room relation would be better but let's stick to original behavior for now validation.
            'student_count' => 0,
        ];

        // Correct Room Count Logic? If classes have specific rooms.
        // Original code was just counting classes again. Let's leave it as is to avoid breaking expectations, or arguably it meant unique rooms used by classes?
        $stats['room_count'] = $major->classes()->count();

        // Student Count
        $classIds = $major->classes()->pluck('id')->all();
        if (!empty($classIds)) {
            $studentQuery = ClassHistory::whereIn('class_id', $classIds);
            if ($activeTerm) {
                $studentQuery->where('terms_id', $activeTerm->id);
            }
            $stats['student_count'] = $studentQuery->count();
        }

        // 4. Companies (Existing Relations)
        $companies = $major->companyRelations()->with('company')->get();

        // 5. All Companies (For Dropdown)
        $allCompanies = Company::orderBy('name')->get();

        $subjectCount = $majorSubjects->count();

        // 6. Eligible Teachers for Roles
        // Logic: Teachers NOT assigned as Head or Coordinator in ANY major for the active term
        // EXCEPT the ones currently assigned to THIS major (so they appear in dropdown)
        $eligibleTeachers = Teacher::with('user');

        if ($activeTerm) {
            $busyTeacherIds = RoleAssignment::where('terms_id', $activeTerm->id)
                ->where('major_id', '!=', $major->id) // Ignore assignments for THIS major
                ->get()
                ->flatMap(function ($assignment) {
                    return [$assignment->head_of_major_id, $assignment->program_coordinator_id];
                })
                ->filter()
                ->unique()
                ->all();

            if (!empty($busyTeacherIds)) {
                $eligibleTeachers->whereNotIn('id', $busyTeacherIds);
            }
        }
        $eligibleTeachers = $eligibleTeachers->get()->sortBy('user.name');

        return view('admin.major.show', [
            'major' => $major,
            'assignment' => $assignment,
            'classes' => $classes,
            'teachers' => $teachers,
            'stats' => $stats,
            'activeTerm' => $activeTerm,
            'companies' => $companies,
            'allCompanies' => $allCompanies,
            'eligibleTeachers' => $eligibleTeachers,
            'majorSubjects' => $majorSubjects,
            'teacherCount' => $teacherCount,
            'subjectCount' => $subjectCount,
            'title' => 'Jurusan',
            'description' => 'Halaman manajemen jurusan',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:majors,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'slogan' => 'nullable|string|max:255',
        ], [
            'code.required' => 'Kode jurusan wajib diisi',
            'code.unique' => 'Kode jurusan sudah digunakan',
            'name.required' => 'Nama jurusan wajib diisi',
            'logo.image' => 'File harus berupa gambar',
            'logo.max' => 'Ukuran gambar maksimal 2MB',
            'website.url' => 'Format website tidak valid',
            'contact_email.email' => 'Format email tidak valid',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('majors/logos', 'public');
        }

        Major::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => $validated['code'],
            'action' => 'create_major',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' membuat jurusan baru pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('majors', 'code')->ignore($major->id)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'slogan' => 'nullable|string|max:255',
        ], [
            'code.required' => 'Kode jurusan wajib diisi',
            'code.unique' => 'Kode jurusan sudah digunakan',
            'name.required' => 'Nama jurusan wajib diisi',
            'logo.image' => 'File harus berupa gambar',
            'logo.max' => 'Ukuran gambar maksimal 2MB',
            'website.url' => 'Format website tidak valid',
            'contact_email.email' => 'Format email tidak valid',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($major->logo && Storage::disk('public')->exists($major->logo)) {
                Storage::disk('public')->delete($major->logo);
            }

            $validated['logo'] = $request->file('logo')->store('majors/logos', 'public');
        }

        $major->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'jurusan (' . $major->name . ')',
            'record_id' => $major->id,
            'action' => 'update_major',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' memperbarui jurusan pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->back()
            ->with('success', 'Jurusan berhasil diperbarui');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Major $major)
    {
        // Check if major has classes
        if ($major->classes()->count() > 0) {
            return redirect()->route('admin.majors.index')
                ->with('error', 'Jurusan tidak dapat dihapus karena masih memiliki kelas');
        }

        // Delete logo if exists
        if ($major->logo && Storage::disk('public')->exists($major->logo)) {
            Storage::disk('public')->delete($major->logo);
        }

        $major->delete();


        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'jurusan (' . $major->name . ')',
            'record_id' => $major->id,
            'action' => 'delete_major',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' menghapus jurusan pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil dihapus');
    }
}
