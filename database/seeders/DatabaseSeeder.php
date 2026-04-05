<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Certificate;
use Illuminate\Support\Facades\Hash;
use App\Services\JWTECDSAService;
use App\Services\QRCodeService;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    }
}