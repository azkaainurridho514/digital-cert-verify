<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSiswa
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'siswa') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Siswa.');
        }

        return $next($request);
    }
}