<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    protected $table = 'bahans';
    protected $fillable = ['nama_bahan', 'satuan', 'stok', 'batas_stok'];
    protected $casts = [
        'stok' => 'integer',
        'batas_stok' => 'integer',
    ];

    public function komposisi()
    {
        return $this->hasMany(Komposisi::class, 'bahan_baku_id');
    }

    public function barangmasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
