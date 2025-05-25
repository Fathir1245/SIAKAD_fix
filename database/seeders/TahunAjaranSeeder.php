<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::create([
            'tahun_ajaran' => '2023/2024',
            'semester' => 'Ganjil',
            'aktif' => false
        ]);

        TahunAjaran::create([
            'tahun_ajaran' => '2023/2024',
            'semester' => 'Genap',
            'aktif' => true
        ]);

        TahunAjaran::create([
            'tahun_ajaran' => '2024/2025',
            'semester' => 'Ganjil',
            'aktif' => false
        ]);
    }
} 