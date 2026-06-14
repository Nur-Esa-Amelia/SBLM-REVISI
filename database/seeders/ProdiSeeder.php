<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode_prodi' => 'TEKKOM', 'nama_prodi' => 'Teknik Komputer'],
            ['kode_prodi' => 'TEKSIP', 'nama_prodi' => 'Teknik Sipil'],
            ['kode_prodi' => 'TEKMES', 'nama_prodi' => 'Teknik Mesin'],
            ['kode_prodi' => 'BISDIG', 'nama_prodi' => 'Bisnis Digital'],
            ['kode_prodi' => 'ADMBIS', 'nama_prodi' => 'Administrasi Bisnis'],
        ];

        foreach ($data as $item) {
            Prodi::updateOrCreate(['kode_prodi' => $item['kode_prodi']], $item);
        }
    }
}
