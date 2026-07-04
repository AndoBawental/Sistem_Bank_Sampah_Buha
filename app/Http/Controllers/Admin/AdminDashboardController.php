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
use App\Models\Supplier;
use App\Models\JenisProduk;
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

        // ==================== PRODUKSI ====================
        $totalProduksi = Produksi::where('tanggal', '>=', Carbon::now()->subDays(30))->count();
        $totalSak = DB::table('detail_hasil_produksi')
            ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
            ->where('produksi.tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('detail_hasil_produksi.jumlah_sak');
        $totalBeratProduksi = DB::table('detail_hasil_produksi')
            ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
            ->where('produksi.tanggal', '>=', Carbon::now()->subDays(30))
            ->sum('detail_hasil_produksi.total_berat_kg');

        // ==================== PRODUKSI BULAN INI ====================
        $totalProduksiBulanIni = Produksi::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalSakBulanIni = DB::table('detail_hasil_produksi')
            ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
            ->whereMonth('produksi.tanggal', now()->month)->whereYear('produksi.tanggal', now()->year)
            ->sum('detail_hasil_produksi.jumlah_sak');
        $totalBeratHasilBulanIni = DB::table('detail_hasil_produksi')
            ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
            ->whereMonth('produksi.tanggal', now()->month)->whereYear('produksi.tanggal', now()->year)
            ->sum('detail_hasil_produksi.total_berat_kg');
        $totalBahanBulanIni = DB::table('detail_bahan_produksi')
            ->join('produksi', 'detail_bahan_produksi.produksi_id', '=', 'produksi.id')
            ->whereMonth('produksi.tanggal', now()->month)->whereYear('produksi.tanggal', now()->year)
            ->sum('detail_bahan_produksi.berat_kg');
        $produkTerbanyak = DB::table('detail_hasil_produksi')
            ->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')
            ->join('jenis_produk', 'detail_hasil_produksi.jenis_produk_id', '=', 'jenis_produk.id')
            ->select('jenis_produk.nama', DB::raw('COUNT(*) as total'))
            ->whereMonth('produksi.tanggal', now()->month)->whereYear('produksi.tanggal', now()->year)
            ->groupBy('jenis_produk.id', 'jenis_produk.nama')
            ->orderBy('total', 'desc')->first();

        // ==================== PENJUALAN ====================
        $totalPenjualan = Penjualan::where('tanggal', '>=', Carbon::now()->subDays(30))->sum('total_harga');

        // ==================== PENJUALAN BULAN INI ====================
        $totalPenjualanBulanIni = Penjualan::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('total_harga');
        $totalTransaksiPenjualanBulanIni = Penjualan::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalSakTerjualBulanIni = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->whereMonth('penjualan.tanggal', now()->month)->whereYear('penjualan.tanggal', now()->year)
            ->sum('detail_penjualan.jumlah_sak');
        $totalBeratTerjualBulanIni = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->whereMonth('penjualan.tanggal', now()->month)->whereYear('penjualan.tanggal', now()->year)
            ->sum('detail_penjualan.berat_nett_kg');
        $totalPenjualanBulanLalu = Penjualan::whereMonth('tanggal', now()->subMonth()->month)->whereYear('tanggal', now()->subMonth()->year)->sum('total_harga');
        $persenPenjualan = $totalPenjualanBulanLalu > 0 ? (($totalPenjualanBulanIni - $totalPenjualanBulanLalu) / $totalPenjualanBulanLalu) * 100 : ($totalPenjualanBulanIni > 0 ? 100 : 0);
        $pembeliTerbanyak = DB::table('penjualan')
            ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
            ->select('pembeli.nama', DB::raw('COUNT(*) as total'))
            ->whereMonth('penjualan.tanggal', now()->month)->whereYear('penjualan.tanggal', now()->year)
            ->groupBy('pembeli.id', 'pembeli.nama')
            ->orderBy('total', 'desc')->first();

        // ==================== STATISTIK PENERIMAAN ====================
        $penerimaanBulanIni = Penerimaan::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('total_berat_kotor_kg');
        $penerimaanBulanLalu = Penerimaan::whereMonth('tanggal', now()->subMonth()->month)->whereYear('tanggal', now()->subMonth()->year)->sum('total_berat_kotor_kg');
        $persenPenerimaan = $penerimaanBulanLalu > 0 ? (($penerimaanBulanIni - $penerimaanBulanLalu) / $penerimaanBulanLalu) * 100 : ($penerimaanBulanIni > 0 ? 100 : 0);
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('total_bayar');
        $totalBeliTransaksi = Penerimaan::where('tipe', 'Beli')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalDonasiBulanIni = Penerimaan::where('tipe', 'Donasi')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('total_berat_kotor_kg');
        $totalDonasiTransaksi = Penerimaan::where('tipe', 'Donasi')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalTransaksiPenerimaan = Penerimaan::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $supplierAktif = Supplier::count();
        $beratKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
        $beratBersih = Penerimaan::where('status_sortir', 'Sudah')->sum('total_berat_kotor_kg');
        $karungBelumSortir = DB::table('detail_penerimaan AS dp')->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')->where('p.status_sortir', 'Belum')->sum('dp.jumlah_karung');
        $karungSudahSortir = DB::table('detail_penerimaan AS dp')->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')->where('p.status_sortir', 'Sudah')->sum('dp.jumlah_karung');
        if ($karungBelumSortir == 0) { $karungBelumSortir = DB::table('detail_penerimaan AS dp')->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')->where('p.status_sortir', 'Belum')->count(); }
        if ($karungSudahSortir == 0) { $karungSudahSortir = DB::table('detail_penerimaan AS dp')->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')->where('p.status_sortir', 'Sudah')->count(); }
        $totalKarung = $karungBelumSortir + $karungSudahSortir;

        // ==================== STOK PER JENIS ====================
        $stokPerJenis = Stok::with('jenisPlastik')->where('total_berat', '>', 0)->orderBy('total_berat', 'desc')->get();

        // ==================== GRAFIK 7 HARI ====================
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $penerimaanHarian = DB::table('detail_penerimaan')->join('penerimaan', 'detail_penerimaan.penerimaan_id', '=', 'penerimaan.id')->whereDate('penerimaan.tanggal', $date)->sum('detail_penerimaan.berat_datang_kg');
            $produksiHarian = DB::table('detail_hasil_produksi')->join('produksi', 'detail_hasil_produksi.produksi_id', '=', 'produksi.id')->whereDate('produksi.tanggal', $date)->sum('detail_hasil_produksi.total_berat_kg');
            $penjualanHarian = Penjualan::whereDate('tanggal', $date)->sum('total_harga');
            $last7Days->push(['date' => $date->format('Y-m-d'), 'day' => $date->locale('id')->translatedFormat('D'), 'penerimaan' => $penerimaanHarian, 'produksi' => $produksiHarian, 'penjualan' => $penjualanHarian]);
        }

        // ==================== TOP SUPPLIER ====================
        $topSuppliers = DB::table('supplier')->join('penerimaan', 'supplier.id', '=', 'penerimaan.supplier_id')->join('detail_penerimaan', 'penerimaan.id', '=', 'detail_penerimaan.penerimaan_id')->select('supplier.nama', DB::raw('SUM(detail_penerimaan.berat_datang_kg) as total_berat'))->groupBy('supplier.id', 'supplier.nama')->orderBy('total_berat', 'desc')->limit(5)->get();

        // ==================== STOK PRODUK ====================
        $stokProduk = JenisProduk::select(
            'jenis_produk.id',
            'jenis_produk.nama',
            'jenis_produk.keterangan',
            DB::raw('COALESCE((SELECT SUM(dhp.total_berat_kg) FROM detail_hasil_produksi dhp WHERE dhp.jenis_produk_id = jenis_produk.id), 0) as total_produksi'),
            DB::raw('COALESCE((SELECT SUM(dp.jumlah_sak) FROM detail_penjualan dp JOIN penjualan p ON dp.penjualan_id = p.id WHERE dp.jenis_produk_id = jenis_produk.id), 0) as total_terjual_sak'),
            DB::raw('COALESCE((SELECT SUM(dp.berat_nett_kg) FROM detail_penjualan dp JOIN penjualan p ON dp.penjualan_id = p.id WHERE dp.jenis_produk_id = jenis_produk.id), 0) as total_terjual_berat'),
            DB::raw('COALESCE((SELECT SUM(CASE WHEN tipe="tambah" THEN berat ELSE -berat END) FROM stok_produk_adjustment_logs WHERE jenis_produk_id = jenis_produk.id), 0) as total_adjustment'),
            DB::raw('GREATEST(0, 
                COALESCE((SELECT SUM(dhp.total_berat_kg) FROM detail_hasil_produksi dhp WHERE dhp.jenis_produk_id = jenis_produk.id), 0)
                - COALESCE((SELECT SUM(dp.berat_nett_kg) FROM detail_penjualan dp JOIN penjualan p ON dp.penjualan_id = p.id WHERE dp.jenis_produk_id = jenis_produk.id), 0)
                + COALESCE((SELECT SUM(CASE WHEN tipe="tambah" THEN berat ELSE -berat END) FROM stok_produk_adjustment_logs WHERE jenis_produk_id = jenis_produk.id), 0)
            ) as stok_aktual')
        )->get()->map(function($item) {
            $item->stok_aktual = (float) $item->stok_aktual;
            $item->total_produksi = (float) $item->total_produksi;
            $item->total_terjual_berat = (float) $item->total_terjual_berat;
            $item->total_terjual_sak = (int) $item->total_terjual_sak;
            $item->total_adjustment = (float) $item->total_adjustment;
            return $item;
        });

        // ✅ Hitung total & status stok produk
        $totalStokProduk = $stokProduk->sum('stok_aktual');
        $jenisProdukCount = $stokProduk->count();
        $stokProdukMenipis = $stokProduk->where('stok_aktual', '<', 100)->where('stok_aktual', '>', 0)->count();
        $stokProdukHabis = $stokProduk->where('stok_aktual', '<=', 0)->count();
        $stokProdukPerluPerhatian = $stokProdukMenipis + $stokProdukHabis;

        // ==================== STOK MENIPIS (PLASTIK) ====================
        $stokMenipis = Stok::with('jenisPlastik')->where('total_berat', '<', 100)->where('total_berat', '>', 0)->orderBy('total_berat', 'asc')->get();

        // ==================== AKTIVITAS TERBARU ====================
        $recentActivities = collect();
        $recentPenerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaan'])->orderBy('tanggal', 'desc')->limit(5)->get()->map(function($item) {
            $totalBerat = $item->detailPenerimaan->sum('berat_datang_kg');
            $totalKarung = $item->detailPenerimaan->sum('jumlah_karung');
            return ['type' => 'penerimaan', 'date' => $item->tanggal, 'description' => ($item->tipe == 'Beli' ? 'Pembelian' : 'Donasi') . " dari {$item->supplier->nama} - {$totalKarung} karung, " . number_format($totalBerat, 2, ',', '.') . " Kg " . ($item->status_sortir == 'Sudah' ? '✅ Bersih' : '⏳ Kotor'), 'user' => $item->user->name ?? 'System', 'icon' => 'truck-loading', 'color' => 'success'];
        });
        $recentProduksi = Produksi::with('user', 'detailHasilProduksi.jenisProduk')->orderBy('tanggal', 'desc')->limit(5)->get()->map(function($item) {
            $totalHasil = $item->detailHasilProduksi->sum('total_berat_kg');
            $produkNama = $item->detailHasilProduksi->first()->jenisProduk->nama ?? '-';
            $totalSak = $item->detailHasilProduksi->sum('jumlah_sak');
            return ['type' => 'produksi', 'date' => $item->tanggal, 'description' => "Produksi {$produkNama} - {$totalSak} sak, " . number_format($totalHasil, 2, ',', '.') . " Kg", 'user' => $item->user->name ?? 'System', 'icon' => 'industry', 'color' => 'warning'];
        });
        $recentPenjualan = Penjualan::with('pembeli', 'user', 'detailPenjualan')->orderBy('tanggal', 'desc')->limit(5)->get()->map(function($item) {
            $totalSak = $item->detailPenjualan->sum('jumlah_sak');
            $totalNett = $item->detailPenjualan->sum('berat_nett_kg');
            return ['type' => 'penjualan', 'date' => $item->tanggal, 'description' => "Penjualan ke {$item->pembeli->nama} - {$totalSak} sak, " . number_format($totalNett, 2, ',', '.') . " Kg - Rp " . number_format($item->total_harga, 0, ',', '.'), 'user' => $item->user->name ?? 'System', 'icon' => 'shopping-cart', 'color' => 'primary'];
        });
        $recentActivities = $recentPenerimaan->concat($recentProduksi)->concat($recentPenjualan)->sortByDesc('date')->take(10);

        // ==================== DATA USER ====================
        $userCount = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $userRoles = \Spatie\Permission\Models\Role::withCount('users')->get()->map(function($role) {
            $colors = ['admin' => '#dc3545', 'gudang' => '#198754', 'produksi' => '#ffc107', 'penjualan' => '#0d6efd'];
            return (object) ['name' => ucfirst($role->name), 'count' => $role->users_count, 'color' => $colors[$role->name] ?? '#6c757d'];
        })->filter(fn($role) => $role->count > 0);

        // ✅ Semua variabel sudah didefinisikan sebelum compact
        return view('dashboard.admin.admin', compact(
            'totalSampahMasuk', 'persenMasuk', 'totalStok', 'jenisPlastikCount',
            'totalProduksi', 'totalSak', 'totalBeratProduksi', 'totalPenjualan',
            'totalProduksiBulanIni', 'totalSakBulanIni', 'totalBeratHasilBulanIni', 'totalBahanBulanIni', 'produkTerbanyak',
            'totalPenjualanBulanIni', 'totalTransaksiPenjualanBulanIni', 'totalSakTerjualBulanIni', 'totalBeratTerjualBulanIni',
            'totalPenjualanBulanLalu', 'persenPenjualan', 'pembeliTerbanyak',
            'penerimaanBulanIni', 'penerimaanBulanLalu', 'persenPenerimaan',
            'totalBeliBulanIni', 'totalBeliTransaksi', 'totalDonasiBulanIni', 'totalDonasiTransaksi',
            'totalTransaksiPenerimaan', 'supplierAktif', 'beratKotor', 'beratBersih',
            'totalKarung', 'karungBelumSortir', 'karungSudahSortir',
            'stokPerJenis', 'last7Days', 'topSuppliers',
            'stokMenipis', 'recentActivities', 'userCount', 'newUsersThisMonth', 
            'userRoles',
            'stokProduk', 'totalStokProduk', 'jenisProdukCount', 'stokProdukMenipis', 'stokProdukHabis', 'stokProdukPerluPerhatian'
        ));
    }
}