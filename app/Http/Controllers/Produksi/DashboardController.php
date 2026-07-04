<?php
// app/Http/Controllers/Produksi/DashboardController.php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\DetailBahanProduksi;
use App\Models\DetailHasilProduksi;
use App\Models\DetailPenjualan;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Jumlah produksi bulan ini
        $produksiBulanIni = Produksi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Total bahan digunakan bulan ini (Kg)
        $totalBahan = DetailBahanProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('berat_kg');

        // Total hasil produksi bulan ini (Kg)
        $totalHasil = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('total_berat_kg');

        // Total sak bulan ini
        $totalSak = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('jumlah_sak');

        // ✅ Total stok produk (masuk - keluar + adjustment) - SAMA DENGAN StokProdukController
        $totalStokMasuk = DetailHasilProduksi::sum('total_berat_kg') ?? 0;
        $totalStokKeluar = DetailPenjualan::sum('berat_nett_kg') ?? 0;
        
        $adjustmentTotal = DB::table('stok_produk_adjustment_logs')
            ->sum(DB::raw('CASE WHEN tipe = "tambah" THEN berat ELSE -berat END')) ?? 0;
        
        $totalStokProduk = max(0, $totalStokMasuk - $totalStokKeluar + $adjustmentTotal);

        // DEBUG: Log untuk cek nilai
        \Log::info('Dashboard Produksi Debug', [
            'totalStokMasuk' => $totalStokMasuk,
            'totalStokKeluar' => $totalStokKeluar,
            'adjustmentTotal' => $adjustmentTotal,
            'totalStokProduk' => $totalStokProduk,
        ]);

        // Produksi terbaru
        $produksiTerbaru = Produksi::with(['detailBahanProduksi.jenisPlastik', 'detailHasilProduksi.jenisProduk'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Stok bahan baku
        $stokBahan = Stok::with('jenisPlastik')
            ->where('total_berat', '>', 0)
            ->orderBy('total_berat', 'asc')
            ->get();

        return view('dashboard.produksi.index', compact(
            'produksiBulanIni',
            'totalBahan',
            'totalHasil',
            'totalSak',
            'totalStokProduk',
            'produksiTerbaru',
            'stokBahan'
        ));
    }
}