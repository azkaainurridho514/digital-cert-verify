<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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
        Student::updateOrCreate(
            ['user_id' => $siswa->id],
            [
                'nis'    => '123456789',
                'status' => 0,
            ]
        );

    }
}