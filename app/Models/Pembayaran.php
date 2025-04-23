<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'pemesanan_id',
        'bukti_pembayaran',
        'jumlah_pembayaran',
        'status',
        'keterangan',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => ['label' => 'Menunggu Konfirmasi', 'class' => 'badge-warning'],
            'valid' => ['label' => 'Valid', 'class' => 'badge-success'],
            'invalid' => ['label' => 'Invalid', 'class' => 'badge-danger'],
        ];

        return $statuses[$this->status] ?? ['label' => 'Unknown', 'class' => 'badge-secondary'];
    }
}
