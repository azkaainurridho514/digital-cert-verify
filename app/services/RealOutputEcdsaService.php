<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * RealOutputEcdsaService
 *
 * Implements ECDSA signing on the secp256k1 elliptic curve using PHP's GMP
 * extension.  All intermediate values are kept as arbitrary-precision integers
 * so the final (r, s) pair is returned as plain decimal integer strings — no
 * hex, no Base64, no DER / ASN.1 wrapping.
 *
 * Curve : secp256k1  (same curve used by Bitcoin / Ethereum)
 * Hash  : SHA-256
 * Nonce : fixed k = 111  (for deterministic, educational demonstration ONLY)
 *
 * ⚠  SECURITY WARNING ─────────────────────────────────────────────────────
 *  Using a fixed nonce in production leaks the private key immediately.
 *  Always use a cryptographically random nonce (RFC 6979) in real systems.
 * ─────────────────────────────────────────────────────────────────────────
 */
final class RealOutputEcdsaService
{
    // ── secp256k1 Domain Parameters ──────────────────────────────────────────

    /** Prime field modulus p */
    private readonly \GMP $p;

    /** Curve coefficient a (= 0 for secp256k1) */
    private readonly \GMP $a;

    /** Curve coefficient b (= 7 for secp256k1) */
    private readonly \GMP $b;

    /** Generator point G — x coordinate */
    private readonly \GMP $Gx;

    /** Generator point G — y coordinate */
    private readonly \GMP $Gy;

    /** Order of the generator point (group order n) */
    private readonly \GMP $n;

    // ── Private Key ──────────────────────────────────────────────────────────

    /** Signer's private key d (fixed for demonstration) */
    private readonly \GMP $d;

    // ─────────────────────────────────────────────────────────────────────────

