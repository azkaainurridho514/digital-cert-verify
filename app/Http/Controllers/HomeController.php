<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\EcdsaService;
use App\Models\Certificate;
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

    public function verifyQr(Request $request)
    {
        $url = $request->qr_code;

        $parts = explode('/', $url);
        $id = end($parts);
        if (!Str::isUuid($id)) {
            return response()->json([
                'message' => VerificationResult::message(VerificationResult::QR_INVALID), 
                'data'=>[]], 
            400);
        }
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return response()->json([
                'message' => VerificationResult::message(VerificationResult::NOT_FOUND), 
                'data'=>[]], 
            404);
        }
        $issuedDate = Carbon::parse($certificate->issued_date);
        $message = implode('|', [
            $certificate->certificate_number,
            $certificate->user->name ?? '-',
            $certificate->grade ?? '-',
            $certificate->program->name ?? '-',
            $issuedDate->format('Y-m-d H:i:s'),
        ]);

        $signatureResult = $this->ecdsa->verify($message, $certificate->signature->signatures, $certificate->signature->public_key);
        if(!$signatureResult){
            return response()->json([
                'message' => VerificationResult::message(VerificationResult::VERIFY_FAILED), 
                'data'=>[]
                ], 
            401);
        }
        return response()->json([
            'message' => VerificationResult::message(VerificationResult::VALID),
            'data' => $certificate
        ], 200);
    }
}
