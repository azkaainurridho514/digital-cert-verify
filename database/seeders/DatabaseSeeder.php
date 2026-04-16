<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Student;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
        $this->call([
            ProgramSeeder::class,
        ]);

        $faker = \Faker\Factory::create('id_ID');

        
        for ($i = 1; $i <= 10; $i++) {

            $user = User::create([
                'role' => 'siswa',
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber(),
                'photo' => 'default.png',
                'address' => $faker->address(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'nis' => 'NIS' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => 0,
            ]);
        }
        echo "SUDAH BERHASIL SEED";
    }
}