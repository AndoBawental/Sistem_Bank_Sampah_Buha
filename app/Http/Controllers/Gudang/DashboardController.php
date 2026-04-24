<?php
// app/Http/Controllers/Gudang/DashboardController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Stok;
use App\Models\Supplier;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard gudang.
     */
    public function index()
    {
        // Total penerimaan hari ini
        $totalPenerimaanHariIni = Penerimaan::whereDate('tanggal', Carbon::today())->count();

        // Pending sortir (Belum atau Proses)
        $pendingSortir = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();

        // Total stok plastik (dalam Kg) - Gunakan model Stok bukan StokPlastik
        $totalStok = Stok::sum('total_berat');

        // Total supplier aktif
        $totalSupplier = Supplier::count();

        // Penerimaan terbaru (5 data terakhir)
        $penerimaanTerbaru = Penerimaan::with(['supplier', 'detailPenerimaan.jenisPlastik'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

      return view('dashboard.gudang.index', compact(
    'totalPenerimaanHariIni',
    'pendingSortir',
    'totalStok',
    'totalSupplier',
    'penerimaanTerbaru'
));
    }
}