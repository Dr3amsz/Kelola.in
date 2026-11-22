@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Banner Sapaan -->
    <div class="bg-[#EEF5FF] rounded-[22px] p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 mb-7 relative overflow-hidden">
        <div class="z-10 text-center sm:text-left">
            <h1 class="text-2xl sm:text-[26px] font-bold text-[#0F2B5C] mb-1.5 tracking-tight">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] font-medium">
                Selamat datang kembali di Kelola.in
            </p>
        </div>

        <!-- Ilustrasi Banner Kanan -->
        <div class="z-10 shrink-0">
            <img
                src="{{ asset('images/admin-hero.png') }}"
                alt="Admin Hero"
                class="h-28 sm:h-32 object-contain"
                onerror="this.style.display='none'; document.getElementById('hero-shield-fallback').style.display='flex';"
            >
            <!-- Fallback Ikon Perisai bila file admin-hero.png belum ada -->
            <div id="hero-shield-fallback" class="w-20 h-20 sm:w-24 sm:h-24 bg-[#1A6CF0] rounded-2xl items-center justify-center text-white shadow-lg shadow-blue-500/25 relative" style="display: none;">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Tiga Kartu Statistik Berderet -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        <!-- 1. Total User -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition flex items-center gap-4">
            <div class="w-13 h-13 p-3.5 rounded-full bg-[#EEF5FF] text-[#1A6CF0] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#64748B] mb-0.5">Total User</p>
                <p class="text-2xl font-bold text-slate-900 tracking-tight">{{ $totalUsers }}</p>
            </div>
        </div>

        <!-- 2. Akun Aktif -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition flex items-center gap-4">
            <div class="w-13 h-13 p-3.5 rounded-full bg-[#E6F9EE] text-[#10B981] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#64748B] mb-0.5">Akun Aktif</p>
                <p class="text-2xl font-bold text-slate-900 tracking-tight">{{ $activeUsers }}</p>
            </div>
        </div>

        <!-- 3. Akun Nonaktif -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition flex items-center gap-4">
            <div class="w-13 h-13 p-3.5 rounded-full bg-[#FEECEC] text-[#EF4444] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#64748B] mb-0.5">Akun Nonaktif</p>
                <p class="text-2xl font-bold text-slate-900 tracking-tight">{{ $inactiveUsers }}</p>
            </div>
        </div>
    </div>

    <!-- Dua Panel Bawah -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Panel Kiri: Ringkasan Sistem -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-bold text-[#0F2B5C]">Ringkasan Sistem</h2>
                    <span class="text-xs text-slate-400 font-medium">Berdasarkan Peran</span>
                </div>

                <div class="divide-y divide-slate-100">
                    <!-- Baris 1: Admin -->
                    <div class="py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#EEF5FF] text-[#1A6CF0] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Administrator</span>
                        </div>
                        <span class="text-sm font-bold text-slate-900">{{ $roleCounts['admin'] ?? 0 }} Orang</span>
                    </div>

                    <!-- Baris 2: Pemilik Toko -->
                    <div class="py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#E6F9EE] text-[#10B981] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Pemilik Toko</span>
                        </div>
                        <span class="text-sm font-bold text-slate-900">{{ $roleCounts['pemilik_toko'] ?? 0 }} Orang</span>
                    </div>

                    <!-- Baris 3: Karyawan -->
                    <div class="py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Karyawan</span>
                        </div>
                        <span class="text-sm font-bold text-slate-900">{{ $roleCounts['karyawan'] ?? 0 }} Orang</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-50">
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#1A6CF0] hover:underline flex items-center gap-1">
                    <span>Lihat kelola pengguna selengkapnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

        <!-- Panel Kanan: Aktivitas Terbaru -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-bold text-[#0F2B5C]">Aktivitas Terbaru</h2>
                    <span class="text-xs text-slate-400 font-medium">Pengguna Terdaftar</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($recentUsers as $userItem)
                        @php
                            $roleColors = match($userItem->role?->slug) {
                                'admin' => ['bg' => 'bg-[#EEF5FF]', 'text' => 'text-[#1A6CF0]'],
                                'pemilik_toko' => ['bg' => 'bg-[#E6F9EE]', 'text' => 'text-[#10B981]'],
                                'karyawan' => ['bg' => 'bg-[#FEF3C7]', 'text' => 'text-[#D97706]'],
                                default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600'],
                            };
                        @endphp
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full {{ $roleColors['bg'] }} {{ $roleColors['text'] }} flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate">
                                        User baru: {{ $userItem->name }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $userItem->role?->name ?? 'Belum ada peran' }}
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs text-slate-400 shrink-0 font-medium">
                                {{ $userItem->created_at ? $userItem->created_at->diffForHumans() : 'Baru saja' }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-sm">
                            Belum ada aktivitas
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-50">
                <a href="{{ route('admin.hak-akses.index') }}" class="text-xs font-semibold text-[#1A6CF0] hover:underline flex items-center gap-1">
                    <span>Atur peran & hak akses akun</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>
    </div>
@endsection
