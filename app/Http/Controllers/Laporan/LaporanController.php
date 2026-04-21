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

class LaporanController extends Controller
{
    public function index()
    {
        $totalPenerimaan = Penerimaan::count();
        $totalProduksi = Produksi::count();
        $totalPenjualan = Penjualan::count();

        $totalBeratPenerimaan = DB::table('detail_penerimaan')->sum('berat') ?? 0;
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

        $query = Penerimaan::with([
            'supplier',
            'user',
            'detailPenerimaan.jenisPlastik',
            'hasilSortir'
        ])->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);

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

        $totalTransaksi = (clone $query)->count();
        $totalBeli = (clone $query)->where('tipe', 'Beli')->count();
        $totalDonasi = (clone $query)->where('tipe', 'Donasi')->count();
        $totalBeratKotor = (clone $query)->sum('total_berat_kotor_kg');

        $totalBeratBersih = HasilSortir::whereHas('penerimaan', function ($q) use ($dariTanggal, $sampaiTanggal) {
            $q->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);
        })->sum('berat_bersih_kg');

        $totalBayar = (clone $query)->where('tipe', 'Beli')->sum('total_bayar');

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

        $query = Produksi::with(['user', 'detailHasilProduksi.jenisProduk'])
            ->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);

        if ($request->filled('jenis_produk_id')) {
            $query->whereHas('detailHasilProduksi', function ($q) use ($request) {
                $q->where('jenis_produk_id', $request->jenis_produk_id);
            });
        }

        $produksi = $query->orderBy('tanggal', 'desc')->paginate(15);

        $jenisProduk = \App\Models\JenisProduk::orderBy('nama')->get();

        // ✅ FIX join (bukan whereHas)
        $totalBerat = DB::table('detail_hasil_produksi')
            ->join('produksi', 'produksi.id', '=', 'detail_hasil_produksi.produksi_id')
            ->whereBetween('produksi.tanggal', [$dariTanggal, $sampaiTanggal])
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

        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);

        $penjualan = $query->orderBy('tanggal', 'desc')->paginate(15);

        // ✅ FIX join
        $totalBerat = DB::table('detail_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
            ->whereBetween('penjualan.tanggal', [$dariTanggal, $sampaiTanggal])
            ->sum('qty');

        $totalHarga = (clone $query)->sum('total_harga');
        $totalTransaksi = (clone $query)->count();

        return view('dashboard.laporan.penjualan', compact(
            'penjualan', 'dariTanggal', 'sampaiTanggal',
            'totalBerat', 'totalHarga', 'totalTransaksi'
        ));
    }

    public function stok()
    {
        $stok = JenisPlastik::withSum('stok', 'total_berat')
            ->orderBy('nama')
            ->paginate(15);

        $totalStok = Stok::sum('total_berat');

        return view('dashboard.laporan.stok', compact('stok', 'totalStok'));
    }

public function exportPenerimaanPdf(Request $request)
{
    $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->format('Y-m-d'));
    $sampaiTanggal = $request->input('sampai_tanggal', now()->format('Y-m-d'));

    $query = Penerimaan::with([
        'supplier',
        'user',
        'detailPenerimaan.jenisPlastik',
        'hasilSortir'
    ])->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal]);

    // Apply filters yang sama seperti di method penerimaan
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
    
    // Set paper orientation
    $pdf->setPaper('A4', 'landscape');
    
    // Stream atau download
    return $pdf->stream('laporan-penerimaan-' . date('Y-m-d') . '.pdf');
}

public function exportPenerimaanExcel(Request $request)
{
    $dariTanggal = $request->input('dari_tanggal');
    $sampaiTanggal = $request->input('sampai_tanggal');
    
    return Excel::download(
        new LaporanPenerimaanExport($dariTanggal, $sampaiTanggal, $request->all()), 
        'laporan-penerimaan-' . date('Y-m-d') . '.xlsx'
    );
}

    private function getDataBulanan($tipe)
    {
        $data = array_fill(0, 12, 0);

        if ($tipe === 'penerimaan') {
            $result = DB::table('detail_penerimaan')
                ->join('penerimaan', 'penerimaan.id', '=', 'detail_penerimaan.penerimaan_id')
                ->whereYear('penerimaan.tanggal', now()->year)
                ->select(DB::raw('MONTH(penerimaan.tanggal) as bulan'), DB::raw('SUM(berat) as total'))
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

