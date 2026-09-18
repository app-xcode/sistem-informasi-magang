<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@magang.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => $admin->id,
            'nama' => 'Administrator',
            'no_hp' => '081234567890',
        ]);

        // MAHASISWA
        $mahasiswa = User::create([
            'name' => 'Mahasiswa Demo',
            'email' => 'mahasiswa@magang.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $mahasiswa->id,
            'nim' => '20260001',
            'nama' => 'Mahasiswa Demo',
            'program_studi' => 'Teknik Informatika',
            'no_hp' => '081234567891',
            'alamat' => 'Kupang',
        ]);

        // DOSEN
        $dosen = User::create([
            'name' => 'Dosen Demo',
            'email' => 'dosen@magang.test',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $dosen->id,
            'nidn' => '1234567890',
            'nama' => 'Dosen Demo',
            'no_hp' => '081234567892',
            'alamat' => 'Kupang',
        ]);
    }
}