<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\RealOutputEcdsaService;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * EcdsaController
 *
 * Exposes a single POST endpoint that signs a message with ECDSA and returns
 * the raw mathematical (r, s) integer pair alongside every intermediate step.
 *
 * Route (add to routes/api.php):
 *   Route::post('/ecdsa/sign', [EcdsaController::class, 'sign']);
 *
 * Example request:
 *   POST /api/ecdsa/sign
 *   Content-Type: application/json
 *   { "message": "AHU-0006744.AH.01.04", "k": 111 }
 */
class EcdsaController extends Controller
{
    public function __construct(
        private readonly RealOutputEcdsaService $ecdsaService
    ) {}

    // ── Endpoint ──────────────────────────────────────────────────────────────

    /**
     * POST /api/ecdsa/sign
     *
     * Signs the provided message and returns the ECDSA signature as pure
     * decimal integers (r, s) together with every computation step.
     */
    public function signRealOutput(): JsonResponse
    {
        // ── Hard-coded inputs (as per the task specification) ─────────────────
        $message = 'AHU-0006744.AH.01.04';
        $k       = 111;                     // Fixed nonce — educational use only

        // ── Sign ──────────────────────────────────────────────────────────────
        try {
            // $result = $this->ecdsaService->sign($message, random_int(101, 999));
            $result = $this->ecdsaService->sign($message, $k);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }

        // ── Format the response ───────────────────────────────────────────────
        return response()->json([
            'success' => true,

            // ── Step-by-step walkthrough ──────────────────────────────────────
            'steps' => [

                'step_1_message_and_hash' => [
                    'description' => 'Hash the message with SHA-256 and convert to an integer (e).',
                    'message'     => $result['message'],
                    'sha256_hex'  => $result['hash_hex'],
                    'e_decimal'   => $result['e_decimal'],
                ],

                'step_2_curve_and_key' => [
                    'description' => 'secp256k1 elliptic curve parameters and the signer\'s private key.',
                    'curve_name'  => $result['curve']['name'],
                    'p'           => $result['curve']['p'],
                    'a'           => $result['curve']['a'],
                    'b'           => $result['curve']['b'],
                    'G_x'         => $result['curve']['Gx'],
                    'G_y'         => $result['curve']['Gy'],
                    'n'           => $result['curve']['n'],
                    'd_private_key' => $result['d_decimal'],
                ],

                'step_3_nonce_and_R' => [
                    'description' => 'Choose nonce k and compute R = k·G via elliptic-curve scalar multiplication.',
                    'k_nonce'     => $result['k'],
                    'R_x'         => $result['R']['x'],
                    'R_y'         => $result['R']['y'],
                ],

                'step_4_r' => [
                    'description' => 'r = R.x mod n   (x-coordinate of R reduced modulo the group order)',
                    'r'           => $result['r'],
                ],

                'step_5_s' => [
                    'description' => 's = k⁻¹ · (e + r·d) mod n',
                    'k_inverse'   => $result['k_inv'],
                    's'           => $result['s'],
                ],
            ],

            // ── Final Signature ───────────────────────────────────────────────
            // Pure decimal integers — no hex, no Base64, no DER, no ASN.1
            'signature' => [
                'format' => 'raw integer pair (r, s)',
                'r'      => $result['r'],
                's'      => $result['s'],
            ],
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function signSameMessage(): JsonResponse
    {
        return response()->json([], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    public function signRealImplement(): JsonResponse
    {
        return response()->json([], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}