<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SertifikatController extends Controller
{
   public function data(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);

        $query = Certificate::with('program')
            ->where('user_id', $user->id);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('grade', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereHas('program', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $paginator = $query->orderByDesc('issued_date')->paginate($perPage);

        $data = collect($paginator->items())->map(function ($cert, $index) use ($paginator) {
            return [
                'no' => ($paginator->currentPage() - 1) * $paginator->perPage() + $index + 1,
                'id' => $cert->id,
                'certificate_number' => $cert->certificate_number ?? '-',
                'grade' => $cert->grade ?? '-',
                'program_name' => $cert->program->name ?? '-',
                'level' => $cert->level ?? '-',
                'description' => $cert->description ?? '-',
                'issued_date' => $cert->issued_date
                    ? \Carbon\Carbon::parse($cert->issued_date)->translatedFormat('d M Y')
                    : '-',
                'status' => $cert->status ?? 'Draft',
                'has_file' => !empty($cert->file_path),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
    public function download(string $id): StreamedResponse|\Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $cert = Certificate::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        if ($cert->status !== 'Di Terbitkan') {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat belum dapat diunduh.',
            ], 403);
        }

        if (empty($cert->file_path) || !Storage::exists($cert->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File sertifikat tidak ditemukan.',
            ], 404);
        }

        $filename = 'Sertifikat-' . ($cert->certificate_number ?? $cert->id) . '.pdf';

        return Storage::download($cert->file_path, $filename, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}