<?php
// app/Http/Controllers/Penjualan/DashboardController.php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Pembeli;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan Hari Ini
        $totalTransaksiHariIni = Penjualan::whereDate('tanggal', today())->count();
        $totalPendapatanHariIni = Penjualan::whereDate('tanggal', today())->sum('total_harga');

        // Bulan Ini
        $totalTransaksiBulanIni = Penjualan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();
        $totalPendapatanBulanIni = Penjualan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->sum('total_harga');

        // Keseluruhan
        $totalSemuaTransaksi = Penjualan::count();
        $totalSemuaPendapatan = Penjualan::sum('total_harga');
        $totalPembeli = Pembeli::count();

        // Rata-rata transaksi
        $rataRataTransaksi = $totalSemuaTransaksi > 0 
            ? $totalSemuaPendapatan / $totalSemuaTransaksi 
            : 0;

        // Transaksi Terbaru (5 data)
        $transaksiTerbaru = Penjualan::with(['pembeli', 'user', 'detailPenjualan'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Produk Terlaris Bulan Ini (pakai field baru)
        $produkTerlaris = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->join('jenis_produk', 'detail_penjualan.jenis_produk_id', '=', 'jenis_produk.id')
            ->select(
                'jenis_produk.nama',
                DB::raw('SUM(detail_penjualan.jumlah_sak) as total_sak'),           // ⬅️ jumlah_sak
                DB::raw('SUM(detail_penjualan.berat_nett_kg) as total_berat'),      // ⬅️ berat_nett_kg
                DB::raw('SUM(detail_penjualan.subtotal) as total_pendapatan')
            )
            ->whereMonth('penjualan.tanggal', now()->month)
            ->whereYear('penjualan.tanggal', now()->year)
            ->groupBy('jenis_produk.id', 'jenis_produk.nama')
            ->orderByDesc('total_berat')
            ->limit(5)
            ->get();

        return view('dashboard.penjualan.index', compact(
            'totalTransaksiHariIni',
            'totalPendapatanHariIni',
            'totalTransaksiBulanIni',
            'totalPendapatanBulanIni',
            'totalSemuaTransaksi',
            'totalSemuaPendapatan',
            'totalPembeli',
            'rataRataTransaksi',
            'transaksiTerbaru',
            'produkTerlaris'
        ));
    }
}