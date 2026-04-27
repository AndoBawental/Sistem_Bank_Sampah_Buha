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
        // Ringkasan Utama
        $totalTransaksiHariIni = Penjualan::whereDate('tanggal', today())->count();
        $totalPendapatanHariIni = Penjualan::whereDate('tanggal', today())->sum('total_harga');
        $totalTransaksiBulanIni = Penjualan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $totalPendapatanBulanIni = Penjualan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');

        // Total Keseluruhan
        $totalSemuaTransaksi = Penjualan::count();
        $totalSemuaPendapatan = Penjualan::sum('total_harga');
        $totalPembeli = Pembeli::count();

        // Rata-rata transaksi
        $rataRataTransaksi = $totalSemuaTransaksi > 0 
            ? $totalSemuaPendapatan / $totalSemuaTransaksi 
            : 0;

        // Transaksi Terbaru (5 data)
        $transaksiTerbaru = Penjualan::with(['pembeli', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Produk Terlaris Bulan Ini
        $produkTerlaris = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->join('jenis_produk', 'detail_penjualan.jenis_produk_id', '=', 'jenis_produk.id')
            ->select(
                'jenis_produk.nama',
                DB::raw('SUM(detail_penjualan.qty) as total_qty'),
                DB::raw('SUM(detail_penjualan.subtotal) as total_pendapatan')
            )
            ->whereMonth('penjualan.tanggal', now()->month)
            ->whereYear('penjualan.tanggal', now()->year)
            ->groupBy('jenis_produk.id', 'jenis_produk.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Pendapatan 7 Hari Terakhir (untuk chart)
        $pendapatan7Hari = $this->getPendapatan7Hari();

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
            'produkTerlaris',
            'pendapatan7Hari'
        ));
    }

    /**
     * Ambil data pendapatan 7 hari terakhir
     */
    private function getPendapatan7Hari()
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $hari = now()->subDays($i)->translatedFormat('l');
            $pendapatan = Penjualan::whereDate('tanggal', $tanggal)->sum('total_harga');
            
            $labels[] = $hari;
            $data[] = (int) $pendapatan;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}