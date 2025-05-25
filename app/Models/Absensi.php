<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'kelas_id',
        'tanggal',
        'materi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function detailAbsensi(): HasMany
    {
        return $this->hasMany(DetailAbsensi::class);
    }

    public function getJumlahHadirAttribute()
    {
        return $this->detailAbsensi()->where('status', 'hadir')->count();
    }

    public function getJumlahSakitAttribute()
    {
        return $this->detailAbsensi()->where('status', 'sakit')->count();
    }

    public function getJumlahIzinAttribute()
    {
        return $this->detailAbsensi()->where('status', 'izin')->count();
    }

    public function getJumlahAlphaAttribute()
    {
        return $this->detailAbsensi()->where('status', 'alpha')->count();
    }
}
