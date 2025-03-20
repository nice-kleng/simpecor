<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id',
        'kategori_cor_id',
        'luas_cor',
        'volume_cor',
        'foto_lokasi',
        'tanggal_pengecoran',
        'harga',
        'jumlah_unit_cor',
        'jumlah_petugas',
        'bukti_pembayaran',
        'status_pembayaran',
        'status_pengerjaan',
        'keterangan_pembayaran'
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

    public function kategoriCor()
    {
        return $this->belongsTo(KategoriCor::class);
    }
}
