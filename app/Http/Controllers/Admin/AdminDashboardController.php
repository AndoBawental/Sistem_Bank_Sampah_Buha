<?php
// app/Http/Controllers/Admin/AdminDashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penerimaan;
use App\Models\Stok;
use App\Models\Produksi;
use App\Models\Penjualan;
use App\Models\JenisPlastik;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ==================== SAMPAH MASUK ====================
        $totalSampahMasuk = DB::table('detail_penerimaan')
            ->join('penerimaan', 'detail_penerimaan.penerimaan_id', '=', 'penerimaan.id')
            ->where('penerimaan.tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('detail_penerimaan.berat_datang_kg');
        
        $totalSampahMasukPrev = DB::table('detail_penerimaan')
            ->join('penerimaan', 'detail_penerimaan.penerimaan_id', '=', 'penerimaan.id')
            ->whereBetween('penerimaan.tanggal', [Carbon::now()->subDays(60), Carbon::now()->subDays(31)])
            ->sum('detail_penerimaan.berat_datang_kg');
        
        $persenMasuk = $totalSampahMasukPrev > 0 
            ? (($totalSampahMasuk - $totalSampahMasukPrev) / $totalSampahMasukPrev) * 100 
            : 0;

        // ==================== STOK GUDANG ====================
        $totalStok = Stok::sum('total_berat');
        $jenisPlastikCount = JenisPlastik::count();

        // ==================== HASIL PRODUKSI ====================
        // ⬇️ GANTI 'jumlah' JADI 'total_berat_kg'
        $totalProduksi = DB::table('detail_hasil_produksi')
            ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
            ->where('produksi.tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('detail_hasil_produksi.total_berat_kg');

        // ==================== PENJUALAN ====================
        $totalPenjualan = Penjualan::where('tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('total_harga');

        // ==================== STOK PER JENIS ====================
        $stokPerJenis = Stok::with('jenisPlastik')
            ->where('total_berat', '>', 0)
            ->orderBy('total_berat', 'desc')
            ->get();

        // ==================== GRAFIK 7 HARI ====================
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $penerimaanHarian = DB::table('detail_penerimaan')
                ->join('penerimaan', 'detail_penerimaan.penerimaan_id', '=', 'penerimaan.id')
                ->whereDate('penerimaan.tanggal', $date)
                ->sum('detail_penerimaan.berat_datang_kg');
            
            // ⬇️ GANTI 'jumlah' JADI 'total_berat_kg'
            $produksiHarian = DB::table('detail_hasil_produksi')
                ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
                ->whereDate('produksi.tanggal', $date)
                ->sum('detail_hasil_produksi.total_berat_kg');
            
            $penjualanHarian = Penjualan::whereDate('tanggal', $date)->sum('total_harga');
            
            $last7Days->push([
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'penerimaan' => $penerimaanHarian,
                'produksi' => $produksiHarian,
                'penjualan' => $penjualanHarian,
            ]);
        }

        // ==================== TOP SUPPLIER ====================
        $topSuppliers = DB::table('supplier')
            ->join('penerimaan', 'supplier.id', '=', 'penerimaan.supplier_id')
            ->join('detail_penerimaan', 'penerimaan.id', '=', 'detail_penerimaan.penerimaan_id')
            ->select('supplier.nama', DB::raw('SUM(detail_penerimaan.berat_datang_kg) as total_berat'))
            ->groupBy('supplier.id', 'supplier.nama')
            ->orderBy('total_berat', 'desc')
            ->limit(5)
            ->get();

        // ==================== TOP PRODUK TERLARIS ====================
        // ⬇️ GANTI 'qty' JADI 'jumlah_sak', 'subtotal' sudah benar
        $topProducts = DB::table('jenis_produk')
            ->join('detail_penjualan', 'jenis_produk.id', '=', 'detail_penjualan.jenis_produk_id')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->select(
                'jenis_produk.nama', 
                DB::raw('SUM(detail_penjualan.jumlah_sak) as total_sak'),
                DB::raw('SUM(detail_penjualan.berat_nett_kg) as total_berat'),
                DB::raw('SUM(detail_penjualan.subtotal) as total_revenue')
            )
            ->groupBy('jenis_produk.id', 'jenis_produk.nama')
            ->orderBy('total_berat', 'desc')
            ->limit(5)
            ->get();

        // ==================== STOK MENIPIS ====================
        $stokMenipis = Stok::with('jenisPlastik')
            ->where('total_berat', '<', 100)
            ->where('total_berat', '>', 0)
            ->orderBy('total_berat', 'asc')
            ->get();

        // ==================== STATISTIK BULANAN ====================
        $monthlyStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $penerimaanBulanan = DB::table('detail_penerimaan')
                ->join('penerimaan', 'detail_penerimaan.penerimaan_id', '=', 'penerimaan.id')
                ->whereBetween('penerimaan.tanggal', [$monthStart, $monthEnd])
                ->sum('detail_penerimaan.berat_datang_kg');
            
            // ⬇️ GANTI 'jumlah' JADI 'total_berat_kg'
            $produksiBulanan = DB::table('detail_hasil_produksi')
                ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
                ->whereBetween('produksi.tanggal', [$monthStart, $monthEnd])
                ->sum('detail_hasil_produksi.total_berat_kg');
            
            $penjualanBulanan = Penjualan::whereBetween('tanggal', [$monthStart, $monthEnd])
                ->sum('total_harga');
            
            $monthlyStats->push([
                'month' => $month->format('M Y'),
                'penerimaan' => $penerimaanBulanan,
                'produksi' => $produksiBulanan,
                'penjualan' => $penjualanBulanan,
            ]);
        }

        // ==================== AKTIVITAS TERBARU ====================
        $recentActivities = collect();
        
        $recentPenerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaan'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $totalBerat = $item->detailPenerimaan->sum('berat_datang_kg');
                $tipeLabel = $item->tipe == 'Beli' ? 'Pembelian' : 'Donasi';
                return [
                    'type' => 'penerimaan',
                    'date' => $item->tanggal,
                    'description' => "{$tipeLabel} sampah dari {$item->supplier->nama} - " . number_format($totalBerat, 2, ',', '.') . " Kg",
                    'user' => $item->user->name ?? 'System',
                    'icon' => 'truck-loading',
                    'color' => 'success'
                ];
            });
        
        // ⬇️ GANTI 'jumlah' JADI 'total_berat_kg'
        $recentProduksi = Produksi::with('user', 'detailHasilProduksi.jenisProduk')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $totalHasil = $item->detailHasilProduksi->sum('total_berat_kg');
                $produkNama = $item->detailHasilProduksi->first()->jenisProduk->nama ?? '-';
                $totalSak = $item->detailHasilProduksi->sum('jumlah_sak');
                return [
                    'type' => 'produksi',
                    'date' => $item->tanggal,
                    'description' => "Produksi {$produkNama} - {$totalSak} sak, " . number_format($totalHasil, 2, ',', '.') . " Kg",
                    'user' => $item->user->name ?? 'System',
                    'icon' => 'industry',
                    'color' => 'info'
                ];
            });
        
        $recentPenjualan = Penjualan::with('pembeli', 'user', 'detailPenjualan')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $totalSak = $item->detailPenjualan->sum('jumlah_sak');
                $totalNett = $item->detailPenjualan->sum('berat_nett_kg');
                return [
                    'type' => 'penjualan',
                    'date' => $item->tanggal,
                    'description' => "Penjualan ke {$item->pembeli->nama} - {$totalSak} sak, {$totalNett} Kg - Rp " . number_format($item->total_harga, 0, ',', '.'),
                    'user' => $item->user->name ?? 'System',
                    'icon' => 'shopping-cart',
                    'color' => 'primary'
                ];
            });
        
        $recentActivities = $recentPenerimaan
            ->concat($recentProduksi)
            ->concat($recentPenjualan)
            ->sortByDesc('date')
            ->take(10);

        // ==================== DATA USER ====================
        $userCount = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();
        
        $userRoles = \Spatie\Permission\Models\Role::withCount('users')->get()
            ->map(function($role) {
                $colors = ['admin' => '#dc3545', 'gudang' => '#198754', 'produksi' => '#ffc107', 'penjualan' => '#0d6efd'];
                return (object) ['name' => ucfirst($role->name), 'count' => $role->users_count, 'color' => $colors[$role->name] ?? '#6c757d'];
            })
            ->filter(fn($role) => $role->count > 0);

        // ==================== STATISTIK PENERIMAAN ====================
        $penerimaanStats = DB::table('penerimaan')
            ->select('tipe', DB::raw('COUNT(*) as total_transaksi'), DB::raw('SUM(total_berat_kotor_kg) as total_berat'), DB::raw('SUM(total_bayar) as total_nilai'))
            ->where('tanggal', '>=', Carbon::now()->subDays(30))
            ->groupBy('tipe')->get();

        $sortirPending = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();

        return view('dashboard.admin.admin', compact(
            'totalSampahMasuk', 'persenMasuk', 'totalStok', 'jenisPlastikCount',
            'totalProduksi', 'totalPenjualan', 'stokPerJenis', 'last7Days',
            'topSuppliers', 'topProducts', 'stokMenipis', 'monthlyStats',
            'recentActivities', 'userCount', 'newUsersThisMonth', 'userRoles',
            'penerimaanStats', 'sortirPending'
        ));
    }
}