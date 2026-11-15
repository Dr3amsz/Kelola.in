<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Menangani request yang masuk dan memverifikasi role pengguna.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Daftar role yang diizinkan (variadic)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Pengguna wajib terotentikasi (login) terlebih dahulu
        if (! Auth::check()) {
            return redirect()->route('login')->withErrors([
                'username' => 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.',
            ]);
        }

        $user = Auth::user();

        // 2. Tolak jika akun berstatus nonaktif (is_active = false)
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'username' => 'Akun Anda sedang dinonaktifkan.',
            ]);
        }

        // 3. Ambil role slug pengguna melalui relasi user->role
        $userRoleSlug = $user->role?->slug;

        // Bersihkan whitespace jika parameter dipisahkan spasi (misal: "admin, pemilik_toko")
        $allowedRoles = array_map('trim', $roles);

        // 4. Jika role pengguna cocok dengan salah satu role yang diizinkan, lanjutkan request
        if ($userRoleSlug && in_array($userRoleSlug, $allowedRoles, true)) {
            return $next($request);
        }

        // 5. Jika role tidak sesuai, tolak dengan response HTTP 403 Forbidden
        abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengakses halaman ini.');
    }
}
