<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Hak Akses Pengguna - Kelola.in</title>
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
                <a href="{{ route('admin.hak-akses.index') }}" class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold shadow-md shadow-indigo-500/20">
                    K
                </a>
                <div>
                    <h1 class="text-base font-bold text-slate-800 leading-tight">Kelola.in</h1>
                    <span class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">Penetapan Peran & Hak Akses</span>
                </div>
            </div>

            <a href="{{ route('admin.hak-akses.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                ← Batal & Kembali
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <!-- Kartu Profil Pengguna Sasaran -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-lg uppercase border border-indigo-200">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pengguna yang Diubah</span>
                    <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-500">Username: <span class="font-mono text-slate-700">@<span>{{ $user->username }}</span></span> • Email: <span class="font-mono text-slate-700">{{ $user->email }}</span></p>
                </div>
            </div>

            <div class="text-right">
                <span class="text-xs text-slate-400 block mb-1">Peran Saat Ini:</span>
                <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-lg border border-slate-200">
                    {{ $user->role?->name ?? 'Belum ada peran' }}
                </span>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm">
                @foreach ($errors->all() as $err)
                    <p class="font-medium">• {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulir Perubahan Role -->
        <form method="POST" action="{{ route('admin.hak-akses.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="mb-5">
                    <h3 class="text-base font-bold text-slate-800">Pilih Peran Baru untuk Pengguna</h3>
                    <p class="text-xs text-slate-500 mt-1">Peran menentukan menu, halaman, dan fitur yang dapat diakses oleh akun ini pada sistem Toko Sembako.</p>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @foreach ($roles as $roleOption)
                        @php
                            $isSelected = (old('role_id', $user->role_id) == $roleOption->id);
                            $accentColor = match($roleOption->slug) {
                                'admin' => 'peer-checked:border-indigo-600 peer-checked:bg-indigo-50/30',
                                'pemilik_toko' => 'peer-checked:border-emerald-600 peer-checked:bg-emerald-50/30',
                                'karyawan' => 'peer-checked:border-amber-600 peer-checked:bg-amber-50/30',
                                default => 'peer-checked:border-slate-600 peer-checked:bg-slate-50'
                            };
                        @endphp
                        <label class="relative flex items-start gap-4 p-5 rounded-2xl border-2 border-slate-200 hover:border-slate-300 cursor-pointer transition {{ $accentColor }}">
                            <input
                                type="radio"
                                name="role_id"
                                value="{{ $roleOption->id }}"
                                {{ $isSelected ? 'checked' : '' }}
                                class="peer sr-only"
                            >
                            <div class="w-5 h-5 mt-0.5 rounded-full border-2 border-slate-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 flex items-center justify-center shrink-0 transition">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-bold text-slate-800">{{ $roleOption->name }}</h4>
                                    <span class="text-xs font-mono font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                        slug: {{ $roleOption->slug }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3">
                                    @if($roleOption->slug === 'admin')
                                        Wewenang penuh pengelolaan data akun pengguna, role & permission, master barang, RORIS, dan seluruh sistem.
                                    @elseif($roleOption->slug === 'pemilik_toko')
                                        Akses monitoring bisnis: Dashboard omset toko, laporan pemasukan harian, stok sembako, dan laporan RORIS.
                                    @elseif($roleOption->slug === 'karyawan')
                                        Akses operasional toko harian: Input transaksi kasir, cek stok sembako, dan pencatatan barang masuk (restock).
                                    @endif
                                </p>

                                <!-- Daftar Hak Akses Terkait -->
                                <div class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-100">
                                    @foreach ($roleOption->permissions as $perm)
                                        <span class="text-[11px] px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md font-medium">
                                            ✓ {{ $perm->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.hak-akses.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2"
                    onclick="return confirm('Apakah Anda yakin ingin memperbarui hak akses pengguna {{ $user->name }}?')"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan Hak Akses</span>
                </button>
            </div>
        </form>
    </main>
</body>
</html>
