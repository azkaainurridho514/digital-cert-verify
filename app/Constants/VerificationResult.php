<?php
namespace App\Constants;

class VerificationResult
{
    const QR_INVALID = 1;
    const NOT_FOUND = 2;
    const VERIFY_FAILED = 3;
    const VALID = 4;

    public static function message($code)
    {
        return match ($code) {
            self::QR_INVALID => 'QR code tidak valid',
            self::NOT_FOUND => 'Sertifikat tidak ditemukan',
            self::VERIFY_FAILED => 'Verifikasi gagal',
            self::VALID => 'Verifikasi Berhasil',
            default => '-',
        };
    }
}