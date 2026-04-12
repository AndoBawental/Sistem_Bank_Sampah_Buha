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
        // Query manual dengan JOIN untuk 30 hari terakhir (menggunakan detail_penerimaan)
        $totalSampahMasuk = DB::table('detail_penerimaan')
            ->join('penerimaan', 'detail_penerimaan.penerimaan_id', '=', 'penerimaan.id')
            ->where('penerimaan.tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('detail_penerimaan.berat_datang_kg');
        
        // Untuk perbandingan (periode sebelumnya)
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
        // Query manual untuk hasil produksi 30 hari terakhir
        $totalProduksi = DB::table('detail_hasil_produksi')
            ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
            ->where('produksi.tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('detail_hasil_produksi.jumlah');

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
            
            // Penerimaan per hari (menggunakan detail_penerimaan)
            $penerimaanHarian = DB::table('detail_penerimaan')
                ->join('penerimaan', 'detail_penerimaan.penerimaan_id', '=', 'penerimaan.id')
                ->whereDate('penerimaan.tanggal', $date)
                ->sum('detail_penerimaan.berat_datang_kg');
            
            // Produksi per hari
            $produksiHarian = DB::table('detail_hasil_produksi')
                ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
                ->whereDate('produksi.tanggal', $date)
                ->sum('detail_hasil_produksi.jumlah');
            
            // Penjualan per hari
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
        $topProducts = DB::table('jenis_produk')
            ->join('detail_penjualan', 'jenis_produk.id', '=', 'detail_penjualan.jenis_produk_id')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->select(
                'jenis_produk.nama', 
                DB::raw('SUM(detail_penjualan.qty) as total_qty'), 
                DB::raw('SUM(detail_penjualan.subtotal) as total_revenue')
            )
            ->groupBy('jenis_produk.id', 'jenis_produk.nama')
            ->orderBy('total_qty', 'desc')
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
            
            $produksiBulanan = DB::table('detail_hasil_produksi')
                ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
                ->whereBetween('produksi.tanggal', [$monthStart, $monthEnd])
                ->sum('detail_hasil_produksi.jumlah');
            
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
        
        // Recent Penerimaan (menggunakan detail_penerimaan)
        $recentPenerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaan'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $totalBerat = $item->detailPenerimaan->sum('berat_datang_kg');
                $tipeLabel = $item->tipe == 'Beli' ? 'Pembelian' : 'Donasi';
                $statusSortir = $item->status_sortir;
                
                return [
                    'type' => 'penerimaan',
                    'date' => $item->tanggal,
                    'description' => "{$tipeLabel} sampah dari {$item->supplier->nama} - " . number_format($totalBerat, 2, ',', '.') . " Kg (Status Sortir: {$statusSortir})",
                    'user' => $item->user->name ?? 'System',
                    'icon' => 'truck-loading',
                    'color' => 'success'
                ];
            });
        
        // Recent Produksi
        $recentProduksi = Produksi::with('jenisProduk', 'user', 'detailHasilProduksi')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $totalHasil = $item->detailHasilProduksi->sum('jumlah');
                return [
                    'type' => 'produksi',
                    'date' => $item->tanggal,
                    'description' => "Produksi {$item->jenisProduk->nama} - " . number_format($totalHasil, 0, ',', '.') . " unit",
                    'user' => $item->user->name ?? 'System',
                    'icon' => 'industry',
                    'color' => 'info'
                ];
            });
        
        // Recent Penjualan
        $recentPenjualan = Penjualan::with('pembeli', 'user', 'detailPenjualan')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'penjualan',
                    'date' => $item->tanggal,
                    'description' => "Penjualan ke {$item->pembeli->nama} - Rp " . number_format($item->total_harga, 0, ',', '.'),
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
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $userRoles = \Spatie\Permission\Models\Role::withCount('users')
            ->get()
            ->map(function($role) {
                $colors = [
                    'admin' => '#dc3545',
                    'gudang' => '#198754',
                    'produksi' => '#ffc107',
                    'penjualan' => '#0d6efd'
                ];
                
                return (object) [
                    'name' => ucfirst($role->name),
                    'count' => $role->users_count,
                    'color' => $colors[$role->name] ?? '#6c757d'
                ];
            })
            ->filter(function($role) {
                return $role->count > 0;
            });

        // ==================== STATISTIK PENERIMAAN ====================
        // Perbandingan tipe penerimaan (Beli vs Donasi)
        $penerimaanStats = DB::table('penerimaan')
            ->select(
                'tipe',
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(total_berat_kotor_kg) as total_berat'),
                DB::raw('SUM(total_bayar) as total_nilai')
            )
            ->where('tanggal', '>=', Carbon::now()->subDays(30))
            ->groupBy('tipe')
            ->get();

        // Status sortir pending
        $sortirPending = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])
            ->count();

        return view('dashboard.admin.admin', compact(
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
            'recentActivities',
            'userCount',
            'newUsersThisMonth',
            'userRoles',
            'penerimaanStats',
            'sortirPending'
        ));
    }
}