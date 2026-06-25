<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Produksi;
use App\Models\Penjualan;
use App\Models\Stok;
use App\Models\JenisPlastik;
use App\Models\Supplier;      
use App\Models\HasilSortir;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenerimaanExport;
use App\Exports\LaporanProduksiExport;
use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanStokExport;

class LaporanController extends Controller
{
    public function index()
    {
        $totalPenerimaan = Penerimaan::count();
        $totalProduksi = Produksi::count();
        $totalPenjualan = Penjualan::count();

        $totalBeratPenerimaan = DB::table('detail_penerimaan')->sum('berat_datang_kg') ?? 0;
        $totalBeratProduksi = DB::table('detail_hasil_produksi')->sum('jumlah') ?? 0;
        $totalBeratPenjualan = DB::table('detail_penjualan')->sum('qty') ?? 0;

        $penerimaanBulanan = $this->getDataBulanan('penerimaan');
        $produksiBulanan = $this->getDataBulanan('produksi');
        $penjualanBulanan = $this->getDataBulanan('penjualan');

        return view('dashboard.laporan.index', compact(
            'totalPenerimaan', 'totalProduksi', 'totalPenjualan',
            'totalBeratPenerimaan', 'totalBeratProduksi', 'totalBeratPenjualan',
            'penerimaanBulanan', 'produksiBulanan', 'penjualanBulanan'
        ));
    }

  public function penerimaan(Request $request)
{
    $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
    $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

    $dariTanggalFull = $dariTanggal . ' 00:00:00';
    $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

    // ✅ HAPUS 'hasilSortir' dari with()
    $query = Penerimaan::with([
        'supplier',
        'user',
        'detailPenerimaan.jenisPlastik',
    ])->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('tipe')) {
        $query->where('tipe', $request->tipe);
    }
    if ($request->filled('status_sortir')) {
        $query->where('status_sortir', $request->status_sortir);
    }

    $penerimaan = $query->orderBy('tanggal', 'desc')->paginate(15);

    $suppliers = Supplier::orderBy('nama')->get();

