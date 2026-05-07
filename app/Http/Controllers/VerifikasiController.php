<?php

namespace App\Http\Controllers;

use App\Models\CertificateVerification; 
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function data(Request $request)
    {
        $search  = $request->search;
        $tahun   = $request->tahun;
        $perPage = (int) $request->get('per_page', 10);

        $query = CertificateVerification::with('certificate')
            ->select([
                'id',
                'certificate_id',
                'verified_at',
                'ip_address',
                'address',
                'device_info',
                'result',
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('ip_address', 'like', "%$search%")
                    ->orWhere('address',   'like', "%$search%")
                    ->orWhere('result',    'like', "%$search%")
                    ->orWhereHas('certificate', function ($q3) use ($search) {
                        $q3->where('username',           'like', "%$search%")
                            ->orWhere('certificate_number', 'like', "%$search%")
                            ->orWhere('program_name',       'like', "%$search%");
                    });
                });
            })
            ->when($tahun, fn($q) => $q->whereYear('verified_at', $tahun))
            ->orderByDesc('verified_at');

        $paginator = $query->paginate($perPage);

        $data = $paginator->map(fn($item, $i) => [
            'no'               => ($paginator->currentPage() - 1) * $paginator->perPage() + $i + 1,
            'id'               => $item->id,
            'certificate_number' => $item->certificate?->certificate_number ?? '-',
            'username'         => $item->certificate?->username           ?? '-',
            'program_name'     => $item->certificate?->program_name       ?? '-',
            'verified_at'      => $item->verified_at
                                    ? \Carbon\Carbon::parse($item->verified_at)->translatedFormat('d F Y H:i')
                                    : '-',
            'ip_address'       => $item->ip_address  ?? '-',
            'address'          => $item->address      ?? '-',
            'device_info'      => $item->device_info  ?? '-',
            'result'           => $item->result,
        ]);

        return response()->json([
            'data' => $data,
            'meta' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
}