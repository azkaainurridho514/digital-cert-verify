<?php
// output base64
namespace App\Services;

use App\DTOs\SignatureResult;
use RuntimeException;

/**
 * EcdsaService
 *
 * Menangani operasi digital signature ECDSA:
 *   - Generate key pair (via OpenSSL)
 *   - Sign pesan (mode penelitian: nonce k fixed; mode produksi: OpenSSL random)
 *   - Verify signature (via OpenSSL)
 *
 * Kurva yang digunakan: prime256v1 / secp256r1 / NIST P-256
 */
final class EcdsaService
{
    // =========================================================================
    // Konfigurasi
    // =========================================================================

    private const CURVE = 'prime256v1';
    private const ALGO  = OPENSSL_ALGO_SHA256;

    /**
     * MODE PENELITIAN — nonce k yang fixed untuk signing deterministik.
     *
     * ⚠️  PERINGATAN KEAMANAN:
     * Nilai ini HANYA untuk keperluan demonstrasi / akademik.
     * Nonce k yang fixed pada ECDSA produksi bersifat FATAL:
     *   - Dua pesan berbeda dengan k yang sama memungkinkan private key
     *     dihitung balik secara trivial (insiden Sony PS3, 2010).
     *
     * Set ke `null` untuk mode produksi normal (nonce random via OpenSSL).
     */
    private const RESEARCH_FIXED_K = '5';

    // =========================================================================
    // Runtime Cache
    // =========================================================================

    /** Cache parameter kurva agar tidak di-resolve ulang per operasi. */
    private ?array $curveParams = null;

    // =========================================================================
    // Public API
    // =========================================================================

    /**
     * Generate pasangan kunci ECDSA baru menggunakan OpenSSL.
     *
     * @return array{ private_key: string, public_key: string }  Format PEM.
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

    /**
     * Tanda tangani pesan.
     *
     * - Jika RESEARCH_FIXED_K di-set  → signing manual dengan nonce k konstan.
     * - Jika RESEARCH_FIXED_K = null  → openssl_sign() normal (aman untuk produksi).
     *
     * Output selalu berupa DER base64, kompatibel dengan verify().
     *
     * @return SignatureResult  Berisi signature (base64) dan public key (PEM).
     */
    public function sign(string $message): SignatureResult
    {
        return self::RESEARCH_FIXED_K !== null
            ? $this->signWithFixedK($message)
            : $this->signWithOpenSsl($message);
    }

    /**
     * Verifikasi signature menggunakan OpenSSL.
     *
     * Tidak dimodifikasi — tetap menggunakan openssl_verify() karena
     * output DER dari signWithFixedK() identik dengan openssl_sign().
     */
    public function verify(string $message, string $signature): bool
    {
        $binary = base64_decode($signature, strict: true);
        if ($binary === false) {
            throw new RuntimeException('Signature bukan format base64 yang valid.');
        }

        $publicKey = openssl_get_publickey($this->resolvePublicKey());
        if ($publicKey === false) {
            throw new RuntimeException('Public key tidak valid: ' . openssl_error_string());
        }

        $result = openssl_verify($message, $binary, $publicKey, self::ALGO);
        openssl_free_key($publicKey);

        return match ($result) {
            1       => true,
            0       => false,
            default => throw new RuntimeException('Verifikasi error: ' . openssl_error_string()),
        };
    }

