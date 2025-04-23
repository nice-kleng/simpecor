<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained()->onDelete('cascade');
            $table->foreignId('kategori_cor_id')->constrained()->onDelete('cascade');
            $table->float('luas_cor');
            $table->float('volume_cor');
            $table->string('foto_lokasi');
            $table->date('tanggal_pengecoran');
            $table->integer('harga')->nullable();
            $table->integer('jumlah_unit_cor')->nullable();
            $table->integer('jumlah_petugas')->nullable();
            $table->string('jenis_pembayaran')->nullable();
            $table->string('status_pembayaran')->default('unpaid');
            $table->enum('status_pengerjaan', ['menunggu_verifikasi', 'disetujui', 'ditolak', 'proses_pengerjaan', 'selesai'])->default('menunggu_verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
