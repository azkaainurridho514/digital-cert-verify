<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index()
    {
        return view('admin.siswa');
    }
    // public function data(Request $request)
    // {
    //     $query = User::with('student')
    //         ->where('role', 'siswa')
    //         ->orderByDesc('created_at');
    //     if ($search = $request->input('search')) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name',  'like', "%{$search}%")
    //               ->orWhere('email', 'like', "%{$search}%")
    //               ->orWhere('phone', 'like', "%{$search}%")
    //               ->orWhereHas('student', fn($s) =>
    //                   $s->where('nis', 'like', "%{$search}%")
    //               );
    //         });
    //     }
    //     if ($tahun = $request->input('tahun')) {
    //         preg_match('/(\d{4})/', $tahun, $m);
    //         if (!empty($m[1])) {
    //             $query->whereYear('created_at', $m[1]);
    //         }
    //     }

    //     $users = $query->get();

    //     $data = $users->map(function ($user, $index) {
    //         return [
    //             'no'         => $index + 1,
    //             'id'         => $user->id,
    //             'nis'        => $user->student->nis    ?? '-',
    //             'name'       => $user->name,
    //             'email'      => $user->email,
    //             'phone'      => $user->phone            ?? '-',
    //             'address'    => $user->address          ?? '-',
    //             'photo'      => $user->photo
    //                 ? Storage::url($user->photo)
    //                 : null,
    //             'joined_at'  => $user->created_at->translatedFormat('d M Y'),
    //             // 'status'     => $user->student->status ?? 'Aktif', 
    //         ];
    //     });

    //     return response()->json(['success' => true, 'data' => $data]);
    // }

    public function data(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $query = User::with('student')
            ->where('role', 'siswa')
            ->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('student', fn($s) =>
                    $s->where('nis', 'like', "%{$search}%")
                );
            });
        }

        if ($tahun = $request->input('tahun')) {
            preg_match('/(\d{4})/', $tahun, $m);
            if (!empty($m[1])) {
                $query->whereYear('created_at', $m[1]);
            }
        }

        $paginator = $query->paginate($perPage);

        $data = $paginator->map(function ($user, $index) use ($paginator) {
            return [
                'no'        => ($paginator->currentPage() - 1) * $paginator->perPage() + $index + 1,
                'id'        => $user->id,
                'nis'       => $user->student->nis ?? '-',
                'name'      => $user->name,
                'email'     => $user->email,
                'phone'     => $user->phone         ?? '-',
                'address'   => $user->address       ?? '-',
                'photo'     => $user->photo
                    ? Storage::url($user->photo)
                    : null,
                'joined_at' => $user->created_at->translatedFormat('d M Y'),
                'status'    => $user->student->status ?? '-',
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'required|string|unique:students,nis',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'address'  => 'nullable|string',
            'photo'    => 'nullable|image|max:2048',
        ]);
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos/siswa', 'public');
        }
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'address'  => $request->address,
            'photo'    => $photoPath,
            'role'     => 'siswa',
        ]);
        $user->student()->create([
            'nis'        => $request->nis,
            'program_id' => null,
        ]);

        return response()->json(['success' => true, 'message' => 'Siswa berhasil ditambahkan.']);
    }
    public function show(string $id)
    {
        $user = User::with('student')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'      => $user->id,
                'nis'     => $user->student->nis ?? '',
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone   ?? '',
                'address' => $user->address ?? '',
                'photo'   => $user->photo ? Storage::url($user->photo) : null,
            ],
        ]);
    }
    public function edit(string $id)
    {
        return $this->show($id);
    }
    public function update(Request $request, string $id)
    {
        $user = User::with('student')->findOrFail($id);

        $request->validate([
            'nis'   => ['required', 'string', Rule::unique('students', 'nis')->ignore($user->student->id ?? 0)],
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'photo'    => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $user->photo = $request->file('photo')->store('photos/siswa', 'public');
        }

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->address = $request->address;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        if ($user->student) {
            $user->student->update(['nis' => $request->nis]);
        }

        return response()->json(['success' => true, 'message' => 'Data siswa berhasil diperbarui.']);
    }
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->photo) Storage::disk('public')->delete($user->photo);

        $user->student()->delete();
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Siswa berhasil dihapus.']);
    }
}