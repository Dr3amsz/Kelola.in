<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoleAccessController extends Controller
{
    /**
     * Menampilkan daftar pengguna beserta role aktif dan perbandingan hak akses (permissions).
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');

        $users = User::with(['role.permissions'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query, $roleFilter) {
                $query->whereHas('role', function ($q) use ($roleFilter) {
                    $q->where('slug', $roleFilter);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::with('permissions')->get();

        return view('admin.hak-akses.index', compact('users', 'roles', 'search', 'roleFilter'));
    }

    /**
     * Menampilkan formulir khusus pengubahan role pengguna.
     */
    public function edit(User $user): View|RedirectResponse
    {
        // Aturan Keamanan: Admin dilarang mengubah role akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.hak-akses.index')
                ->with('error', 'Tindakan ditolak: Anda tidak dapat mengubah peran (role) akun Anda sendiri.');
        }

        $user->load('role.permissions');
        $roles = Role::with('permissions')->get();

        return view('admin.hak-akses.edit', compact('user', 'roles'));
    }

    /**
     * Menyimpan perubahan role pengguna ke dalam database dengan proteksi transaksi.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // 1. Aturan Keamanan: Admin dilarang mengubah role akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.hak-akses.index')
                ->with('error', 'Tindakan ditolak: Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        // 2. Validasi input: role_id wajib ada dan harus eksis di tabel roles
        $validated = $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ], [
            'role_id.required' => 'Silakan pilih peran (role) baru untuk pengguna ini.',
            'role_id.integer' => 'Format role ID tidak valid.',
            'role_id.exists' => 'Role yang dipilih tidak terdaftar di sistem basis data.',
        ]);

        $newRole = Role::findOrFail($validated['role_id']);
        $oldRoleName = $user->role?->name ?? 'Belum memiliki role';

        // 3. Eksekusi pembaruan dalam Transaksi Database (Atomic Operation)
        DB::transaction(function () use ($user, $validated) {
            $user->role_id = $validated['role_id'];
            $user->save();
        });

        return redirect()->route('admin.hak-akses.index')
            ->with('success', "Hak akses '{$user->name}' berhasil diubah dari '{$oldRoleName}' menjadi '{$newRole->name}'.");
    }
}
