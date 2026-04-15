<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

            // insert ke users
            $userId = DB::table('users')->insertGetId([
                'role' => 'siswa',
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber(),
                'photo' => 'default.png',
                'address' => $faker->address(), // sudah cukup panjang
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // insert ke students
            DB::table('students')->insert([
                'user_id' => $userId,
                'program_id' => null,
                'nis' => 'NIS' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        echo "SUDAH BERHASIL SEED";
    }
}