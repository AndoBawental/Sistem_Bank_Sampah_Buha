<?php
// app/Http/Controllers/Produksi/StokProdukController.php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\JenisProduk;
use App\Models\DetailHasilProduksi;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokProdukController extends Controller
{
    public function index(Request $request)
    {
        $stokQuery = JenisProduk::select(
                'jenis_produk.id as jenis_produk_id',
                'jenis_produk.nama',
                'jenis_produk.keterangan',
                // Stok Masuk (Kg) - dari produksi
                DB::raw('COALESCE((
                    SELECT SUM(dhp.total_berat_kg)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_masuk'),
                // Stok Keluar (Unit) - dari penjualan
                DB::raw('COALESCE((
                    SELECT SUM(dp.qty)
                    FROM detail_penjualan dp
                    WHERE dp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_keluar'),
                // Total Stok (Kg) - hanya dari produksi
                DB::raw('COALESCE((
                    SELECT SUM(dhp.total_berat_kg)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) as total_berat')
            );

        if ($request->filled('jenis_produk_id')) {
            $stokQuery->where('jenis_produk.id', $request->jenis_produk_id);
        }

        if ($request->filled('filter')) {
            if ($request->filter === 'menipis') {
                $stokQuery->havingRaw('total_berat < 100 AND total_berat > 0');
            } elseif ($request->filter === 'habis') {
                $stokQuery->havingRaw('total_berat <= 0');
            }
        }

        $stok = $stokQuery->orderBy('jenis_produk.nama')->paginate(10);

        // Statistik
        $totalStokMasuk = DetailHasilProduksi::sum('total_berat_kg') ?? 0;
        $totalStokKeluar = DetailPenjualan::sum('qty') ?? 0;
        $totalStok = $totalStokMasuk; // Stok = total produksi (Kg)
        $jenisProdukCount = JenisProduk::count();

        // Masuk bulan ini (Kg)
        $stokMasukBulanIni = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('total_berat_kg') ?? 0;

        // Keluar bulan ini (Unit)
        $stokKeluarBulanIni = DetailPenjualan::whereHas('penjualan', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('qty') ?? 0;

        // Hitung menipis & habis
        $semuaProduk = JenisProduk::select(
                'jenis_produk.id',
                DB::raw('COALESCE((
                    SELECT SUM(dhp.total_berat_kg)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) as total')
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

   public function riwayat(Request $request, $jenisProdukId)
{
    $jenisProduk = JenisProduk::findOrFail($jenisProdukId);
    
    $dariTanggal = $request->get('dari_tanggal', now()->subDays(30)->format('Y-m-d'));
    $sampaiTanggal = $request->get('sampai_tanggal', now()->format('Y-m-d'));
    $filterTipe = $request->get('tipe', 'semua');
    $perPage = $request->get('per_page', 10);
    if (!in_array($perPage, [5, 10, 15, 25, 50, 100])) {
        $perPage = 10;
    }
    
    // Riwayat Masuk (Produksi)
    $riwayatMasuk = collect();
    if ($filterTipe === 'semua' || $filterTipe === 'masuk') {
        $riwayatMasuk = DetailHasilProduksi::with(['produksi.user'])
            ->where('jenis_produk_id', $jenisProdukId)
            ->whereHas('produksi', function ($query) use ($dariTanggal, $sampaiTanggal) {
                $query->whereDate('tanggal', '>=', $dariTanggal)
                      ->whereDate('tanggal', '<=', $sampaiTanggal);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'm-' . $item->id,
                    'tanggal' => $item->produksi->tanggal ?? now(),
                    'tipe' => 'masuk',
                    'jumlah' => $item->total_berat_kg,
                    'satuan' => 'Kg',
                    'keterangan' => 'Produksi #' . $item->produksi_id,
                    'referensi' => 'Produksi #' . $item->produksi_id,
                    'user' => $item->produksi->user->name ?? 'System',
                    'harga' => null,
                ];
            });
    }
    
    // Riwayat Keluar (Penjualan)
    $riwayatKeluar = collect();
    if ($filterTipe === 'semua' || $filterTipe === 'keluar') {
        $riwayatKeluar = DetailPenjualan::select(
                'detail_penjualan.*',
                'penjualan.tanggal as tanggal_penjualan',
                'pembeli.nama as nama_pembeli',
                'users.name as nama_user'
            )
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->leftJoin('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
            ->leftJoin('users', 'penjualan.user_id', '=', 'users.id')
            ->where('detail_penjualan.jenis_produk_id', $jenisProdukId)
            ->whereDate('penjualan.tanggal', '>=', $dariTanggal)
            ->whereDate('penjualan.tanggal', '<=', $sampaiTanggal)
            ->orderBy('penjualan.tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'k-' . $item->id,
                    'tanggal' => $item->tanggal_penjualan ?? now(),
                    'tipe' => 'keluar',
                    'jumlah' => $item->qty,
                    'satuan' => 'Unit',
                    'keterangan' => 'Penjualan ke ' . ($item->nama_pembeli ?? 'Pembeli'),
                    'referensi' => 'Invoice #' . $item->penjualan_id,
                    'user' => $item->nama_user ?? 'System',
                    'harga' => $item->harga,
                ];
            });
    }
    
    // Gabungkan
    $riwayat = $riwayatMasuk->concat($riwayatKeluar)->sortByDesc('tanggal')->values();
    
    // Pagination manual
    $currentPage = $request->get('page', 1);
    $pagedData = $riwayat->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $riwayatPaginate = new \Illuminate\Pagination\LengthAwarePaginator(
        $pagedData, $riwayat->count(), $perPage, $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
    // Statistik
    $totalMasuk = $riwayatMasuk->sum('jumlah');
    $totalKeluar = $riwayatKeluar->sum('jumlah');
    $countMasuk = $riwayatMasuk->count();
    $countKeluar = $riwayatKeluar->count();
    $countTotal = $countMasuk + $countKeluar;
    $stokSekarang = DetailHasilProduksi::where('jenis_produk_id', $jenisProdukId)->sum('total_berat_kg') ?? 0;
    
    return view('dashboard.produksi.stok-produk.riwayat', compact(
        'jenisProduk', 'riwayatPaginate', 'dariTanggal', 'sampaiTanggal',
        'filterTipe', 'perPage', 'totalMasuk', 'totalKeluar',
        'countMasuk', 'countKeluar', 'countTotal', 'stokSekarang'  // ⬅️ TAMBAHKAN INI
    ));
}
}