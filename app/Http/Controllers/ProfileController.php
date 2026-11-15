<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil akun pengguna saat ini.
     * Dapat diakses oleh Admin, Pemilik Toko, dan Karyawan.
     */
    public function edit(): View
    {
        $user = Auth::user()->load('role');

        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui data profil pribadi (nama, username, email).
     * Terproteksi: Role dan Status Akun TIDAK DAPAT diubah dari form ini.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

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
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
        ]);

        // Keamanan Ketat: Hanya kolom biodata yang di-update
        // role_id dan is_active sengaja tidak diproses agar tidak dapat disusupi
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('profile.edit')
            ->with('success_profile', 'Informasi profil akun Anda berhasil diperbarui.');
    }

    /**
     * Mengubah kata sandi akun dengan verifikasi kata sandi lama.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.different' => 'Kata sandi baru harus berbeda dari kata sandi lama Anda.',
        ]);

        // 1. Verifikasi kecocokan kata sandi lama menggunakan Hash::check()
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
            ]);
        }

        // 2. Hash kata sandi baru menggunakan Bcrypt sebelum disimpan ke database
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')
            ->with('success_password', 'Kata sandi Anda berhasil diperbarui. Gunakan kata sandi baru ini pada sesi masuk berikutnya.');
    }
}
