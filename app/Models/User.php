<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'nim_nip',
        'alamat',
        'no_hp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function kelasDosen(): HasMany
    {
        return $this->hasMany(Kelas::class, 'dosen_id');
    }

    public function kelasMahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa', 'mahasiswa_id', 'kelas_id')
            ->withPivot(['nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'grade'])
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role->slug === 'admin';
    }

    public function isDosen(): bool
    {
        return $this->role->slug === 'dosen';
    }

    public function isMahasiswa(): bool
    {
        return $this->role->slug === 'mahasiswa';
    }
}
