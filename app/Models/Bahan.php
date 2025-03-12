<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    protected $table = 'bahans';
    protected $fillable = ['nama_bahan', 'satuan', 'stok'];

    public function komposisi()
    {
        return $this->hasMany(Komposisi::class);
    }

    public function barangmasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
