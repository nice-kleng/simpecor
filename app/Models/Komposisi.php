<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komposisi extends Model
{
    protected $table = 'komposisis';
    protected $fillable = ['kategori_cor_id', 'bahan_baku_id', 'jumlah'];

    public function kategoriCor()
    {
        return $this->belongsTo(KategoriCor::class);
    }

    public function bahanBaku()
    {
        return $this->belongsTo(Bahan::class);
    }
}
