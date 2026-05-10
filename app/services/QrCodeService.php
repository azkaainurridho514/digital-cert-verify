<?php
namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(string $text, int $size = 300): array
    {
        // $folder   = public_path('v/qrcode'); // develpment
        $folder = '/home/cery9751/public_html/v/qrcode'; // production
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
