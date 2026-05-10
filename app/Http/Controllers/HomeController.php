<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\EcdsaService;
use App\Models\Certificate;
use App\Models\CertificateVerification;
use App\Constants\VerificationResult;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct(
        private EcdsaService $ecdsa
    ) {}
    public function index(){
        return view('index');
    }

    public function scannerHome(){
        return view('scanner');
    }

    public function scannerDashboarc(){
        return view('verifikasi');
    }

    // public function verifyQr(Request $request)
    // {
    //     $url = $request->qr_code;

    //     $parts = explode('/', $url);
    //     $id = end($parts);
    //     if (!Str::isUuid($id)) {
    //         return response()->json([
    //             'message' => VerificationResult::message(VerificationResult::QR_INVALID), 
    //             'data'=>[]], 
    //         400);
    //     }
    //     $certificate = Certificate::find($id);

    //     if (!$certificate) {
    //         return response()->json([
    //             'message' => VerificationResult::message(VerificationResult::NOT_FOUND), 
    //             'data'=>[]], 
    //         404);
    //     }
    //     $signatureResult = $this->ecdsa->verify($certificate->id, $certificate->digital_signature);
    //     if(!$signatureResult){
    //         return response()->json([
    //             'message' => VerificationResult::message(VerificationResult::VERIFY_FAILED), 
    //             'data'=>[]
    //             ], 
    //         401);
    //     }
    //     return response()->json([
    //         'message' => VerificationResult::message(VerificationResult::VALID),
    //         'data' => $certificate
    //     ], 200);
    // }

    public function verifyQr(Request $request)
    {
        $url   = $request->qr_code;
        $parts = explode('/', $url);
        $id    = end($parts);
        $now = Carbon::now();
        $geo     = geoip($request->ip());
        $address = collect([
            $geo->city,
            $geo->state,
            $geo->country,
        ])->filter()->implode(', ');

        if (!Str::isUuid($id)) {
            CertificateVerification::create([
                'certificate_id' => null,
                'verified_at'    => $now,
                'ip_address'     => $request->ip(),
                'address'        => $address ?: null,
                'device_info'    => $request->device_info,
                'result'         => VerificationResult::QR_INVALID,
            ]);
            return response()->json([
                'message' => VerificationResult::message(VerificationResult::QR_INVALID),
                'data'    => [],
            ], 400);
        }

        $certificate = Certificate::find($id);

        if (!$certificate) {
            CertificateVerification::create([
                'certificate_id' => null,
                'verified_at'    => $now,
                'ip_address'     => $request->ip(),
                'address'        => $address ?: null,
                'device_info'    => $request->device_info,
                'result'         =>  VerificationResult::NOT_FOUND,
            ]);
            return response()->json([
                'message' => VerificationResult::message(VerificationResult::NOT_FOUND),
                'data'    => [],
            ], 404);
        }

        try {
            $signatureResult = $this->ecdsa->verify($certificate->id, $certificate->digital_signature);
        } catch (RuntimeException $e) {
            $signatureResult = false;
        }

        $result = $signatureResult ? VerificationResult::VALID : VerificationResult::VERIFY_FAILED;
       
        CertificateVerification::create([
            'certificate_id' => $certificate->id,
            'verified_at'    => $now,
            'ip_address'     => $request->ip(),
            'address'        => $address ?: null,
            'device_info'    => $request->device_info,
            'result'         => $result,
        ]);

        if (!$signatureResult) {
            return response()->json([
                'message' => VerificationResult::message(VerificationResult::VERIFY_FAILED),
                'data'    => [],
            ], 401);
        }

        return response()->json([
            'message' => VerificationResult::message(VerificationResult::VALID),
            'data'    => $certificate,
        ], 200);
    }
}
