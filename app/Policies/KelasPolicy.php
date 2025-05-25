<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    public function viewNilai(User $user, Kelas $kelas): bool
    {
        return $user->isDosen() && $user->id === $kelas->dosen_id;
    }

    public function updateNilai(User $user, Kelas $kelas): bool
    {
        return $user->isDosen() && $user->id === $kelas->dosen_id;
    }
} 