    public function __construct()
    {
        if (!extension_loaded('gmp')) {
            throw new RuntimeException(
                'The PHP GMP extension is required. Enable it in php.ini: extension=gmp'
            );
        }

        /*
         * secp256k1 parameters (all values are hex strings).
         *
         *  y² = x³ + 7  (mod p)
         *
         *  Ref: https://www.secg.org/sec2-v2.pdf — Section 2.4.1
         */
        $this->p  = gmp_init('FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFEFFFFFC2F', 16);
        $this->a  = gmp_init('0', 10);   // a = 0
        $this->b  = gmp_init('7', 10);   // b = 7
        $this->Gx = gmp_init('79BE667EF9DCBBAC55A06295CE870B07029BFCDB2DCE28D959F2815B16F81798', 16);
        $this->Gy = gmp_init('483ADA7726A3C4655DA4FBFC0E1108A8FD17B448A68554199C47D08FFB10D4B8', 16);
        $this->n  = gmp_init('FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFEBAAEDCE6AF48A03BBFD25E8CD0364141', 16);

        /*
         * Private key d — fixed demo value.
         * In production generate this with: openssl ecparam -genkey -name secp256k1
         */
        $this->d = gmp_init('18E14A7B6A307F426A94F8114701E7C8E774E7F9A47E2C2035DB29A206321725', 16);
    }

    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Sign a message with ECDSA and return the raw (r, s) integer pair together
     * with every intermediate step for educational inspection.
     *
     * @param  string $message  UTF-8 plaintext to sign
     * @param  int    $k        Nonce value (use 111 for this demo)
     * @return array{
     *     message:    string,
     *     hash_hex:   string,
     *     e_decimal:  string,
     *     k:          int,
     *     R:          array{x: string, y: string},
     *     r:          string,
     *     k_inv:      string,
     *     s:          string,
     *     curve:      array<string, string>,
     *     d_decimal:  string,
     * }
     */
    public function sign(string $message, int $k = 111): array
    {
        // ── Step 1 : Hash the message ─────────────────────────────────────────
        //
        //  e = SHA-256(message)  converted to an integer
        //
        $hashHex = hash('sha256', $message);               // 256-bit hex string
        $e       = gmp_init($hashHex, 16);                 // interpret as big integer

        // ── Step 2 : Validate the nonce ───────────────────────────────────────
        //
        //  k must satisfy  1 ≤ k ≤ n-1
        //
        $kGmp = gmp_init($k);
        if (gmp_cmp($kGmp, 1) < 0 || gmp_cmp($kGmp, gmp_sub($this->n, 1)) > 0) {
            throw new RuntimeException('Nonce k must be in the range [1, n-1].');
        }

        // ── Step 3 : Compute R = k · G ───────────────────────────────────────
        //
        //  Scalar multiplication of the generator point G by the nonce k.
        //  R is a point on the curve with coordinates (Rx, Ry).
        //
        $G = [$this->Gx, $this->Gy];
        $R = $this->scalarMult($kGmp, $G);

        if ($R === null) {
            throw new RuntimeException('k·G resulted in the point at infinity; choose a different k.');
        }

        [$Rx, $Ry] = $R;

        // ── Step 4 : Compute r ───────────────────────────────────────────────
        //
        //  r = Rx mod n
        //
        //  If r == 0, ECDSA requires a different k.  With k = 111 this never
        //  happens on secp256k1.
        //
        $r = gmp_mod($Rx, $this->n);

        if (gmp_cmp($r, 0) === 0) {
            throw new RuntimeException('r = 0; the chosen nonce k is invalid for this curve.');
        }

        // ── Step 5 : Compute s ───────────────────────────────────────────────
        //
        //  s = k⁻¹ · (e + r·d)  mod n
        //
        //  where:
        //    k⁻¹  = modular inverse of k modulo n
        //    e    = integer representation of the message hash
        //    r    = x-coordinate computed above
        //    d    = signer's private key
        //
        $kInv = $this->modInverse($kGmp, $this->n);   // k⁻¹ mod n
        $rd   = gmp_mod(gmp_mul($r, $this->d), $this->n);  // r·d mod n
        $erd  = gmp_mod(gmp_add($e, $rd), $this->n);        // (e + r·d) mod n
        $s    = gmp_mod(gmp_mul($kInv, $erd), $this->n);    // s mod n

        if (gmp_cmp($s, 0) === 0) {
            throw new RuntimeException('s = 0; the chosen nonce k is invalid for this curve.');
        }

        // ── Return all steps + final signature ───────────────────────────────
        return [
            // Input
            'message'   => $message,

            // Step 1 — hashing
            'hash_hex'  => $hashHex,
            'e_decimal' => gmp_strval($e, 10),

            // Step 2 — nonce
            'k'         => $k,

            // Step 3 — point R
            'R'         => [
                'x' => gmp_strval($Rx, 10),
                'y' => gmp_strval($Ry, 10),
            ],

            // Step 4 — r
            'r'         => gmp_strval($r, 10),

            // Step 5 intermediates
            'k_inv'     => gmp_strval($kInv, 10),

            // Step 5 — s
            's'         => gmp_strval($s, 10),

            // Reference — curve info
            'curve'     => [
                'name' => 'secp256k1',
                'p'    => gmp_strval($this->p, 10),
                'a'    => gmp_strval($this->a, 10),
                'b'    => gmp_strval($this->b, 10),
                'Gx'   => gmp_strval($this->Gx, 10),
                'Gy'   => gmp_strval($this->Gy, 10),
                'n'    => gmp_strval($this->n, 10),
            ],

            // Reference — private key
            'd_decimal' => gmp_strval($this->d, 10),
        ];
    }

    // ── Elliptic Curve Arithmetic ─────────────────────────────────────────────

    /**
     * Point addition on secp256k1: P + Q
     *
     * Formula (affine coordinates, P ≠ Q):
     *   λ  = (y₂ − y₁) · (x₂ − x₁)⁻¹  mod p
     *   x₃ = λ² − x₁ − x₂             mod p
     *   y₃ = λ·(x₁ − x₃) − y₁         mod p
     *
     * @param  array{0:\GMP,1:\GMP}|null $P
     * @param  array{0:\GMP,1:\GMP}|null $Q
     * @return array{0:\GMP,1:\GMP}|null  Resulting curve point, or null = ∞
     */
    private function pointAdd(?array $P, ?array $Q): ?array
    {
        if ($P === null) {
            return $Q;          // ∞ + Q = Q
        }
        if ($Q === null) {
            return $P;          // P + ∞ = P
        }

        [$x1, $y1] = $P;
        [$x2, $y2] = $Q;

        // Same x-coordinate?
        if (gmp_cmp($x1, $x2) === 0) {
            // Inverse point  →  P + (-P) = ∞
            if (gmp_cmp($y1, $y2) !== 0) {
                return null;
            }
            // Same point  →  use doubling formula
            return $this->pointDouble($P);
        }

        // General addition
        $dy     = gmp_mod(gmp_sub($y2, $y1), $this->p);
        $dx     = gmp_mod(gmp_sub($x2, $x1), $this->p);
        $lambda = gmp_mod(gmp_mul($dy, $this->modInverse($dx, $this->p)), $this->p);

        $x3 = gmp_mod(gmp_sub(gmp_sub(gmp_mul($lambda, $lambda), $x1), $x2), $this->p);
        $y3 = gmp_mod(gmp_sub(gmp_mul($lambda, gmp_sub($x1, $x3)), $y1), $this->p);

        return [$x3, $y3];
    }

