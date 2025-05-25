<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Dosen', 'slug' => 'dosen']);
        Role::create(['name' => 'Mahasiswa', 'slug' => 'mahasiswa']);
    }
} 