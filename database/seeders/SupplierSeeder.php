<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'nama_supplier' => 'PT Metalindo Jaya',
                'alamat' => 'Jl. Industri Raya No. 45, Surabaya',
                'email' => 'metalindo@example.com',
                'telp' => '031-5558899'
            ],
            [
                'nama_supplier' => 'CV Logam Abadi',
                'alamat' => 'Jl. Raya Pengecoran 123, Sidoarjo',
                'email' => 'logamabadi@example.com',
                'telp' => '031-7773322'
            ],
            [
                'nama_supplier' => 'UD Bahan Cor Makmur',
                'alamat' => 'Jl. Industri Timur 78, Gresik',
                'email' => 'cormakmur@example.com',
                'telp' => '031-8881234'
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
