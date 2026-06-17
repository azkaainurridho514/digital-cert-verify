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

        $certificates = [ ['username' => 'Qyura Elsa Syafitri', 'certificate_number' => 'XXIX /10-04-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Intermediate'], ['username' => 'Tennisa Ummi Hamidah', 'certificate_number' => 'XXXII /12-04-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Beginner'], ['username' => 'Yuna Maatcha Maulidia', 'certificate_number' => 'XXXVII /14-04-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Intermediate'], ['username' => 'Gheitsa Zalfa Syahida', 'certificate_number' => 'XLI /15-04-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Intermediate'], ['username' => 'Virda Nur Fatwa', 'certificate_number' => 'XLV /17-04-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Beginner'], ['username' => 'Nadine Arthayuka W', 'certificate_number' => 'XLVIII /18-04-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Intermediate'], ['username' => 'Auliya Nur Azizah', 'certificate_number' => 'LII /20-04-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Intermediate'], ['username' => 'Aldiansyah Pradita', 'certificate_number' => 'LVI /22-04-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Beginner'], ['username' => 'Zidan Khoirul Imam', 'certificate_number' => 'LX /25-04-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Intermediate'], ['username' => 'Rofifah Khaerunnisa', 'certificate_number' => 'LXIV /28-04-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Intermediate'], ['username' => 'Dhira Nada Azahra', 'certificate_number' => 'LXVIII /02-05-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Beginner'], ['username' => 'Dandi Ramadhani', 'certificate_number' => 'LXXII /05-05-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Intermediate'], ['username' => 'Sheila Merlin P.', 'certificate_number' => 'LXXVI /08-05-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Intermediate'], ['username' => 'Intan Azzahra', 'certificate_number' => 'LXXX /12-05-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Beginner'], ['username' => 'Wiana Eiyrun Nisa', 'certificate_number' => 'LXXXIV /16-05-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Intermediate'], ['username' => 'Zahwa Alya Vanessa', 'certificate_number' => 'LXXXVIII /22-05-2026', 'program_name' => 'Speaking', 'grade' => 'B', 'level' => 'Intermediate'], ['username' => 'Rakha Rasyid Arrayan', 'certificate_number' => 'XCII /28-05-2026', 'program_name' => 'Speaking', 'grade' => 'A', 'level' => 'Beginner'], ]; $times = [ '08:15:22', '09:23:11', '10:44:52', '11:12:34', '13:05:18', '14:27:41', '15:18:55', '16:02:13', '08:47:29', '09:36:07', '10:11:45', '11:59:32', '13:24:16', '14:41:53', '15:08:27', '16:15:48', '16:52:10', ]; 
        foreach ($certificates as $index => $certificate) { 
            $datePart = trim(explode('/', $certificate['certificate_number'])[1]); 
            $createdAt = Carbon::createFromFormat( 'd-m-Y H:i:s', $datePart . ' ' . $times[$index] ); 
            Certificate::create(
                [ ...$certificate, 
                'created_at' => $createdAt, 
                'status' => 'Draft', 
                'description' => null, 
            ]); 
        } $this->command->info('17 certificates seeded successfully.');
        echo "SUDAH BERHASIL SEED";
    }
}