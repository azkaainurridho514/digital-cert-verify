<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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