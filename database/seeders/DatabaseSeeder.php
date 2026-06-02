<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Certificate;
use App\Models\CertificateVerification;
use Carbon\Carbon;
// use App\Services\EcdsaServiceV1;
use App\Services\EcdsaService;
use App\Services\QrCodeService;
use App\Constants\VerificationResult;

class DatabaseSeeder extends Seeder
{
    public function __construct(
        // private EcdsaServiceV1 $ecdsa,
        private EcdsaService $ecdsa,
        private QrCodeService $qrCodeService
    ) {}

    public function run(): void
    {
        User::create([
            'name' => "Admin OLC",
            'email' => "ourlearningcenterkuningan@gmail.com",
            'password' => Hash::make('password'),
        ]);
        echo "SUDAH BERHASIL SEED";
    }
}