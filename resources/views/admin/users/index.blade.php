<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Kelola.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <!-- Navbar Top -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl flex items-center justify-center font-bold shadow-md shadow-indigo-500/20 transition">
                    K
                </a>
                <div>
                    <h1 class="text-base font-bold text-slate-800 leading-tight">Kelola.in</h1>
                    <span class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">Modul Kelola Pengguna</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                    ← Kembali ke Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg border border-rose-200 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Halaman & Tombol Tambah -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Manajemen Data Pengguna</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola akun staf, hak akses, dan status operasional sistem Toko Sembako.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Pengguna Baru</span>
            </a>
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

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- Search Keyword -->
                <div class="sm:col-span-5 relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, username, atau email..."
                        class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Filter Role -->
                <div class="sm:col-span-3">
                    <select name="role" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-700">
                        <option value="">Semua Peran (Role)</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="sm:col-span-2">
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-700">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition text-center">
                        Cari
                    </button>
                    @if(request('search') || request('role') || request('status') !== null && request('status') !== '')
                        <a href="{{ route('admin.users.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Data Pengguna -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 font-semibold">Pengguna</th>
                            <th class="py-3.5 px-4 font-semibold">Email</th>
                            <th class="py-3.5 px-4 font-semibold">Role</th>
                            <th class="py-3.5 px-4 font-semibold">Status Akun</th>
                            <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/60 transition {{ $user->id === auth()->id() ? 'bg-indigo-50/30' : '' }}">
                                <!-- Kolom Pengguna -->
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

                                <!-- Kolom Email -->
                                <td class="py-3.5 px-4 text-xs font-mono text-slate-600">
                                    {{ $user->email }}
                                </td>

                                <!-- Kolom Role -->
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
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium border {{ $badgeClass }}">
                                        {{ $user->role?->name ?? 'Belum ada role' }}
                                    </span>
                                </td>

                                <!-- Kolom Status -->
                                <td class="py-3.5 px-4">
                                    @if ($user->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Nonaktif</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Aksi -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition" title="Edit Data Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        @if ($user->id !== auth()->id())
                                            <!-- Tombol Toggle Status -->
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    class="p-1.5 {{ $user->is_active ? 'text-amber-500 hover:text-amber-700 hover:bg-amber-50' : 'text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50' }} rounded-lg transition"
                                                    title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengubah status akun {{ $user->name }}?')"
                                                >
                                                    @if($user->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Tombol Hapus dengan Konfirmasi -->
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Data yang dihapus tidak dapat dipulihkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic px-2">Akun Saya</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <p class="font-medium text-slate-600">Tidak ada pengguna yang sesuai dengan kriteria pencarian.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci atau reset filter pencarian.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if ($users->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>
