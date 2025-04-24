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
        'tanggal_selesai',
        'catatan_pengerjaan',
        'alamat_lokasi',
        'harga',
        'jumlah_unit_cor',
        'jumlah_petugas',
        'jenis_pembayaran',
        'status_pembayaran',
        'status_pengerjaan',
        // 'keterangan_pembayaran',
        'pj_lapangan'
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

    public function kategoriCor()
    {
        return $this->belongsTo(KategoriCor::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pemesanan_id');
    }

    public function getHarga()
    {
        $total = $this->kategoriCor->harga * $this->volume_cor;
        return $total;
    }

    public function getStatusPembayaranLabelAttribute()
    {
        $statuses = [
            'angsur' => ['label' => 'Angsur', 'class' => 'badge-warning'],
            'unpaid' => ['label' => 'Belum Bayar', 'class' => 'badge-danger'],
            'paid' => ['label' => 'Lunas', 'class' => 'badge-success'],
        ];

        return $statuses[$this->status_pembayaran] ?? ['label' => 'Unknown', 'class' => 'badge-secondary'];
    }
}
