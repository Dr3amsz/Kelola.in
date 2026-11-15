<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Kelola.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 p-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-rose-50 text-rose-600 rounded-3xl mb-5 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <span class="px-3 py-1 bg-rose-100 text-rose-700 text-xs font-bold uppercase tracking-wider rounded-full">
            HTTP 403 Forbidden
        </span>

        <h1 class="text-2xl font-bold text-slate-800 mt-4">Akses Ditolak</h1>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            {{ $exception->getMessage() ?: 'Peran (Role) akun Anda tidak memiliki izin untuk membuka halaman ini.' }}
        </p>

        @auth
            <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 inline-block text-left">
                <p><span class="font-semibold text-slate-700">Akun Anda:</span> {{ auth()->user()->name }}</p>
                <p><span class="font-semibold text-slate-700">Role Saat Ini:</span> <span class="capitalize text-emerald-600 font-semibold">{{ auth()->user()->role?->name ?? 'Belum ada role' }}</span></p>
            </div>
        @endauth

        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition">
                Kembali ke Dashboard Saya
            </a>

            @auth
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl border border-slate-300 transition">
                        Ganti Akun
                    </button>
                </form>
            @endauth
        </div>
    </div>
</body>
</html>
