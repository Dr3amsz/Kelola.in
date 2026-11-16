<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna Baru - Kelola.in</title>
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
                    <span class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">Tambah Pengguna Baru</span>
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                ← Batal & Kembali
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="mb-6 pb-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800">Formulir Tambah Pengguna</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftarkan akun staf baru untuk operasional toko sembako.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

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
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: Budi Santoso"
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('name') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition"
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
                            value="{{ old('username') }}"
                            required
                            placeholder="Contoh: budi_kasir"
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('username') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition"
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
                            value="{{ old('email') }}"
                            required
                            placeholder="budi@kelolain.com"
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('email') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition"
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
                            <option value="">-- Pilih Peran Akun --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }} ({{ $role->slug }})
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kata Sandi Awal -->
                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Kata Sandi Awal <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('password') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 @enderror text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition"
                        >
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Ulangi Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            placeholder="Masukkan kata sandi yang sama"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-indigo-200 focus:border-indigo-600 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 transition"
                        >
                    </div>

                    <!-- Status Akun -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">
                            Status Akun Awal <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                <span class="text-sm font-medium text-slate-700">Aktif (Dapat Login)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-slate-300">
                                <span class="text-sm font-medium text-slate-700">Nonaktif (Diblokir)</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition">
                        Simpan Pengguna Baru
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
