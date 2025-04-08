<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->date('tanggal_selesai')->nullable()->after('tanggal_pengecoran');
            $table->string('catatan_pengerjaan')->nullable()->after('tanggal_selesai');
            $table->string('alamat_lokasi')->nullable()->after('catatan_pengerjaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai');
            $table->dropColumn('catatan_pengerjaan');
            $table->dropColumn('alamat_lokasi');
        });
    }
};
