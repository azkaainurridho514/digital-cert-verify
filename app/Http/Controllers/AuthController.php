<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->with('login_error', 'Email atau password salah. Silakan coba lagi.');
        }

        $request->session()->regenerate();

        $role = Auth::user()->role;

        return match($role) {
            'admin'  => redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!'),
            'siswa'  => redirect()->route('siswa.dashboard')->with('success', 'Selamat datang, ' . Auth::user()->name . '!'),
            default  => redirect('/')->with('error', 'Role tidak dikenali.'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}