<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::select('id', 'user_id', 'code', 'phone', 'avatar')
            ->with([
                'user:id,name,email',
                'subjects:id,name,code',
                'guardianClassHistories.class:id,full_name',
                'roleAssignments.major:id,name',
                'asCoordinatorAssignments.major:id,name'
            ])
            ->paginate(15);

        $subjects = Subject::select('id', 'name', 'code')
            ->orderBy('name')
            ->paginate(50);

        return view('admin.teacher.index', [
            'title' => 'Guru',
            'description' => 'Halaman guru',
            'teachers' => $teachers,
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'code' => 'nullable|string|unique:teachers,code',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama guru wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'code.unique' => 'Kode guru sudah digunakan',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            // Create User
            $role = Role::where('name', 'Guru')->first();
            if (!$role) {
                $role = Role::create(['name' => 'Guru']);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password_hash' => 'teacher123', // Default password
                'role_id' => $role->id,
            ]);

            // Handle avatar upload
            $avatar = null;
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar')->store('teachers/avatars', 'public');
            }

            // Create Teacher
            Teacher::create([
                'user_id' => $user->id,
                'code' => $request->code,
                'phone' => $request->phone,
                'avatar' => $avatar,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Guru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan guru: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $teacher = Teacher::select('id', 'user_id', 'code', 'phone', 'avatar')
                ->with([
                    'user:id,name,email',
                    'subjects:id,name,code',
                    'guardianClassHistories:id,class_id,teacher_id,started_at,ended_at',
                    'guardianClassHistories.class:id,full_name,major_id',
                    'guardianClassHistories.class.major:id,name',
                    'roleAssignments:id,teacher_id,major_id',
                    'roleAssignments.major:id,name',
                    'asCoordinatorAssignments:id,teacher_id,major_id',
                    'asCoordinatorAssignments.major:id,name'
                ])
                ->findOrFail($id);

            return response()->json([
                'id' => $teacher->id,
                'name' => $teacher->user->name,
                'email' => $teacher->user->email,
                'code' => $teacher->code,
                'phone' => $teacher->phone,
                'avatar' => $teacher->avatar,
                'subjects' => $teacher->subjects->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'code' => $s->code,
                ]),
                'guardian_histories' => $teacher->guardianClassHistories->map(fn($h) => [
                    'classroom' => $h->class->full_name ?? '-',
                    'major' => $h->class->major->name ?? '-',
                    'started_at' => $h->started_at?->format('d M Y') ?? '-',
                    'ended_at' => $h->ended_at?->format('d M Y') ?? 'Sekarang',
                ]),
                'role_assignments' => $teacher->roleAssignments->map(fn($r) => [
                    'major' => $r->major->name ?? '-',
                    'role' => 'Kepala Jurusan',
                ]),
                'coordinator_assignments' => $teacher->asCoordinatorAssignments->map(fn($r) => [
                    'major' => $r->major->name ?? '-',
                    'role' => 'Koordinator Program',
                ]),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data guru: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'code' => 'nullable|string|unique:teachers,code,' . $id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama guru wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'code.unique' => 'Kode guru sudah digunakan',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            // Update User
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Handle avatar upload
            $avatar = $teacher->avatar;
            if ($request->hasFile('avatar')) {
                if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
                    Storage::disk('public')->delete($teacher->avatar);
                }
                $avatar = $request->file('avatar')->store('teachers/avatars', 'public');
            }

            // Update Teacher
            $teacher->update([
                'code' => $request->code,
                'phone' => $request->phone,
                'avatar' => $avatar,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data guru: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $teacher = Teacher::findOrFail($id);
            $user = $teacher->user;

            // Check for dependencies
            if ($teacher->teacherSubjects()->exists()) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus guru yang masih memiliki mata pelajaran.');
            }

            if ($teacher->guardianClassHistories()->exists()) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus guru yang pernah/sedang menjadi wali kelas.');
            }

            if ($teacher->roleAssignments()->exists() || $teacher->asCoordinatorAssignments()->exists()) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus guru yang memiliki jabatan struktural.');
            }

            // Delete avatar if exists
            if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
                Storage::disk('public')->delete($teacher->avatar);
            }

            // Delete teacher
            $teacher->delete();

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Guru berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }
}
