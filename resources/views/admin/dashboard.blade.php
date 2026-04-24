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

    {{-- Total Siswa --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card h-100" style="border-left:3px solid #2563eb;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--clr-text-muted);">Total Siswa</span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(37,99,235,.1);color:#2563eb;font-size:1rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:700;line-height:1;color:var(--clr-text-primary);">
                    {{ $totalSiswa }}
                </div>
                <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.75rem;">
                    <span class="{{ $newSiswa > 0 ? 'text-success' : 'text-muted' }}">
                        <i class="bi bi-arrow-up-short"></i>+{{ $newSiswa }} bulan ini
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Mengikuti Program --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card h-100" style="border-left:3px solid #17d0e8;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--clr-text-muted);">Ikuti Program</span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(23,208,232,.1);color:#17d0e8;font-size:1rem;">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:700;line-height:1;color:var(--clr-text-primary);">
                    {{ $totalSiswaMengikutiProgram }}
                </div>
                <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.75rem;">
                    <span class="{{ $newSiswaMengikutiProgram > 0 ? 'text-success' : 'text-muted' }}">
                        <i class="bi bi-arrow-up-short"></i>+{{ $newSiswaMengikutiProgram }} bulan ini
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tidak Mengikuti Program --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card h-100" style="border-left:3px solid #6725eb;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--clr-text-muted);">Non-Program</span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(103,37,235,.1);color:#6725eb;font-size:1rem;">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:700;line-height:1;color:var(--clr-text-primary);">
                    {{ $totalSiswaTidakMengikutiProgram }}
                </div>
                <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.75rem;">
                    <span class="{{ $newSiswaTidakMengikutiProgram > 0 ? 'text-success' : 'text-muted' }}">
                        <i class="bi bi-arrow-up-short"></i>+{{ $newSiswaTidakMengikutiProgram }} bulan ini
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sertifikat Diterbitkan --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card h-100" style="border-left:3px solid #ceca12;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--clr-text-muted);">Sertifikat</span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(185,182,16,.1);color:#ceca12;font-size:1rem;">
                        <i class="bi bi-award-fill"></i>
                    </div>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:700;line-height:1;color:var(--clr-text-primary);">
                    {{ $totalSertifikat }}
                </div>
                <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.75rem;">
                    <span class="{{ $newSertifikat > 0 ? 'text-success' : 'text-muted' }}">
                        <i class="bi bi-arrow-up-short"></i>+{{ $newSertifikat }} bulan ini
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Percobaan Verifikasi --}}
    <div class="col-6 col-md-4 col-xl-4">
        <div class="card h-100" style="border-left:3px solid #8b5cf6;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--clr-text-muted);">Total Verifikasi</span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(139,92,246,.1);color:#8b5cf6;font-size:1rem;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:700;line-height:1;color:var(--clr-text-primary);">
                    {{ $totalVerifikasi }}
                </div>
                <div class="mt-2" style="font-size:.75rem; color:var(--clr-text-muted);">
                    +{{ $newVerifikasi }} bulan ini
                </div>
            </div>
        </div>
    </div>

    {{-- Verifikasi Berhasil --}}
    <div class="col-6 col-md-4 col-xl-4">
        <div class="card h-100" style="border-left:3px solid #22c55e;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--clr-text-muted);">Berhasil</span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(34,197,94,.12);color:#16a34a;font-size:1rem;">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:700;line-height:1;color:var(--clr-text-primary);">
                    {{ $verifikasiBerhasil }}
                </div>
                <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.75rem;">
                    <span class="{{ $newBerhasil > 0 ? 'text-success' : 'text-muted' }}">
                        +{{ $newBerhasil }} bulan ini
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Verifikasi Gagal --}}
    <div class="col-6 col-md-4 col-xl-4">
        <div class="card h-100" style="border-left:3px solid #ef4444;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--clr-text-muted);">Gagal</span>
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:rgba(239,68,68,.1);color:#ef4444;font-size:1rem;">
                        <i class="bi bi-x-octagon-fill"></i>
                    </div>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:700;line-height:1;color:var(--clr-text-primary);">
                    {{ $verifikasiGagal }}
                </div>
                <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.75rem; color:#ef4444;">
                    +{{ $newGagal }} bulan ini
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     ROW 2 — Aktivitas Terbaru + Quick Actions
============================================================ --}}
<div class="row g-3">

    {{-- Aktivitas Verifikasi Terbaru --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div style="font-size:.9rem; font-weight:600; font-family:'Sora',sans-serif;">
                        Aktivitas Verifikasi Terbaru
                    </div>
                    <div style="font-size:.75rem; color:var(--clr-text-muted); margin-top:1px;">7 hari terakhir</div>
                </div>
                <a href="{{ route('verifikasi') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                </a>
            </div>

            {{-- [UI] Tabel responsif tanpa padding card-body --}}
            <div class="table-responsive" style="border-radius:0 0 var(--radius-lg) var(--radius-lg);">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:20px;">Nama Siswa</th>
                            <th>Sertifikat</th>
                            <th class="d-none d-sm-table-cell">Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitas as $act)
                        <tr>
                            <td style="padding-left:20px;">
                                {{-- [UI] Avatar inisial + nama --}}
                                <div class="d-flex align-items-center gap-2">
                                    <div style="
                                        width:30px; height:30px; min-width:30px;
                                        background:var(--clr-primary-soft);
                                        color:var(--clr-primary);
                                        border-radius:50%;
                                        display:flex; align-items:center; justify-content:center;
                                        font-family:'Sora',sans-serif;
                                        font-size:.65rem; font-weight:700;">
                                        {{ strtoupper(substr($act['name'], 0, 2)) }}
                                    </div>
                                    <span style="font-size:.85rem;">{{ $act['name'] }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:.85rem; color:var(--clr-text-secondary);">{{ $act['program'] }}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span style="font-size:.8rem; color:var(--clr-text-muted);">{{ $act['tanggal'] }}</span>
                            </td>
                            <td>
                                {{-- [LOGIC] Status badge — conditional tidak diubah --}}
                                @if($act['status'] === 'Terverifikasi')
                                    <span class="badge" style="background:rgba(34,197,94,.12); color:#15803d; font-size:.72rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>{{ $act['status'] }}
                                    </span>
                                @elseif($act['status'] === 'Menunggu')
                                    <span class="badge" style="background:rgba(234,179,8,.12); color:#a16207; font-size:.72rem;">
                                        <i class="bi bi-clock-fill me-1"></i>{{ $act['status'] }}
                                    </span>
                                @else
                                    <span class="badge" style="background:rgba(239,68,68,.1); color:#b91c1c; font-size:.72rem;">
                                        <i class="bi bi-x-circle-fill me-1"></i>{{ $act['status'] }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color:var(--clr-text-muted);">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                Belum ada aktivitas 7 hari terakhir.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Info Sistem --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <span style="font-size:.9rem; font-weight:600; font-family:'Sora',sans-serif;">Aksi Cepat</span>
            </div>
            <div class="card-body d-flex flex-column gap-2">

                {{-- [LOGIC] Route tidak diubah --}}
                <a href="{{ route('admin.siswa') }}"
                   class="btn btn-outline-primary w-100 text-start"
                   style="font-size:.85rem;">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Siswa Baru
                </a>
                <a href="{{ route('admin.sertifikat') }}"
                   class="btn btn-outline-success w-100 text-start"
                   style="font-size:.85rem;">
                    <i class="bi bi-file-earmark-plus-fill me-2"></i>Buat Sertifikat
                </a>
                <a href="{{ route('verifikasi') }}"
                   class="btn btn-outline-warning w-100 text-start"
                   style="font-size:.85rem;">
                    <i class="bi bi-patch-check me-2"></i>Proses Verifikasi
                </a>

                {{-- [UI] Divider --}}
                <hr class="my-1" style="border-color:var(--clr-border);">

                {{-- [UI] Info Sistem panel --}}
                <div class="rounded-3 p-3" style="background:var(--clr-bg); border:1px solid var(--clr-border);">
                    <div class="d-flex align-items-center gap-2 mb-3"
                         style="font-family:'Sora',sans-serif; font-size:.8rem; font-weight:600; color:var(--clr-text-secondary);">
                        <i class="bi bi-info-circle-fill text-primary"></i>Info Sistem
                    </div>
                    <div class="d-flex flex-column gap-2" style="font-size:.8rem;">
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="color:var(--clr-text-muted);">
                                <i class="bi bi-calendar3 me-1"></i>Tanggal
                            </span>
                            {{-- [LOGIC] Variable tidak diubah --}}
                            <span style="color:var(--clr-text-primary); font-weight:500;">{{ now()->isoFormat('D MMM Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="color:var(--clr-text-muted);">
                                <i class="bi bi-hdd-network me-1"></i>Server
                            </span>
                            <span class="d-flex align-items-center gap-1" style="color:#16a34a; font-weight:500;">
                                <span style="width:6px;height:6px;background:#22c55e;border-radius:50%;display:inline-block;"></span>
                                Online
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="color:var(--clr-text-muted);">
                                <i class="bi bi-person me-1"></i>Login sebagai
                            </span>
                            {{-- [LOGIC] Variable tidak diubah --}}
                            <span style="color:var(--clr-primary); font-weight:600; font-size:.75rem;">
                                {{ auth()->user()->name }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection