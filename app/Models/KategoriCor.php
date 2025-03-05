<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriCor extends Model
{
    protected $table = 'kategori_cors';
    protected $fillable = ['nama_kategori', 'slug', 'harga', 'deskripsi'];
}
