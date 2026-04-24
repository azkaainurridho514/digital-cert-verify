@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard')

@section('content')

{{-- ============================================================
     INLINE STYLES — Dashboard Siswa (UI Only)
============================================================ --}}
<style>
    /* ── Welcome Banner ───────────────────────────────────────── */
    .welcome-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #0ea5e9 100%);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        color: #fff;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -60px; right: 80px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .welcome-banner .wb-label {
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        opacity: .7;
        margin-bottom: 6px;
    }
    .welcome-banner h4 {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 6px;
        line-height: 1.3;
    }
    .welcome-banner p {
        font-size: .875rem;
        opacity: .8;
        margin-bottom: 0;
    }
    .welcome-banner .wb-icon {
        position: absolute;
        right: 32px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 5rem;
        opacity: .1;
        line-height: 1;
        pointer-events: none;
    }

    /* ── Stat Cards ───────────────────────────────────────────── */
    .stat-card-new {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: 18px;
        padding: 22px 22px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.04);
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: transform .2s ease, box-shadow .2s ease;
        height: 100%;
    }
    .stat-card-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0,0,0,.09);
    }
    .stat-card-new .sc-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-card-new .sc-icon {
        width: 46px; height: 46px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .stat-card-new .sc-badge {
        font-size: .68rem;
        font-weight: 600;
        padding: .28em .7em;
        border-radius: 20px;
    }
    .stat-card-new .sc-value {
        font-family: 'Sora', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: var(--clr-text-primary);
    }
    .stat-card-new .sc-label {
        font-size: .8rem;
        color: var(--clr-text-secondary);
        font-weight: 500;
    }
    .stat-card-new .sc-sub {
        font-size: .72rem;
        color: var(--clr-text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Main Content Cards ───────────────────────────────────── */
    .content-card {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.04);
        overflow: hidden;
        height: 100%;
    }
    .content-card .cc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--clr-border);
    }
    .content-card .cc-header .cc-title {
        font-family: 'Sora', sans-serif;
        font-size: .9rem;
        font-weight: 700;
        color: var(--clr-text-primary);
        margin-bottom: 2px;
    }
    .content-card .cc-header .cc-subtitle {
        font-size: .73rem;
        color: var(--clr-text-muted);
    }
    .content-card .cc-body {
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* ── Certificate Row ──────────────────────────────────────── */
    .cert-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: #f8fafc;
        border: 1px solid #e9eef5;
        border-radius: 14px;
        transition: background .15s ease, border-color .15s ease;
    }
    .cert-row:hover {
        background: #f0f6ff;
        border-color: #bfdbfe;
    }
    .cert-row .cert-icon {
        width: 46px; height: 46px;
        background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(37,99,235,.25);
    }
    .cert-row .cert-info {
        flex: 1;
        min-width: 0;
    }
    .cert-row .cert-name {
        font-size: .875rem;
        font-weight: 600;
        color: var(--clr-text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 3px;
    }
    .cert-row .cert-meta {
        font-size: .72rem;
        color: var(--clr-text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .cert-row .cert-meta code {
        font-size: .68rem;
        background: #e2e8f0;
        color: #475569;
        padding: 1px 6px;
        border-radius: 5px;
    }
    .cert-row .cert-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* Status Badges */
    .badge-published {
        background: rgba(16,185,129,.1);
        color: #059669;
        font-size: .68rem;
        font-weight: 600;
        padding: .3em .75em;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .badge-process {
        background: rgba(245,158,11,.1);
        color: #d97706;
        font-size: .68rem;
        font-weight: 600;
        padding: .3em .75em;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .badge-default {
        background: rgba(100,116,139,.1);
        color: #64748b;
        font-size: .68rem;
        font-weight: 600;
        padding: .3em .75em;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    /* Download Button */
    .btn-dl {
        width: 34px; height: 34px;
        border-radius: 10px;
        border: 1px solid var(--clr-border);
        background: var(--clr-surface);
        color: var(--clr-text-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        transition: all .18s ease;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-dl:hover:not(:disabled) {
        background: var(--clr-primary);
        border-color: var(--clr-primary);
        color: #fff;
        box-shadow: 0 3px 10px rgba(37,99,235,.3);
    }
    .btn-dl:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 36px 20px;
        color: var(--clr-text-muted);
        text-align: center;
    }
    .empty-state .es-icon {
        width: 56px; height: 56px;
        background: var(--clr-bg);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 12px;
        color: #cbd5e1;
    }
    .empty-state p { font-size: .82rem; margin: 0; }

    /* ── Profile Card ─────────────────────────────────────────── */
    .profile-avatar {
        width: 68px; height: 68px;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Sora', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: #fff;
        margin: 0 auto 14px;
        box-shadow: 0 6px 20px rgba(37,99,235,.3);
    }
    .profile-name {
        font-family: 'Sora', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--clr-text-primary);
        margin-bottom: 3px;
    }
    .profile-email {
        font-size: .78rem;
        color: var(--clr-text-muted);
        margin-bottom: 10px;
    }
    .profile-divider {
        border: none;
        border-top: 1px solid var(--clr-border);
        margin: 16px 0;
    }
    .btn-verify {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px 16px;
        background: var(--clr-primary);
        color: #fff;
        border-radius: 12px;
        border: none;
        font-size: .875rem;
        font-weight: 600;
        font-family: 'Sora', sans-serif;
        transition: background .18s ease, box-shadow .18s ease, transform .15s ease;
        text-decoration: none;
    }
    .btn-verify:hover {
        background: var(--clr-primary-hover);
        color: #fff;
        box-shadow: 0 4px 16px rgba(37,99,235,.35);
        transform: translateY(-1px);
    }

    /* Quick Stats in profile */
    .profile-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 14px;
    }
    .ps-item {
        background: var(--clr-bg);
        border-radius: 10px;
        padding: 10px 12px;
        text-align: center;
    }
    .ps-item .ps-val {
        font-family: 'Sora', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--clr-text-primary);
    }
    .ps-item .ps-key {
        font-size: .68rem;
        color: var(--clr-text-muted);
        margin-top: 1px;
    }

    /* ── Responsive tweaks ────────────────────────────────────── */
    @media (max-width: 576px) {
        .welcome-banner { padding: 20px 20px; }
        .welcome-banner h4 { font-size: 1.1rem; }
        .welcome-banner .wb-icon { display: none; }
        .cert-row { flex-wrap: wrap; }
    }
</style>

{{-- ── Welcome Banner ──────────────────────────────────────────────────── --}}
<div class="welcome-banner">
    <div class="wb-label">Selamat Datang</div>
    <h4>Halo, {{ auth()->user()->name }}! 👋</h4>
    <p>Pantau progress belajar dan sertifikat kamu di sini.</p>
    <div class="wb-icon"><i class="bi bi-mortarboard-fill"></i></div>
</div>

{{-- ── Stat Cards ───────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-sm-4">
        <div class="stat-card-new">
            <div class="sc-top">
                <div class="sc-icon" style="background: rgba(37,99,235,.1); color: #2563eb;">
                    <i class="bi bi-award-fill"></i>
                </div>
                <span class="sc-badge" style="background: rgba(37,99,235,.08); color: #2563eb;">Total</span>
            </div>
            <div>
                <div class="sc-value">{{ $totalSertifikat }}</div>
                <div class="sc-label">Total Sertifikat</div>
            </div>
            <div class="sc-sub">
                <i class="bi bi-check-circle-fill" style="color: #10b981;"></i>
                Semua terdaftar
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-4">
        <div class="stat-card-new">
            <div class="sc-top">
                <div class="sc-icon" style="background: rgba(16,185,129,.1); color: #10b981;">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <span class="sc-badge" style="background: rgba(16,185,129,.08); color: #059669;">Resmi</span>
            </div>
            <div>
                <div class="sc-value">{{ $sudahDiterbitkan }}</div>
                <div class="sc-label">Sudah Diterbitkan</div>
            </div>
            <div class="sc-sub">
                <i class="bi bi-shield-check" style="color: #10b981;"></i>
                Valid & Resmi
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-4">
        <div class="stat-card-new">
            <div class="sc-top">
                <div class="sc-icon" style="background: rgba(245,158,11,.1); color: #f59e0b;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <span class="sc-badge" style="background: rgba(245,158,11,.08); color: #d97706;">Proses</span>
            </div>
            <div>
                <div class="sc-value">{{ $menungguProses }}</div>
                <div class="sc-label">Menunggu Proses</div>
            </div>
            <div class="sc-sub">
                <i class="bi bi-clock"></i>
                Sedang diproses
            </div>
        </div>
    </div>

</div>

{{-- ── Main Section ─────────────────────────────────────────────────────── --}}
<div class="row g-3">

    {{-- Sertifikat Saya --}}
    <div class="col-lg-8">
        <div class="content-card">
            <div class="cc-header">
                <div>
                    <div class="cc-title">Sertifikat Saya</div>
                    <div class="cc-subtitle">Daftar sertifikat terbaru yang dimiliki</div>
                </div>
                <a href="{{ route('siswa.sertifikat') }}"
                   class="btn btn-sm btn-outline-primary rounded-pill px-3"
                   style="font-size: .75rem; font-weight: 600;">
                    <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                </a>
            </div>

            <div class="cc-body">
                @forelse($sertifikat as $cert)
                    <div class="cert-row">

                        {{-- Icon --}}
                        <div class="cert-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>

                        {{-- Info --}}
                        <div class="cert-info">
                            <div class="cert-name">{{ $cert->program->name ?? '-' }}</div>
                            <div class="cert-meta">
                                <i class="bi bi-calendar3"></i>
                                {{ $cert->issued_date ? \Carbon\Carbon::parse($cert->issued_date)->translatedFormat('M Y') : '-' }}
                                <span style="opacity:.4;">·</span>
                                <code>{{ $cert->certificate_number ?? '-' }}</code>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="cert-actions">
                            @if($cert->status === 'Di Terbitkan')
                                <span class="badge-published">
                                    <i class="bi bi-check-circle-fill"></i>Diterbitkan
                                </span>
                            @elseif($cert->status === 'Di Proses')
                                <span class="badge-process">
                                    <i class="bi bi-clock-fill"></i>Di Proses
                                </span>
                            @else
                                <span class="badge-default">
                                    <i class="bi bi-pencil-fill"></i>{{ $cert->status }}
                                </span>
                            @endif

                            {{-- Download --}}
                            @if($cert->status === 'Di Terbitkan' && $cert->file_path)
                                <a href="{{ route('siswa.sertifikat.download', $cert->id) }}"
                                   class="btn-dl" title="Download Sertifikat">
                                    <i class="bi bi-download"></i>
                                </a>
                            @else
                                <button class="btn-dl" disabled title="Belum tersedia">
                                    <i class="bi bi-slash-circle"></i>
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="empty-state">
                        <div class="es-icon"><i class="bi bi-inbox"></i></div>
                        <p>Belum ada sertifikat yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Panel Profil --}}
    <div class="col-lg-4">
        <div class="content-card">
            <div class="cc-header">
                <div>
                    <div class="cc-title">Profil Saya</div>
                    <div class="cc-subtitle">Informasi akun siswa</div>
                </div>
                <span class="badge-published">
                    <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>Aktif
                </span>
            </div>

            <div style="padding: 22px 22px 20px; text-align: center;">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-email">{{ auth()->user()->email }}</div>

                <div class="profile-stats">
                    <div class="ps-item">
                        <div class="ps-val" style="color: #2563eb;">{{ $totalSertifikat }}</div>
                        <div class="ps-key">Total</div>
                    </div>
                    <div class="ps-item">
                        <div class="ps-val" style="color: #10b981;">{{ $sudahDiterbitkan }}</div>
                        <div class="ps-key">Terbit</div>
                    </div>
                </div>

                <hr class="profile-divider">

                <a href="{{ route('verifikasi') }}" class="btn-verify">
                    <i class="bi bi-qr-code-scan"></i>
                    Verifikasi Sertifikat
                </a>
            </div>
        </div>
    </div>

</div>

@endsection