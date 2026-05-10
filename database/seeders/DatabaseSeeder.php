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

        $statuses = ['Draft', 'Di Terbitkan'];
        $programs = [
            'English Conversation Course',
            'General English Course',
            'Business English Course',
            'Academic English Course',
            'TOEFL Preparation Course',
            'IELTS Preparation Course',
            'English Grammar Course',
            'English Speaking Course',
            'English Writing Course',
            'English for Beginners Course'
        ];
        $levels   = ['Basic', 'Intermediate', 'Advanced'];

        $names = [
            'Andi Pratama', 'Budi Santoso', 'Siti Aisyah', 'Rizky Ramadhan', 'Dewi Lestari',
            'Ahmad Fauzi', 'Nadia Putri', 'Fajar Nugroho', 'Maya Sari', 'Ilham Maulana',
            'Dinda Amelia', 'Yoga Saputra', 'Putri Anggraini', 'Reza Kurniawan', 'Lina Wati',
            'Agus Setiawan', 'Salsa Febriani', 'Rafi Akbar', 'Nina Kartika', 'Eko Prasetyo',
            'Intan Permata', 'Bagas Wirawan', 'Tasya Nurhaliza', 'Gilang Pratama', 'Novi Handayani'
        ];
        for ($i = 0; $i < 10; $i++) {

            $status = $statuses[array_rand($statuses)];
            $now = Carbon::now();

            $certNumber = 'CERT-' . strtoupper(Str::random(6)) . '-' . $i;

            $message = $certNumber . '|Student ' . $i . '|Program';

            $signature = null;
            $qr = null;

            $cert = Certificate::create([
                'ids'                => "b8aadd39-62ff-4158-a447-c9582eacb31f",
                'username'           => $names[$i],
                'certificate_number' => $certNumber,
                'program_name'       => $programs[array_rand($programs)],
                'grade'              => collect(['A', 'B', 'C'])->random(),
                'level'              => $levels[array_rand($levels)],
                'status'             => $status,
                'publication_date'   => $status === 'Di Terbitkan' ? $now : null,
                'digital_signature'  => null,
                'file_path'          => null,
                'description'        => 'Demo certificate',
                'created_at'         => $now->copy()->subDays(rand(1, 30)),
            ]);
            if ($status === 'Di Terbitkan') {
                $text = (string) $cert->ids;
                $signature = $this->ecdsa->sign($text)->signature;
                $url = url('/scan?id=' . $text);
                $qr = $this->qrCodeService->generate($url);
                $cert->update([
                    'file_path' => $qr['path'],
                    'digital_signature' => $signature,
                    ]);
                    

                for ($v = 0; $v < 3; $v++) {

                    $result = collect([
                        VerificationResult::QR_INVALID,
                        VerificationResult::NOT_FOUND,
                        VerificationResult::VERIFY_FAILED,
                        VerificationResult::VALID,
                    ])->random();

                    $hasCertificate = in_array($result, [
                        VerificationResult::VERIFY_FAILED,
                        VerificationResult::VALID,
                    ]);

                    CertificateVerification::create([

                        // hanya ada jika sertifikat ditemukan
                        'certificate_id' => $hasCertificate
                            ? $cert->id
                            : null,

                        // random datetime 30 hari terakhir
                        'verified_at' => Carbon::createFromTimestamp(
                            rand(
                                now()->subDays(30)->timestamp,
                                now()->timestamp
                            )
                        ),

                        // random ip
                        'ip_address' => collect([
                            '192.168.1.' . rand(10, 200),
                            '172.16.0.' . rand(10, 200),
                            '10.0.0.' . rand(10, 200),
                        ])->random(),

                        // random lokasi
                        'address' => collect([
                            'Indonesia, Jawa Barat, Bandung',
                            'Indonesia, DKI Jakarta, Jakarta Selatan',
                            'Indonesia, Jawa Timur, Surabaya',
                            'Indonesia, Jawa Tengah, Semarang',
                        ])->random(),

                        // random device
                        'device_info' => collect([
                            'Chrome - Windows',
                            'Safari - iPhone',
                            'Firefox - Android',
                            'Edge - Windows',
                            'Chrome - Android',
                            'Safari - MacOS',
                        ])->random(),

                        // hasil verifikasi
                        'result' => $result,
                    ]);
                }
            }
        }
        echo "SUDAH BERHASIL SEED";
    }
}