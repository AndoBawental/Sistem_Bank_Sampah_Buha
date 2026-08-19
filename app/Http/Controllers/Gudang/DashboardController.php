<?php
// app/Http/Controllers/Gudang/DashboardController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\HasilSortir;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalPenerimaanHariIni = Penerimaan::whereDate('tanggal', Carbon::today())->count();
        $totalStok = Stok::sum('total_berat');
        $totalSupplier = Supplier::count();
        $totalJenisStok = Stok::count();

        // Stok kotor (sisa yang belum disortir)
        $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        $stokKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);

        // Karung belum sortir
        $karungBelumSortir = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Belum')
            ->sum('dp.jumlah_karung');
        if ($karungBelumSortir == 0) {
            $karungBelumSortir = Penerimaan::where('status_sortir', 'Belum')->count();
        }

        // Penerimaan terbaru
        $penerimaanTerbaru = Penerimaan::with(['supplier', 'detailPenerimaan'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Stok menipis
        $stokMenipisList = Stok::with('jenisPlastik')
            ->where('total_berat', '<', 100)
            ->orderBy('total_berat', 'asc')
            ->limit(5)
            ->get();
        
        $stokMenipis = Stok::where('total_berat', '<', 100)
            ->where('total_berat', '>', 0)
            ->count();

        // Sortir terbaru
        $sortirTerbaru = HasilSortir::with('jenisPlastik')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.gudang.index', compact(
            'totalPenerimaanHariIni',
            'totalStok',
            'totalSupplier',
            'totalJenisStok',
            'stokKotor',
            'karungBelumSortir',
            'penerimaanTerbaru',
            'stokMenipis',
            'stokMenipisList',
            'sortirTerbaru'
        ));
    }
}