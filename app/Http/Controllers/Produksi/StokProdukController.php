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
                // Stok Keluar (Sak) - dari penjualan
                DB::raw('COALESCE((
                    SELECT SUM(dp.jumlah_sak)
                    FROM detail_penjualan dp
                    WHERE dp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_keluar'),
                // ⬇️ Total Stok (Kg) - produksi + adjustment
                DB::raw('GREATEST(0, 
                    COALESCE((SELECT SUM(dhp.total_berat_kg) FROM detail_hasil_produksi dhp WHERE dhp.jenis_produk_id = jenis_produk.id), 0)
                    + COALESCE((SELECT SUM(CASE WHEN tipe="tambah" THEN berat ELSE -berat END) FROM stok_produk_adjustment_logs WHERE jenis_produk_id = jenis_produk.id), 0)
                ) as total_berat')
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
        $totalStokKeluar = DetailPenjualan::sum('jumlah_sak') ?? 0;
        
        // Total stok = produksi + adjustment
        $adjustmentTotal = \App\Models\StokProdukAdjustmentLog::sum(
            DB::raw('CASE WHEN tipe = "tambah" THEN berat ELSE -berat END')
        ) ?? 0;
        $totalStok = max(0, $totalStokMasuk + $adjustmentTotal);
        $jenisProdukCount = JenisProduk::count();

        // Masuk bulan ini (Kg)
        $stokMasukBulanIni = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('total_berat_kg') ?? 0;

        // Keluar bulan ini (Sak)
        $stokKeluarBulanIni = DetailPenjualan::whereHas('penjualan', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('jumlah_sak') ?? 0;

        // Hitung menipis & habis (dengan adjustment)
        $semuaProduk = JenisProduk::select(
                'jenis_produk.id',
                DB::raw('GREATEST(0, 
                    COALESCE((SELECT SUM(dhp.total_berat_kg) FROM detail_hasil_produksi dhp WHERE dhp.jenis_produk_id = jenis_produk.id), 0)
                    + COALESCE((SELECT SUM(CASE WHEN tipe="tambah" THEN berat ELSE -berat END) FROM stok_produk_adjustment_logs WHERE jenis_produk_id = jenis_produk.id), 0)
                ) as total')
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
     * Halaman form adjustment stok produk
     */
    public function adjustment($jenisProdukId)
    {
        $produk = JenisProduk::findOrFail($jenisProdukId);
        
        // Hitung stok: produksi + adjustment
        $stokMasuk = DetailHasilProduksi::where('jenis_produk_id', $jenisProdukId)
            ->sum('total_berat_kg') ?? 0;
        
        $stokAdjustment = \App\Models\StokProdukAdjustmentLog::where('jenis_produk_id', $jenisProdukId)
            ->sum(DB::raw('CASE WHEN tipe = "tambah" THEN berat ELSE -berat END')) ?? 0;
        
        $totalBerat = max(0, $stokMasuk + $stokAdjustment);

        return view('dashboard.produksi.stok-produk.adjustment', compact('produk', 'totalBerat'));
    }

    /**
     * Simpan adjustment stok produk
     */
    public function storeAdjustment(Request $request, $jenisProdukId)
    {
        $request->validate([
            'tipe' => 'required|in:tambah,kurang',
            'berat' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $produk = JenisProduk::findOrFail($jenisProdukId);

        // Hitung stok saat ini
        $stokMasuk = DetailHasilProduksi::where('jenis_produk_id', $jenisProdukId)->sum('total_berat_kg') ?? 0;
        $stokAdjustment = \App\Models\StokProdukAdjustmentLog::where('jenis_produk_id', $jenisProdukId)
            ->sum(DB::raw('CASE WHEN tipe = "tambah" THEN berat ELSE -berat END')) ?? 0;
        
        $totalStok = max(0, $stokMasuk + $stokAdjustment);

        // Validasi untuk pengurangan
        if ($request->tipe == 'kurang' && $totalStok < $request->berat) {
            return back()->with('error', 'Stok tidak mencukupi! Stok saat ini: ' . number_format($totalStok, 2, ',', '.') . ' Kg');
        }

        // Simpan log adjustment
        \App\Models\StokProdukAdjustmentLog::create([
            'jenis_produk_id' => $jenisProdukId,
            'user_id' => auth()->id(),
            'tipe' => $request->tipe,
            'berat' => $request->berat,
            'stok_sebelum' => $totalStok,
            'stok_sesudah' => $request->tipe == 'tambah' ? $totalStok + $request->berat : $totalStok - $request->berat,
            'keterangan' => $request->keterangan
        ]);

        $message = $request->tipe == 'tambah' ? 'ditambahkan' : 'dikurangi';

        return redirect()->route('produksi.stok.index')
            ->with('success', sprintf(
                'Stok %s berhasil %s %s Kg.',
                $produk->nama, $message, number_format($request->berat, 2, ',', '.')
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
                        'jumlah' => $item->jumlah_sak,
                        'satuan' => 'Sak',
                        'keterangan' => 'Penjualan ke ' . ($item->nama_pembeli ?? 'Pembeli'),
                        'referensi' => 'Invoice #' . $item->penjualan_id,
                        'user' => $item->nama_user ?? 'System',
                        'harga' => $item->harga_per_kg,
                    ];
                });
        }
        
        // Riwayat Adjustment
       $riwayatAdjustment = \App\Models\StokProdukAdjustmentLog::with('user')
    ->where('jenis_produk_id', $jenisProdukId)
    ->when($dariTanggal, fn($q) => $q->whereDate('created_at', '>=', $dariTanggal))
    ->when($sampaiTanggal, fn($q) => $q->whereDate('created_at', '<=', $sampaiTanggal))
    ->orderBy('created_at', 'desc')
    ->get()
    ->map(function ($item) {
        $isTambah = $item->tipe === 'tambah';
        return [
            'id' => 'a-' . $item->id,
            'tanggal' => $item->created_at,
            'tipe' => $isTambah ? 'masuk' : 'keluar', // ⬅️ PERBAIKI: 'masuk' untuk tambah, 'keluar' untuk kurang
            'jumlah' => $item->berat,
            'satuan' => 'Kg',
            'keterangan' => 'Adjustment: ' . ($item->keterangan ?: ($isTambah ? 'Penambahan stok' : 'Pengurangan stok')),
            'referensi' => 'Adjustment #' . $item->id,
            'user' => $item->user->name ?? 'System',
            'harga' => null,
        ];
    });
        
        // Gabungkan semua
        $riwayat = $riwayatMasuk->concat($riwayatKeluar)->concat($riwayatAdjustment)
            ->sortByDesc('tanggal')->values();
        
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
        $totalAdjustment = $riwayatAdjustment->sum('jumlah');
        $countMasuk = $riwayatMasuk->count();
        $countKeluar = $riwayatKeluar->count();
        $countAdjustment = $riwayatAdjustment->count();
        $countTotal = $countMasuk + $countKeluar + $countAdjustment;
        
        // Stok sekarang
        $stokProduksi = DetailHasilProduksi::where('jenis_produk_id', $jenisProdukId)->sum('total_berat_kg') ?? 0;
        $stokAdjustment = \App\Models\StokProdukAdjustmentLog::where('jenis_produk_id', $jenisProdukId)
            ->sum(DB::raw('CASE WHEN tipe = "tambah" THEN berat ELSE -berat END')) ?? 0;
        $stokSekarang = max(0, $stokProduksi + $stokAdjustment);
        
        return view('dashboard.produksi.stok-produk.riwayat', compact(
            'jenisProduk', 'riwayatPaginate', 'dariTanggal', 'sampaiTanggal',
            'filterTipe', 'perPage', 'totalMasuk', 'totalKeluar',
            'countMasuk', 'countKeluar', 'countTotal', 'stokSekarang'
        ));
    }
}