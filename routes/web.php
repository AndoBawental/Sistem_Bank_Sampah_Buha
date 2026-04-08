<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED (HARUS LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // dashboard umum
    Route::get('/dashboard', function () {
        return "Dashboard umum";
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->middleware('role:admin')
        ->group(function () {

            Route::get('/dashboard', function () {
                return "Dashboard Admin";
            })->name('admin.dashboard');

        });

    /*
    |--------------------------------------------------------------------------
    | GUDANG
    |--------------------------------------------------------------------------
    */
    Route::prefix('gudang')
        ->middleware('role:gudang')
        ->group(function () {

            Route::get('/dashboard', function () {
                return "Dashboard Gudang";
            })->name('gudang.dashboard');

        });

    /*
    |--------------------------------------------------------------------------
    | PRODUKSI
    |--------------------------------------------------------------------------
    */
    Route::prefix('produksi')
        ->middleware('role:produksi')
        ->group(function () {

            Route::get('/dashboard', function () {
                return "Dashboard Produksi";
            })->name('produksi.dashboard');

        });

    /*
    |--------------------------------------------------------------------------
    | PENJUALAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('penjualan')
        ->middleware('role:penjualan')
        ->group(function () {

            Route::get('/dashboard', function () {
                return "Dashboard Penjualan";
            })->name('penjualan.dashboard');

        });

});


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
});