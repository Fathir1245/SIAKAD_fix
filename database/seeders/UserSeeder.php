<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // Admin
            'nim_nip' => '198501012010011001',
            'alamat' => 'Jl. Admin No. 1',
            'no_hp' => '081234567890'
        ]);

        // Dosen
        User::create([
            'name' => 'Dr. John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // Dosen
            'nim_nip' => '198601012010011002',
            'alamat' => 'Jl. Dosen No. 1',
            'no_hp' => '081234567891'
        ]);

        User::create([
            'name' => 'Dr. Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // Dosen
            'nim_nip' => '198701012010011003',
            'alamat' => 'Jl. Dosen No. 2',
            'no_hp' => '081234567892'
        ]);

        // Mahasiswa
        User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // Mahasiswa
            'nim_nip' => '2021001',
            'alamat' => 'Jl. Mahasiswa No. 1',
            'no_hp' => '081234567893'
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // Mahasiswa
            'nim_nip' => '2021002',
            'alamat' => 'Jl. Mahasiswa No. 2',
            'no_hp' => '081234567894'
        ]);

        User::create([
            'name' => 'Charlie Brown',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // Mahasiswa
            'nim_nip' => '2021003',
            'alamat' => 'Jl. Mahasiswa No. 3',
            'no_hp' => '081234567895'
        ]);
    }
} 