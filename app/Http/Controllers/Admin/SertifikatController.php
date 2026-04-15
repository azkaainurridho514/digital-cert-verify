<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SertifikatController extends Controller
{
    public function index()
    {
        return view('admin.sertifikat');
    }

    public function data(Request $request)
    {
        $search  = $request->search;
        $tahun   = $request->tahun;
        $perPage = (int) $request->get('per_page', 10);

        $query = Certificate::with(['user', 'program'])
            ->when($search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$search%"))
                                        ->orWhere('certificate_number', 'like', "%$search%"))
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->latest();

        $paginator = $query->paginate($perPage);

        $data = $paginator->map(fn($cert, $i) => [
            'no'                 => ($paginator->currentPage() - 1) * $paginator->perPage() + $i + 1,
            'id'                 => $cert->id,
            'user_name'          => $cert->user->name,
            'user_id'            => $cert->user->id,
            'certificate_number' => $cert->certificate_number,
            'grade'              => $cert->grade     ?? '-',
            'program_name'       => $cert->program->name  ?? '-',
            'level'              => $cert->level ?? '-',
            'description'        => $cert->description    ?? '-',
            'issued_date'        => $cert->issued_date    ?? '-',
            'status'             => $cert->status,
            'has_file'           => $cert->has_file,
        ]);

        return response()->json([
            'data' => $data,
            'meta' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ]);
    }

    public function searchStudents(Request $request)
    {
        $search = $request->input('search', '');

        $students = User::where('role', 'siswa')
            ->where(fn($q) => $q->where('name',  'like', "%{$search}%")
                                ->orWhere('email','like', "%{$search}%"))
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($students);
    }

    public function searchPrograms(Request $request)
    {
        $search = $request->input('search', '');

        $programs = Program::where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->select('id', 'name', 'code')
            ->limit(10)
            ->get();

        return response()->json($programs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'program_id'        => 'nullable|exists:programs,id',
            'certificate_number'=> 'nullable|string',
            'grade'             => 'nullable|string|max:10',
            'description'       => 'nullable|string',
            'level'             => 'nullable|string',
            'status'            => ['nullable', Rule::in(['Draft', 'Di Proses', 'Di Terbitkan'])],
        ]);

        Certificate::create([
            'user_id'            => $request->user_id,
            'program_id'         => $request->program_id ?? "",
            'certificate_number' => $request->certificate_number ?? "",
            'level'              => $request->level ?? "",
            'grade'              => $request->grade ?? "",
            'description'        => $request->description ?? "",
            'status'             => $request->status ?? "Draft",
            'created_by'         => Auth::user()->id,
        ]);
        $user = User::findOrFail($request->user_id);
        $user->update(['status' => true]);

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil dibuat.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(['Draft', 'Di Proses', 'Di Terbitkan'])],
            'user_id' => 'required|exists:users,id',
            'program_id' => 'nullable|required_if:status,Di Terbitkan|exists:programs,id',
            'grade' => 'nullable|required_if:status,Di Terbitkan|string|max:10',
            'level' => 'nullable|required_if:status,Di Terbitkan|string|max:10',
            'description' => 'nullable|string',
            'certificate_number' => 'nullable|required_if:status,Di Terbitkan',
        ]);
        
       
        $sertifikat = Certificate::findOrFail($id);
        $dataUpdate = collect($request->only([
            'status',
            'user_id',
            'program_id',
            'grade',
            'level',
            'description',
            'certificate_number',
        ]))
        ->filter(function ($value) {
            return !is_null($value) && $value !== '';
        })
        ->toArray();

        if ($request->status == "Di Terbitkan") {
            $dataUpdate['issued_date'] = Carbon::now();
            $user = User::findOrFail($request->user_id);
            $user->update(['status' => false]);
        }
        $sertifikat->update($dataUpdate);
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
            'data'    => $sertifikat
        ]);
    }
    

    public function show(string $id)
    {
        $cert = Certificate::with(['user', 'program'])->findOrFail($id);
        $programs = Program::select('id', 'name', 'code')->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                 => $cert->id,
                'user_name'          => $cert->user->name           ?? '-',
                'user_email'         => $cert->user->email          ?? '-',
                'certificate_number' => $cert->certificate_number   ?? '-',
                'grade'              => $cert->grade                ?? '-',
                'level'              => $cert->level                ?? '-',
                'program_name'       => $cert->program->name        ?? '-',
                'program_code'       => $cert->program->code        ?? '-',
                'description'        => $cert->description          ?? '-',
                'issued_date'        => $cert->issued_date
                    ? Carbon::parse($cert->issued_date)->translatedFormat('d M Y')
                    : '-',
                'status'             => $cert->status               ?? 'Draft',
                'has_file'           => !empty($cert->file_path),
            ],
            'programs' => $programs
        ]);
    }

    public function print(string $id)
    {
        $cert = Certificate::findOrFail($id);

        if ($cert->status !== 'Di Terbitkan') {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat belum diterbitkan.',
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
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }


    public function destroy(string $id)
    {
        Certificate::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Sertifikat dihapus.']);
    }

    private function generateCertNumber(int $programId): string
    {
        $program = Program::find($programId);
        $code    = $program ? strtoupper($program->code) : 'GEN';
        $year    = now()->year;
        $last    = Certificate::whereYear('created_at', $year)->count() + 1;

        return sprintf('CERT-%s-%d-%04d', $code, $year, $last);
    }
}