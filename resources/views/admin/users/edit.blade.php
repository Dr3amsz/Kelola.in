<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - Kelola.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold shadow-md shadow-indigo-500/20">
                    K
                </a>
                <div>
                    <h1 class="text-base font-bold text-slate-800 leading-tight">Kelola.in</h1>
                    <span class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">Edit Data Pengguna</span>
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                ← Batal & Kembali
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="mb-6 pb-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Edit Pengguna: {{ $user->name }}</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui profil, hak akses peran, atau setel ulang kata sandi.</p>
                </div>
                @if ($user->id === auth()->id())
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-lg">
                        Akun Anda Saat Ini
                    </span>
                @endif
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3 text-rose-800 text-sm">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="font-medium">{{ session('error') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('name') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 focus:outline-none focus:ring-4 transition"
                        >
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username', $user->username) }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('username') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 focus:outline-none focus:ring-4 transition"
                        >
                        @error('username')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('email') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 focus:outline-none focus:ring-4 transition"
                        >
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Pengguna -->
                    <div>
                        <label for="role_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Peran (Role) <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="role_id"
                            name="role_id"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('role_id') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 focus:outline-none focus:ring-4 transition"
                        >
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }} ({{ $role->slug }})
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Akun -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">
                            Status Akun <span class="text-rose-500">*</span>
                        </label>
                        @if ($user->id === auth()->id())
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 mb-2">
                                <strong>Catatan Keamanan:</strong> Anda tidak dapat menonaktifkan status akun Anda sendiri agar tidak terkunci keluar dari sistem.
                            </div>
                            <input type="hidden" name="is_active" value="1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Aktif (Terkunci untuk akun sendiri)
                            </span>
                        @else
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', (string)$user->is_active) === '1' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                    <span class="text-sm font-medium text-slate-700">Aktif (Dapat Login)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="0" {{ old('is_active', (string)$user->is_active) === '0' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-slate-300">
                                    <span class="text-sm font-medium text-slate-700">Nonaktif (Diblokir)</span>
                                </label>
                            </div>
                        @endif
                        @error('is_active')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pemberitahuan Ganti Password -->
                    <div class="sm:col-span-2 pt-4 border-t border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-800">Ubah Kata Sandi (Opsional)</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kosongkan kedua kolom di bawah jika Anda tidak berniat mengubah kata sandi pengguna ini.</p>
                    </div>

                    <!-- Kata Sandi Baru -->
                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Biarkan kosong jika tidak diubah"
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('password') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition"
                        >
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Kata Sandi Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Ulangi Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ketik ulang kata sandi baru"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition"
                        >
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
