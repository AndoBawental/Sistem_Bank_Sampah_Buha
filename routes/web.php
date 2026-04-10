<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;

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
    Route::get('/dashboard', fn() => view('dashboard.gudang.gudang'))->name('gudang.dashboard');
    // Route untuk gudang
Route::middleware(['auth', 'role:admin|gudang'])->prefix('gudang')->name('gudang.')->group(function () {
    // Stok routes
    Route::resource('stok', StokController::class);
    
    // Penerimaan routes
    Route::resource('penerimaan', PenerimaanController::class);
    
    // Supplier routes
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
    Route::resource('/', ProduksiController::class);
    Route::resource('produksi', ProduksiController::class);
    
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

});

// Route untuk penjualan
Route::middleware(['auth', 'role:admin|penjualan'])->prefix('penjualan')->name('penjualan.')->group(function () {
    // Penjualan routes
    Route::resource('/', PenjualanController::class);
    Route::resource('penjualan', PenjualanController::class);
    Route::get('penjualan/{id}/nota', [PenjualanController::class, 'nota'])->name('nota');
    Route::get('penjualan/{id}/pdf', [PenjualanController::class, 'exportPdf'])->name('pdf');
    
    // Pembeli routes
    Route::resource('pembeli', PembeliController::class);
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('landing'))->name('landing');