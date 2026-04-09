<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;


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
    Route::prefix('gudang')->middleware('role:gudang')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.gudang'))->name('gudang.dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | PRODUKSI ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('produksi')->middleware('role:produksi')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.produksi'))->name('produksi.dashboard');
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

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('landing'))->name('landing');