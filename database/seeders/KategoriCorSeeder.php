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
                'nama_kategori' => 'BISJ K. 80',
                'slug' => Str::slug('BISJ K. 80'),
                'harga' => 650000,
                'deskripsi' => '12 pcs/m2, 80mm tebal'
            ],
            [
                'nama_kategori' => 'BISJ K. 100',
                'slug' => Str::slug('BISJ K. 100'),
                'harga' => 660000,
                'deskripsi' => '10 pcs/m2, 100mm tebal'
            ],
            [
                'nama_kategori' => 'BISJ K. 120',
                'slug' => Str::slug('BISJ K. 120',),
                'harga' => 675000,
                'deskripsi' => '8 pcs/m2, 120mm tebal'
            ],
            [
                'nama_kategori' => 'BISJ K. 150',
                'slug' => Str::slug('BISJ K. 150',),
                'harga' => 685000,
                'deskripsi' => '6 pcs/m2, 150mm tebal'
            ],
            [
                'nama_kategori' => 'BISJ K. 200',
                'slug' => Str::slug('BISJ K. 200',),
                'harga' => 720000,
                'deskripsi' => '5 pcs/m2, 200mm tebal',
            ]
        ];

        DB::table('kategori_cors')->insert($kategori_cors);
    }
}
