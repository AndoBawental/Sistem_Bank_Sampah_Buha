<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Produksi;
use App\Models\Penjualan;
use App\Models\Supplier;      
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenerimaanExport;
use App\Exports\LaporanProduksiExport;
use App\Exports\LaporanPenjualanExport;

class LaporanController extends Controller
{
    // ==================== INDEX ====================
    public function index()
    {
        $totalPenerimaan = Penerimaan::count();
        $totalProduksi = Produksi::count();
        $totalPenjualan = Penjualan::count();

        $totalBeratPenerimaan = DB::table('detail_penerimaan')->sum('berat_datang_kg') ?? 0;
        $totalBeratProduksi = DB::table('detail_hasil_produksi')->sum('total_berat_kg') ?? 0;
        $totalBeratPenjualan = DB::table('detail_penjualan')->sum('berat_nett_kg') ?? 0;

        $penerimaanBulanan = $this->getDataBulanan('penerimaan');
        $produksiBulanan = $this->getDataBulanan('produksi');
        $penjualanBulanan = $this->getDataBulanan('penjualan');

        return view('dashboard.laporan.index', compact(
            'totalPenerimaan', 'totalProduksi', 'totalPenjualan',
            'totalBeratPenerimaan', 'totalBeratProduksi', 'totalBeratPenjualan',
            'penerimaanBulanan', 'produksiBulanan', 'penjualanBulanan'
        ));
    }

    // ==================== PENERIMAAN ====================
    public function penerimaan(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

        if ($request->filled('supplier_id')) $query->where('supplier_id', $request->supplier_id);
        if ($request->filled('tipe')) $query->where('tipe', $request->tipe);
        if ($request->filled('status_sortir')) $query->where('status_sortir', $request->status_sortir);

        $penerimaan = $query->orderBy('tanggal', 'desc')->paginate(15);
        $suppliers = Supplier::orderBy('nama')->get();

        $statsQuery = Penerimaan::whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);
        if ($request->filled('supplier_id')) $statsQuery->where('supplier_id', $request->supplier_id);
        if ($request->filled('tipe')) $statsQuery->where('tipe', $request->tipe);
        if ($request->filled('status_sortir')) $statsQuery->where('status_sortir', $request->status_sortir);

        $totalTransaksi = $statsQuery->count();
        $totalBeli = (clone $statsQuery)->where('tipe', 'Beli')->count();
        $totalDonasi = (clone $statsQuery)->where('tipe', 'Donasi')->count();
        $totalBeratKotor = (clone $statsQuery)->sum('total_berat_kotor_kg');
        $totalBayar = (clone $statsQuery)->where('tipe', 'Beli')->sum('total_bayar');
        $totalBeratBersih = (clone $statsQuery)->where('status_sortir', 'Sudah')->sum('total_berat_kotor_kg');

