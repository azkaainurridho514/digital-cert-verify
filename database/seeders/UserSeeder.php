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
        User::updateOrCreate(
            ['email' => 'admin@olc.id'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '081234567890',
                'photo'    => 'default.png',
                'address'  => 'Jl. Admin No. 1, Jakarta',
            ]
        );

        // Siswa
        $siswa = User::updateOrCreate(
            ['email' => 'siswa@olc.id'],
            [
                'name'     => 'Ahmad Siswa',
                'password' => Hash::make('password'),
                'role'     => 'siswa',
                'phone'    => '081234567891',
                'photo'    => 'default.png',
                'address'  => 'Jl. Siswa No. 2, Bandung',
            ]
        );

    }
}