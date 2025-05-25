<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailAbsensi extends Model
{
    use HasFactory;

    protected $table = 'detail_absensi';

    protected $fillable = [
        'absensi_id',
        'mahasiswa_id',
        'status',
        'keterangan',
    ];

    public function absensi(): BelongsTo
    {
        return $this->belongsTo(Absensi::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
