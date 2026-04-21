<?php
// app/Http/Controllers/Produksi/StokProdukController.php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\JenisProduk;
use App\Models\DetailHasilProduksi;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokProdukController extends Controller
{
    public function index(Request $request)
    {
        // Query dengan subquery untuk mendapatkan stok masuk dan stok keluar
        $stokQuery = JenisProduk::select(
                'jenis_produk.id as jenis_produk_id',
                'jenis_produk.nama',
                'jenis_produk.keterangan',
                // Stok masuk dari produksi (dari tabel detail_hasil_produksi)
                DB::raw('COALESCE((
                    SELECT SUM(dhp.jumlah)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_masuk'),
                // Stok keluar dari penjualan (dari tabel detail_penjualan)
                DB::raw('COALESCE((
                    SELECT SUM(dp.qty)
                    FROM detail_penjualan dp
                    WHERE dp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_keluar')
            )
            ->selectRaw('GREATEST(0, (
                COALESCE((
                    SELECT SUM(dhp.jumlah)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) - 
                COALESCE((
                    SELECT SUM(dp.qty)
                    FROM detail_penjualan dp
                    WHERE dp.jenis_produk_id = jenis_produk.id
                ), 0)
            )) as total_berat');

        // Filter berdasarkan jenis produk
        if ($request->filled('jenis_produk_id')) {
            $stokQuery->where('jenis_produk.id', $request->jenis_produk_id);
        }

        // Filter berdasarkan status stok (menggunakan having untuk total_berat)
        if ($request->filled('filter')) {
            if ($request->filter === 'menipis') {
                $stokQuery->havingRaw('total_berat < 100')
                          ->havingRaw('total_berat > 0');
            } elseif ($request->filter === 'habis') {
                $stokQuery->havingRaw('total_berat <= 0');
            }
        }

        // Order dan pagination
        $stok = $stokQuery->orderBy('jenis_produk.nama')->paginate(10);

        // ── Statistik ────────────────────────────────────────────────────────

        // Total stok masuk dari produksi
        $totalStokMasuk = DetailHasilProduksi::sum('jumlah') ?? 0;
        
        // Total stok keluar dari penjualan
        $totalStokKeluar = 0;
        if (class_exists('App\Models\DetailPenjualan')) {
            $totalStokKeluar = DetailPenjualan::sum('qty') ?? 0;
        }
        
        // Total stok bersih
        $totalStok = max(0, $totalStokMasuk - $totalStokKeluar);

        // Jumlah jenis produk terdaftar
        $jenisProdukCount = JenisProduk::count();

        // Stok masuk bulan ini (dari produksi)
        $stokMasukBulanIni = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('jumlah') ?? 0;

        // Stok keluar bulan ini (dari penjualan)
        $stokKeluarBulanIni = 0;
        if (class_exists('App\Models\DetailPenjualan')) {
            $stokKeluarBulanIni = DetailPenjualan::whereHas('penjualan', function ($q) {
                $q->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
            })->sum('qty') ?? 0;
        }

        // Hitung produk dengan stok menipis dan habis
        $semuaProduk = JenisProduk::select(
                'jenis_produk.id',
                DB::raw('GREATEST(0, (
                    COALESCE((
                        SELECT SUM(dhp.jumlah)
                        FROM detail_hasil_produksi dhp
                        WHERE dhp.jenis_produk_id = jenis_produk.id
                    ), 0) - 
                    COALESCE((
                        SELECT SUM(dp.qty)
                        FROM detail_penjualan dp
                        WHERE dp.jenis_produk_id = jenis_produk.id
                    ), 0)
                )) as total')
            )
            ->get();

        $stokMenipis = 0;
        $stokHabis = 0;
        
        foreach ($semuaProduk as $produk) {
            $total = (float) $produk->total;
            if ($total <= 0) {
                $stokHabis++;
            } elseif ($total < 100) {
                $stokMenipis++;
            }
        }

        // Data untuk dropdown filter
        $jenisProduk = JenisProduk::orderBy('nama')->get();

        return view('dashboard.produksi.stok-produk.index', compact(
            'stok',
            'totalStok',
            'jenisProdukCount',
            'stokMasukBulanIni',
            'stokKeluarBulanIni',
            'stokMenipis',
            'stokHabis',
            'jenisProduk'
        ));
    }

     /**
     * Menampilkan riwayat stok masuk dan keluar untuk produk tertentu
     */
    public function riwayat(Request $request, $jenisProdukId)
{
    $jenisProduk = JenisProduk::findOrFail($jenisProdukId);
    
    // Filter tanggal
    $dariTanggal = $request->get('dari_tanggal', now()->subDays(30)->format('Y-m-d'));
    $sampaiTanggal = $request->get('sampai_tanggal', now()->format('Y-m-d'));
    
    // Filter tipe transaksi
    $filterTipe = $request->get('tipe', 'semua'); // semua, masuk, keluar
    
    // Pagination
    $perPage = $request->get('per_page', 10);
    if (!in_array($perPage, [5, 10, 15, 25, 50, 100])) {
        $perPage = 10;
    }
    
    // Riwayat Stok Masuk (dari Produksi)
    $riwayatMasuk = collect();
    if ($filterTipe === 'semua' || $filterTipe === 'masuk') {
        $riwayatMasuk = DetailHasilProduksi::with(['produksi.user'])
            ->where('jenis_produk_id', $jenisProdukId)
            ->whereHas('produksi', function ($query) use ($dariTanggal, $sampaiTanggal) {
                $query->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'm-' . $item->id,
                    'tanggal' => $item->produksi->tanggal ?? $item->created_at->format('Y-m-d'),
                    'tipe' => 'masuk',
                    'jumlah' => $item->jumlah,
                    'keterangan' => $item->produksi->keterangan ?? 'Hasil Produksi',
                    'user' => $item->produksi->user->name ?? 'System',
                    'referensi' => 'Produksi #' . $item->produksi_id,
                ];
            });
    }
    
    // Riwayat Stok Keluar (dari Penjualan)
    $riwayatKeluar = collect();
    if ($filterTipe === 'semua' || $filterTipe === 'keluar') {
        $riwayatKeluar = DetailPenjualan::with(['penjualan.user', 'penjualan.pembeli'])
            ->where('jenis_produk_id', $jenisProdukId)
            ->whereHas('penjualan', function ($query) use ($dariTanggal, $sampaiTanggal) {
                $query->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'k-' . $item->id,
                    'tanggal' => $item->penjualan->tanggal ?? $item->created_at->format('Y-m-d'),
                    'tipe' => 'keluar',
                    'jumlah' => $item->qty,
                    'keterangan' => 'Penjualan ke ' . ($item->penjualan->pembeli->nama ?? 'Pembeli'),
                    'user' => $item->penjualan->user->name ?? 'System',
                    'referensi' => 'Invoice #' . $item->penjualan_id,
                    'harga' => $item->harga,
                    'subtotal' => $item->subtotal,
                ];
            });
    }
    
    // Gabungkan dan urutkan berdasarkan tanggal
    $riwayat = $riwayatMasuk->concat($riwayatKeluar)
        ->sortByDesc('tanggal')
        ->values();
    
    // Hitung saldo berjalan (running balance)
    $saldo = $this->hitungSaldoAwal($jenisProdukId, $dariTanggal);
    $riwayatDenganSaldo = $riwayat->map(function ($item) use (&$saldo) {
        if ($item['tipe'] === 'masuk') {
            $saldo += $item['jumlah'];
        } else {
            $saldo -= $item['jumlah'];
        }
        
        $item['saldo'] = max(0, $saldo);
        return $item;
    });
    
    // Manual pagination untuk collection
    $currentPage = $request->get('page', 1);
    $pagedData = $riwayatDenganSaldo->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $riwayatPaginate = new \Illuminate\Pagination\LengthAwarePaginator(
        $pagedData,
        $riwayatDenganSaldo->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
    // Statistik periode
    $totalMasuk = $riwayatMasuk->sum('jumlah');
    $totalKeluar = $riwayatKeluar->sum('jumlah');
    $stokAwal = $this->hitungSaldoAwal($jenisProdukId, $dariTanggal);
    $stokAkhir = $stokAwal + $totalMasuk - $totalKeluar;
    
    // Data stok saat ini
    $stokSekarang = $this->hitungStokSekarang($jenisProdukId);
    
    // Hitung jumlah transaksi per tipe
    $countMasuk = $riwayatMasuk->count();
    $countKeluar = $riwayatKeluar->count();
    $countTotal = $countMasuk + $countKeluar;
    
    return view('dashboard.produksi.stok-produk.riwayat', compact(
        'jenisProduk',
        'riwayatPaginate',
        'dariTanggal',
        'sampaiTanggal',
        'filterTipe',
        'perPage',
        'totalMasuk',
        'totalKeluar',
        'stokAwal',
        'stokAkhir',
        'stokSekarang',
        'countMasuk',
        'countKeluar',
        'countTotal'
    ));
}
    
    /**
     * Hitung saldo awal sebelum periode yang dipilih
     */
    private function hitungSaldoAwal($jenisProdukId, $dariTanggal)
    {
        // Stok masuk sebelum tanggal
        $masukSebelumnya = DetailHasilProduksi::where('jenis_produk_id', $jenisProdukId)
            ->whereHas('produksi', function ($query) use ($dariTanggal) {
                $query->where('tanggal', '<', $dariTanggal);
            })
            ->sum('jumlah');
        
        // Stok keluar sebelum tanggal
        $keluarSebelumnya = DetailPenjualan::where('jenis_produk_id', $jenisProdukId)
            ->whereHas('penjualan', function ($query) use ($dariTanggal) {
                $query->where('tanggal', '<', $dariTanggal);
            })
            ->sum('qty');
        
        return max(0, $masukSebelumnya - $keluarSebelumnya);
    }
    
    /**
     * Hitung stok saat ini
     */
    private function hitungStokSekarang($jenisProdukId)
    {
        $totalMasuk = DetailHasilProduksi::where('jenis_produk_id', $jenisProdukId)->sum('jumlah') ?? 0;
        $totalKeluar = DetailPenjualan::where('jenis_produk_id', $jenisProdukId)->sum('qty') ?? 0;
        
        return max(0, $totalMasuk - $totalKeluar);
    }
}


