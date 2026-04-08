<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return "Dashboard umum";
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return "Dashboard Admin";
    })->name('admin.dashboard')->middleware('role:admin');

    Route::get('/gudang/dashboard', function () {
        return "Dashboard Gudang";
    })->name('gudang.dashboard')->middleware('role:gudang');

    Route::get('/produksi/dashboard', function () {
        return "Dashboard Produksi";
    })->name('produksi.dashboard')->middleware('role:produksi');

    Route::get('/penjualan/dashboard', function () {
        return "Dashboard Penjualan";
    })->name('penjualan.dashboard')->middleware('role:penjualan');

});

Route::get('/', function () {
    return view('landing');
});
