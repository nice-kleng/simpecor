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
                'stok' => 1000
            ],
            [
                'nama_bahan' => 'Besi Scrap',
                'satuan' => 'kg',
                'stok' => 500
            ],
            [
                'nama_bahan' => 'Aluminium Ingot',
                'satuan' => 'kg',
                'stok' => 300
            ],
            [
                'nama_bahan' => 'Bentonit',
                'satuan' => 'kg',
                'stok' => 200
            ],
            [
                'nama_bahan' => 'Grafit',
                'satuan' => 'kg',
                'stok' => 150
            ],
        ];

        foreach ($bahans as $bahan) {
            Bahan::create($bahan);
        }
    }
}
