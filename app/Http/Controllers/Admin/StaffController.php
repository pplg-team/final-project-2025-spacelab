<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of staff users.
     */
    public function index(Request $request)
    {
        $query = User::with('role')
            ->whereHas('role', function ($q) {
                $q->where('name', 'Staff');
            });

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $staff = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.staff.index', [
            'title' => 'Staff',
            'description' => 'Kelola data staff',
            'staff' => $staff,
        ]);
    }

    /**
     * Store a newly created staff user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();

            // Get or create Staff role
            $role = Role::where('name', 'Staff')->first();
            if (!$role) {
                $role = Role::create(['name' => 'Staff']);
            }

            // Create User with Staff role
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'role_id' => $role->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Staff berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan staff: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified staff user (JSON).
     */
    public function show($id)
    {
        try {
            $staff = User::with('role')->findOrFail($id);

            // Ensure user is a staff
            if ($staff->role?->name !== 'Staff') {
                return response()->json(['error' => 'User bukan staff'], 404);
            }

            return response()->json([
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'role' => $staff->role?->name,
                'last_login_at' => $staff->last_login_at?->format('d M Y H:i'),
                'created_at' => $staff->created_at?->format('d M Y'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data staff: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified staff user.
     */
    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        // Ensure user is a staff
        if ($staff->role?->name !== 'Staff') {
            return redirect()->back()->with('error', 'User bukan staff');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
        ];

        // Only validate password if provided
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
            $messages['password.min'] = 'Password minimal 8 karakter';
            $messages['password.confirmed'] = 'Konfirmasi password tidak cocok';
        }

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password_hash'] = Hash::make($request->password);
            }

            $staff->update($updateData);

            DB::commit();

            return redirect()->back()->with('success', 'Data staff berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data staff: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified staff user.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $staff = User::findOrFail($id);

            // Ensure user is a staff
            if ($staff->role?->name !== 'Staff') {
                return redirect()->back()->with('error', 'User bukan staff');
            }

            // Check if this is the last admin (safety check)
            if ($staff->role?->name === 'Admin') {
                $adminCount = User::whereHas('role', fn($q) => $q->where('name', 'Admin'))->count();
                if ($adminCount <= 1) {
                    return redirect()->back()->with('error', 'Tidak dapat menghapus admin terakhir.');
                }
            }

            // Delete user
            $staff->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Staff berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus staff: ' . $e->getMessage());
        }
    }

    /**
     * Reset password for staff user.
     */
    public function resetPassword($id)
    {
        try {
            $staff = User::findOrFail($id);

            // Ensure user is a staff
            if ($staff->role?->name !== 'Staff') {
                return redirect()->back()->with('error', 'User bukan staff');
            }

            // Reset to default password
            $staff->update([
                'password_hash' => Hash::make('staff123'),
            ]);

            return redirect()->back()->with('success', 'Password staff berhasil direset ke default (staff123).');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
}
