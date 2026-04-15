@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <h4>Dashboard Siswa</h4>
    <p>Halo, <strong>{{ auth()->user()->name }}</strong>! Pantau progress dan sertifikat kamu di sini.</p>
</div>

{{-- Stat Cards Siswa --}}
<div class="row g-4 mb-4">

    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="stat-value">{{ $totalSertifikat }}</div>
            <div class="stat-label">Total Sertifikat</div>
            <div class="stat-change up">
                <i class="bi bi-check-circle-fill"></i> Semua terdaftar
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="stat-value">{{ $sudahDiterbitkan }}</div>
            <div class="stat-label">Sudah Diterbitkan</div>
            <div class="stat-change up">
                <i class="bi bi-shield-check"></i> Valid & Resmi
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value">{{ $menungguProses }}</div>
            <div class="stat-label">Menunggu Proses</div>
            <div class="stat-change">
                <i class="bi bi-clock"></i> Sedang diproses
            </div>
        </div>
    </div>

</div>

{{-- Sertifikat Saya --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-modern">
            <div class="card-header-modern">
                <div>
                    <h6 class="mb-0 fw-bold" style="font-size: 15px;">Sertifikat Saya</h6>
                    <span class="text-muted" style="font-size: 12px;">Daftar sertifikat terbaru yang dimiliki</span>
                </div>
                <a href="{{ route('siswa.sertifikat') }}" class="btn btn-sm btn-outline-primary rounded-3" style="font-size: 12px;">
                    Lihat Semua
                </a>
            </div>

            <div class="p-3 d-flex flex-column gap-3">
                @forelse($sertifikat as $cert)
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3"
                         style="background: #f8fafc; border: 1px solid #e2e8f0;">

                        {{-- Icon --}}
                        <div style="width: 44px; height: 44px;
                                    background: linear-gradient(135deg, #2563eb, #7c3aed);
                                    border-radius: 12px; display: flex; align-items: center;
                                    justify-content: center; flex-shrink: 0;
                                    color: #fff; font-size: 20px;">
                            <i class="bi bi-award-fill"></i>
                        </div>

                        {{-- Info --}}
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold" style="font-size: 14px; color: #0f172a;">
                                {{ $cert->program->name ?? '-' }}
                            </div>
                            <div class="text-muted" style="font-size: 12px;">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $cert->issued_date ? \Carbon\Carbon::parse($cert->issued_date)->translatedFormat('M Y') : '-' }}
                                &nbsp;·&nbsp;
                                <code style="font-size: 11px;">{{ $cert->certificate_number ?? '-' }}</code>
                            </div>
                        </div>

                        {{-- Badge Status --}}
                        <div>
                            @if($cert->status === 'Di Terbitkan')
                                <span class="badge rounded-pill px-3"
                                      style="background: rgba(16,185,129,0.12); color: #10b981; font-size: 11px;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Diterbitkan
                                </span>
                            @elseif($cert->status === 'Di Proses')
                                <span class="badge rounded-pill px-3"
                                      style="background: rgba(245,158,11,0.12); color: #f59e0b; font-size: 11px;">
                                    <i class="bi bi-clock-fill me-1"></i>Di Proses
                                </span>
                            @else
                                <span class="badge rounded-pill px-3"
                                      style="background: rgba(100,116,139,0.12); color: #64748b; font-size: 11px;">
                                    <i class="bi bi-pencil-fill me-1"></i>{{ $cert->status }}
                                </span>
                            @endif
                        </div>

                        {{-- Tombol Download --}}
                        <div>
                            @if($cert->status === 'Di Terbitkan' && $cert->file_path)
                                <a href="{{ route('siswa.sertifikat.download', $cert->id) }}"
                                   class="btn btn-sm btn-outline-secondary rounded-3 px-2"
                                   title="Download Sertifikat">
                                    <i class="bi bi-download" style="font-size: 13px;"></i>
                                </a>
                            @else
                                <button class="btn btn-sm btn-outline-secondary rounded-3 px-2"
                                        disabled title="Belum tersedia">
                                    <i class="bi bi-slash-circle" style="font-size: 13px;"></i>
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada sertifikat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Panel Kanan --}}
    <div class="col-lg-4">
        <div class="card-modern">
            <div class="card-header-modern">
                <h6 class="mb-0 fw-bold" style="font-size: 15px;">Profil Saya</h6>
            </div>
            <div class="p-4 text-center">
                <div class="user-avatar mx-auto mb-3"
                     style="width:64px;height:64px;font-size:22px;border-radius:18px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="fw-bold" style="font-size: 16px;">{{ auth()->user()->name }}</div>
                <div class="text-muted" style="font-size: 13px;">{{ auth()->user()->email }}</div>
                <span class="badge bg-success text-white rounded-pill mt-2 px-3">Siswa Aktif</span>

                <hr class="my-3">

                <a href="{{ route('siswa.verifikasi') }}" class="btn btn-primary w-100 rounded-3 py-2">
                    <i class="bi bi-qr-code-scan me-2"></i>
                    Verifikasi Sertifikat
                </a>
            </div>
        </div>
    </div>
</div>

@endsection