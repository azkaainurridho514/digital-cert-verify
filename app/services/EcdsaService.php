<?php

// Mendefinisikan namespace untuk service ini, sesuai struktur folder App/Services
namespace App\Services;

// Mengimpor kelas SignatureResult sebagai return type dari method sign()
use App\DTOs\SignatureResult;
// Mengimpor RuntimeException untuk melempar error saat terjadi kegagalan
use RuntimeException;

// Mendefinisikan kelas EcdsaService sebagai final (tidak bisa di-extend)
final class EcdsaService
{
    // Nama kurva eliptik yang digunakan: prime256v1 (setara NIST P-256)
    private const CURVE = 'prime256v1'; // NIST P-256 elliptic curve

    // Algoritma hash yang digunakan saat proses tanda tangan digital ECDSA
    private const ALGO  = OPENSSL_ALGO_SHA256;

    // Nama variabel .env untuk menyimpan ECDSA private key
    private const ENV_PRIVATE_KEY_NAME = 'ECDSA_PRIVATE_KEY';

    // Nama variabel .env untuk menyimpan ECDSA public key
    private const ENV_PUBLIC_KEY_NAME  = 'ECDSA_PUBLIC_KEY';

    // Constructor kosong, tidak ada inisialisasi khusus saat objek dibuat
    public function __construct()
    {
        //
    }

    // Method untuk menghasilkan tanda tangan digital ECDSA dari sebuah pesan
    public function sign(string $message): SignatureResult
    {
        // Memuat ECDSA private key dari .env untuk digunakan dalam proses signing
        $privateKey = $this->loadPrivateKey();

        // Menyiapkan variabel kosong untuk menampung hasil tanda tangan dalam format binary
        $binarySignature = '';

        // Melakukan proses tanda tangan digital ECDSA menggunakan private key dan algoritma SHA-256
        $success = openssl_sign($message, $binarySignature, $privateKey, self::ALGO);

        // Jika proses signing gagal, lempar exception dengan pesan error dari OpenSSL
        if (! $success) {
            throw new RuntimeException('ECDSA signing gagal: ' . openssl_error_string());
        }

        // Mengubah hasil tanda tangan binary menjadi format base64 agar aman disimpan/dikirim
        $signature = base64_encode($binarySignature);

        // Mengambil public key yang berpadanan dengan private key yang digunakan
        $publicKey = $this->derivePublicKey($privateKey);

        // Mengembalikan hasil tanda tangan dan public key dalam bentuk DTO SignatureResult
        return new SignatureResult(
            signature: $signature,
            publicKey: $publicKey,
        );
    }

    // Method untuk memverifikasi keabsahan tanda tangan digital ECDSA
    public function verify(string $message, string $signature, string $publicKey): bool
    {
        // Mendekode tanda tangan dari base64 kembali ke format binary; strict=true agar validasi ketat
        $binarySignature = base64_decode($signature, strict: true);

        // Jika hasil decode false, berarti format base64 tidak valid, lempar exception
        if ($binarySignature === false) {
            throw new RuntimeException('Signature bukan format base64 yang valid.');
        }

        // Memuat public key OpenSSL dari string PEM untuk digunakan dalam verifikasi
        $pubKeyResource = openssl_get_publickey($publicKey);

        // Jika public key tidak valid atau gagal dimuat, lempar exception
        if ($pubKeyResource === false) {
            throw new RuntimeException('Public key tidak valid: ' . openssl_error_string());
        }

        // Melakukan verifikasi tanda tangan ECDSA: mencocokkan pesan, tanda tangan binary, dan public key
        $result = openssl_verify($message, $binarySignature, $pubKeyResource, self::ALGO);

        // Mengembalikan hasil verifikasi: 1 = valid, 0 = tidak valid, selain itu = error
        return match ($result) {
            1       => true,   // Tanda tangan valid
            0       => false,  // Tanda tangan tidak cocok
            default => throw new RuntimeException('Verifikasi error: ' . openssl_error_string()),
        };
    }

    // Method untuk membuat pasangan kunci ECDSA baru (private key + public key)
    public function generateKeyPair(): array
    {
        // Membuat resource kunci baru menggunakan konfigurasi OpenSSL (ECDSA dengan kurva prime256v1)
        $keyResource = openssl_pkey_new($this->opensslConfig());

        // Jika gagal membuat kunci, lempar exception dengan pesan error OpenSSL
        if ($keyResource === false) {
            throw new RuntimeException('Gagal generate ECDSA key: ' . openssl_error_string());
        }

        // Mengekspor private key dari resource ke format string PEM, disimpan di $privateKeyPem
        openssl_pkey_export($keyResource, $privateKeyPem);

        // Mengambil detail lengkap dari kunci, termasuk public key dalam format PEM
        $details = openssl_pkey_get_details($keyResource);

        // Jika gagal mengambil detail kunci, lempar exception
        if ($details === false) {
            throw new RuntimeException('Gagal mengambil detail key: ' . openssl_error_string());
        }

        // Mengembalikan array berisi private key dan public key dalam format PEM
        return [
            'private_key' => $privateKeyPem,  // Private key PEM untuk signing
            'public_key'  => $details['key'], // Public key PEM untuk verifikasi
        ];
    }

