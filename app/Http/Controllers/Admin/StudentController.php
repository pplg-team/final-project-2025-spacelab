<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Classroom;
use App\Models\ClassHistory;
use App\Models\Term;
use App\Models\Role;
use App\Models\Major;
use App\Services\QueryOptimizationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $majors = Major::select('id', 'name', 'code')->orderBy('name')->paginate(50);
        $classrooms = Classroom::select('id', 'major_id', 'level', 'rombel', 'full_name')
            ->with('major:id,name,code')
            ->orderBy('level')
            ->orderBy('rombel')
            ->paginate(50);

        return view('admin.student.index', [
            'title' => 'Siswa',
            'description' => 'Halaman siswa',
            'majors' => $majors,
            'classrooms' => $classrooms,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nis' => 'nullable|string|unique:students,nis',
            'nisn' => 'required|string|unique:students,nisn',
            'classroom_id' => 'required|exists:classes,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama siswa wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah digunakan',
            'classroom_id.required' => 'Kelas wajib dipilih',
            'classroom_id.exists' => 'Kelas yang dipilih tidak valid',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            // Get active term with caching
            $activeTerm = QueryOptimizationService::getActiveTerm();
            if (!$activeTerm) {
                throw new \Exception('No active term found.');
            }

            // 2. Create User
            $role = Role::where('name', 'Siswa')->firstOrFail();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password_hash' => Hash::make($request->nisn),
                'role_id' => $role->id,
            ]);

            // 3. Create Student
            $avatar = null;
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar')->store('students/avatars', 'public');
            }

            $student = Student::create([
                'users_id' => $user->id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'avatar' => $avatar,
            ]);

            // 4. Assign to Class (ClassHistory)
            ClassHistory::create([
                'student_id' => $student->id,
                'class_id' => $request->classroom_id,
                'terms_id' => $activeTerm->id,
                'block_id' => $activeTerm->blocks->first()->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }



    public function show($id)
    {
        try {
            $activeTerm = QueryOptimizationService::getActiveTerm();

            $classHistory = ClassHistory::where('student_id', $id)
                ->where('terms_id', $activeTerm->id)
                ->with(['student.user:id,name,email', 'classroom.major:id,name,code', 'block:id,name'])
                ->select('id', 'student_id', 'class_id', 'terms_id', 'block_id')
                ->first();

            if (!$classHistory || !$classHistory->student) {
                return response()->json(['error' => 'Student not found'], 404);
            }

            $student = $classHistory->student;
            $user = $student->user;

            return response()->json([
                'id' => $student->id,
                'name' => $user->name,
                'email' => $user->email,
                'nis' => $student->nis,
                'nisn' => $student->nisn,
                'avatar' => $student->avatar,
                'classroom' => $classHistory->classroom->full_name ?? '-',
                'classroom_id' => $classHistory->classroom->id ?? null,
                'major' => $classHistory->classroom->major->name ?? '-',
                'major_code' => $classHistory->classroom->major->code ?? '-',
                'block' => $classHistory->block->name ?? '-',
                'term' => $activeTerm->tahun_ajaran ?? '-',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch student data'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Student::find($id)->users_id,
            'nis' => 'nullable|string|unique:students,nis,' . $id,
            'nisn' => 'required|string|unique:students,nisn,' . $id,
            'classroom_id' => 'required|exists:classes,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama siswa wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah digunakan',
            'classroom_id.required' => 'Kelas wajib dipilih',
            'classroom_id.exists' => 'Kelas yang dipilih tidak valid',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($id);
            $user = $student->user;

            // Update user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Handle avatar upload
            $avatar = $student->avatar;
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
                    Storage::disk('public')->delete($student->avatar);
                }
                $avatar = $request->file('avatar')->store('students/avatars', 'public');
            }

            // Update student
            $student->update([
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'avatar' => $avatar
            ]);

            // Update class history if classroom changed
            $activeTerm = QueryOptimizationService::getActiveTerm();
            $classHistory = ClassHistory::where('student_id', $student->id)
                ->where('terms_id', $activeTerm->id)
                ->first();

            if ($classHistory && $classHistory->class_id != $request->classroom_id) {
                $classHistory->update([
                    'class_id' => $request->classroom_id,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $student = Student::findOrFail($id);
            $user = $student->user;

            // Delete class histories
            ClassHistory::where('student_id', $student->id)->delete();

            // Delete student
            $student->delete();

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }
}
