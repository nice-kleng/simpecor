<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriCorController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\KomposisiController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthenticateController::class, 'index'])->name('login');
    Route::post('/login', [AuthenticateController::class, 'authenticate'])->name('auth');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticateController::class, 'logout'])->name('logout');

    // Routes khusus admin
    Route::middleware(['checkRole:admin,direktur'])->group(function () {
        Route::resources([
            'supplier' => SupplierController::class,
            'mitra' => MitraController::class,
            'bahan' => BahanController::class,
            'kategori' => KategoriCorController::class,
            'barang-masuk' => BarangMasukController::class,
            'user' => UserController::class,
        ]);

        Route::controller(KomposisiController::class)->group(function () {
            Route::get('/komposisi/{id}/create', 'create')->name('komposisi.create');
            Route::post('/komposisi', 'store')->name('komposisi.store');
            Route::get('/komposisi/{id}/edit', 'edit')->name('komposisi.edit');
            Route::put('/komposisi/{id}', 'update')->name('komposisi.update');
            Route::delete('/komposisi/{id}', 'destroy')->name('komposisi.destroy');
        });

        Route::patch('/barang-masuk/{barangMasuk}/status', [BarangMasukController::class, 'updateStatus'])
            ->name('barang-masuk.status');
    });

    // Routes untuk pemesanan (bisa diakses admin dan mitra)
    Route::resource('pemesanan', PemesananController::class);
    Route::middleware(['checkRole:admin,direktur'])->group(function () {
        Route::post('pemesanan/{pemesanan}/verify', [PemesananController::class, 'verify'])->name('pemesanan.verify');
        Route::post('pemesanan/{pembayaran}/verify-pembayaran', [PemesananController::class, 'verifyPembayaran'])->name('pemesanan.verify-pembayaran');
        Route::post('pemesanan/{pemesanan}/update-status', [PemesananController::class, 'updateStatus'])->name('pemesanan.update-status');

        Route::get('history-pemesanan', [PemesananController::class, 'historyPemesanan'])->name('pemesanan.history');
        Route::get('export-history', [PemesananController::class, 'exportHistoryPDF'])->name('pemesanan.export-history');
    });

    // Route untuk upload bukti pembayaran (khusus mitra)
    Route::middleware(['checkRole:mitra'])->group(function () {
        Route::post('pemesanan/{pemesanan}/upload-bukti', [PemesananController::class, 'uploadBuktiPembayaran'])->name('pemesanan.upload-bukti');
    });

    Route::get('/pemesanan/{pemesanan}/download-bukti-transafer', [PemesananController::class, 'downloadBuktiPembayaran'])->name('pemesanan.download-bukti-transfer');
    Route::get('/pemesanan/{pemesanan}/download-foto-lokasi', [PemesananController::class, 'downloadFotoLokasi'])->name('pemesanan.download-foto-lokasi');
    Route::get('/pemesanan/{pemesanan}/download-surat-jalan', [PemesananController::class, 'downloadSuratJalan'])->name('pemesanan.download-surat-jalan');
});
