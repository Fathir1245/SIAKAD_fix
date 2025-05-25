<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Kelas Pemrograman Dasar
        Kelas::create([
            'nama_kelas' => 'IF101-A',
            'mata_kuliah_id' => 1, // Pemrograman Dasar
            'dosen_id' => 2, // Dr. John Doe
            'tahun_ajaran_id' => 2 // 2023/2024 Genap
        ]);

        // Kelas Algoritma
        Kelas::create([
            'nama_kelas' => 'IF102-A',
            'mata_kuliah_id' => 2, // Algoritma dan Struktur Data
            'dosen_id' => 3, // Dr. Jane Smith
            'tahun_ajaran_id' => 2 // 2023/2024 Genap
        ]);

        // Kelas Pemrograman Web
        Kelas::create([
            'nama_kelas' => 'IF201-A',
            'mata_kuliah_id' => 3, // Pemrograman Web
            'dosen_id' => 2, // Dr. John Doe
            'tahun_ajaran_id' => 2 // 2023/2024 Genap
        ]);

        // Tambahkan mahasiswa ke kelas
        $kelas1 = Kelas::find(1); // IF101-A
        $kelas1->mahasiswa()->attach([4, 5, 6]); // Alice, Bob, Charlie

        $kelas2 = Kelas::find(2); // IF102-A
        $kelas2->mahasiswa()->attach([4, 5, 6]); // Alice, Bob, Charlie

        $kelas3 = Kelas::find(3); // IF201-A
        $kelas3->mahasiswa()->attach([4, 5, 6]); // Alice, Bob, Charlie

        // Set nilai untuk beberapa mahasiswa
        $kelas1->mahasiswa()->updateExistingPivot(4, [
            'nilai_tugas' => 85,
            'nilai_uts' => 80,
            'nilai_uas' => 90
        ]);

        $kelas1->mahasiswa()->updateExistingPivot(5, [
            'nilai_tugas' => 75,
            'nilai_uts' => 70,
            'nilai_uas' => 80
        ]);

        $kelas1->mahasiswa()->updateExistingPivot(6, [
            'nilai_tugas' => 90,
            'nilai_uts' => 85,
            'nilai_uas' => 95
        ]);

        // Hitung nilai akhir untuk semua mahasiswa di kelas 1
        foreach ([4, 5, 6] as $mahasiswaId) {
            $kelas1->calculateNilaiAkhir($mahasiswaId);
        }
    }
} 