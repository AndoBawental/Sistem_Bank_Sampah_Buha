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
use App\Http\Controllers\Produksi\JenisPlastikController;
use App\Http\Controllers\Penjualan\PenjualanController;
use App\Http\Controllers\Penjualan\PembeliController;

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
        // Dashboard Admin
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.reset-password');

        // Role Management
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    });

    /*
    |--------------------------------------------------------------------------
    | GUDANG ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/gudang/dashboard', fn() => view('dashboard.gudang.gudang'))->name('gudang.dashboard');
    
    // Route untuk gudang
    Route::middleware(['auth', 'role:admin|gudang'])->prefix('gudang')->name('gudang.')->group(function () {
        
        // ==================== STOK ROUTES ====================
        // Resource stok (otomatis membuat index, create, store, show, edit, update, destroy)
        Route::resource('stok', StokController::class);
        
        // Route tambahan untuk stok
        Route::get('stok/{id}/history', [StokController::class, 'history'])->name('stok.history');
        Route::get('stok/{id}/adjustment', [StokController::class, 'adjustment'])->name('stok.adjustment');
        Route::post('stok/{id}/adjustment', [StokController::class, 'storeAdjustment'])->name('stok.store-adjustment');
        Route::get('stok/export', [StokController::class, 'export'])->name('stok.export');
        
        // ==================== PENERIMAAN ROUTES ====================
        // Resource penerimaan
        Route::resource('penerimaan', PenerimaanController::class);

        // Sortir Routes (Gudang)
Route::prefix('sortir')->name('sortir.')->group(function () {
    Route::get('/', [SortirController::class, 'index'])->name('index');
    Route::get('/{id}', [SortirController::class, 'show'])->name('show');
    Route::post('/{id}', [SortirController::class, 'store'])->name('store');
    Route::put('/{id}', [SortirController::class, 'update'])->name('update');
});
        
        // Route tambahan untuk penerimaan
        Route::get('penerimaan/{id}/sortir', [PenerimaanController::class, 'sortir'])->name('penerimaan.sortir');
        Route::post('penerimaan/{id}/sortir', [PenerimaanController::class, 'storeSortir'])->name('penerimaan.store-sortir');
        Route::post('penerimaan/{id}/pembayaran', [PenerimaanController::class, 'updatePembayaran'])->name('penerimaan.update-pembayaran');
        
        // ==================== SUPPLIER ROUTES ====================
        Route::resource('supplier', SupplierController::class);
    });
    

    /*
    |--------------------------------------------------------------------------
    | PRODUKSI ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('produksi')->middleware('role:produksi')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.produksi'))->name('produksi.dashboard');
    });

    // Route untuk produksi
    Route::middleware(['auth', 'role:admin|produksi'])->prefix('produksi')->name('produksi.')->group(function () {
        // Produksi routes
        Route::get('/', [ProduksiController::class, 'index'])->name('index');
        Route::resource('produksi', ProduksiController::class)->except(['index']);
        
        // Jenis Plastik routes (master data)
        Route::resource('jenis-plastik', JenisPlastikController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | PENJUALAN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('penjualan')->middleware('role:penjualan')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.penjualan'))->name('penjualan.dashboard');
    });

    // Route untuk penjualan
    Route::middleware(['auth', 'role:admin|penjualan'])->prefix('penjualan')->name('penjualan.')->group(function () {
        // Penjualan routes
        Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::resource('penjualan', PenjualanController::class)->except(['index']);
        Route::get('penjualan/{id}/nota', [PenjualanController::class, 'nota'])->name('nota');
        Route::get('penjualan/{id}/pdf', [PenjualanController::class, 'exportPdf'])->name('pdf');
        
        // Pembeli routes
        Route::resource('pembeli', PembeliController::class);
    });

});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('landing'))->name('landing');