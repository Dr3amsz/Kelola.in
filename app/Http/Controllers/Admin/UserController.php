<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Menampilkan daftar seluruh pengguna dengan filter pencarian dan pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');

        $users = User::with('role')
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
            ->when($statusFilter !== null && $statusFilter !== '', function ($query) use ($statusFilter) {
                $query->where('is_active', (bool) $statusFilter);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles', 'search', 'roleFilter', 'statusFilter'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create(): View
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'is_active' => ['required', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.unique' => 'Username ini sudah digunakan, silakan pilih yang lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi awal wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role_id.required' => 'Peran (Role) pengguna wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid di database.',
        ]);

        // Hash kata sandi awal menggunakan algoritma Bcrypt bawaan Laravel
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna baru '{$validated['name']}' berhasil ditambahkan ke sistem.");
    }

    /**
     * Menampilkan detail satu pengguna.
     */
    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Menampilkan form edit pengguna.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'min:3',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'is_active' => ['required', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal 6 karakter jika ingin diubah.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'role_id.required' => 'Role pengguna wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',
        ]);

        // Validasi Aturan Keamanan 7: Admin dilarang menonaktifkan akunnya sendiri
        if ($user->id === Auth::id() && ! $request->boolean('is_active')) {
            return back()->withInput()->with('error', 'Tindakan ditolak: Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // Jika password diisi, hash password baru; jika kosong, pertahankan password lama
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Menghapus akun pengguna dari database.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Validasi Aturan Keamanan 6: Admin dilarang menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tindakan ditolak: Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna '{$userName}' berhasil dihapus dari sistem.");
    }

    /**
     * Mengaktifkan atau menonaktifkan status akun pengguna secara cepat.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        // Validasi Aturan Keamanan 7: Admin dilarang menonaktifkan akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tindakan ditolak: Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.users.index')
            ->with('success', "Status akun '{$user->name}' berhasil {$statusText}.");
    }
}
