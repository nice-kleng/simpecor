<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriCorSeeder extends Seeder
{
    public function run(): void
    {
        $kategori_cors = [
            [
                'nama_kategori' => 'Plat Cor',
                'slug' => Str::slug('Plat Cor'),
                'harga' => 85000,
                'deskripsi' => 'Plat cor beton dengan ketebalan standar'
            ],
            [
                'nama_kategori' => 'Tiang Cor',
                'slug' => Str::slug('Tiang Cor'),
                'harga' => 95000,
                'deskripsi' => 'Tiang cor beton untuk konstruksi'
            ],
            [
                'nama_kategori' => 'Gorong-gorong',
                'slug' => Str::slug('Gorong-gorong'),
                'harga' => 120000,
                'deskripsi' => 'Gorong-gorong beton untuk saluran air'
            ],
            [
                'nama_kategori' => 'Saluran Air',
                'slug' => Str::slug('Saluran Air'),
                'harga' => 75000,
                'deskripsi' => 'Saluran air beton precast'
            ],
            [
                'nama_kategori' => 'Paving Block',
                'slug' => Str::slug('Paving Block'),
                'harga' => 65000,
                'deskripsi' => 'Paving block beton untuk area parkir dan jalan'
            ]
        ];

        DB::table('kategori_cors')->insert($kategori_cors);
    }
}
