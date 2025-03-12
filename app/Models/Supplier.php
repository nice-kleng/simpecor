<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = ['nama_supplier', 'alamat', 'email', 'telp'];

    public function barangmasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
