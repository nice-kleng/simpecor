<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'mitras';
    protected $fillable = ['user_id', 'nama_mitra', 'alamat', 'email', 'telp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
