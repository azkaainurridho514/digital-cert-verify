<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\EcdsaService;
use App\Services\QrCodeService;
use App\Models\CertificateSignature;
use App\Models\CertificateTemplate;
use TCPDF;

class SertifikatController extends Controller
{
    public function __construct(
        private EcdsaService $ecdsa,
        private QrCodeService $qrCodeService
    ) {}

    public function data(Request $request)
    {
        $search  = $request->search;
        $bulan   = $request->bulan;
        $tahun   = $request->tahun;
        $perPage = (int) $request->get('per_page', 10);

        $query = Certificate::query()
            ->when($search, function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                ->orWhere('program_name', 'like', "%$search%");
            })
            ->when($bulan, fn($q) => $q->whereMonth('created_at', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
            ->orderByDesc('created_at');

        $paginator = $query->paginate($perPage);

        $data = $paginator->map(fn($cert, $i) => [
            'no'                 => ($paginator->currentPage() - 1) * $paginator->perPage() + $i + 1,
            'id'                 => $cert->id,
            'username'           => $cert->username,
            'certificate_number' => $cert->certificate_number,
            'program_name'       => $cert->program_name ?? '-',
            'grade'              => $cert->grade ?? '-',
            'file_path'          => $cert->file_path ? asset($cert->file_path) : "",
            'description'        => $cert->description ?? '-',
            'publication_date' => $cert->publication_date 
            ? \Carbon\Carbon::parse($cert->publication_date)->translatedFormat('d F Y') 
            : null,
            'level'             => $cert->level,
            'status'             => $cert->status,
        ]);

        return response()->json([
            'data' => $data,
            'meta' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'username'           => 'required|string',
            'program_name'       => 'nullable|string',
            'certificate_number' => 'nullable|string|unique:certificates,certificate_number',
            'grade'              => 'nullable|string|max:10',
            'level'              => 'nullable|string|max:50',
            'status'             => ['required', Rule::in(['Draft', 'Di Terbitkan'])],
            'description'        => 'nullable|string',
        ]);

        $data = [
            'username'           => $request->username,
            'program_name'       => $request->program_name,
            'certificate_number' => $request->certificate_number,
            'grade'              => $request->grade,
            'level'              => $request->level,
            'description'        => $request->description,
            'status'             => $request->status,
            'created_at'         => now(),
        ];

        $cert = Certificate::create($data);

        if ($request->status === 'Di Terbitkan') {
            $now = now();
            // $message = implode('|', [
            //     $request->certificate_number,
            //     $request->username,
            //     $request->program_name,
            //     $request->grade,
            //     $now->format('Y-m-d H:i:s'),
            // ]);
            $text = (string) $cert->id;
            $signature = $this->ecdsa->sign($text);
            $url = url('/scan?id=' . $text);
            $qr = $this->qrCodeService->generate($url);
            $cert->update([
                'file_path' => $qr['path'],
                'digital_signature' => $signature->signature,
                'publication_date'  => $now,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil dibuat.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $cert = Certificate::findOrFail($id);
        if ($cert->status === 'Di Terbitkan') {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat sudah diterbitkan dan tidak bisa diubah.'
            ], 422);
        }

        $request->validate([
            'username'           => 'required|string',
            'program_name'       => 'nullable|string',
            'certificate_number' => 'nullable|string|unique:certificates,certificate_number,' . $id,
            'grade'              => 'nullable|string|max:10',
            'level'              => 'nullable|string|max:50',
            'status'             => ['required', Rule::in(['Draft', 'Di Terbitkan'])],
            'description'        => 'nullable|string',
        ]);

        $dataUpdate = [
            'username'           => $request->username,
            'program_name'       => $request->program_name,
            'certificate_number' => $request->certificate_number,
            'grade'              => $request->grade,
            'level'              => $request->level,
            'description'        => $request->description,
            'status'             => $request->status,
        ];

        if ($request->status === 'Di Terbitkan') {

            $now = now();

            // $message = implode('|', [
            //     $request->certificate_number,
            //     $request->username,
            //     $request->program_name,
            //     $request->grade,
            //     $now->format('Y-m-d H:i:s'),
            // ]);
            $text = (string) $cert->id;
            $url = url('/scan?id=' . $text);
            $signature = $this->ecdsa->sign($text);
            $qr = $this->qrCodeService->generate($text);

            $dataUpdate['file_path'] = $qr['path'];
            $dataUpdate['digital_signature'] = $signature->signature;
            $dataUpdate['publication_date']  = $now;
        }

        $cert->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil diperbarui.'
        ]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'string|exists:certificates,id',
            'status' => ['required', Rule::in(['Draft', 'Di Terbitkan'])],
        ]);

