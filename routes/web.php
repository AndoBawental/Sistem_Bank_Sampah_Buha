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
    Route::get('/dashboard', fn() => view('dashboard.index'))->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    });

    /*
    |--------------------------------------------------------------------------
    | GUDANG ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/gudang/dashboard', fn() => view('dashboard.gudang.gudang'))->name('gudang.dashboard');
    
    Route::middleware(['auth', 'role:admin|gudang'])->prefix('gudang')->name('gudang.')->group(function () {
        
        // Stok routes
        Route::resource('stok', StokController::class);
        Route::get('stok/{id}/history', [StokController::class, 'history'])->name('stok.history');
        Route::get('stok/{id}/adjustment', [StokController::class, 'adjustment'])->name('stok.adjustment');
        Route::post('stok/{id}/adjustment', [StokController::class, 'storeAdjustment'])->name('stok.store-adjustment');
        
        // Penerimaan routes
        Route::resource('penerimaan', PenerimaanController::class);
        Route::get('penerimaan/{id}/sortir', [PenerimaanController::class, 'sortir'])->name('penerimaan.sortir');
        Route::post('penerimaan/{id}/sortir', [PenerimaanController::class, 'storeSortir'])->name('penerimaan.store-sortir');
        
        // Sortir routes (terpisah)
        Route::prefix('sortir')->name('sortir.')->group(function () {
            Route::get('/', [SortirController::class, 'index'])->name('index');
            Route::get('/{id}', [SortirController::class, 'show'])->name('show');
            Route::post('/{id}', [SortirController::class, 'store'])->name('store');
            Route::put('/{id}', [SortirController::class, 'update'])->name('update');
        });
        
        // Supplier routes
        Route::resource('supplier', SupplierController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | PRODUKSI ROUTES
    |--------------------------------------------------------------------------
    */
  Route::middleware(['auth', 'role:admin|produksi'])
    ->prefix('produksi')
    ->name('produksi.')
    ->group(function () {

        Route::get('/', [ProduksiController::class, 'index'])->name('index');
        Route::get('/create', [ProduksiController::class, 'create'])->name('create');
        Route::post('/', [ProduksiController::class, 'store'])->name('store');

        // penting: taruh stok di atas
        Route::get('/stok', [StokProdukController::class, 'index'])->name('stok.index');

 Route::get('/stok/{jenisProduk}/riwayat', [StokProdukController::class, 'riwayat'])
            ->name('stok.riwayat');

        Route::get('/{id}', [ProduksiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProduksiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProduksiController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProduksiController::class, 'destroy'])->name('destroy');
    });

    /*
|--------------------------------------------------------------------------
| DATA UTAMA ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('data-utama')->name('data-utama.')->group(function () {

    // Jenis Plastik
    Route::resource('jenis-plastik', JenisPlastikController::class);

    // Jenis Produk
    Route::resource('jenis-produk', JenisProdukController::class);

});

    /*
    |--------------------------------------------------------------------------
    | PENJUALAN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin|penjualan'])->prefix('penjualan')->name('penjualan.')->group(function () {
     Route::resource('pembeli', PembeliController::class);  
    Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::get('/create', [PenjualanController::class, 'create'])->name('create');
        Route::post('/', [PenjualanController::class, 'store'])->name('store');
        Route::get('/{id}', [PenjualanController::class, 'show'])->name('show');
        Route::get('/{id}/nota', [PenjualanController::class, 'nota'])->name('nota');
        Route::delete('/{id}', [PenjualanController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [PenjualanController::class, 'edit'])->name('edit');
Route::put('/{id}', [PenjualanController::class, 'update'])->name('update');
        
    });

});

/*
|--------------------------------------------------------------------------
| LAPORAN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|gudang|produksi|penjualan'])
    ->prefix('laporan')
    ->name('laporan.')
    ->group(function () {
        
        // Halaman utama laporan
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        
        // Laporan Penerimaan
        Route::get('/penerimaan', [LaporanController::class, 'penerimaan'])->name('penerimaan');
        Route::get('/penerimaan/export-pdf', [LaporanController::class, 'exportPenerimaanPdf'])->name('penerimaan.pdf');
        Route::get('/penerimaan/export-excel', [LaporanController::class, 'exportPenerimaanExcel'])->name('penerimaan.excel');
        
        // Laporan Produksi
        Route::get('/produksi', [LaporanController::class, 'produksi'])->name('produksi');
        Route::get('/produksi/export-pdf', [LaporanController::class, 'exportProduksiPdf'])->name('produksi.pdf');
        Route::get('/produksi/export-excel', [LaporanController::class, 'exportProduksiExcel'])->name('produksi.excel');
        
        // Laporan Penjualan
        Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/penjualan/export-pdf', [LaporanController::class, 'exportPenjualanPdf'])->name('penjualan.pdf');
        Route::get('/penjualan/export-excel', [LaporanController::class, 'exportPenjualanExcel'])->name('penjualan.excel');
        
        // Laporan Stok
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/stok/export-pdf', [LaporanController::class, 'exportStokPdf'])->name('stok.pdf');
        Route::get('/stok/export-excel', [LaporanController::class, 'exportStokExcel'])->name('stok.excel');
    });

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('landing'))->name('landing');