    /**
     * Point doubling on secp256k1: P + P = 2P
     *
     * Formula (affine coordinates, a = 0 for secp256k1):
     *   λ  = 3·x₁² · (2·y₁)⁻¹  mod p
     *   x₃ = λ² − 2·x₁          mod p
     *   y₃ = λ·(x₁ − x₃) − y₁  mod p
     *
     * @param  array{0:\GMP,1:\GMP}|null $P
     * @return array{0:\GMP,1:\GMP}|null
     */
    private function pointDouble(?array $P): ?array
    {
        if ($P === null) {
            return null;
        }

        [$x1, $y1] = $P;

        // λ = (3·x₁² + a) / (2·y₁)  mod p
        // Since a = 0 for secp256k1, simplifies to (3·x₁²) / (2·y₁)
        $three      = gmp_init('3');
        $two        = gmp_init('2');
        $numerator  = gmp_mod(gmp_add(gmp_mul($three, gmp_mul($x1, $x1)), $this->a), $this->p);
        $denominator = gmp_mod(gmp_mul($two, $y1), $this->p);
        $lambda     = gmp_mod(gmp_mul($numerator, $this->modInverse($denominator, $this->p)), $this->p);

        $x3 = gmp_mod(gmp_sub(gmp_sub(gmp_mul($lambda, $lambda), $x1), $x1), $this->p);
        $y3 = gmp_mod(gmp_sub(gmp_mul($lambda, gmp_sub($x1, $x3)), $y1), $this->p);

        return [$x3, $y3];
    }

    /**
     * Scalar multiplication: k · P
     *
     * Uses the "double-and-add" algorithm (left-to-right binary method).
     * Each bit of k (LSB first) decides whether to add the current addend.
     *
     * Time complexity: O(log k) point doublings + O(log k / 2) point additions
     *
     * @param  \GMP                       $k  Scalar multiplier
     * @param  array{0:\GMP,1:\GMP}       $P  Starting curve point
     * @return array{0:\GMP,1:\GMP}|null      Result, or null if k ≡ 0 mod n
     */
    private function scalarMult(\GMP $k, array $P): ?array
    {
        $result = null;   // Start with the point at infinity (identity element)
        $addend = $P;     // Current doubling of P

        while (gmp_cmp($k, 0) > 0) {
            // If the least-significant bit of k is set, add current addend
            if (gmp_testbit($k, 0)) {
                $result = $this->pointAdd($result, $addend);
            }

            // Double the current addend
            $addend = $this->pointDouble($addend);

            // Shift k right by one bit (k = k >> 1)
            $k = gmp_div_q($k, gmp_init('2'));
        }

        return $result;
    }

    /**
     * Compute the modular inverse of $a modulo $m using PHP's built-in
     * Extended Euclidean implementation via gmp_invert().
     *
     * Satisfies:  a · a⁻¹ ≡ 1  (mod m)
     *
     * @throws RuntimeException if gcd(a, m) ≠ 1 (inverse does not exist)
     */
    private function modInverse(\GMP $a, \GMP $m): \GMP
    {
        $inv = gmp_invert($a, $m);

        if ($inv === false) {
            throw new RuntimeException(
                sprintf(
                    'Modular inverse does not exist: gcd(%s, %s) ≠ 1',
                    gmp_strval($a),
                    gmp_strval($m)
                )
            );
        }

        return $inv;
    }
}