        DB::transaction(function () use ($request) {
            $certificates = Certificate::whereIn('id', $request->ids)->get();

            foreach ($certificates as $cert) {
                if ($cert->status === 'Di Terbitkan') continue;

                $dataUpdate = ['status' => $request->status];

                if ($request->status === 'Di Terbitkan') {
                    $now     = now();
                    // $message = implode('|', [
                    //     $cert->certificate_number,
                    //     $cert->username,
                    //     $cert->program_name,
                    //     $cert->grade,
                    //     $now->format('Y-m-d H:i:s'),
                    // ]);

                    $text = (string) $cert->id;
                    $signature = $this->ecdsa->sign($text);
                    $url = url('/scan?id=' . $text);

                    $qr = $this->qrCodeService->generate($url);

                    $dataUpdate['file_path'] = $qr['path'];
                    $dataUpdate['digital_signature'] = $signature->signature;
                    $dataUpdate['publication_date']  = $now;
                }

                $cert->update($dataUpdate);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Bulk update status berhasil.',
        ]);
    }


    public function show(string $id)
    {
        $cert = Certificate::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $cert
        ]);
    }

    
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'string|exists:certificates,id',
        ]);

        DB::transaction(function () use ($request) {
            Certificate::whereIn('id', $request->ids)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Bulk hapus berhasil.',
        ]);
    }

    public function destroy(string $id)
    {
        $cert = Certificate::findOrFail($id);
        if ($cert->file_path) {
            
            $isProduction = app()->environment('production');
            $templatePath = $isProduction ? '/home/cery9751/public_html/v/qrcode' : public_path($cert->file_path); 

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $cert->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat dihapus.'
        ]);
    }



    // v1
    //  public function print($id)
    // {
    //     // ── 1. Ambil data sertifikat ──────────────────────────────────────
    //     $cert = Certificate::find($id);
    //     if (!$cert) {
    //         return response()->json(['success' => false, 'message' => 'Sertifikat tidak ditemukan.'], 404);
    //     }

    //     // ── 2. Ambil template terbaru ─────────────────────────────────────
    //     $template = CertificateTemplate::latest()->first();
    //     if (!$template) {
    //         return response()->json(['success' => false, 'message' => 'Template sertifikat belum dibuat. Silakan buat template terlebih dahulu.'], 404);
    //     }

    //     // ── 3. Resolve path file template ─────────────────────────────────
    //     // DB menyimpan: "cert-templates/template_xxx.png"
    //     $templatePath = public_path($template->path);
    //     if (!file_exists($templatePath)) {
    //         return response()->json(['success' => false, 'message' => 'File template tidak ditemukan di server.'], 404);
    //     }

    //     // ── 4. Resolve path QR Code ───────────────────────────────────────
    //     // file_path di DB sudah berisi path relatif seperti "v/qrcode/qrcode_xxx.svg"
    //     $qrPath = null;
    //     if ($cert->file_path) {
    //         $qrPath = public_path($cert->file_path);
    //         if (!file_exists($qrPath)) {
    //             $qrPath = null; // QR tidak ada, lanjut tanpa QR
    //         }
    //     }

    //     // ── 5. Generate PDF ───────────────────────────────────────────────
    //     $pdf = $this->generateCertificatePdf($cert, $template, $templatePath, $qrPath);

    //     // ── 6. Output sebagai download ────────────────────────────────────
    //     $filename = 'certificate-' . ($cert->certificate_number ?? $cert->id) . '.pdf';

    //     return response()->streamDownload(function () use ($pdf, $filename) {
    //         echo $pdf->Output($filename, 'S');
    //     }, $filename, [
    //         'Content-Type' => 'application/pdf',
    //     ]);
    // }

    public function print($id)
    {
        // ── 1. Ambil data sertifikat ──────────────────────────────────────
        $cert = Certificate::find($id);
        if (!$cert) {
            return response()->json(['success' => false, 'message' => 'Sertifikat tidak ditemukan.'], 404);
        }

        // ── 2. Ambil template terbaru ─────────────────────────────────────
        $template = CertificateTemplate::latest()->first();
        if (!$template) {
            return response()->json(['success' => false, 'message' => 'Template sertifikat belum dibuat. Silakan buat template terlebih dahulu.'], 404);
        }
        

        // ── 3. Resolve path file template ─────────────────────────────────
        $isProduction = app()->environment('production');
        $templatePath = $isProduction ? '/home/cery9751/public_html/'.$template->path : public_path($template->path); 
        if (!file_exists($templatePath)) {
            return response()->json(['success' => false, 'message' => 'File template tidak ditemukan di server.'], 404);
        }

        // ── 4. Resolve path QR Code ───────────────────────────────────────
        $qrPath = null;
        if ($cert->file_path) {
            $isProduction = app()->environment('production');
            $qrPath = $isProduction ? '/home/cery9751/public_html/v/qrcode' : public_path($cert->file_path); 
            if (!file_exists($qrPath)) {
                $qrPath = null;
            }
        }

        // ── 4b. Konversi SVG → PNG jika QR Code berformat SVG ────────────
        if ($qrPath && str_ends_with(strtolower($qrPath), '.svg')) {
            $tmpPng = sys_get_temp_dir() . '/qr_' . $cert->id . '.png';

            // Baca dimensi asli SVG
            $svgContent = file_get_contents($qrPath);
            preg_match('/width=["\']?(\d+)["\']?/i', $svgContent, $wm);
            preg_match('/height=["\']?(\d+)["\']?/i', $svgContent, $hm);
            $svgW = !empty($wm) ? (int)$wm[1] : 300;
            $svgH = !empty($hm) ? (int)$hm[1] : 300;

            // Render 2x ukuran asli SVG
            $renderW = $svgW * 2;
            $renderH = $svgH * 2;

            $svgImage    = \SVG\SVG::fromFile($qrPath);
            $rasterImage = $svgImage->toRasterImage($renderW, $renderH);

            $transparent = imagecreatetruecolor($renderW, $renderH);
            imagealphablending($transparent, false);
            imagesavealpha($transparent, true);
            $clear = imagecolorallocatealpha($transparent, 0, 0, 0, 127);
            imagefill($transparent, 0, 0, $clear);

            for ($px = 0; $px < $renderW; $px++) {
                for ($py = 0; $py < $renderH; $py++) {
                    $color = imagecolorat($rasterImage, $px, $py);
                    $r = ($color >> 16) & 0xFF;
                    $g = ($color >> 8)  & 0xFF;
                    $b =  $color        & 0xFF;
                    if ($r > 240 && $g > 240 && $b > 240) continue;
                    imagesetpixel($transparent, $px, $py, imagecolorallocate($transparent, $r, $g, $b));
                }
            }

            imagepng($transparent, $tmpPng);
            imagedestroy($rasterImage);
            imagedestroy($transparent);

            $qrPath = file_exists($tmpPng) ? $tmpPng : null;
        }


        // ── 5. Generate PDF ───────────────────────────────────────────────
        $pdf = $this->generateCertificatePdf($cert, $template, $templatePath, $qrPath);

        // ── 6. Output sebagai download ────────────────────────────────────
        $filename = 'certificate-' . ($cert->certificate_number ?? $cert->id) . '.pdf';

        return response()->streamDownload(function () use ($pdf, $filename) {
            echo $pdf->Output($filename, 'S');
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Build TCPDF dengan template sebagai background
     * dan tempel semua field sesuai koordinat dari database.
     */
    private function generateCertificatePdf(
    Certificate         $cert,
    CertificateTemplate $template,
    string              $templatePath,
    ?string             $qrPath
    ): TCPDF {
        $natW = (int) $template->width_template;
        $natH = (int) $template->height_template;

        // ── Gunakan A4 Landscape sebagai ukuran PDF ───────────────────
        $pdfW = 297; // mm
        $pdfH = 210; // mm

        // ── Scale factor: template pixel → PDF mm ─────────────────────
        $scaleX = $pdfW / $natW;
        $scaleY = $pdfH / $natH;

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->AddPage();

        // ── Background ────────────────────────────────────────────────
        $pdf->Image($templatePath, 0, 0, $pdfW, $pdfH, '', '', '', false, 300, '', false, false, 0);

        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        // ── Helper scale px → mm ──────────────────────────────────────
        $sx = fn(int $px) => round($px * $scaleX, 4);
        $sy = fn(int $px) => round($px * $scaleY, 4);

        // 1. Nama
        $this->putTextMm($pdf,
            $cert->username ?? '',
            $sx($template->x_position_name), $sy($template->y_position_name),
            $sx($template->width_position_name), $sy($template->height_position_name),
            35,
            [31, 41, 55],  
            true 
        );

        // 2. Nomor Sertifikat
        $this->putTextMm($pdf,
            $cert->certificate_number ?? '',
            $sx($template->x_position_cert_number), $sy($template->y_position_cert_number),
            $sx($template->width_cert_number), $sy($template->height_cert_number),
            18,
            [31, 41, 55],
            false
        );

        // 3. Nilai
        $this->putTextMm($pdf,
            $cert->grade ?? '',
            $sx($template->x_position_grade), $sy($template->y_position_grade),
            $sx($template->width_grade), $sy($template->height_grade),
            25,
            [255, 255, 255],
            false

        );

        // 4. Program
        $this->putTextMm($pdf,
            $cert->program_name ?? '',
            $sx($template->x_position_program_name), $sy($template->y_position_program_name),
            $sx($template->width_program_name), $sy($template->height_program_name),
            18,
            [31, 41, 55],
            false,
        );

        // 5. Tanggal Terbit
        $publishDate = $cert->publication_date
            ? \Carbon\Carbon::parse($cert->publication_date)->format('d F Y')
            : '';
        $this->putTextMm($pdf,
            $publishDate,
            $sx($template->x_position_publish_date), $sy($template->y_position_publish_date),
            $sx($template->width_publish_date), $sy($template->height_publish_date),
            15,
            [31, 41, 55],
            false
        );

        // 6. QR Code
        if ($qrPath) {
            $qrSize = 250; // mm - hardcode 40mm, sesuaikan kalau perlu

            $pdf->Image(
                $qrPath,
                $sx($template->x_position_qr_code), // posisi x tetap dari database
                $sy($template->y_position_qr_code), // posisi y tetap dari database
                $qrSize,
                $qrSize,
                '', '', '', false, 300, '', false, false, 0
            );
        }

        return $pdf;
    }

    /**
     * Versi putText yang menerima mm langsung (bukan px).
     */
    private function putTextMm(
        TCPDF $pdf,
        string $text,
        float $x, float $y,
        float $w, float $h,
        ?float $fontSize = null,
        array $color = [0, 0, 0],   // RGB, default hitam
        bool $bold = false
    ): void {
        if (trim($text) === '') return;

        $style = $bold ? 'B' : '';
        $pdf->SetFont('helvetica', $style, 12);

        $size = $fontSize ?? $this->autoFontSizeMm($pdf, $text, $w, $h);
        $pdf->SetFontSize($size);
        $pdf->SetTextColor($color[0], $color[1], $color[2]);
        $pdf->SetXY($x, $y);
        $pdf->MultiCell($w, $h, $text, 0, 'C', false, 1, $x, $y, true, 0, false, true, $h, 'M');

        // Reset warna ke hitam setelah render
        $pdf->SetTextColor(0, 0, 0);
    }

    /**
     * autoFontSize versi mm.
     */
    private function autoFontSizeMm(TCPDF $pdf, string $text, float $wMm, float $hMm): float
    {
        $size = 14;
        $min  = 6;
        while ($size > $min) {
            $pdf->SetFontSize($size);
            if ($pdf->GetStringWidth($text) <= $wMm && $size <= ($hMm * 2.83)) break;
            $size -= 0.5;
        }
        return max($size, $min);
    }

    /**
     * Tempel teks pada koordinat & ukuran (pixel) → dikonversi ke mm.
     * Teks otomatis di-center secara vertikal di dalam area yang ditentukan.
     */
    private function putText(
        TCPDF $pdf,
        string $text,
        int $x, int $y,
        int $w, int $h
    ): void {
        if (trim($text) === '') return;

        $xMm = $this->pxToMm($x);
        $yMm = $this->pxToMm($y);
        $wMm = $this->pxToMm($w);
        $hMm = $this->pxToMm($h);

        // Auto-size font agar muat dalam kotak
        $fontSize = $this->autoFontSize($pdf, $text, $wMm, $hMm);
        $pdf->SetFontSize($fontSize);

        $pdf->SetXY($xMm, $yMm);
        $pdf->MultiCell(
            $wMm,   // lebar
            $hMm,   // tinggi baris
            $text,
            0,      // border
            'C',    // align center
            false,  // fill
            1,      // next line
            $xMm,
            $yMm,
            true,
            0,
            false,
            true,
            $hMm,   // max height
            'M'     // vertical align middle
        );
    }

    /**
     * Tempel gambar (QR Code, dsb.) pada koordinat & ukuran (pixel) → mm.
     */
    private function putImage(
        TCPDF $pdf,
        string $path,
        int $x, int $y,
        int $w, int $h
    ): void {
        $pdf->Image(
            $path,
            $this->pxToMm($x),
            $this->pxToMm($y),
            $this->pxToMm($w),
            $this->pxToMm($h),
            '', '', '', false, 300, '', false, false, 0
        );
    }

    /**
     * Konversi pixel ke mm (asumsi 96 DPI).
     * 1 inch = 25.4 mm, 1 inch = 96 px → 1 px = 25.4/96 mm
     */
    private function pxToMm(int $px): float
    {
        return round($px * 25.4 / 96, 4);
    }

    /**
     * Hitung font size optimal agar teks muat dalam kotak (mm).
     * Mulai dari 14pt, turunkan sampai muat atau minimal 6pt.
     */
    private function autoFontSize(TCPDF $pdf, string $text, float $wMm, float $hMm): float
    {
        $size = 14;
        $min  = 6;

        while ($size > $min) {
            $pdf->SetFontSize($size);
            $strW = $pdf->GetStringWidth($text);
            if ($strW <= $wMm && $size <= ($hMm * 2.83)) { // 1pt ≈ 0.353mm
                break;
            }
            $size -= 0.5;
        }

        return max($size, $min);
    }

}