    // Method static untuk memastikan pasangan kunci ECDSA sudah tersimpan di .env
    public static function ensureKeysExist(): void
    {
        // Mengambil path absolut ke file .env milik aplikasi Laravel
        $envPath  = base_path('.env');

        // Membaca seluruh isi file .env sebagai string
        $contents = file_get_contents($envPath);

        // Mencari baris ECDSA_PRIVATE_KEY di dalam isi .env menggunakan regex
        preg_match('/^ECDSA_PRIVATE_KEY=(.*)$/m', $contents, $privateMatches);

        // Mencari baris ECDSA_PUBLIC_KEY di dalam isi .env menggunakan regex
        preg_match('/^ECDSA_PUBLIC_KEY=(.*)$/m', $contents, $publicMatches);

        // Mengambil nilai private key yang ditemukan, atau null jika tidak ada
        $existingPrivate = $privateMatches[1] ?? null;

        // Mengambil nilai public key yang ditemukan, atau null jika tidak ada
        $existingPublic  = $publicMatches[1] ?? null;

        // Jika kedua kunci ECDSA sudah ada di .env, tidak perlu melakukan apapun
        if (!empty($existingPrivate) && !empty($existingPublic)) {
            return;
        }

        // Membuat instance EcdsaService untuk memanggil generateKeyPair()
        $instance = new self();

        // Membuat pasangan kunci ECDSA baru (private + public key dalam PEM)
        $keyPair  = $instance->generateKeyPair();

        // Mengubah private key PEM ke format base64 agar aman disimpan di .env
        $encodedPrivate = base64_encode($keyPair['private_key']);

        // Mengubah public key PEM ke format base64 agar aman disimpan di .env
        $encodedPublic  = base64_encode($keyPair['public_key']);

        // Memastikan file .env benar-benar ada sebelum mencoba menulis ke dalamnya
        if (! file_exists($envPath)) {
            throw new RuntimeException('.env file tidak ditemukan.');
        }

        // Jika private key belum ada, tambahkan baris ECDSA_PRIVATE_KEY ke akhir .env
        if (empty($existingPrivate)) {
            $contents .= PHP_EOL . self::ENV_PRIVATE_KEY_NAME . '=' . $encodedPrivate . PHP_EOL;
        }

        // Jika public key belum ada, tambahkan baris ECDSA_PUBLIC_KEY ke akhir .env
        if (empty($existingPublic)) {
            $contents .= self::ENV_PUBLIC_KEY_NAME . '=' . $encodedPublic . PHP_EOL;
        }

        // Menyimpan kembali isi .env yang sudah ditambahkan kunci ECDSA ke file
        file_put_contents($envPath, $contents);
    }

    // Method private untuk memuat ECDSA private key dari .env
    private function loadPrivateKey(): \OpenSSLAsymmetricKey
    {
        // Mengambil nilai ECDSA_PRIVATE_KEY dari .env (masih dalam format base64)
        $encoded = env(self::ENV_PRIVATE_KEY_NAME);

        // Jika nilai kosong, berarti private key belum dikonfigurasi, lempar exception
        if (empty($encoded)) {
            throw new RuntimeException('ECDSA private key tidak tersedia.');
        }

        // Mendekode nilai base64 ke format PEM asli; strict=true agar validasi ketat
        $pem = base64_decode($encoded, strict: true);

        // Jika decode gagal (bukan base64 valid), gunakan nilai asli sebagai fallback
        if ($pem === false) {
            $pem = $encoded;
        }

        // Memuat private key OpenSSL dari string PEM untuk digunakan dalam signing
        $key = openssl_pkey_get_private($pem);

        // Jika private key gagal dimuat, lempar exception dengan pesan error OpenSSL
        if ($key === false) {
            throw new RuntimeException('Gagal load private key: ' . openssl_error_string());
        }

        // Mengembalikan resource OpenSSL private key siap pakai
        return $key;
    }

    // Method private untuk mengekstrak public key dari resource private key
    private function derivePublicKey(\OpenSSLAsymmetricKey $privateKey): string
    {
        // Mengambil semua detail kunci termasuk public key dalam format PEM
        $details = openssl_pkey_get_details($privateKey);

        // Jika gagal mengambil detail, lempar exception
        if ($details === false) {
            throw new RuntimeException('Gagal mengambil detail key: ' . openssl_error_string());
        }

        // Mengembalikan public key dalam format PEM dari array detail kunci
        return $details['key'];
    }

    // Method private untuk menyediakan konfigurasi OpenSSL khusus ECDSA
    private function opensslConfig(): array
    {
        // Mengembalikan array konfigurasi untuk generate kunci ECDSA dengan OpenSSL
        return [
            'curve_name'       => self::CURVE,        // Menggunakan kurva eliptik prime256v1 (NIST P-256)
            'private_key_type' => OPENSSL_KEYTYPE_EC, // Tipe kunci Elliptic Curve (EC) untuk ECDSA
        ];
    }
}