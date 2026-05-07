@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- [UI] Page Header --}}
<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="font-family:'Sora',sans-serif; font-size:1.25rem;">Dashboard Admin</h4>
        <p class="mb-0 text-secondary" style="font-size:.875rem;">
            Selamat datang kembali, <strong class="text-primary">{{ auth()->user()->name }}</strong>!
            Berikut ringkasan sistem bulan ini.
        </p>
    </div>
    {{-- [UI] Tanggal kanan atas --}}
    <div class="d-none d-sm-flex align-items-center gap-2 px-3 py-2 rounded-3"
         style="background:var(--clr-surface); border:1px solid var(--clr-border); font-size:.8rem; color:var(--clr-text-secondary); white-space:nowrap;">
        <i class="bi bi-calendar3 text-primary"></i>
        {{ now()->isoFormat('dddd, D MMMM Y') }}
    </div>
</div>

{{-- ============================================================
     STAT CARDS
============================================================ --}}
<div class="row g-3 mb-4">

    {{-- Total Sertifikat --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:3px solid #2563eb;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase fw-semibold" style="font-size:.75rem; letter-spacing:.05em; color:var(--clr-text-muted);">
                        Total Sertifikat
                    </span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(37,99,235,.1);color:#2563eb;">
                        <i class="bi bi-award-fill"></i>
                    </div>
                </div>

                <div class="fw-bold" style="font-family:'Sora',sans-serif;font-size:1.6rem;">
                    {{ $totalSertifikat }}
                </div>
            </div>
        </div>
    </div>

    {{-- Sertifikat Diterbitkan --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:3px solid #22c55e;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase fw-semibold" style="font-size:.75rem; letter-spacing:.05em; color:var(--clr-text-muted);">
                        Diterbitkan
                    </span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(34,197,94,.12);color:#16a34a;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>

                <div class="fw-bold" style="font-family:'Sora',sans-serif;font-size:1.6rem;">
                    {{ $totalSertifikatDiterbitkan }}
                </div>
            </div>
        </div>
    </div>

    {{-- Sertifikat Draft --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:3px solid #f59e0b;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase fw-semibold" style="font-size:.75rem; letter-spacing:.05em; color:var(--clr-text-muted);">
                        Draft
                    </span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(245,158,11,.12);color:#d97706;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>

                <div class="fw-bold" style="font-family:'Sora',sans-serif;font-size:1.6rem;">
                    {{ $totalSertifikatDraft }}
                </div>
            </div>
        </div>
    </div>

    {{-- Total Verifikasi --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:3px solid #6366f1;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase fw-semibold" style="font-size:.75rem; letter-spacing:.05em; color:var(--clr-text-muted);">
                        Verifikasi
                    </span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(99,102,241,.12);color:#4f46e5;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>

                <div class="fw-bold" style="font-family:'Sora',sans-serif;font-size:1.6rem;">
                    {{ $totalVerifikasi }}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     ROW 2 — Aktivitas Terbaru + Quick Actions
============================================================ --}}
<div class="row g-3">

    {{-- KIRI: SERTIFIKAT TERBARU --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold" style="font-family:'Sora'; font-size:.9rem;">
                        Sertifikat Terbaru
                    </div>
                    <div style="font-size:.75rem; color:var(--clr-text-muted);">
                        10 data terakhir
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:20px;">Nama</th>
                            <th>Program</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestCertificates as $cert)
                        <tr>
                            <td style="padding-left:20px;">
                                {{ $cert->username }}
                            </td>
                            <td>{{ $cert->program_name }}</td>
                            <td>
                                @if($cert->status == 'Di Terbitkan')
                                    <span class="badge bg-success">Diterbitkan</span>
                                @else
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KANAN: VERIFIKASI TERBARU --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold" style="font-family:'Sora'; font-size:.9rem;">
                        Verifikasi Terbaru
                    </div>
                    <div style="font-size:.75rem; color:var(--clr-text-muted);">
                        10 aktivitas terakhir
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:20px;">Nama</th>
                            <th>Sertifikat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestVerifications as $v)
                        <tr>
                            <td style="padding-left:20px;">
                                {{ $v['name'] }}
                            </td>
                            <td>{{ $v['program'] }}</td>
                            <td>{{ $v['tanggal'] }}</td>
                          <td>
                                @php
                                    $resultMap = [
                                        1 => ['label' => 'QR Tidak Valid',     'class' => 'danger'],
                                        2 => ['label' => 'Tidak Ditemukan',     'class' => 'warning'],
                                        3 => ['label' => 'Verifikasi Gagal',    'class' => 'secondary'],
                                        4 => ['label' => 'Verifikasi Berhasil', 'class' => 'success'],
                                    ];
                                    $result = $resultMap[$v['result']] ?? ['label' => '-', 'class' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $result['class'] }}">{{ $result['label'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection