<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'mata_kuliah_id',
        'dosen_id',
        'tahun_ajaran_id',
        'kapasitas',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'kelas_id', 'mahasiswa_id')
            ->withPivot(['nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'grade'])
            ->withTimestamps();
    }

    /**
     * Calculate nilai akhir for a specific mahasiswa
     * Formula: 30% Tugas + 30% UTS + 40% UAS
     */
    public function calculateNilaiAkhir($mahasiswaId)
    {
        $pivot = $this->mahasiswa()->where('mahasiswa_id', $mahasiswaId)->first()?->pivot;
        
        if (!$pivot) {
            return false;
        }

        $nilaiTugas = $pivot->nilai_tugas ?? 0;
        $nilaiUts = $pivot->nilai_uts ?? 0;
        $nilaiUas = $pivot->nilai_uas ?? 0;

        // Calculate nilai akhir (30% tugas, 30% UTS, 40% UAS)
        $nilaiAkhir = ($nilaiTugas * 0.3) + ($nilaiUts * 0.3) + ($nilaiUas * 0.4);
        
        // Determine grade
        $grade = $this->determineGrade($nilaiAkhir);

        // Update pivot table
        $this->mahasiswa()->updateExistingPivot($mahasiswaId, [
            'nilai_akhir' => round($nilaiAkhir, 2),
            'grade' => $grade
        ]);

        return true;
    }

    /**
     * Determine grade based on nilai akhir
     */
    private function determineGrade($nilaiAkhir)
    {
        if ($nilaiAkhir >= 85) return 'A';
        if ($nilaiAkhir >= 70) return 'B';
        if ($nilaiAkhir >= 60) return 'C';
        if ($nilaiAkhir >= 50) return 'D';
        return 'E';
    }

    /**
     * Get grade point for GPA calculation
     */
    public static function getGradePoint($grade)
    {
        return match($grade) {
            'A' => 4.0,
            'B' => 3.0,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
            default => 0.0
        };
    }
}
