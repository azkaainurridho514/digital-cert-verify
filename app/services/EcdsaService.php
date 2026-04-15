<?php

namespace App\Services;

use App\DTOs\SignatureResult;
use RuntimeException;

final class EcdsaService
{
    private const CURVE = 'prime256v1';
    private const ALGO = OPENSSL_ALGO_SHA256;
    private const ENV_KEY_NAME = 'ECDSA_PRIVATE_KEY';
    public function __construct()
    {
        $this->ensureKeyExists();
    }

    public function sign(string $message): SignatureResult
    {
        $privateKey = $this->loadPrivateKey();

        $binarySignature = '';
        $success = openssl_sign($message, $binarySignature, $privateKey, self::ALGO);

        if (! $success) {
            throw new RuntimeException('ECDSA signing gagal: ' . openssl_error_string());
        }

        $signature = base64_encode($binarySignature);
        $publicKey = $this->derivePublicKey($privateKey);

        return new SignatureResult(
            signature: $signature,
            publicKey: $publicKey,
        );
    }

    public function verify(string $message, string $signature, string $publicKey): bool
    {
        $binarySignature = base64_decode($signature, strict: true);

        if ($binarySignature === false) {
            throw new RuntimeException('Signature bukan format base64 yang valid.');
        }

        $pubKeyResource = openssl_get_publickey($publicKey);

        if ($pubKeyResource === false) {
            throw new RuntimeException('Public key tidak valid: ' . openssl_error_string());
        }

        $result = openssl_verify($message, $binarySignature, $pubKeyResource, self::ALGO);

        return match ($result) {
            1       => true,  
            0       => false,  
            default => throw new RuntimeException('Verifikasi error: ' . openssl_error_string()),
        };
    }
    public function generateKeyPair(): array
    {
        $keyResource = openssl_pkey_new($this->opensslConfig());

        if ($keyResource === false) {
            throw new RuntimeException('Gagal generate ECDSA key: ' . openssl_error_string());
        }

        openssl_pkey_export($keyResource, $privateKeyPem);

        return [
            'private_key' => $privateKeyPem,
        ];
    }

    // menggunakan cache
    // private function ensureKeyExists(): void
    // {
    //     $existing = config('ecdsa.private_key') ?? env(self::ENV_KEY_NAME);
    
    //     if (!empty($existing)) {
    //         return;
    //     }
    
    //     $keyPair = $this->generateKeyPair();
    //     $this->writePrivateKeyToEnv($keyPair['private_key']);
    
    //     config(['ecdsa.private_key' => $keyPair['private_key']]);
    // }

    // tidak menggunakan cache
    private function ensureKeyExists(): void
    {
        $envPath  = base_path('.env');
        $contents = file_get_contents($envPath);

        preg_match('/^ECDSA_PRIVATE_KEY=(.*)$/m', $contents, $matches);
        $existing = $matches[1] ?? null;

        if (!empty($existing)) {
            config(['ecdsa.private_key' => base64_decode($existing) ?: $existing]);
            return;
        }

        $keyPair = $this->generateKeyPair();
        $this->writePrivateKeyToEnv($keyPair['private_key']);
        config(['ecdsa.private_key' => $keyPair['private_key']]);
    }
    private function writePrivateKeyToEnv(string $privateKeyPem): void
    {
        $envPath  = base_path('.env');
        $encoded  = base64_encode($privateKeyPem);
        $envLine  = self::ENV_KEY_NAME . '=' . $encoded;

        if (! file_exists($envPath)) {
            throw new RuntimeException('.env file tidak ditemukan.');
        }

        $contents = file_get_contents($envPath);

        if (str_contains($contents, self::ENV_KEY_NAME . '=')) {
            $contents = preg_replace(
                '/^' . self::ENV_KEY_NAME . '=.*/m',
                $envLine,
                $contents
            );
        } else {
            $contents .= PHP_EOL . $envLine . PHP_EOL;
        }

        file_put_contents($envPath, $contents);
    }
    private function loadPrivateKey(): \OpenSSLAsymmetricKey
    {
        $encoded = config('ecdsa.private_key') ?? env(self::ENV_KEY_NAME);

        if (empty($encoded)) {
            throw new RuntimeException('ECDSA private key tidak tersedia.');
        }

        $pem = base64_decode($encoded, strict: true);
        if ($pem === false) {
            $pem = $encoded;
        }

        $key = openssl_pkey_get_private($pem);

        if ($key === false) {
            throw new RuntimeException('Gagal load private key: ' . openssl_error_string());
        }

        return $key;
    }

    private function derivePublicKey(\OpenSSLAsymmetricKey $privateKey): string
    {
        $details = openssl_pkey_get_details($privateKey);

        if ($details === false) {
            throw new RuntimeException('Gagal mengambil detail key: ' . openssl_error_string());
        }

        return $details['key']; 
    }

    private function opensslConfig(): array
    {
        return [
            'curve_name'       => self::CURVE,
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ];
    }
}