    /**
     * Pastikan ECDSA_PRIVATE_KEY dan ECDSA_PUBLIC_KEY tersedia di .env.
     * Dipanggil saat aplikasi boot (AppServiceProvider).
     *
     * - Key sudah ada  → tidak melakukan apapun.
     * - Key belum ada  → generate key pair baru dan tulis ke .env.
     */
    public function ensureKeysExist(): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            throw new RuntimeException('.env file tidak ditemukan.');
        }

        $contents = file_get_contents($envPath);

        $hasPrivate = $this->envKeyExists($contents, 'ECDSA_PRIVATE_KEY');
        $hasPublic  = $this->envKeyExists($contents, 'ECDSA_PUBLIC_KEY');

        if ($hasPrivate && $hasPublic) {
            return;
        }

        $pair = $this->generateKeyPair();

        if (! $hasPrivate) {
            $contents = $this->writeEnvValue($contents, 'ECDSA_PRIVATE_KEY', base64_encode($pair['private_key']));
        }
        if (! $hasPublic) {
            $contents = $this->writeEnvValue($contents, 'ECDSA_PUBLIC_KEY', base64_encode($pair['public_key']));
        }

        file_put_contents($envPath, $contents);
    }

    // =========================================================================
    // Signing — Internal
    // =========================================================================

    /**
     * Mode produksi: signing menggunakan openssl_sign() dengan nonce random.
     */
    private function signWithOpenSsl(string $message): SignatureResult
    {
        $privateKey = openssl_get_privatekey($this->resolvePrivateKey());
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

    /**
     * Mode penelitian: signing manual ECDSA dengan nonce k konstan.
     *
     * Algoritma standar ECDSA (FIPS 186-4) — satu-satunya perbedaan
     * dari implementasi normal adalah pada Step 2 (nonce k fixed):
     *
     *   1. e  = SHA-256(message)
     *   2. k  = RESEARCH_FIXED_K          ← satu-satunya perubahan
     *   3. R  = k·G  →  r = R.x mod n
     *   4. s  = k⁻¹ · (e + r·d) mod n
     *   5. Encode (r, s) ke DER ASN.1
     */
    private function signWithFixedK(string $message): SignatureResult
    {
        // Step 1: Hash pesan → integer e
        $e = gmp_init(hash('sha256', $message), 16);

        // Step 2: Nonce k fixed (satu-satunya hardcode selain konstanta kurva)
        $params = $this->resolveCurveParams();
        $n      = $params['n'];
        $G      = $params['G'];
        $k      = gmp_init(self::RESEARCH_FIXED_K, 10);

        $this->assertValidNonce($k, $n);

        // Step 3: R = k·G, r = R.x mod n
        $r = gmp_mod($this->pointMultiply($k, $G)['x'], $n);
        if (gmp_cmp($r, 0) === 0) {
            throw new RuntimeException('r = 0, pilih nilai k lain.');
        }

        // Step 4: s = k⁻¹ · (e + r·d) mod n
        $d    = $this->extractPrivateScalar($this->resolvePrivateKey());
        $kInv = $this->modularInverse($k, $n);

        $s = gmp_mod(
            gmp_mul($kInv, gmp_mod(gmp_add($e, gmp_mul($r, $d)), $n)),
            $n
        );
        if (gmp_cmp($s, 0) === 0) {
            throw new RuntimeException('s = 0, pilih nilai k lain.');
        }

        // Step 5: Encode (r, s) → DER ASN.1 → base64
        return new SignatureResult(
            signature: base64_encode($this->encodeDerSignature($r, $s)),
            publicKey: $this->resolvePublicKey(),
        );
    }

    // =========================================================================
    // Curve Parameter Resolution
    // =========================================================================

    /**
     * Resolve parameter kurva aktif.
     *
     * OpenSSL pada environment ini hanya mengekspos: curve_name, curve_oid, x, y, d.
     * Field Gx, Gy, order, p, a tidak tersedia sehingga semua parameter
     * diambil dari konstanta standar berdasarkan nama kurva.
     *
     * Hasil di-cache ke $curveParams untuk menghindari resolve ulang.
     *
     * @return array{ p: \GMP, a: \GMP, n: \GMP, G: array{ x: \GMP, y: \GMP } }
     */
    private function resolveCurveParams(): array
    {
        if ($this->curveParams !== null) {
            return $this->curveParams;
        }

        $curveName = $this->detectCurveName();

        $this->curveParams = $this->getCurveFieldParams($curveName);

        return $this->curveParams;
    }

    /**
     * Baca nama kurva dari private key via OpenSSL.
     */
    private function detectCurveName(): string
    {
        $keyResource = openssl_get_privatekey($this->resolvePrivateKey());
        if ($keyResource === false) {
            throw new RuntimeException('Private key tidak valid: ' . openssl_error_string());
        }

        $details = openssl_pkey_get_details($keyResource);
        openssl_free_key($keyResource);

        if ($details === false || ! isset($details['ec'])) {
            throw new RuntimeException('Gagal extract parameter kurva dari key.');
        }

        return $details['ec']['curve_name'] ?? self::CURVE;
    }

    /**
     * Kembalikan parameter kurva lengkap berdasarkan nama kurva.
     *
     * Mendukung: prime256v1 / P-256 / secp256r1,
     *            secp384r1 / P-384,
     *            secp521r1 / P-521.
     *
     * @return array{ p: \GMP, a: \GMP, n: \GMP, G: array{ x: \GMP, y: \GMP } }
     */
    private function getCurveFieldParams(string $curveName): array
    {
        return match ($curveName) {
            'prime256v1', 'P-256', 'secp256r1' => [
                'p' => gmp_init('ffffffff00000001000000000000000000000000ffffffffffffffffffffffff', 16),
                'a' => gmp_init('ffffffff00000001000000000000000000000000fffffffffffffffffffffffc', 16),
                'n' => gmp_init('ffffffff00000000ffffffffffffffffbce6faada7179e84f3b9cac2fc632551', 16),
                'G' => [
                    'x' => gmp_init('6b17d1f2e12c4247f8bce6e563a440f277037d812deb33a0f4a13945d898c296', 16),
                    'y' => gmp_init('4fe342e2fe1a7f9b8ee7eb4a7c0f9e162bce33576b315ececbb6406837bf51f5', 16),
                ],
            ],
            'secp384r1', 'P-384' => [
                'p' => gmp_init('fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffeffffffff0000000000000000ffffffff', 16),
                'a' => gmp_init('fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffeffffffff0000000000000000fffffffc', 16),
                'n' => gmp_init('ffffffffffffffffffffffffffffffffffffffffffffffffc7634d81f4372ddf581a0db248b0a77aecec196accc52973', 16),
                'G' => [
                    'x' => gmp_init('aa87ca22be8b05378eb1c71ef320ad746e1d3b628ba79b9859f741e082542a385502f25dbf55296c3a545e3872760ab7', 16),
                    'y' => gmp_init('3617de4a96262c6f5d9e98bf9292dc29f8f41dbd289a147ce9da3113b5f0b8c00a60b1ce1d7e819d7a431d7c90ea0e5f', 16),
                ],
            ],
            'secp521r1', 'P-521' => [
                'p' => gmp_init('01ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', 16),
                'a' => gmp_init('01fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffc', 16),
                'n' => gmp_init('01fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffa51868783bf2f966b7fcc0148f709a5d03bb5c9b8899c47aebb6fb71e91386409', 16),
                'G' => [
                    'x' => gmp_init('00c6858e06b70404e9cd9e3ecb662395b4429c648139053fb521f828af606b4d3dbaa14b5e77efe75928fe1dc127a2ffa8de3348b3c1856a429bf97e7e31c2e5bd66', 16),
                    'y' => gmp_init('011839296a789a3bc0045c8a5fb42c7d1bd998f54449579b446817afbd17273e662c97ee72995ef42640c550b9013fad0761353c7086a272c24088be94769fd16650', 16),
                ],
            ],
            default => throw new RuntimeException(
                "Parameter kurva '{$curveName}' tidak dikenali. Tambahkan ke getCurveFieldParams()."
            ),
        };
    }

    // =========================================================================
    // Elliptic Curve Arithmetic
    // =========================================================================

    /**
     * Scalar multiplication: hitung k·P menggunakan algoritma double-and-add.
     *
     * @param  \GMP                            $k  Scalar.
     * @param  array{ x: \GMP, y: \GMP }       $P  Titik awal.
     * @return array{ x: \GMP, y: \GMP }           Titik hasil.
     */
    private function pointMultiply(\GMP $k, array $P): array
    {
        $result = null; // Representasi point at infinity
        $addend = $P;

        foreach (str_split(strrev(gmp_strval($k, 2))) as $bit) {
            if ($bit === '1') {
                $result = ($result === null)
                    ? $addend
                    : $this->pointAdd($result, $addend);
            }
            $addend = $this->pointDouble($addend);
        }

        return $result;
    }

    /**
     * Point addition: R = P + Q
     *
     * Formula affine Weierstrass:
     *   λ  = (y_Q − y_P) · (x_Q − x_P)⁻¹  mod p
     *   x_R = λ² − x_P − x_Q               mod p
     *   y_R = λ(x_P − x_R) − y_P           mod p
     *
     * @param  array{ x: \GMP, y: \GMP } $P
     * @param  array{ x: \GMP, y: \GMP } $Q
     * @return array{ x: \GMP, y: \GMP }
     */
    private function pointAdd(array $P, array $Q): array
    {
        $p = $this->resolveCurveParams()['p'];

        if (gmp_cmp($P['x'], $Q['x']) === 0 && gmp_cmp($P['y'], $Q['y']) === 0) {
            return $this->pointDouble($P);
        }

        $lambda  = $this->fieldDiv(gmp_sub($Q['y'], $P['y']), gmp_sub($Q['x'], $P['x']), $p);
        $lambda2 = $this->fieldMul($lambda, $lambda, $p);

        $xR = gmp_mod(gmp_sub(gmp_sub($lambda2, $P['x']), $Q['x']), $p);
        $yR = gmp_mod(gmp_sub($this->fieldMul($lambda, gmp_sub($P['x'], $xR), $p), $P['y']), $p);

        return ['x' => $xR, 'y' => $yR];
    }

    /**
     * Point doubling: R = 2·P
     *
     * Formula affine Weierstrass:
     *   λ  = (3·x_P² + a) · (2·y_P)⁻¹  mod p
     *   x_R = λ² − 2·x_P               mod p
     *   y_R = λ(x_P − x_R) − y_P       mod p
     *
     * @param  array{ x: \GMP, y: \GMP } $P
     * @return array{ x: \GMP, y: \GMP }
     */
    private function pointDouble(array $P): array
    {
        $params = $this->resolveCurveParams();
        $p      = $params['p'];
        $a      = $params['a'];

        $x2      = $this->fieldMul($P['x'], $P['x'], $p);
        $num     = gmp_mod(gmp_add(gmp_mul(gmp_init(3), $x2), $a), $p);
        $lambda  = $this->fieldDiv($num, gmp_mul(gmp_init(2), $P['y']), $p);
        $lambda2 = $this->fieldMul($lambda, $lambda, $p);

        $xR = gmp_mod(gmp_sub(gmp_sub($lambda2, $P['x']), $P['x']), $p);
        $yR = gmp_mod(gmp_sub($this->fieldMul($lambda, gmp_sub($P['x'], $xR), $p), $P['y']), $p);

        return ['x' => $xR, 'y' => $yR];
    }

    // =========================================================================
    // Field Arithmetic Helpers
    // =========================================================================

    /**
     * Perkalian field: (a · b) mod p
     * Menggunakan gmp_mul → gmp_mod untuk menghindari overflow gmp_pow.
     */
    private function fieldMul(\GMP $a, \GMP $b, \GMP $p): \GMP
    {
        return gmp_mod(gmp_mul($a, $b), $p);
    }

    /**
     * Pembagian field: (num · den⁻¹) mod p
     */
    private function fieldDiv(\GMP $num, \GMP $den, \GMP $p): \GMP
    {
        return gmp_mod(gmp_mul(gmp_mod($num, $p), gmp_invert(gmp_mod($den, $p), $p)), $p);
    }

    /**
     * Modular inverse: k⁻¹ mod n
     * Melempar exception jika inverse tidak ada (gcd(k, n) ≠ 1).
     */
    private function modularInverse(\GMP $k, \GMP $n): \GMP
    {
        $inv = gmp_invert($k, $n);
        if ($inv === false) {
            throw new RuntimeException('Modular inverse k tidak ada (gcd(k, n) ≠ 1).');
        }

        return $inv;
    }

    // =========================================================================
    // Validation Helpers
    // =========================================================================

    /**
     * Validasi nonce k berada dalam range [1, n−1].
     */
    private function assertValidNonce(\GMP $k, \GMP $n): void
    {
        if (gmp_cmp($k, 1) < 0 || gmp_cmp($k, gmp_sub($n, 1)) > 0) {
            throw new RuntimeException('Fixed k di luar range valid [1, n-1].');
        }
    }

    // =========================================================================
    // DER ASN.1 Encoding
    // =========================================================================

    /**
     * Encode pasangan (r, s) ke format DER ASN.1:
     *
     *   SEQUENCE {
     *     INTEGER r,
     *     INTEGER s
     *   }
     *
     * Format ini identik dengan output openssl_sign() sehingga
     * openssl_verify() dapat memverifikasi tanpa modifikasi.
     */
    private function encodeDerSignature(\GMP $r, \GMP $s): string
    {
        $rDer  = $this->derEncodeInteger($r);
        $sDer  = $this->derEncodeInteger($s);
        $inner = $rDer . $sDer;

        return "\x30" . $this->derLength(strlen($inner)) . $inner;
    }

    /**
     * Encode satu GMP integer ke DER INTEGER (tag 0x02 + length + value).
     */
    private function derEncodeInteger(\GMP $n): string
    {
        $bytes = $this->gmpToUnsignedBytes($n);

        return "\x02" . $this->derLength(strlen($bytes)) . $bytes;
    }

    /**
     * Konversi GMP integer ke representasi big-endian unsigned bytes.
     * Tambahkan leading 0x00 jika MSB set (DER INTEGER adalah signed).
     */
    private function gmpToUnsignedBytes(\GMP $n): string
    {
        $hex = gmp_strval($n, 16);
        if (strlen($hex) % 2 !== 0) {
            $hex = '0' . $hex;
        }

        $bytes = hex2bin($hex);

        return (ord($bytes[0]) >= 0x80) ? "\x00" . $bytes : $bytes;
    }

    /**
     * Encode panjang field DER.
     * Short form jika length < 128, long form jika >= 128.
     */
    private function derLength(int $length): string
    {
        if ($length < 0x80) {
            return chr($length);
        }

        $lengthBytes = '';
        $temp        = $length;
        while ($temp > 0) {
            $lengthBytes = chr($temp & 0xff) . $lengthBytes;
            $temp >>= 8;
        }

        return chr(0x80 | strlen($lengthBytes)) . $lengthBytes;
    }

    // =========================================================================
    // Key Extraction
    // =========================================================================

    /**
     * Ekstrak private scalar d dari PEM menggunakan OpenSSL.
     *
     * Field 'ec.d' yang di-expose OpenSSL adalah binary string
     * representasi dari private scalar d secara langsung.
     */
    private function extractPrivateScalar(string $privateKeyPem): \GMP
    {
        $keyResource = openssl_get_privatekey($privateKeyPem);
        if ($keyResource === false) {
            throw new RuntimeException('Private key tidak valid: ' . openssl_error_string());
        }

        $details = openssl_pkey_get_details($keyResource);
        openssl_free_key($keyResource);

        if ($details === false || ! isset($details['ec']['d'])) {
            throw new RuntimeException('Gagal extract private scalar d dari key.');
        }

        return gmp_import($details['ec']['d']);
    }

    // =========================================================================
    // Key Resolution
    // =========================================================================

    /**
     * Baca private key PEM dari konfigurasi.
     * Nilai di .env disimpan dalam base64 — decode jika perlu.
     */
    private function resolvePrivateKey(): string
    {
        return $this->resolveKeyFromConfig('app.ecdsa_private_key', 'ECDSA_PRIVATE_KEY');
    }

    /**
     * Baca public key PEM dari konfigurasi.
     * Nilai di .env disimpan dalam base64 — decode jika perlu.
     */
    private function resolvePublicKey(): string
    {
        return $this->resolveKeyFromConfig('app.ecdsa_public_key', 'ECDSA_PUBLIC_KEY');
    }

    /**
     * Helper: baca key dari config, decode base64 jika diperlukan.
     */
    private function resolveKeyFromConfig(string $configKey, string $envKey): string
    {
        $value = config($configKey);

        if (! $value) {
            throw new RuntimeException("{$envKey} tidak ditemukan di ENV.");
        }

        $decoded = base64_decode($value, strict: true);

        return $decoded !== false ? $decoded : $value;
    }

    // =========================================================================
    // .env Management
    // =========================================================================

    /**
     * Periksa apakah sebuah key memiliki nilai non-kosong di konten .env.
     */
    private function envKeyExists(string $contents, string $key): bool
    {
        preg_match("/^{$key}=(.+)$/m", $contents, $match);

        return ! empty(trim($match[1] ?? ''));
    }

    /**
     * Update nilai key di .env jika baris sudah ada,
     * atau append baris baru ke akhir file jika belum ada.
     */
    private function writeEnvValue(string $contents, string $key, string $value): string
    {
        if (preg_match("/^{$key}=.*$/m", $contents)) {
            return preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $contents);
        }

        return rtrim($contents) . PHP_EOL . "{$key}={$value}" . PHP_EOL;
    }
}