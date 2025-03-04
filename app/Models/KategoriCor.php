<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriCor extends Model
{
    protected $table = 'kategori_cor';
    protected $fillable = ['nama_kategori', 'slug', 'harga', 'deskripsi'];
}
