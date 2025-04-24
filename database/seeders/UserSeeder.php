<?php

namespace Database\Seeders;

use App\Models\Mitra;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $mitra = User::create([
            'name' => 'Mitra',
            'email' => 'mitra@mitra.com',
            'password' => bcrypt('mitra'),
            'role' => 'mitra',
        ]);

        User::create([
            'name' => 'Bambang Priyatno',
            'email' => 'direktur@direktur.com',
            'password' => bcrypt('direktur'),
            'role' => 'direktur',
        ]);

        Mitra::create([
            'user_id' => $mitra->id,
            'nama_mitra' => 'Mitra Jaya',
            'nama_pemilik' => 'Jaya',
            'alamat' => 'Jl. Jaya No. 1',
            'email' => $mitra->email,
            'telp' => '09837483',
            'npwp' => '1234567890',
        ]);
    }
}
