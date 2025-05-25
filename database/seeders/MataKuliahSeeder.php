<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        MataKuliah::create([
            'kode_mk' => 'IF101',
            'nama_mk' => 'Pemrograman Dasar',
            'sks' => 3,
            'semester' => 1
        ]);

        MataKuliah::create([
            'kode_mk' => 'IF102',
            'nama_mk' => 'Algoritma dan Struktur Data',
            'sks' => 4,
            'semester' => 2
        ]);

        MataKuliah::create([
            'kode_mk' => 'IF201',
            'nama_mk' => 'Pemrograman Web',
            'sks' => 3,
            'semester' => 3
        ]);

        MataKuliah::create([
            'kode_mk' => 'IF202',
            'nama_mk' => 'Basis Data',
            'sks' => 3,
            'semester' => 3
        ]);

        MataKuliah::create([
            'kode_mk' => 'IF301',
            'nama_mk' => 'Pemrograman Mobile',
            'sks' => 3,
            'semester' => 5
        ]);
    }
} 