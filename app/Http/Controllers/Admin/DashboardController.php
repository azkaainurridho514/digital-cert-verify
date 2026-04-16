<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateVerification;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa       = User::where('role', 'siswa')->count();
        $totalSiswaMengikutiProgram  = User::where('role', 'siswa')
                    ->whereHas('certificates', function ($q) {
                        $q->whereIn('status', ['Draft', 'Di Proses']);
                    })
                    ->count();
        $totalSiswaTidakMengikutiProgram = User::where('role', 'siswa')
                    ->whereDoesntHave('certificates', function ($q) {
                        $q->whereIn('status', ['Draft', 'Di Proses']);
                    })
                    ->count();
        $totalSertifikat  = Certificate::where('status', 'Di Terbitkan')->count();

        $totalVerifikasi  = CertificateVerification::count();
        $verifikasiBerhasil = CertificateVerification::where('result', 'valid')->count();
        $verifikasiGagal  = CertificateVerification::where('result', 'invalid')->count();

        $aktivitas = CertificateVerification::with(['certificate.user', 'certificate.program'])
            ->where('verified_at', '>=', Carbon::now()->subDays(7))
            ->orderByDesc('verified_at')
            ->limit(10)
            ->get()
            ->map(fn($v) => [
                'name'        => $v->certificate->user->name       ?? '-',
                'program'     => $v->certificate->program->name    ?? '-',
                'tanggal'     => Carbon::parse($v->verified_at)->translatedFormat('d M Y'),
                'status'      => $v->result === 'valid' ? 'Terverifikasi'
                               : ($v->result === 'invalid' ? 'Ditolak' : 'Menunggu'),
            ]);

        $startOfMonth = Carbon::now()->startOfMonth();

        $newSiswa        = User::where('role', 'siswa')->where('created_at', '>=', $startOfMonth)->count();
        $newSiswaMengikutiProgram   = User::where('role', 'siswa')
                               ->whereHas('student', fn($q) => $q->where('status', 'Aktif'))
                               ->where('created_at', '>=', $startOfMonth)->count();
        $newSiswaTidakMengikutiProgram       = User::where('role', 'siswa')
                               ->whereHas('student', fn($q) => $q->where('status', 'Alumni'))
                               ->where('created_at', '>=', $startOfMonth)->count();
        $newSertifikat   = Certificate::where('status', 'Di Terbitkan')->where('created_at', '>=', $startOfMonth)->count();
        $newVerifikasi   = CertificateVerification::where('verified_at', '>=', $startOfMonth)->count();
        $newBerhasil     = CertificateVerification::where('result', 'valid')->where('verified_at', '>=', $startOfMonth)->count();
        $newGagal        = CertificateVerification::where('result', 'invalid')->where('verified_at', '>=', $startOfMonth)->count();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalSiswaMengikutiProgram',
            'totalSiswaTidakMengikutiProgram',
            'totalSertifikat',
            'totalVerifikasi',
            'verifikasiBerhasil',
            'verifikasiGagal',
            'aktivitas',
            'newSiswa',
            'newSiswaMengikutiProgram',
            'newSiswaTidakMengikutiProgram',
            'newSertifikat',
            'newVerifikasi',
            'newBerhasil',
            'newGagal',
        ));
    }

    public function allStudents()
    {
        return view('admin.siswa');
    }

    public function sertifikat()
    {
        return view('admin.sertifikat');
    }

    public function verifikasi()
    {
        return view('verifikasi');
    }
}