<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin (Bisa CRUD Buku & Anggota)
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Akun Siswa Contoh (Bisa Pinjam & Balik Buku)
        User::create([
            'name' => 'Siswa Contoh',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);
    }
}