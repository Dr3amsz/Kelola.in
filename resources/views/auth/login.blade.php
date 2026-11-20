<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Informasi Toko Sembako (Kelola.in)</title>
    <!-- Tailwind CSS CDN untuk preview instan & konsistensi tampilan -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo & Judul Aplikasi -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 text-white rounded-2xl shadow-lg shadow-emerald-500/30 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Kelola.in</h1>
            <p class="text-sm text-slate-500 mt-1">Sistem Informasi Toko Sembako</p>
        </div>

        <!-- Kartu Form Login -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-800">Masuk ke Akun</h2>
                <p class="text-xs text-slate-500 mt-1">Gunakan username dan kata sandi yang telah didaftarkan oleh Administrator.</p>
            </div>

            <!-- Pesan Notifikasi Sukses / Status Logout -->
            @if (session('status'))
            <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-2.5 text-emerald-800 text-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <!-- Pesan Kesalahan / Error Alert -->
            @if ($errors->any())
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-rose-800 text-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="space-y-1">
                    @foreach ($errors->all() as $error)
                    <p class="font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4"> <!-- CSRF Token: Perlindungan Cross-Site Request Forgery -->
                @csrf

                <!-- Input Username / Email -->
                <div>
                    <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Username atau Email
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            placeholder="Contoh: admin atau admin@kelolain.com"
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('username') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-emerald-200 focus:border-emerald-600 @enderror text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition duration-150">
                    </div>
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('password') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-emerald-200 focus:border-emerald-600 @enderror text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition duration-150">
                    </div>
                </div>

                <!-- Checkbox Ingat Saya -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <span class="text-xs text-slate-600 select-none">Ingat sesi saya</span>
                    </label>
                    <a href="{{ route('password.forgot') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition">
                        Lupa kata sandi?
                    </a>
                </div>

                <!-- Tombol Submit -->
                <button
                    type="submit"
                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 transition duration-150 flex items-center justify-center gap-2">
                    <span>Masuk ke Sistem</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer Hak Akses -->
        <p class="text-center text-xs text-slate-400 mt-6">
            © 2026 Kelola.in • Akun hanya dapat didaftarkan oleh Administrator.
        </p>
    </div>
</body>

</html>