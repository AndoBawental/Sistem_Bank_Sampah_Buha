<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\DetailBahanProduksi;
use App\Models\DetailHasilProduksi;
use App\Models\Stok;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard produksi.
     */
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
        })->sum('berat');

        // Total hasil produksi bulan ini (Unit)
        $totalHasil = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('jumlah');

        // Total stok produk tersedia
        $totalStokMasuk = DetailHasilProduksi::sum('jumlah') ?? 0;

        $totalStokKeluar = class_exists(\App\Models\DetailPenjualan::class)
            ? \App\Models\DetailPenjualan::sum('qty')
            : 0;

        $totalStokProduk = max(0, $totalStokMasuk - $totalStokKeluar);

        // Produksi terbaru
        $produksiTerbaru = Produksi::with([
                'jenisProduk',
                'detailBahanProduksi',
                'detailHasilProduksi'
            ])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // Stok bahan baku
        $stokBahan = Stok::with('jenisPlastik')
            ->orderBy('total_berat', 'asc')
            ->get();

        return view('dashboard.produksi.index', compact(
            'produksiBulanIni',
            'totalBahan',
            'totalHasil',
            'totalStokProduk',
            'produksiTerbaru',
            'stokBahan'
        ));
    }
}