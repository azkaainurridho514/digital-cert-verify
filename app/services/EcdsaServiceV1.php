<?php

namespace App\Services;

use App\DTOs\SignatureResult;
use RuntimeException;

final class EcdsaServiceV1 // real
{
    // ── Konfigurasi ───────────────────────────────────────────────────────────

    // Kurva eliptik yang digunakan untuk generate key pair
    private const CURVE = 'prime256v1';

    // Algoritma hash yang digunakan saat proses digital signature
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
        // Generate resource key baru menggunakan kurva eliptik prime256v1
        $resource = openssl_pkey_new([
            'curve_name'       => self::CURVE,
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ]);

        // Validasi hasil generate — hentikan proses jika generate gagal
        if ($resource === false) {
            throw new RuntimeException('Gagal generate ECDSA key: ' . openssl_error_string());
        }

        // Ekspor private key ke format PEM
        openssl_pkey_export($resource, $privateKeyPem);

        // Ambil detail key untuk mendapatkan public key
        $details = openssl_pkey_get_details($resource);
        if ($details === false) {
            throw new RuntimeException('Gagal mengambil detail key: ' . openssl_error_string());
        }

        // Kembalikan pasangan private key dan public key dalam format PEM
        return [
            'private_key' => $privateKeyPem,
            'public_key'  => $details['key'],
        ];
    }
    
    public function ensureKeysExist(): void
    {
        // Tentukan path file .env pada root aplikasi
        $envPath = base_path('.env');

        // Validasi keberadaan file .env
        if (! file_exists($envPath)) {
            throw new RuntimeException('.env file tidak ditemukan.');
        }

        // Baca langsung dari file .env (bukan env())
        // env() membaca runtime cache → tidak reliable saat boot pertama kali
        $contents = file_get_contents($envPath);

        // Cek keberadaan key langsung dari isi file menggunakan regex
        preg_match('/^ECDSA_PRIVATE_KEY=(.+)$/m', $contents, $privateMatch);
        preg_match('/^ECDSA_PUBLIC_KEY=(.+)$/m',  $contents, $publicMatch);

        $hasPrivate = ! empty(trim($privateMatch[1] ?? ''));
        $hasPublic  = ! empty(trim($publicMatch[1] ?? ''));

        // Kedua key sudah ada di file .env → tidak perlu generate ulang
        if ($hasPrivate && $hasPublic) {
            return;
        }

        // Generate key pair baru karena salah satu atau kedua key belum ada
        $pair = $this->generateKeyPair();

        // Enkode key ke Base64 agar aman disimpan sebagai nilai ENV satu baris
        $encodedPrivate = base64_encode($pair['private_key']);
        $encodedPublic  = base64_encode($pair['public_key']);

        // Tulis private key ke .env jika belum ada
        if (! $hasPrivate) {
            $contents = $this->writeEnvValue($contents, 'ECDSA_PRIVATE_KEY', $encodedPrivate);
        }

        // Tulis public key ke .env jika belum ada
        if (! $hasPublic) {
            $contents = $this->writeEnvValue($contents, 'ECDSA_PUBLIC_KEY', $encodedPublic);
        }

        // Simpan perubahan ke file .env
        file_put_contents($envPath, $contents);
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
        // Ambil private key dari ENV atau runtime cache
        $privateKeyPem = $this->resolvePrivateKey();

        // Muat private key PEM ke dalam resource OpenSSL
        $privateKey    = openssl_get_privatekey($privateKeyPem);

        // Validasi private key — hentikan proses jika key tidak valid
        if ($privateKey === false) {
            throw new RuntimeException('Private key tidak valid: ' . openssl_error_string());
        }

        // Buat digital signature dari pesan menggunakan private key dan algoritma SHA-256
        // openssl_sign() melakukan hashing pesan secara internal sebelum menandatangani
        $binary = '';
        if (! openssl_sign($message, $binary, $privateKey, self::ALGO)) {
            throw new RuntimeException('ECDSA signing gagal: ' . openssl_error_string());
        }

        // Kembalikan signature dalam format Base64 beserta public key
        // HEX digunakan agar signature aman disimpan di database
        return new SignatureResult(
            signature: bin2hex($binary),
            publicKey: $this->resolvePublicKey(),
        );
    }

    // ── 3. Verify ─────────────────────────────────────────────────────────────

    public function verify(string $message, string $signature): bool
    {
        // Validasi bahwa signature merupakan string hexadecimal yang valid
        if (!ctype_xdigit($signature)) {
            throw new RuntimeException('Signature bukan format hex yang valid.');
        }

        // Konversi signature HEX menjadi raw binary
        $binary = hex2bin($signature);

        // Validasi hasil konversi HEX ke binary
        if ($binary === false) {
            throw new RuntimeException('Gagal konversi hex ke binary.');
        }

        // Ambil public key dari ENV atau runtime cache
        $publicKeyPem = $this->resolvePublicKey();

        // Muat public key PEM ke dalam resource OpenSSL
        $publicKey    = openssl_get_publickey($publicKeyPem);

        // Validasi public key — hentikan proses jika key tidak valid
        if ($publicKey === false) {
            throw new RuntimeException('Public key tidak valid: ' . openssl_error_string());
        }

        // Lakukan verification — cocokkan signature dengan pesan menggunakan public key
        // Mengembalikan 1 jika valid, 0 jika tidak cocok, -1 jika terjadi error
        $result = openssl_verify($message, $binary, $publicKey, self::ALGO);

        // Bebaskan resource key dari memory
        openssl_free_key($publicKey);

        // Petakan hasil verification ke nilai boolean atau lempar eksepsi jika error
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
        // Baca nilai ECDSA_PRIVATE_KEY dari konfigurasi aplikasi
        $key = config('app.ecdsa_private_key');

        // Validasi keberadaan key — hentikan proses jika key tidak ditemukan di ENV
        if (!$key) {
            throw new RuntimeException('ECDSA_PRIVATE_KEY tidak ditemukan di ENV.');
        }

        // Dekode dari Base64 jika key disimpan dalam format Base64
        $decoded = base64_decode($key, strict: true);

        return $decoded !== false ? $decoded : $key;
    }

    private function resolvePublicKey(): string
    {
        // Baca nilai ECDSA_PUBLIC_KEY dari konfigurasi aplikasi
        $key = config('app.ecdsa_public_key');

        // Validasi keberadaan key — hentikan proses jika key tidak ditemukan di ENV
        if (!$key) {
            throw new RuntimeException('ECDSA_PUBLIC_KEY tidak ditemukan di ENV.');
        }

        // Dekode dari Base64 jika key disimpan dalam format Base64
        $decoded = base64_decode($key, strict: true);

        return $decoded !== false ? $decoded : $key;
    }

    // ── Key Initialization ────────────────────────────────────────────────────

    /**
     * Dipanggil saat aplikasi boot (AppServiceProvider).
     * Cek .env → jika kosong, generate key baru dan simpan ke .env.
     * Jika sudah ada → tidak melakukan apapun.
     */

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