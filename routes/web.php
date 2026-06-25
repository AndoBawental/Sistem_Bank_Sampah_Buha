<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Gudang\SortirController;
use App\Http\Controllers\Gudang\StokController;
use App\Http\Controllers\Gudang\PenerimaanController;
use App\Http\Controllers\Gudang\SupplierController;
use App\Http\Controllers\Produksi\ProduksiController;
use App\Http\Controllers\Produksi\StokProdukController;
use App\Http\Controllers\DataUtama\JenisPlastikController;
use App\Http\Controllers\DataUtama\JenisProdukController;
use App\Http\Controllers\Penjualan\PenjualanController;
use App\Http\Controllers\Penjualan\PembeliController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\Produksi\DashboardController;
use App\Http\Controllers\Gudang\DashboardController as GudangDashboardController;


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (HARUS LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // DASHBOARD UMUM
    Route::get('/dashboard', function() {
        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.dashboard' : 'dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        
        // Role Management (Optional - Hanya lihat)
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    });

    /*
    |--------------------------------------------------------------------------
    | GUDANG ROUTES
    |--------------------------------------------------------------------------
    */
   Route::middleware(['auth', 'role:admin|gudang'])
    ->prefix('gudang')
    ->name('gudang.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [GudangDashboardController::class, 'index'])
            ->name('dashboard');

        // ========================
        // STOK
        // ========================
        Route::resource('stok', StokController::class);

        Route::prefix('stok/{stok}')->name('stok.')->group(function () {
            Route::get('/history', [StokController::class, 'history'])->name('history');
            Route::get('/adjustment', [StokController::class, 'adjustment'])->name('adjustment');
            Route::post('/adjustment', [StokController::class, 'storeAdjustment'])->name('store-adjustment');
        });

        // ========================
        // PENERIMAAN
        // ========================
        Route::resource('penerimaan', PenerimaanController::class);

        Route::prefix('penerimaan/{penerimaan}')->name('penerimaan.')->group(function () {
            Route::get('/sortir', [PenerimaanController::class, 'sortir'])->name('sortir');
            Route::post('/sortir', [PenerimaanController::class, 'storeSortir'])->name('store-sortir');
        });

        // ========================
        // SORTIR
        // ========================
        Route::prefix('sortir')->name('sortir.')->group(function () {
        Route::get('/', [SortirController::class, 'index'])->name('index');
    Route::get('/create', [SortirController::class, 'create'])->name('create');
    Route::post('/store', [SortirController::class, 'store'])->name('store');
    Route::delete('/destroy/{id}', [SortirController::class, 'destroy'])->name('destroy');
        });

        // ========================
        // SUPPLIER
        // ========================
        Route::resource('supplier', SupplierController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | PRODUKSI ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin|produksi'])->prefix('produksi')->name('produksi.')->group(function () {
        
       Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
        
        Route::get('/', [ProduksiController::class, 'produksi'])->name('produksi');
        Route::get('/create', [ProduksiController::class, 'create'])->name('create');
        Route::post('/', [ProduksiController::class, 'store'])->name('store');
        
        Route::get('/stok', [StokProdukController::class, 'index'])->name('stok.index');
        Route::get('/stok/{jenisProduk}/riwayat', [StokProdukController::class, 'riwayat'])->name('stok.riwayat');
        
        Route::get('/{id}', [ProduksiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProduksiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProduksiController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProduksiController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | DATA UTAMA ROUTES (Hanya Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('data-utama')->name('data-utama.')->group(function () {
        Route::resource('jenis-plastik', JenisPlastikController::class);
        Route::resource('jenis-produk', JenisProdukController::class);
    });

 Route::middleware(['auth', 'role:admin|penjualan'])
    ->prefix('penjualan')
    ->name('penjualan.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Penjualan\DashboardController::class, 'index'])
            ->name('dashboard');

        // Pembeli
        Route::resource('pembeli', PembeliController::class);

        // Halaman utama transaksi penjualan
        Route::get('/', [PenjualanController::class, 'penjualan'])->name('penjualan');

        // Tambah transaksi
        Route::get('/create', [PenjualanController::class, 'create'])->name('create');

        // Simpan transaksi
        Route::post('/', [PenjualanController::class, 'store'])->name('store');

        // Detail transaksi
        Route::get('/{id}', [PenjualanController::class, 'show'])->name('show');

        // Edit transaksi
        Route::get('/{id}/edit', [PenjualanController::class, 'edit'])->name('edit');

        // Update transaksi
        Route::put('/{id}', [PenjualanController::class, 'update'])->name('update');

        // Hapus transaksi
        Route::delete('/{id}', [PenjualanController::class, 'destroy'])->name('destroy');

        // Nota
        Route::get('/{id}/nota', [PenjualanController::class, 'nota'])->name('nota');
    });

    /*
    |--------------------------------------------------------------------------
    | LAPORAN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin|gudang|produksi|penjualan'])->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/penerimaan', [LaporanController::class, 'penerimaan'])->name('penerimaan');
        Route::get('/penerimaan/export-pdf', [LaporanController::class, 'exportPenerimaanPdf'])->name('penerimaan.pdf');
        Route::get('/penerimaan/export-excel', [LaporanController::class, 'exportPenerimaanExcel'])->name('penerimaan.excel');
        Route::get('/produksi', [LaporanController::class, 'produksi'])->name('produksi');
        Route::get('/produksi/export-pdf', [LaporanController::class, 'exportProduksiPdf'])->name('produksi.pdf');
        Route::get('/produksi/export-excel', [LaporanController::class, 'exportProduksiExcel'])->name('produksi.excel');
        Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/penjualan/export-pdf', [LaporanController::class, 'exportPenjualanPdf'])->name('penjualan.pdf');
        Route::get('/penjualan/export-excel', [LaporanController::class, 'exportPenjualanExcel'])->name('penjualan.excel');
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/stok/export-pdf', [LaporanController::class, 'exportStokPdf'])->name('stok.pdf');
        Route::get('/stok/export-excel', [LaporanController::class, 'exportStokExcel'])->name('stok.excel');
    });
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function() {
    return view('landing');
})->name('landing');