        return view('dashboard.laporan.penerimaan', compact(
            'penerimaan', 'suppliers', 'dariTanggal', 'sampaiTanggal',
            'totalTransaksi', 'totalBeli', 'totalDonasi',
            'totalBeratKotor', 'totalBeratBersih', 'totalBayar'
        ));
    }

    // ==================== PRODUKSI ====================
    public function produksi(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Produksi::with(['user', 'detailHasilProduksi.jenisProduk', 'detailBahanProduksi.jenisPlastik'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

        if ($request->filled('jenis_produk_id')) {
            $query->whereHas('detailHasilProduksi', fn($q) => $q->where('jenis_produk_id', $request->jenis_produk_id));
        }

        $produksi = $query->orderBy('tanggal', 'desc')->paginate(15);
        $jenisProduk = \App\Models\JenisProduk::orderBy('nama')->get();

        $totalBerat = DB::table('detail_hasil_produksi')
            ->join('produksi', 'produksi.id', '=', 'detail_hasil_produksi.produksi_id')
            ->whereBetween('produksi.tanggal', [$dariTanggalFull, $sampaiTanggalFull])
            ->when($request->filled('jenis_produk_id'), fn($q) => $q->where('detail_hasil_produksi.jenis_produk_id', $request->jenis_produk_id))
            ->sum('total_berat_kg');

        $totalSak = DB::table('detail_hasil_produksi')
            ->join('produksi', 'produksi.id', '=', 'detail_hasil_produksi.produksi_id')
            ->whereBetween('produksi.tanggal', [$dariTanggalFull, $sampaiTanggalFull])
            ->when($request->filled('jenis_produk_id'), fn($q) => $q->where('detail_hasil_produksi.jenis_produk_id', $request->jenis_produk_id))
            ->sum('jumlah_sak');

        $totalTransaksi = (clone $query)->count();

        return view('dashboard.laporan.produksi', compact(
            'produksi', 'jenisProduk', 'dariTanggal', 'sampaiTanggal',
            'totalBerat', 'totalSak', 'totalTransaksi'
        ));
    }

    // ==================== PENJUALAN ====================
    public function penjualan(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);

        if ($request->filled('pembeli_id')) $query->where('pembeli_id', $request->pembeli_id);

        $perPage = $request->input('per_page', 15);
        if (!in_array($perPage, [10, 15, 25, 50, 100])) $perPage = 15;

        $penjualan = $query->orderBy('tanggal', 'desc')->paginate($perPage);
        $pembeliList = \App\Models\Pembeli::orderBy('nama')->get();

        $totalBerat = DB::table('detail_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
            ->whereBetween('penjualan.tanggal', [$dariTanggalFull, $sampaiTanggalFull])
            ->when($request->filled('pembeli_id'), fn($q) => $q->where('penjualan.pembeli_id', $request->pembeli_id))
            ->sum('berat_nett_kg');

        $totalSak = DB::table('detail_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
            ->whereBetween('penjualan.tanggal', [$dariTanggalFull, $sampaiTanggalFull])
            ->when($request->filled('pembeli_id'), fn($q) => $q->where('penjualan.pembeli_id', $request->pembeli_id))
            ->sum('jumlah_sak');

        $totalHarga = (clone $query)->sum('total_harga');
        $totalTransaksi = (clone $query)->count();

        return view('dashboard.laporan.penjualan', compact(
            'penjualan', 'pembeliList', 'dariTanggal', 'sampaiTanggal',
            'totalBerat', 'totalSak', 'totalHarga', 'totalTransaksi'
        ));
    }

    // ==================== EXPORT PDF ====================
   public function exportPenerimaanPdf(Request $request)
{
    $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
    $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));
    $dariTanggalFull = $dariTanggal . ' 00:00:00';
    $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

    $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik'])
        ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);
    
    if ($request->filled('supplier_id')) $query->where('supplier_id', $request->supplier_id);
    if ($request->filled('tipe')) $query->where('tipe', $request->tipe);
    if ($request->filled('status_sortir')) $query->where('status_sortir', $request->status_sortir);

    $data = $query->orderBy('tanggal', 'desc')->get();

    $pdf = Pdf::loadView('dashboard.laporan.pdf.penerimaan', compact('data', 'dariTanggal', 'sampaiTanggal'));
    $pdf->setPaper('A4', 'landscape');
    
    return $pdf->stream('laporan-penerimaan-' . date('Y-m-d') . '.pdf');
}

    public function exportProduksiPdf(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));
        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Produksi::with(['user', 'detailHasilProduksi.jenisProduk', 'detailBahanProduksi.jenisPlastik'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);
        
        if ($request->filled('jenis_produk_id')) {
            $query->whereHas('detailHasilProduksi', fn($q) => $q->where('jenis_produk_id', $request->jenis_produk_id));
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        $pdf = Pdf::loadView('dashboard.laporan.pdf.produksi', compact('data', 'dariTanggal', 'sampaiTanggal'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('laporan-produksi-' . date('Y-m-d') . '.pdf');
    }

    public function exportPenjualanPdf(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));
        $dariTanggalFull = $dariTanggal . ' 00:00:00';
        $sampaiTanggalFull = $sampaiTanggal . ' 23:59:59';

        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->whereBetween('tanggal', [$dariTanggalFull, $sampaiTanggalFull]);
        
        if ($request->filled('pembeli_id')) $query->where('pembeli_id', $request->pembeli_id);

        $data = $query->orderBy('tanggal', 'desc')->get();
        $totalBerat = $data->sum(fn($p) => $p->detailPenjualan->sum('berat_nett_kg'));
        $totalHarga = $data->sum('total_harga');

        $pdf = Pdf::loadView('dashboard.laporan.pdf.penjualan', compact('data', 'dariTanggal', 'sampaiTanggal', 'totalBerat', 'totalHarga'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    // ==================== EXPORT EXCEL ====================
    public function exportPenerimaanExcel(Request $request)
    {
        return Excel::download(new LaporanPenerimaanExport($request->all()), 'laporan-penerimaan-' . date('Y-m-d') . '.xlsx');
    }

    public function exportProduksiExcel(Request $request)
    {
        return Excel::download(new LaporanProduksiExport($request->all()), 'laporan-produksi-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPenjualanExcel(Request $request)
    {
        return Excel::download(new LaporanPenjualanExport($request->all()), 'laporan-penjualan-' . date('Y-m-d') . '.xlsx');
    }

    // ==================== HELPER ====================
    private function getDataBulanan($tipe)
    {
        $data = array_fill(0, 12, 0);

        if ($tipe === 'penerimaan') {
            $result = DB::table('detail_penerimaan')
                ->join('penerimaan', 'penerimaan.id', '=', 'detail_penerimaan.penerimaan_id')
                ->whereYear('penerimaan.tanggal', now()->year)
                ->select(DB::raw('MONTH(penerimaan.tanggal) as bulan'), DB::raw('SUM(berat_datang_kg) as total'))
                ->groupBy('bulan')->get();
        } elseif ($tipe === 'produksi') {
            $result = DB::table('detail_hasil_produksi')
                ->join('produksi', 'produksi.id', '=', 'detail_hasil_produksi.produksi_id')
                ->whereYear('produksi.tanggal', now()->year)
                ->select(DB::raw('MONTH(produksi.tanggal) as bulan'), DB::raw('SUM(total_berat_kg) as total'))
                ->groupBy('bulan')->get();
        } else {
            $result = DB::table('detail_penjualan')
                ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                ->whereYear('penjualan.tanggal', now()->year)
                ->select(DB::raw('MONTH(penjualan.tanggal) as bulan'), DB::raw('SUM(berat_nett_kg) as total'))
                ->groupBy('bulan')->get();
        }

        foreach ($result as $row) $data[$row->bulan - 1] = $row->total;
        return $data;
    }
}