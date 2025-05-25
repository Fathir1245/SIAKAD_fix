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
}
