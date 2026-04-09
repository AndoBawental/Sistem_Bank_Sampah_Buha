<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penerimaan;
use App\Models\Stok;
use App\Models\Produksi;
use App\Models\Penjualan;
use App\Models\JenisPlastik;
use App\Models\DetailPenerimaanStok;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ✅ Jumlah User
        $userCount = User::count();

        // =========================
        // 📥 TOTAL SAMPAH MASUK (30 hari terakhir)
        // =========================
        $totalSampahMasuk = DetailPenerimaanStok::whereHas('penerimaan', function($q) {
            $q->where('tanggal', '>=', Carbon::now()->subDays(30));
        })->sum('berat');
        
        $totalSampahMasukPrev = DetailPenerimaanStok::whereHas('penerimaan', function($q) {
            $q->whereBetween('tanggal', [Carbon::now()->subDays(60), Carbon::now()->subDays(31)]);
        })->sum('berat');
        
        $persenMasuk = $totalSampahMasukPrev > 0 
            ? (($totalSampahMasuk - $totalSampahMasukPrev) / $totalSampahMasukPrev) * 100 
            : 0;

        // =========================
        // 📦 STOK
        // =========================
        $totalStok = Stok::sum('total_berat');
        $jenisPlastikCount = JenisPlastik::count();

        // =========================
        // 🏭 PRODUKSI
        // =========================
        $totalProduksi = Produksi::where('tanggal', '>=', Carbon::now()->subDays(30))
            ->with('detailHasilProduksi')
            ->get()
            ->sum(fn($p) => $p->detailHasilProduksi->sum('jumlah'));

        // =========================
        // 💰 PENJUALAN
        // =========================
        $totalPenjualan = Penjualan::where('tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('total_harga');

        // =========================
        // 📊 STOK PER JENIS
        // =========================
        $stokPerJenis = Stok::with('jenisPlastik')
            ->orderBy('total_berat', 'desc')
            ->take(10)
            ->get();

        // =========================
        // 📈 GRAFIK 7 HARI
        // =========================
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $last7Days->push([
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'penerimaan' => DetailPenerimaanStok::whereHas('penerimaan', function($q) use ($date) {
                    $q->whereDate('tanggal', $date);
                })->sum('berat'),
                'produksi' => Produksi::whereDate('tanggal', $date)
                    ->with('detailHasilProduksi')
                    ->get()
                    ->sum(fn($p) => $p->detailHasilProduksi->sum('jumlah')),
                'penjualan' => Penjualan::whereDate('tanggal', $date)->sum('total_harga'),
            ]);
        }

        // =========================
        // 🏆 TOP SUPPLIER
        // =========================
        $topSuppliers = DB::table('supplier')
            ->join('penerimaan', 'supplier.id', '=', 'penerimaan.supplier_id')
            ->join('detail_penerimaan_stok', 'penerimaan.id', '=', 'detail_penerimaan_stok.penerimaan_id')
            ->select('supplier.nama', DB::raw('SUM(detail_penerimaan_stok.berat) as total_berat'))
            ->where('penerimaan.tanggal', '>=', Carbon::now()->subMonths(3))
            ->groupBy('supplier.id', 'supplier.nama')
            ->orderByDesc('total_berat')
            ->limit(5)
            ->get();

        // =========================
        // 🛒 TOP PRODUK
        // =========================
        $topProducts = DB::table('jenis_produk')
            ->join('detail_penjualan', 'jenis_produk.id', '=', 'detail_penjualan.jenis_produk_id')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->select(
                'jenis_produk.nama',
                DB::raw('SUM(detail_penjualan.qty) as total_qty'),
                DB::raw('SUM(detail_penjualan.subtotal) as total_revenue')
            )
            ->where('penjualan.tanggal', '>=', Carbon::now()->subMonths(3))
            ->groupBy('jenis_produk.id', 'jenis_produk.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // =========================
        // ⚠️ STOK MENIPIS
        // =========================
        $stokMenipis = Stok::with('jenisPlastik')
            ->where('total_berat', '<', 50)
            ->orderBy('total_berat', 'asc')
            ->get();

        // =========================
        // 📅 STATISTIK BULANAN
        // =========================
        $monthlyStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $monthlyStats->push([
                'month' => $month->format('M Y'),
                'penerimaan' => DetailPenerimaanStok::whereHas('penerimaan', function($q) use ($month) {
                    $q->whereBetween('tanggal', [$month->startOfMonth(), $month->endOfMonth()]);
                })->sum('berat'),
                'penjualan' => Penjualan::whereBetween('tanggal', [$month->startOfMonth(), $month->endOfMonth()])
                    ->sum('total_harga'),
                'produksi' => Produksi::whereBetween('tanggal', [$month->startOfMonth(), $month->endOfMonth()])
                    ->with('detailHasilProduksi')
                    ->get()
                    ->sum(fn($p) => $p->detailHasilProduksi->sum('jumlah')),
            ]);
        }

        // =========================
        // 🕒 AKTIVITAS TERBARU
        // =========================
        $recentActivities = collect();

        $recentActivities = Penerimaan::with('supplier', 'user')->latest('tanggal')->limit(5)->get()
            ->map(fn($item) => [
                'type' => 'penerimaan',
                'date' => $item->tanggal,
                'description' => "Penerimaan dari {$item->supplier->nama}",
                'user' => $item->user->name,
            ])
            ->concat(
                Produksi::with('jenisProduk', 'user')->latest('tanggal')->limit(5)->get()
                ->map(fn($item) => [
                    'type' => 'produksi',
                    'date' => $item->tanggal,
                    'description' => "Produksi {$item->jenisProduk->nama}",
                    'user' => $item->user->name,
                ])
            )
            ->concat(
                Penjualan::with('pembeli', 'user')->latest('tanggal')->limit(5)->get()
                ->map(fn($item) => [
                    'type' => 'penjualan',
                    'date' => $item->tanggal,
                    'description' => "Penjualan ke {$item->pembeli->nama}",
                    'user' => $item->user->name,
                ])
            )
            ->sortByDesc('date')
            ->take(10);

        // =========================
        // 🎯 RETURN VIEW
        // =========================
        return view('dashboard.admin.admin', compact(
            'userCount',
            'totalSampahMasuk',
            'persenMasuk',
            'totalStok',
            'jenisPlastikCount',
            'totalProduksi',
            'totalPenjualan',
            'stokPerJenis',
            'last7Days',
            'topSuppliers',
            'topProducts',
            'stokMenipis',
            'monthlyStats',
            'recentActivities'
        ));
    }
}