<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriCorController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PemesananController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthenticateController::class, 'index'])->name('login');
    Route::post('/login', [AuthenticateController::class, 'authenticate'])->name('auth');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticateController::class, 'logout'])->name('logout');

    // Routes khusus admin
    Route::middleware(['checkRole:admin'])->group(function () {
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

    // Routes untuk pemesanan (bisa diakses admin dan mitra)
    Route::resource('pemesanan', PemesananController::class);
    Route::middleware(['checkRole:admin'])->group(function () {
        Route::post('pemesanan/{pemesanan}/verify', [PemesananController::class, 'verify'])->name('pemesanan.verify');
        Route::post('pemesanan/{pemesanan}/verify-pembayaran', [PemesananController::class, 'verifyPembayaran'])->name('pemesanan.verify-pembayaran');
        Route::post('pemesanan/{pemesanan}/update-status', [PemesananController::class, 'updateStatus'])->name('pemesanan.update-status');
    });

    // Route untuk upload bukti pembayaran (khusus mitra)
    Route::middleware(['checkRole:mitra'])->group(function () {
        Route::post('pemesanan/{pemesanan}/upload-bukti', [PemesananController::class, 'uploadBuktiPembayaran'])->name('pemesanan.upload-bukti');
    });
});
