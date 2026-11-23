<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemasukanRequest;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    /**
     * Daftar pemasukan harian sekaligus perhitungan total per tanggal.
     */
    public function index(Request $request)
    {
        $this->authorizeRole();

        $tanggal = $request->input('tanggal', now()->toDateString());

        $baseQuery = Pemasukan::whereDate('tanggal', $tanggal);

        $totalHarian     = (clone $baseQuery)->sum('total_pemasukan');
        $jumlahTransaksi = (clone $baseQuery)->count();
        $totalTunai      = (clone $baseQuery)->where('metode_bayar', Pemasukan::METODE_TUNAI)->sum('total_pemasukan');
        $totalNonTunai   = (clone $baseQuery)->whereIn('metode_bayar', [
            Pemasukan::METODE_TRANSFER,
            Pemasukan::METODE_QRIS,
        ])->sum('total_pemasukan');

        $pemasukanList = (clone $baseQuery)
            ->with('user')
            ->withCount('details')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('pemilik.pemasukan.index', compact(
            'tanggal',
            'totalHarian',
            'jumlahTransaksi',
            'totalTunai',
            'totalNonTunai',
            'pemasukanList'
        ));
    }

    /**
     * Form catat pemasukan baru.
     */
    public function create()
    {
        $this->authorizeRole();

        return view('pemilik.pemasukan.create');
    }

    /**
     * Simpan pemasukan baru beserta rincian barangnya.
     */
    public function store(StorePemasukanRequest $request)
    {
        $details = collect($request->validated()['details'])->map(function ($item) {
            $subtotal = (float) $item['qty'] * (float) $item['harga_satuan'];

            return [
                'nama_barang'  => $item['nama_barang'],
                'satuan'       => $item['satuan'],
                'qty'          => $item['qty'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal'     => $subtotal,
            ];
        });

        $totalPemasukan = $details->sum('subtotal');

        DB::transaction(function () use ($request, $details, $totalPemasukan) {
            $pemasukan = Pemasukan::create([
                'tanggal'         => $request->tanggal,
                'user_id'         => auth()->id(),
                'metode_bayar'    => $request->metode_bayar,
                'total_pemasukan' => $totalPemasukan,
                'catatan'         => $request->catatan,
            ]);

            $pemasukan->details()->createMany($details->toArray());
        });

        return redirect()->route('pemilik.pemasukan.index', ['tanggal' => $request->tanggal])
            ->with('success', 'Data pemasukan berhasil disimpan.');
    }

    // Helper 

    private function authorizeRole(): void
    {
        if (! auth()->user()?->hasRole('pemilik_toko')) {
            abort(403, 'Akses Ditolak: Halaman ini hanya untuk Pemilik Toko.');
        }
    }
}