<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     * Jika pengguna sudah terotentikasi, langsung arahkan ke dashboard masing-masing.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = User::findByIdentifier($request->input('username'));

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'username' => 'Username atau kata sandi salah.',
            ])->onlyInput('username');
        }

        if (! $user->is_active) {
            return back()->withErrors([
                'username' => 'Akun Anda sedang dinonaktifkan.',
            ])->onlyInput('username');
        }

        if (! $user->dashboardRouteName()) {
            return back()->withErrors([
                'username' => 'Akun Anda belum memiliki peran. Hubungi Admin.',
            ])->onlyInput('username');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectToDashboard($user);
    }

    protected function redirectToDashboard(User $user): RedirectResponse
    {
        return redirect()->intended(route($user->dashboardRouteName()));
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
