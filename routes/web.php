<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriCorController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthenticateController::class, 'index'])->name('login');
    Route::post('/login', [AuthenticateController::class, 'authenticate'])->name('auth');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticateController::class, 'logout'])->name('logout');

    Route::resources([
        'supplier' => SupplierController::class,
        'mitra' => MitraController::class,
        'bahan' => BahanController::class,
        'kategori' => KategoriCorController::class,
        'barang-masuk' => BarangMasukController::class,
    ]);

    Route::patch('/barang-masuk/{barangMasuk}/status', [BarangMasukController::class, 'updateStatus'])
        ->name('barang-masuk.status');
});
