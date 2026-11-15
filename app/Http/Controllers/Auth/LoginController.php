<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     * Jika pengguna sudah terotentikasi, langsung arahkan ke dashboard masing-masing.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Menangani proses autentikasi pengguna.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi input: username dan password wajib diisi
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = $request->input('username');
        $passwordInput = $request->input('password');

        // Mendukung login menggunakan username maupun email
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $loginInput,
            'password' => $passwordInput,
        ];

        // 2. Cek kecocokan kredensial menggunakan mekanisme autentikasi bawaan Laravel (Auth::validate)
        // Auth::validate melakukan verifikasi password secara aman via algoritma Bcrypt (Hash::check)
        if (! Auth::validate($credentials)) {
            return back()->withErrors([
                'username' => 'Username atau kata sandi salah.',
            ])->onlyInput('username');
        }

        // 3. Ambil data user yang berhasil divalidasi
        $user = User::where($loginField, $loginInput)->first();

        // 4. Tolak login jika akun berstatus nonaktif (is_active = false)
        if (! $user || ! $user->is_active) {
            return back()->withErrors([
                'username' => 'Akun Anda sedang dinonaktifkan.',
            ])->onlyInput('username');
        }

        // 5. Login pengguna ke dalam session Laravel
        Auth::login($user, $request->boolean('remember'));

        // 6. Session Regeneration: Mencegah serangan Session Fixation
        $request->session()->regenerate();

        // 7. Pengalihan halaman (Redirect) berdasarkan Role pengguna
        return $this->authenticatedRedirect($user);
    }

    /**
     * Mengarahkan pengguna ke dashboard sesuai role yang dimilikinya.
     */
    protected function authenticatedRedirect(User $user): RedirectResponse
    {
        $roleSlug = $user->role?->slug;

        return match ($roleSlug) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'pemilik_toko' => redirect()->intended(route('pemilik.dashboard')),
            'karyawan' => redirect()->intended(route('karyawan.dashboard')),
            default => redirect()->intended('/'),
        };
    }

    /**
     * Mengakhiri sesi login (Logout).
     * Melakukan pengosongan session dan regenerasi CSRF token.
     */
    public function logout(Request $request): RedirectResponse
    {
        // 1. Keluarkan pengguna dari autentikasi Laravel guard
        Auth::logout();

        // 2. Invalidasi session lama di server
        $request->session()->invalidate();

        // 3. Regenerasi CSRF token untuk mencegah serangan CSRF pada sesi berikutnya
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah berhasil keluar dari sistem.');
    }
}
