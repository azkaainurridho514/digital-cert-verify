<?php

namespace App\Services;

use App\DTOs\SignatureResult;
use RuntimeException;

final class EcdsaService
{
    // ── Konfigurasi ───────────────────────────────────────────────────────────

    private const CURVE = 'prime256v1';
    private const ALGO  = OPENSSL_ALGO_SHA256;

    // ── Runtime Cache ─────────────────────────────────────────────────────────

    // Menyimpan key yang sudah di-resolve agar tidak load ulang per request
    private ?\OpenSSLAsymmetricKey $privateKey = null;
    private ?string                $publicKey  = null;

    // ── 1. Key Generation ─────────────────────────────────────────────────────

    /**
     * Generate pasangan kunci ECDSA baru.
     * Hanya dipanggil jika .env kosong.
     * Hasil hanya disimpan di runtime memory, tidak menulis ke .env.
     *
     * @return array{ private_key: string, public_key: string } format PEM
     */
    public function generateKeyPair(): array
    {
        $resource = openssl_pkey_new([
            'curve_name'       => self::CURVE,
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ]);

        if ($resource === false) {
            throw new RuntimeException('Gagal generate ECDSA key: ' . openssl_error_string());
        }

        openssl_pkey_export($resource, $privateKeyPem);

        $details = openssl_pkey_get_details($resource);
        if ($details === false) {
            throw new RuntimeException('Gagal mengambil detail key: ' . openssl_error_string());
        }

        return [
            'private_key' => $privateKeyPem,
            'public_key'  => $details['key'],
        ];
    }

    // ── 2. Sign ───────────────────────────────────────────────────────────────

    /**
     * Tanda tangani pesan menggunakan ECDSA private key.
     * Key diambil dari .env jika ada, atau dari hasil generate jika .env kosong.
     *
     * @return SignatureResult berisi signature (base64) dan public key (PEM)
     */
    public function sign(string $message): SignatureResult
{
    $privateKeyPem = $this->resolvePrivateKey();
    $privateKey    = openssl_get_privatekey($privateKeyPem);

    if ($privateKey === false) {
        throw new RuntimeException('Private key tidak valid: ' . openssl_error_string());
    }

    $binary = '';
    if (! openssl_sign($message, $binary, $privateKey, self::ALGO)) {
        throw new RuntimeException('ECDSA signing gagal: ' . openssl_error_string());
    }

    return new SignatureResult(
        signature: base64_encode($binary),
        publicKey: $this->resolvePublicKey(),
    );
}

public function verify(string $message, string $signature): bool
{
    $binary = base64_decode($signature, strict: true);
    if ($binary === false) {
        throw new RuntimeException('Signature bukan format base64 yang valid.');
    }

    $publicKeyPem = $this->resolvePublicKey();
    $publicKey    = openssl_get_publickey($publicKeyPem);

    if ($publicKey === false) {
        throw new RuntimeException('Public key tidak valid: ' . openssl_error_string());
    }

    $result = openssl_verify($message, $binary, $publicKey, self::ALGO);

    // Bebaskan resource key dari memory
    openssl_free_key($publicKey);

    return match ($result) {
        1       => true,
        0       => false,
        default => throw new RuntimeException('Verifikasi error: ' . openssl_error_string()),
    };
}

    // ── Private: Key Resolution ───────────────────────────────────────────────

    /**
     * Ambil private key:
     * 1. Dari runtime cache jika sudah pernah di-resolve
     * 2. Dari ECDSA_PRIVATE_KEY di .env jika ada
     * 3. Generate baru jika .env kosong (simpan ke runtime cache saja)
     */
    private function resolvePrivateKey(): string
    {
        $key = config('app.ecdsa_private_key');

        if (!$key) {
            throw new RuntimeException('ECDSA_PRIVATE_KEY tidak ditemukan di ENV.');
        }

        $decoded = base64_decode($key, strict: true);

        return $decoded !== false ? $decoded : $key;
    }

    private function resolvePublicKey(): string
    {
        $key = config('app.ecdsa_public_key');

        if (!$key) {
            throw new RuntimeException('ECDSA_PUBLIC_KEY tidak ditemukan di ENV.');
        }

        $decoded = base64_decode($key, strict: true);

        return $decoded !== false ? $decoded : $key;
    }


    // ── Key Initialization ────────────────────────────────────────────────────

    /**
     * Dipanggil saat aplikasi boot (AppServiceProvider).
     * Cek .env → jika kosong, generate key baru dan simpan ke .env.
     * Jika sudah ada → tidak melakukan apapun.
     */
    public function ensureKeysExist(): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            throw new RuntimeException('.env file tidak ditemukan.');
        }

        // Baca langsung dari file .env (bukan env())
        // env() membaca runtime cache → tidak reliable saat boot pertama kali
        $contents = file_get_contents($envPath);

        // Cek keberadaan key langsung dari isi file
        preg_match('/^ECDSA_PRIVATE_KEY=(.+)$/m', $contents, $privateMatch);
        preg_match('/^ECDSA_PUBLIC_KEY=(.+)$/m',  $contents, $publicMatch);

        $hasPrivate = ! empty(trim($privateMatch[1] ?? ''));
        $hasPublic  = ! empty(trim($publicMatch[1] ?? ''));

        // Kedua key sudah ada di file .env → tidak perlu generate ulang
        if ($hasPrivate && $hasPublic) {
            return;
        }

        // Generate key pair baru
        $pair = $this->generateKeyPair();

        $encodedPrivate = base64_encode($pair['private_key']);
        $encodedPublic  = base64_encode($pair['public_key']);

        if (! $hasPrivate) {
            $contents = $this->writeEnvValue($contents, 'ECDSA_PRIVATE_KEY', $encodedPrivate);
        }
        if (! $hasPublic) {
            $contents = $this->writeEnvValue($contents, 'ECDSA_PUBLIC_KEY', $encodedPublic);
        }

        file_put_contents($envPath, $contents);
    }

    /**
     * Update nilai key di .env jika baris sudah ada,
     * atau append baris baru jika belum ada.
     */
    private function writeEnvValue(string $contents, string $key, string $value): string
    {
        // Jika baris key sudah ada → replace nilainya
        if (preg_match("/^{$key}=.*$/m", $contents)) {
            return preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $contents);
        }

        // Belum ada → append ke akhir file
        return rtrim($contents) . PHP_EOL . "{$key}={$value}" . PHP_EOL;
    }
}