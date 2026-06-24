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
                DB::raw('COALESCE((
                    SELECT SUM(dhp.jumlah)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_masuk'),
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

        $totalStokMasuk = DetailHasilProduksi::sum('jumlah') ?? 0;
        $totalStokKeluar = DetailPenjualan::sum('qty') ?? 0;
        $totalStok = max(0, $totalStokMasuk - $totalStokKeluar);
        $jenisProdukCount = JenisProduk::count();

        $stokMasukBulanIni = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('jumlah') ?? 0;

        $stokKeluarBulanIni = DetailPenjualan::whereHas('penjualan', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('qty') ?? 0;

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
        $filterTipe = $request->get('tipe', 'semua');
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 15, 25, 50, 100])) {
            $perPage = 10;
        }
        
        // Debug: Cek data penjualan
        \Log::info('Mencari riwayat untuk produk ID: ' . $jenisProdukId);
        \Log::info('Periode: ' . $dariTanggal . ' sampai ' . $sampaiTanggal);
        
        // ============ RIWAYAT STOK MASUK (DARI PRODUKSI) ============
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
                        'tanggal' => $item->produksi->tanggal ?? $item->created_at->format('Y-m-d H:i:s'),
                        'tipe' => 'masuk',
                        'jumlah' => $item->jumlah,
                        'keterangan' => $item->produksi->keterangan ?? 'Hasil Produksi',
                        'user' => $item->produksi->user->name ?? 'System',
                        'referensi' => 'Produksi #' . $item->produksi_id,
                        'harga' => null,
                        'subtotal' => null,
                    ];
                });
            
            \Log::info('Riwayat Masuk ditemukan: ' . $riwayatMasuk->count());
        }
        
        // ============ RIWAYAT STOK KELUAR (DARI PENJUALAN) ============
        $riwayatKeluar = collect();
        if ($filterTipe === 'semua' || $filterTipe === 'keluar') {
            // CARA 1: Query langsung dengan join (lebih reliable)
            $riwayatKeluar = DetailPenjualan::select(
                    'detail_penjualan.*',
                    'penjualan.tanggal as tanggal_penjualan',
                    'penjualan.pembeli_id',
                    'penjualan.user_id as user_penjualan',
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
                        'tanggal' => $item->tanggal_penjualan ?? $item->created_at->format('Y-m-d H:i:s'),
                        'tipe' => 'keluar',
                        'jumlah' => $item->qty,
                        'keterangan' => 'Penjualan ke ' . ($item->nama_pembeli ?? 'Pembeli Umum'),
                        'user' => $item->nama_user ?? 'System',
                        'referensi' => 'Invoice #' . $item->penjualan_id,
                        'harga' => $item->harga,
                        'subtotal' => $item->subtotal,
                    ];
                });
            
            \Log::info('Riwayat Keluar ditemukan: ' . $riwayatKeluar->count());
            
            // Debug: Log semua penjualan
            foreach ($riwayatKeluar as $keluar) {
                \Log::info('Penjualan: ' . json_encode($keluar));
            }
        }
        
        // Gabungkan dan urutkan berdasarkan tanggal
        $riwayat = $riwayatMasuk->concat($riwayatKeluar)
            ->sortByDesc('tanggal')
            ->values();
        
        \Log::info('Total riwayat: ' . $riwayat->count());
        
        // Hitung saldo berjalan
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
        
        // Manual pagination
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
        
        // Hitung jumlah transaksi
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
                $query->whereDate('tanggal', '<', $dariTanggal);
            })
            ->sum('jumlah');
        
        // Stok keluar sebelum tanggal
        $keluarSebelumnya = DetailPenjualan::where('jenis_produk_id', $jenisProdukId)
            ->whereHas('penjualan', function ($query) use ($dariTanggal) {
                $query->whereDate('tanggal', '<', $dariTanggal);
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