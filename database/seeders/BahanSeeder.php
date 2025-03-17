<?php

namespace Database\Seeders;

use App\Models\Bahan;
use Illuminate\Database\Seeder;

class BahanSeeder extends Seeder
{
    public function run()
    {
        $bahans = [
            [
                'nama_bahan' => 'Pasir Silika',
                'satuan' => 'kg',
                'stok' => 500000
            ],
            [
                'nama_bahan' => 'Air',
                'satuan' => 'liter',
                'stok' => 100000
            ],
            [
                'nama_bahan' => 'Semen',
                'satuan' => 'kg',
                'stok' => 100000
            ],
            [
                'nama_bahan' => 'Kerikil',
                'satuan' => 'kg',
                'stok' => 100000
            ],
        ];

        foreach ($bahans as $bahan) {
            Bahan::create($bahan);
        }
    }
}