    // Statistik
    $statsQuery = Penerimaan::whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);
    
    if ($request->filled('supplier_id')) {
        $statsQuery->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('tipe')) {
        $statsQuery->where('tipe', $request->tipe);
    }
    if ($request->filled('status_sortir')) {
        $statsQuery->where('status_sortir', $request->status_sortir);
    }

    $totalTransaksi = $statsQuery->count();
    $totalBeli = (clone $statsQuery)->where('tipe', 'Beli')->count();
    $totalDonasi = (clone $statsQuery)->where('tipe', 'Donasi')->count();
    $totalBeratKotor = (clone $statsQuery)->sum('total_berat_kotor_kg');
    $totalBayar = (clone $statsQuery)->where('tipe', 'Beli')->sum('total_bayar');

    // ✅ Berat bersih dari penerimaan yang SUDAH sortir (status Sudah)
    $totalBeratBersih = (clone $statsQuery)->where('status_sortir', 'Sudah')
        ->sum('total_berat_kotor_kg');

    return view('dashboard.laporan.penerimaan', compact(
        'penerimaan', 'suppliers', 'dariTanggal', 'sampaiTanggal',
        'totalTransaksi', 'totalBeli', 'totalDonasi',
        'totalBeratKotor', 'totalBeratBersih', 'totalBayar'
    ));
}

    public function produksi(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

        // Tambahkan waktu untuk mencakup sehari penuh
        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Produksi::with(['user', 'detailHasilProduksi.jenisProduk'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

        if ($request->filled('jenis_produk_id')) {
            $query->whereHas('detailHasilProduksi', function ($q) use ($request) {
                $q->where('jenis_produk_id', $request->jenis_produk_id);
            });
        }

        $produksi = $query->orderBy('tanggal', 'desc')->paginate(15);

        $jenisProduk = \App\Models\JenisProduk::orderBy('nama')->get();

        // Total berat dengan join
        $totalBerat = DB::table('detail_hasil_produksi')
            ->join('produksi', 'produksi.id', '=', 'detail_hasil_produksi.produksi_id')
            ->whereBetween('produksi.tanggal', [$dariTanggalFull, $sampaiTanggalFull])
            ->when($request->filled('jenis_produk_id'), function ($q) use ($request) {
                $q->where('detail_hasil_produksi.jenis_produk_id', $request->jenis_produk_id);
            })
            ->sum('jumlah');

        $totalTransaksi = (clone $query)->count();

        return view('dashboard.laporan.produksi', compact(
            'produksi', 'jenisProduk', 'dariTanggal', 'sampaiTanggal',
            'totalBerat', 'totalTransaksi'
        ));
    }

    public function penjualan(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

        // Tambahkan waktu untuk mencakup sehari penuh
        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

        // Filter by pembeli
        if ($request->filled('pembeli_id')) {
            $query->where('pembeli_id', $request->pembeli_id);
        }

        $perPage = $request->input('per_page', 15);
        if (!in_array($perPage, [10, 15, 25, 50, 100])) {
            $perPage = 15;
        }

        $penjualan = $query->orderBy('tanggal', 'desc')->paginate($perPage);

        // Get pembeli list for filter dropdown
        $pembeliList = \App\Models\Pembeli::orderBy('nama')->get();

        // Total berat
        $totalBerat = DB::table('detail_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
            ->whereBetween('penjualan.tanggal', [$dariTanggalFull, $sampaiTanggalFull])
            ->when($request->filled('pembeli_id'), function ($q) use ($request) {
                $q->where('penjualan.pembeli_id', $request->pembeli_id);
            })
            ->sum('qty');

        $totalHarga = (clone $query)->sum('total_harga');
        $totalTransaksi = (clone $query)->count();

        return view('dashboard.laporan.penjualan', compact(
            'penjualan', 
            'pembeliList',
            'dariTanggal', 
            'sampaiTanggal',
            'totalBerat', 
            'totalHarga', 
            'totalTransaksi'
        ));
    }

    public function stok()
    {
        // Stok Bahan Baku (Plastik)
        $stokPlastik = Stok::with('jenisPlastik')
            ->orderBy('total_berat', 'desc')
            ->paginate(10, ['*'], 'page_plastik');
        
        $totalStokPlastik = Stok::sum('total_berat');
        $jenisPlastikCount = JenisPlastik::count();
        
        // Hitung status stok plastik
        $stokPlastikMenipis = Stok::where('total_berat', '<', 100)
            ->where('total_berat', '>', 0)
            ->count();
        $stokPlastikHabis = Stok::where('total_berat', '<=', 0)->count();
        
        // Stok Produk Jadi
        $stokProduk = \App\Models\JenisProduk::select(
                'jenis_produk.id',
                'jenis_produk.nama',
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
            )) as total_berat')
            ->orderBy('total_berat', 'desc')
            ->paginate(10, ['*'], 'page_produk');
        
        $totalStokProduk = $stokProduk->sum('total_berat');
        $jenisProdukCount = \App\Models\JenisProduk::count();
        
        // Hitung status stok produk
        $allProduk = \App\Models\JenisProduk::select(
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
        
        $stokProdukMenipis = 0;
        $stokProdukHabis = 0;
        
        foreach ($allProduk as $produk) {
            $total = (float) $produk->total;
            if ($total <= 0) {
                $stokProdukHabis++;
            } elseif ($total < 100) {
                $stokProdukMenipis++;
            }
        }

        return view('dashboard.laporan.stok', compact(
            'stokPlastik',
            'stokProduk',
            'totalStokPlastik',
            'totalStokProduk',
            'jenisPlastikCount',
            'jenisProdukCount',
            'stokPlastikMenipis',
            'stokPlastikHabis',
            'stokProdukMenipis',
            'stokProdukHabis'
        ));
    }

    /**
     * Export stok ke PDF
     */
    public function exportStokPdf()
    {
        // Stok Bahan Baku
        $stokPlastik = Stok::with('jenisPlastik')
            ->orderBy('total_berat', 'desc')
            ->get();
        
        $totalStokPlastik = $stokPlastik->sum('total_berat');
        
        // Stok Produk Jadi
        $stokProduk = \App\Models\JenisProduk::select(
                'jenis_produk.id',
                'jenis_produk.nama',
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
            )) as total_berat')
            ->orderBy('total_berat', 'desc')
            ->get();
        
        $totalStokProduk = $stokProduk->sum('total_berat');

        $pdf = Pdf::loadView('dashboard.laporan.pdf.stok', compact(
            'stokPlastik',
            'stokProduk',
            'totalStokPlastik',
            'totalStokProduk'
        ));
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('laporan-stok-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export stok ke Excel
     */
    public function exportStokExcel()
    {
        return Excel::download(
            new LaporanStokExport(), 
            'laporan-stok-' . date('Y-m-d') . '.xlsx'
        );
    }

   public function exportPenerimaanPdf(Request $request)
{
    $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
    $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

    $dariTanggalFull = $dariTanggal . ' 00:00:00';
    $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

    // ✅ HAPUS 'hasilSortir' dari with()
    $query = Penerimaan::with([
        'supplier',
        'user',
        'detailPenerimaan.jenisPlastik',
    ])->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('tipe')) {
        $query->where('tipe', $request->tipe);
    }
    if ($request->filled('status_sortir')) {
        $query->where('status_sortir', $request->status_sortir);
    }

    $data = $query->orderBy('tanggal', 'desc')->get();

    $pdf = Pdf::loadView('dashboard.laporan.pdf.penerimaan', compact('data', 'dariTanggal', 'sampaiTanggal'));
    $pdf->setPaper('A4', 'landscape');
    
    return $pdf->stream('laporan-penerimaan-' . date('Y-m-d') . '.pdf');
}


    public function exportPenerimaanExcel(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampaiTanggal');
        
        return Excel::download(
            new LaporanPenerimaanExport($dariTanggal, $sampaiTanggal, $request->all()), 
            'laporan-penerimaan-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportProduksiPdf(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

        // Tambahkan waktu untuk mencakup sehari penuh
        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Produksi::with([
            'user', 
            'detailHasilProduksi.jenisProduk'
        ])->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

        if ($request->filled('jenis_produk_id')) {
            $query->whereHas('detailHasilProduksi', function ($q) use ($request) {
                $q->where('jenis_produk_id', $request->jenis_produk_id);
            });
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        $pdf = Pdf::loadView('dashboard.laporan.pdf.produksi', compact('data', 'dariTanggal', 'sampaiTanggal'));
        
        // Set paper orientation
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('laporan-produksi-' . date('Y-m-d') . '.pdf');
    }

    public function exportProduksiExcel(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));
        
        return Excel::download(
            new LaporanProduksiExport($dariTanggal, $sampaiTanggal, $request->all()), 
            'laporan-produksi-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPenjualanPdf(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

        // Tambahkan waktu untuk mencakup sehari penuh
        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

        if ($request->filled('pembeli_id')) {
            $query->where('pembeli_id', $request->pembeli_id);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        // Hitung total
        $totalBerat = $data->sum(function($p) {
            return $p->detailPenjualan->sum('qty');
        });
        
        $totalHarga = $data->sum('total_harga');

        $pdf = Pdf::loadView('dashboard.laporan.pdf.penjualan', compact(
            'data', 'dariTanggal', 'sampaiTanggal', 'totalBerat', 'totalHarga'
        ));
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    public function exportPenjualanExcel(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));
        
        return Excel::download(
            new LaporanPenjualanExport($dariTanggal, $sampaiTanggal, $request->all()), 
            'laporan-penjualan-' . date('Y-m-d') . '.xlsx'
        );
    }

    private function getDataBulanan($tipe)
    {
        $data = array_fill(0, 12, 0);

        if ($tipe === 'penerimaan') {
            $result = DB::table('detail_penerimaan')
                ->join('penerimaan', 'penerimaan.id', '=', 'detail_penerimaan.penerimaan_id')
                ->whereYear('penerimaan.tanggal', now()->year)
                ->select(DB::raw('MONTH(penerimaan.tanggal) as bulan'), DB::raw('SUM(berat_datang_kg) as total'))
                ->groupBy('bulan')
                ->get();
        } elseif ($tipe === 'produksi') {
            $result = DB::table('detail_hasil_produksi')
                ->join('produksi', 'produksi.id', '=', 'detail_hasil_produksi.produksi_id')
                ->whereYear('produksi.tanggal', now()->year)
                ->select(DB::raw('MONTH(produksi.tanggal) as bulan'), DB::raw('SUM(jumlah) as total'))
                ->groupBy('bulan')
                ->get();
        } else {
            $result = DB::table('detail_penjualan')
                ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                ->whereYear('penjualan.tanggal', now()->year)
                ->select(DB::raw('MONTH(penjualan.tanggal) as bulan'), DB::raw('SUM(qty) as total'))
                ->groupBy('bulan')
                ->get();
        }

        foreach ($result as $row) {
            $data[$row->bulan - 1] = $row->total;
        }

        return $data;
    }
}