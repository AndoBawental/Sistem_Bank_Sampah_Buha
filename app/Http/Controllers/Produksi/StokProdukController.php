<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\JenisPlastik;
use Illuminate\Http\Request;

class StokProdukController extends Controller
{
   public function index(Request $request)
{
    $query = Stok::with('jenisPlastik');

    if ($request->filled('jenis_plastik_id')) {
        $query->where('jenis_plastik_id', $request->jenis_plastik_id);
    }

    // clone query untuk statistik (PENTING)
    $stokQuery = clone $query;

    $stok = $query->paginate(10);

    $totalStok = $stokQuery->sum('total_berat');
    $jenisPlastikCount = JenisPlastik::count();

    return view('dashboard.produksi.stok-produk.index', compact(
        'stok',
        'totalStok',
        'jenisPlastikCount'
    ));
}
}