<?php
// app/Http/Controllers/Gudang/DashboardController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenerimaanHariIni = Penerimaan::whereDate('tanggal', Carbon::today())->count();
        $totalStok = Stok::sum('total_berat');
        $totalSupplier = Supplier::count();

        // Total karung dari semua penerimaan
        $totalKarung = DB::table('detail_penerimaan')->sum('jumlah_karung');
        if ($totalKarung == 0) {
            $totalKarung = DB::table('detail_penerimaan')->count();
        }

        // Karung belum sortir
        $karungBelumSortir = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Belum')
            ->sum('dp.jumlah_karung');
        if ($karungBelumSortir == 0) {
            $karungBelumSortir = Penerimaan::where('status_sortir', 'Belum')->count();
        }

        $penerimaanTerbaru = Penerimaan::with(['supplier', 'detailPenerimaan'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        $stokMenipis = Stok::where('total_berat', '<', 100)
            ->where('total_berat', '>', 0)
            ->count();

        return view('dashboard.gudang.index', compact(
            'totalPenerimaanHariIni',
            'totalStok',
            'totalSupplier',
            'totalKarung',
            'karungBelumSortir',
            'penerimaanTerbaru',
            'stokMenipis'
        ));
    }
}