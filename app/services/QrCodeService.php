<?php
namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(string $text, int $size = 300): array
    {
        $isProduction = app()->environment('production');
        $folder = $isProduction ? '/home/cery9751/public_html/v/qrcode' : public_path('v/qrcode'); 
        $filename = 'qrcode_' . uniqid() . '.svg'; 
        $filePath = $folder . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        QrCode::format('svg')
            ->size($size)
            ->errorCorrection('Q')
            ->generate($text, $filePath);

        return [
            'filename'   => $filename,
            'path'       => 'v/qrcode/' . $filename,
            'public_url' => asset('v/qrcode/' . $filename),
        ];
    }
}
