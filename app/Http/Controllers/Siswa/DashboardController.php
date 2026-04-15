<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalSertifikat      = Certificate::where('user_id', $user->id)->count();
        $sudahDiterbitkan     = Certificate::where('user_id', $user->id)
                                    ->where('status', 'Di Terbitkan')->count();
        $menungguProses       = Certificate::where('user_id', $user->id)
                                    ->where('status', 'Di Proses')->count();
        $sertifikat = Certificate::with('program')
                        ->where('user_id', $user->id)
                        ->whereIn('status', ['Di Terbitkan', 'Di Proses'])
                        ->orderByDesc('issued_date')
                        ->limit(5)
                        ->get();
 
        return view('siswa.dashboard', compact(
            'totalSertifikat',
            'sudahDiterbitkan',
            'menungguProses',
            'sertifikat'
        ));
    }
    public function sertifikat()
    {
        return view('siswa.sertifikat');
    }
    public function verifikasi()
    {
        return view('verifikasi');
    }
}