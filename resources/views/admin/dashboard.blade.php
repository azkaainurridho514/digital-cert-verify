@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <h4>Dashboard Admin</h4>
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Berikut ringkasan sistem hari ini.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-4 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value">{{ $totalSiswa ?? 248 }}</div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i> +12 bulan ini
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="stat-value">{{ $totalSertifikat ?? 186 }}</div>
            <div class="stat-label">Sertifikat Diterbitkan</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i> +8 minggu ini
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value">{{ $menungguVerifikasi ?? 14 }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
            <div class="stat-change down">
                <i class="bi bi-arrow-down-short"></i> -3 hari ini
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="stat-value">{{ $terverifikasi ?? 172 }}</div>
            <div class="stat-label">Terverifikasi</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i> +5 hari ini
            </div>
        </div>
    </div>

</div>

{{-- Row 2: Recent Activity + Quick Actions --}}
<div class="row g-4">

    {{-- Aktivitas Terbaru --}}
    <div class="col-lg-8">
        <div class="card-modern">
            <div class="card-header-modern">
                <div>
                    <h6 class="mb-0 fw-bold" style="font-size: 15px;">Aktivitas Terbaru</h6>
                    <span class="text-muted" style="font-size: 12px;">7 hari terakhir</span>
                </div>
                <a href="#" class="btn btn-sm btn-outline-primary rounded-3" style="font-size: 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="p-0">
                <table class="table table-hover mb-0" style="font-size: 13px;">
                    <thead>
                        <tr class="table-light">
                            <th class="px-4 py-3 fw-semibold text-secondary">Nama Siswa</th>
                            <th class="py-3 fw-semibold text-secondary">Sertifikat</th>
                            <th class="py-3 fw-semibold text-secondary">Tanggal</th>
                            <th class="py-3 fw-semibold text-secondary">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $activities = [
                            ['Ahmad Fadillah', 'TOEFL Preparation', '09 Jan 2025', 'Terverifikasi'],
                            ['Siti Rahayu', 'English for Business', '08 Jan 2025', 'Menunggu'],
                            ['Budi Santoso', 'Conversation Class', '07 Jan 2025', 'Terverifikasi'],
                            ['Dewi Lestari', 'IELTS Intensive', '07 Jan 2025', 'Ditolak'],
                            ['Reza Pratama', 'Grammar Mastery', '06 Jan 2025', 'Terverifikasi'],
                        ];
                        @endphp

                        @foreach($activities as $act)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                        {{ strtoupper(substr($act[0], 0, 2)) }}
                                    </div>
                                    {{ $act[0] }}
                                </div>
                            </td>
                            <td class="py-3">{{ $act[1] }}</td>
                            <td class="py-3 text-muted">{{ $act[2] }}</td>
                            <td class="py-3">
                                @if($act[3] === 'Terverifikasi')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i>{{ $act[3] }}
                                    </span>
                                @elseif($act[3] === 'Menunggu')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3">
                                        <i class="bi bi-clock-fill me-1"></i>{{ $act[3] }}
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                                        <i class="bi bi-x-circle-fill me-1"></i>{{ $act[3] }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                <h6 class="mb-0 fw-bold" style="font-size: 15px;">Aksi Cepat</h6>
            </div>
            <div class="p-3 d-flex flex-column gap-2">
                <a href="{{ route('admin.siswa') }}" class="btn btn-outline-primary w-100 text-start rounded-3 py-2">
                    <i class="bi bi-person-plus-fill me-2"></i> Tambah Siswa Baru
                </a>
                <a href="{{ route('admin.sertifikat') }}" class="btn btn-outline-success w-100 text-start rounded-3 py-2">
                    <i class="bi bi-file-earmark-plus-fill me-2"></i> Buat Sertifikat
                </a>
                <a href="{{ route('admin.verifikasi') }}" class="btn btn-outline-warning w-100 text-start rounded-3 py-2">
                    <i class="bi bi-patch-check me-2"></i> Proses Verifikasi
                </a>
                <hr class="my-1">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="fw-semibold mb-2" style="font-size: 13px;">
                        <i class="bi bi-calendar-event-fill text-primary me-2"></i>
                        Info Sistem
                    </div>
                    <div class="text-muted" style="font-size: 12px; line-height: 1.7;">
                        <div>📅 {{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                        <div>🟢 Server: <span class="text-success fw-semibold">Online</span></div>
                        <div>👤 Login sebagai: <strong>Admin</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection