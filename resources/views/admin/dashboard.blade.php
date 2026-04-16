@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <h4>Dashboard Admin</h4>
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Berikut ringkasan sistem bulan ini.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-4 justify-content-center mb-5">

    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value">{{ $totalSiswa }}</div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-change {{ $newSiswa > 0 ? 'up' : '' }}">
                <i class="bi bi-arrow-up-short"></i> +{{ $newSiswa }} bulan ini
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(37,222,235,0.1); color: #17d0e8;">
                <i class="bi bi-person-lines-fill"></i>
            </div>
            <div class="stat-value">{{ $totalSiswaMengikutiProgram }}</div>
            <div class="stat-label">Mengikuti Program</div>
            <div class="stat-change {{ $newSiswaMengikutiProgram > 0 ? 'up' : '' }}">
                <i class="bi bi-arrow-up-short"></i> +{{ $newSiswaMengikutiProgram }} bulan ini
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(103,37,235,0.1); color: #6725eb;">
                <i class="bi bi-person-vcard-fill"></i>
            </div>
            <div class="stat-value">{{ $totalSiswaTidakMengikutiProgram }}</div>
            <div class="stat-label">Tidak Mengikuti Program</div>
            <div class="stat-change {{ $newSiswaTidakMengikutiProgram > 0 ? 'up' : '' }}">
                <i class="bi bi-arrow-up-short"></i> +{{ $newSiswaTidakMengikutiProgram }} bulan ini
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(185,182,16,0.1); color: #ceca12;">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="stat-value">{{ $totalSertifikat }}</div>
            <div class="stat-label">Sertifikat Diterbitkan</div>
            <div class="stat-change {{ $newSertifikat > 0 ? 'up' : '' }}">
                <i class="bi bi-arrow-up-short"></i> +{{ $newSertifikat }} bulan ini
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="stat-value">{{ $totalVerifikasi }}</div>
            <div class="stat-label">Percobaan Verifikasi</div>
            <div class="stat-change {{ $newVerifikasi > 0 ? 'up' : '' }}">
                +{{ $newVerifikasi }} bulan ini
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(92,246,113,0.2); color: #1bc50f;">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <div class="stat-value">{{ $verifikasiBerhasil }}</div>
            <div class="stat-label">Verifikasi Berhasil</div>
            <div class="stat-change {{ $newBerhasil > 0 ? 'up' : '' }}">
                +{{ $newBerhasil }} bulan ini
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,11,11,0.1); color: #f50b0b;">
                <i class="bi bi-x-octagon-fill"></i>
            </div>
            <div class="stat-value">{{ $verifikasiGagal }}</div>
            <div class="stat-label">Verifikasi Gagal</div>
            <div class="stat-change down">
                +{{ $newGagal }} bulan ini
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
                    <h6 class="mb-0 fw-bold" style="font-size: 15px;">Aktivitas Verifikasi Terbaru</h6>
                    <span class="text-muted" style="font-size: 12px;">7 hari terakhir</span>
                </div>
                <a href="{{ route('verifikasi') }}" class="btn btn-sm btn-outline-primary rounded-3" style="font-size: 12px;">
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
                        @forelse($aktivitas as $act)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                        {{ strtoupper(substr($act['name'], 0, 2)) }}
                                    </div>
                                    {{ $act['name'] }}
                                </div>
                            </td>
                            <td class="py-3">{{ $act['program'] }}</td>
                            <td class="py-3 text-muted">{{ $act['tanggal'] }}</td>
                            <td class="py-3">
                                @if($act['status'] === 'Terverifikasi')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i>{{ $act['status'] }}
                                    </span>
                                @elseif($act['status'] === 'Menunggu')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3">
                                        <i class="bi bi-clock-fill me-1"></i>{{ $act['status'] }}
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                                        <i class="bi bi-x-circle-fill me-1"></i>{{ $act['status'] }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox me-2"></i>Belum ada aktivitas 7 hari terakhir.
                            </td>
                        </tr>
                        @endforelse
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
                <a href="{{ route('verifikasi') }}" class="btn btn-outline-warning w-100 text-start rounded-3 py-2">
                    <i class="bi bi-patch-check me-2"></i> Proses Verifikasi
                </a>
                <hr class="my-1">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="fw-semibold mb-2" style="font-size: 13px;">
                        <i class="bi bi-calendar-event-fill text-primary me-2"></i>Info Sistem
                    </div>
                    <div class="text-muted" style="font-size: 12px; line-height: 1.7;">
                        <div>📅 {{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                        <div>🟢 Server: <span class="text-success fw-semibold">Online</span></div>
                        <div>👤 Login sebagai: <strong>{{ auth()->user()->name }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection