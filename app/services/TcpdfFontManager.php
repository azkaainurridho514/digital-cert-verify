<?php

namespace App\Services;

use TCPDF_FONTS;

class TcpdfFontManager
{
    private const FONT_SOURCES = [
        'cinzel' => [
            'path' => 'fonts/cinzel-font.ttf',
        ],
        'cinzel-bold' => [                          // ← tambah ini
            'path' => 'fonts/cinzel-font-bold.ttf',
        ],
        'alice' => [
            'path' => 'fonts/alice-font.ttf',
        ],
    ];

    // Cache key → nama font hasil konversi TCPDF
    private array $resolvedNames = [];

    private string $tcpdfFontDir;

    public function __construct()
    {
        $this->tcpdfFontDir = base_path('vendor/tecnickcom/tcpdf/fonts');
    }

    public function ensure(string $fontKey): string
    {
        $fontKey = strtolower($fontKey);

        if (!isset(self::FONT_SOURCES[$fontKey])) {
            throw new \InvalidArgumentException("Font '{$fontKey}' tidak terdaftar.");
        }

        // Sudah pernah di-resolve di request ini
        if (isset($this->resolvedNames[$fontKey])) {
            return $this->resolvedNames[$fontKey];
        }

        $ttfPath      = $this->resolveTtf($fontKey);
        $resolvedName = $this->convertToTcpdf($ttfPath);

        $this->resolvedNames[$fontKey] = $resolvedName;

        \Log::info("[TcpdfFontManager] '{$fontKey}' → TCPDF font name: '{$resolvedName}'");

        return $resolvedName;
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function resolveTtf(string $fontKey): string
    {
        $fullPath = $this->resolvePath(self::FONT_SOURCES[$fontKey]['path']);

        if (!file_exists($fullPath)) {
            throw new \RuntimeException(
                "Font file tidak ditemukan: {$fullPath}\n" .
                "Letakkan file TTF di public/fonts/"
            );
        }

        return $fullPath;
    }

    private function resolvePath(string $relativePath): string
    {
        $isProduction = app()->environment('production');

        return $isProduction
            ? '/home/cery9751/public_html/' . $relativePath
            : public_path($relativePath);
    }

    /**
     * Konversi TTF → TCPDF dan return nama font yang dihasilkan.
     * Kalau sudah pernah dikonversi, TCPDF_FONTS tetap return nama yang sama.
     */
    private function convertToTcpdf(string $ttfPath): string
    {
        $result = TCPDF_FONTS::addTTFfont(
            $ttfPath,
            'TrueTypeUnicode',
            '',
            32,
            $this->tcpdfFontDir . '/'
        );

        if (empty($result)) {
            throw new \RuntimeException("Konversi font gagal untuk: {$ttfPath}");
        }

        // $result adalah nama font yang bisa langsung dipakai di SetFont()
        return $result;
    }
}