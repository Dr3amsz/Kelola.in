@extends('layouts.app')

@section('title', 'Pemasukan Harian')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-[#0F2B5C]">Pemasukan Harian</h1>
        <p class="text-xs text-slate-500 mt-0.5">Catat dan kelola pemasukan penjualan toko sembako.</p>
    </div>
    <a href="{{ route('pemilik.pemasukan.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Catat Pemasukan
    </a>
</div>

{{-- Flash success --}}
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Tabel Daftar Pemasukan --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    @if ($pemasukanList->isEmpty())
        <div class="py-16 text-center text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
            <p class="text-sm font-medium">Belum ada data pemasukan yang dicatat.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Transaksi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Item</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pemasukan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($pemasukanList as $item)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-3.5 font-mono text-sm font-semibold text-slate-700">
                                #PMS-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-700">
                                {{ $item->tanggal->format('d-m-Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item->badgeMetodeClass() }}">
                                    {{ $item->labelMetode() }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-600">
                                {{ $item->details_count }} item
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">
                                Rp {{ number_format($item->total_pemasukan, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $item->user?->name ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($pemasukanList->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-100">
                {{ $pemasukanList->links() }}
            </div>
        @endif
    @endif
</div>
@endsection