<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hak Akses - Kelola.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl flex items-center justify-center font-bold shadow-md shadow-indigo-500/20 transition">
                    K
                </a>
                <div>
                    <h1 class="text-base font-bold text-slate-800 leading-tight">Kelola.in</h1>
                    <span class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">Otorisasi & Hak Akses (RBAC)</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                    ← Kembali ke Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg border border-rose-200 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Hak Akses & Peran Pengguna</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Tetapkan dan ubah wewenang aktor sistem: Admin, Pemilik Toko, atau Karyawan.</p>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3 text-emerald-800 text-sm shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3 text-rose-800 text-sm shadow-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Kartu Ikhtisar Role & Permission -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            @foreach ($roles as $r)
                @php
                    $style = match($r->slug) {
                        'admin' => ['border' => 'border-indigo-200', 'bg' => 'bg-indigo-50/50', 'text' => 'text-indigo-700', 'pill' => 'bg-indigo-100 text-indigo-700'],
                        'pemilik_toko' => ['border' => 'border-emerald-200', 'bg' => 'bg-emerald-50/50', 'text' => 'text-emerald-700', 'pill' => 'bg-emerald-100 text-emerald-700'],
                        'karyawan' => ['border' => 'border-amber-200', 'bg' => 'bg-amber-50/50', 'text' => 'text-amber-700', 'pill' => 'bg-amber-100 text-amber-700'],
                        default => ['border' => 'border-slate-200', 'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'pill' => 'bg-slate-100 text-slate-700']
                    };
                @endphp
                <div class="bg-white rounded-2xl border {{ $style['border'] }} p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md {{ $style['pill'] }}">
                            {{ $r->slug }}
                        </span>
                        <span class="text-xs text-slate-400 font-semibold">{{ $r->permissions->count() }} Izin Fitur</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">{{ $r->name }}</h3>
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach ($r->permissions->take(4) as $p)
                            <span class="text-[11px] px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md font-medium">
                                {{ $p->name }}
                            </span>
                        @endforeach
                        @if ($r->permissions->count() > 4)
                            <span class="text-[11px] px-1.5 py-0.5 bg-slate-200 text-slate-500 rounded-md font-bold">
                                +{{ $r->permissions->count() - 4 }} lainnya
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filter Pencarian -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.hak-akses.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="sm:col-span-6 relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari pengguna berdasarkan nama, username, atau email..."
                        class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-700 placeholder-slate-400"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <div class="sm:col-span-4">
                    <select name="role" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-700">
                        <option value="">Semua Peran (Role)</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2">
                    <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition text-center">
                        Cari
                    </button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.hak-akses.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Otorisasi User -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 font-semibold">Pengguna</th>
                            <th class="py-3.5 px-4 font-semibold">Email</th>
                            <th class="py-3.5 px-4 font-semibold">Peran Saat Ini</th>
                            <th class="py-3.5 px-4 font-semibold">Cakupan Hak Akses</th>
                            <th class="py-3.5 px-4 font-semibold text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/60 transition {{ $user->id === auth()->id() ? 'bg-indigo-50/30' : '' }}">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs uppercase border border-slate-200">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 flex items-center gap-1.5">
                                                <span>{{ $user->name }}</span>
                                                @if ($user->id === auth()->id())
                                                    <span class="px-1.5 py-0.5 text-[10px] font-bold bg-indigo-100 text-indigo-700 rounded-md">
                                                        Anda
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-slate-400">@<span>{{ $user->username }}</span></p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-xs font-mono text-slate-600">
                                    {{ $user->email }}
                                </td>

                                <td class="py-3.5 px-4">
                                    @php
                                        $roleSlug = $user->role?->slug;
                                        $badgeClass = match($roleSlug) {
                                            'admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'pemilik_toko' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'karyawan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border {{ $badgeClass }}">
                                        {{ $user->role?->name ?? 'Belum ada role' }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-xs text-slate-500">
                                    @if ($user->role)
                                        <span class="font-medium text-slate-700">{{ $user->role->permissions->count() }} Hak Akses Terpasang</span>
                                        <span class="text-slate-400 block text-[11px] truncate max-w-xs">
                                            {{ $user->role->permissions->pluck('name')->implode(', ') }}
                                        </span>
                                    @else
                                        <span class="text-rose-500 italic">Tidak memiliki hak akses</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    @if ($user->id !== auth()->id())
                                        <a
                                            href="{{ route('admin.hak-akses.edit', $user) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg border border-indigo-200 transition"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            <span>Ubah Peran</span>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 italic px-2 py-1 bg-slate-100 rounded-md">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            Akun Sendiri (Terkunci)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400">
                                    Tidak ada data pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>
