<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateVerification;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function template()
    {
        return view('admin.template');
    }
    public function index()
    {
        // ===== CARD =====
        $totalSertifikat = Certificate::count();
        $totalSertifikatDiterbitkan = Certificate::where('status', 'Di Terbitkan')->count();
        $totalSertifikatDraft = Certificate::where('status', 'Draft')->count();
        $totalVerifikasi = CertificateVerification::count();

        // ===== SERTIFIKAT TERBARU (5) =====
        $latestCertificates = Certificate::orderByDesc('created_at')
            ->limit(10)
            ->get();

        // ===== VERIFIKASI TERBARU (10) =====
        $latestVerifications = CertificateVerification::with('certificate')
            ->orderByDesc('verified_at')
            ->limit(10)
            ->get()
            ->map(function ($v) {
                return [
                    'name'     => $v->certificate->username ?? '-',
                    'program'  => $v->certificate->program_name ?? '-',
                    'tanggal'  => \Carbon\Carbon::parse($v->verified_at)->format('d M Y'),
                    'result'           => $v->result,
                ];
            });

        return view('admin.dashboard', compact(
            'totalSertifikat',
            'totalSertifikatDiterbitkan',
            'totalSertifikatDraft',
            'totalVerifikasi',
            'latestCertificates',
            'latestVerifications'
        ));
    }
    
    public function sertifikat()
    {
        return view('admin.sertifikat');
    }

    public function verifikasi()
    {
        return view('admin.verifikasi');
    }

}