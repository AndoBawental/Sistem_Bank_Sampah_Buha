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

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD UMUM
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');


   /*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware('role:admin')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

        // USER MANAGEMENT
        Route::resource('/users', \App\Http\Controllers\Admin\UserController::class);

        // Reset password for a specific user
        Route::post('/users/{user}/reset-password', 
            [\App\Http\Controllers\Admin\UserController::class, 'resetPassword']
        )->name('users.reset-password');

        // ROLE MANAGEMENT
        Route::get('/roles', 
            [\App\Http\Controllers\Admin\RoleController::class, 'index']
        )->name('roles.index');

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
                return view('gudang.dashboard');
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
                return view('produksi.dashboard');
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
                return view('penjualan.dashboard');
            })->name('penjualan.dashboard');

        });

});


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing'); // pastikan file ini ada
});