<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\EcdsaService;
use App\Services\QrCodeService;
use App\Models\CertificateSignature;

class SertifikatController extends Controller
{
    public function __construct(
        private EcdsaService $ecdsa,
        private QrCodeService $qrCodeService
    ) {}

    public function data(Request $request)
    {
        $search  = $request->search;
        $bulan   = $request->bulan;
        $tahun   = $request->tahun;
        $perPage = (int) $request->get('per_page', 10);

        $query = Certificate::query()
            ->when($search, function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                ->orWhere('program_name', 'like', "%$search%");
            })
            ->when($bulan, fn($q) => $q->whereMonth('created_at', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
            ->orderByDesc('created_at');

        $paginator = $query->paginate($perPage);

        $data = $paginator->map(fn($cert, $i) => [
            'no'                 => ($paginator->currentPage() - 1) * $paginator->perPage() + $i + 1,
            'id'                 => $cert->id,
            'username'           => $cert->username,
            'certificate_number' => $cert->certificate_number,
            'program_name'       => $cert->program_name ?? '-',
            'grade'              => $cert->grade ?? '-',
            'file_path'          => $cert->file_path ? asset($cert->file_path) : "",
            'description'        => $cert->description ?? '-',
            'publication_date' => $cert->publication_date 
            ? \Carbon\Carbon::parse($cert->publication_date)->translatedFormat('d F Y') 
            : null,
            'level'             => $cert->level,
            'status'             => $cert->status,
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
    
    public function store(Request $request)
    {
        $request->validate([
            'username'           => 'required|string',
            'program_name'       => 'nullable|string',
            'certificate_number' => 'nullable|string|unique:certificates,certificate_number',
            'grade'              => 'nullable|string|max:10',
            'level'              => 'nullable|string|max:50',
            'status'             => ['required', Rule::in(['Draft', 'Di Terbitkan'])],
            'description'        => 'nullable|string',
        ]);

        $data = [
            'username'           => $request->username,
            'program_name'       => $request->program_name,
            'certificate_number' => $request->certificate_number,
            'grade'              => $request->grade,
            'level'              => $request->level,
            'description'        => $request->description,
            'status'             => $request->status,
            'created_at'         => now(),
        ];

        $cert = Certificate::create($data);

        if ($request->status === 'Di Terbitkan') {
            $now = now();
            // $message = implode('|', [
            //     $request->certificate_number,
            //     $request->username,
            //     $request->program_name,
            //     $request->grade,
            //     $now->format('Y-m-d H:i:s'),
            // ]);
            $text = (string) $cert->id;
            $signature = $this->ecdsa->sign($text);
            $qr = $this->qrCodeService->generate($text);
            $cert->update([
                'file_path' => $qr['path'],
                'digital_signature' => $signature->signature,
                'publication_date'  => $now,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil dibuat.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $cert = Certificate::findOrFail($id);
        if ($cert->status === 'Di Terbitkan') {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat sudah diterbitkan dan tidak bisa diubah.'
            ], 422);
        }

        $request->validate([
            'username'           => 'required|string',
            'program_name'       => 'nullable|string',
            'certificate_number' => 'nullable|string|unique:certificates,certificate_number,' . $id,
            'grade'              => 'nullable|string|max:10',
            'level'              => 'nullable|string|max:50',
            'status'             => ['required', Rule::in(['Draft', 'Di Terbitkan'])],
            'description'        => 'nullable|string',
        ]);

        $dataUpdate = [
            'username'           => $request->username,
            'program_name'       => $request->program_name,
            'certificate_number' => $request->certificate_number,
            'grade'              => $request->grade,
            'level'              => $request->level,
            'description'        => $request->description,
            'status'             => $request->status,
        ];

        if ($request->status === 'Di Terbitkan') {

            $now = now();

            $message = implode('|', [
                $request->certificate_number,
                $request->username,
                $request->program_name,
                $request->grade,
                $now->format('Y-m-d H:i:s'),
            ]);

            $signature = $this->ecdsa->sign($message);
            $qr = $this->qrCodeService->generate($cert->id);

            $dataUpdate['file_path'] = $qr['path'];
            $dataUpdate['digital_signature'] = $signature->signature;
            $dataUpdate['publication_date']  = $now;
        }

        $cert->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil diperbarui.'
        ]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'string|exists:certificates,id',
            'status' => ['required', Rule::in(['Draft', 'Di Terbitkan'])],
        ]);

        DB::transaction(function () use ($request) {
            $certificates = Certificate::whereIn('id', $request->ids)->get();

            foreach ($certificates as $cert) {
                if ($cert->status === 'Di Terbitkan') continue;

                $dataUpdate = ['status' => $request->status];

                if ($request->status === 'Di Terbitkan') {
                    $now     = now();
                    // $message = implode('|', [
                    //     $cert->certificate_number,
                    //     $cert->username,
                    //     $cert->program_name,
                    //     $cert->grade,
                    //     $now->format('Y-m-d H:i:s'),
                    // ]);

                    $signature = $this->ecdsa->sign($cert->id);
                    $qr = $this->qrCodeService->generate($cert->id);

                    $dataUpdate['file_path'] = $qr['path'];
                    $dataUpdate['digital_signature'] = $signature->signature;
                    $dataUpdate['publication_date']  = $now;
                }

                $cert->update($dataUpdate);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Bulk update status berhasil.',
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'string|exists:certificates,id',
        ]);

        DB::transaction(function () use ($request) {
            Certificate::whereIn('id', $request->ids)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Bulk hapus berhasil.',
        ]);
    }

    public function show(string $id)
    {
        $cert = Certificate::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $cert
        ]);
    }

    // public function print(string $id)
    // {
    //     $cert = Certificate::findOrFail($id);

    //     if ($cert->status !== 'Di Terbitkan') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Sertifikat belum diterbitkan.',
    //         ], 403);
    //     }

    //     if (empty($cert->file_path) || !Storage::exists($cert->file_path)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'File sertifikat tidak ditemukan.',
    //         ], 404);
    //     }

    //     $filename = 'Sertifikat-' . ($cert->certificate_number ?? $cert->id) . '.pdf';

    //     return Storage::download($cert->file_path, $filename, [
    //         'Content-Type'        => 'application/pdf',
    //         'Content-Disposition' => 'inline; filename="' . $filename . '"',
    //     ]);
    // }

    public function print(string $id)
    {
        $cert = Certificate::findOrFail($id);

        if ($cert->status !== 'Di Terbitkan') {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat belum diterbitkan.',
            ], 403);
        }

        return view('certificates.template', [
            'nomor' => $cert->certificate_number,
            'nama' => $cert->username,
            'program' => $cert->program_name,
            'grade' => $cert->grade,
            'fileUrl' => asset('storage/' . $cert->file_path),
        ]);
    }

    public function destroy(string $id)
    {
        $cert = Certificate::findOrFail($id);
        if ($cert->file_path) {
            $filePath = public_path($cert->file_path);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $cert->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat dihapus.'
        ]);
    }

}