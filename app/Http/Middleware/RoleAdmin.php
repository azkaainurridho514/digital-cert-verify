<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAdmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}