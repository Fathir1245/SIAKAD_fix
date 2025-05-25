<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Kelas Pemrograman Dasar
        $kelas1 = Kelas::create([
            'nama_kelas' => 'IF101-A',
            'mata_kuliah_id' => 1, // Pemrograman Dasar
            'dosen_id' => 2, // Dr. John Doe
            'tahun_ajaran_id' => 2, // 2023/2024 Genap
            'kapasitas' => 30
        ]);

        // Kelas Algoritma
        $kelas2 = Kelas::create([
            'nama_kelas' => 'IF102-A',
            'mata_kuliah_id' => 2, // Algoritma dan Struktur Data
            'dosen_id' => 3, // Dr. Jane Smith
            'tahun_ajaran_id' => 2, // 2023/2024 Genap
            'kapasitas' => 25
        ]);

        // Kelas Pemrograman Web
        $kelas3 = Kelas::create([
            'nama_kelas' => 'IF201-A',
            'mata_kuliah_id' => 3, // Pemrograman Web
            'dosen_id' => 2, // Dr. John Doe
            'tahun_ajaran_id' => 2, // 2023/2024 Genap
            'kapasitas' => 20
        ]);

        // Tambahkan mahasiswa ke kelas
        $kelas1->mahasiswa()->attach([4, 5, 6]); // Alice, Bob, Charlie

        $kelas2->mahasiswa()->attach([4, 5, 6]); // Alice, Bob, Charlie

        $kelas3->mahasiswa()->attach([4, 5, 6]); // Alice, Bob, Charlie

        // Set nilai untuk beberapa mahasiswa di kelas 1
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

        // Set nilai untuk kelas 2
        $kelas2->mahasiswa()->updateExistingPivot(4, [
            'nilai_tugas' => 88,
            'nilai_uts' => 85,
            'nilai_uas' => 92
        ]);

        $kelas2->mahasiswa()->updateExistingPivot(5, [
            'nilai_tugas' => 78,
            'nilai_uts' => 75,
            'nilai_uas' => 82
        ]);

        $kelas2->mahasiswa()->updateExistingPivot(6, [
            'nilai_tugas' => 92,
            'nilai_uts' => 88,
            'nilai_uas' => 95
        ]);

        // Hitung nilai akhir untuk kelas 2
        foreach ([4, 5, 6] as $mahasiswaId) {
            $kelas2->calculateNilaiAkhir($mahasiswaId);
        }
    }
}
