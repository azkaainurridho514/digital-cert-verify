<style>
/* ============================================================
   SIDEBAR COMPONENT STYLES
   (Extends & overrides base vars from layouts/app.blade.php)
============================================================ */

/* ── Brand ─────────────────────────────────────────────────── */
#sidebar .sidebar-brand {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 18px 16px 16px;
    min-height: var(--navbar-h);
    border-bottom: 1px solid rgba(255,255,255,.06);
    white-space: nowrap;
    overflow: hidden;
    text-decoration: none;
}
#sidebar .brand-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    color: #fff;
    box-shadow: 0 4px 12px rgba(37,99,235,.4);
    flex-shrink: 0;
}
#sidebar .brand-text {
    font-family: 'Sora', sans-serif;
    font-size: .92rem;
    font-weight: 700;
    color: #f1f5f9;
    letter-spacing: -.015em;
    line-height: 1.2;
    transition: opacity .2s ease;
}
#sidebar .brand-text small {
    display: block;
    font-size: .65rem;
    font-weight: 400;
    color: #475569;
    letter-spacing: .05em;
    text-transform: uppercase;
    margin-top: 1px;
}

/* ── Nav Scroll Area ────────────────────────────────────────── */
#sidebar .sidebar-nav {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 14px 10px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.06) transparent;
}
#sidebar .sidebar-nav::-webkit-scrollbar { width: 3px; }
#sidebar .sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,.08);
    border-radius: 3px;
}

/* ── Section Label ──────────────────────────────────────────── */
#sidebar .nav-label {
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #334155;
    padding: 14px 10px 5px;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity .2s ease;
}

/* ── Nav Links ──────────────────────────────────────────────── */
#sidebar .nav-item-link {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 9px 12px;
    border-radius: 11px;
    color: #64748b;
    font-size: .845rem;
    font-weight: 400;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    transition: background .18s ease, color .18s ease;
    position: relative;
    margin-bottom: 2px;
}
#sidebar .nav-item-link .nav-icon {
    font-size: 1rem;
    min-width: 20px;
    text-align: center;
    flex-shrink: 0;
    transition: color .18s ease, transform .18s ease;
}
#sidebar .nav-item-link .nav-text {
    transition: opacity .2s ease;
}
#sidebar .nav-item-link:hover {
    background: rgba(255,255,255,.06);
    color: #cbd5e1;
}
#sidebar .nav-item-link:hover .nav-icon {
    transform: translateX(1px);
}

/* Active state */
#sidebar .nav-item-link.active {
    background: rgba(37, 99, 235, .16);
    color: #93c5fd;
    font-weight: 600;
}
#sidebar .nav-item-link.active .nav-icon {
    color: #3b82f6;
}
#sidebar .nav-item-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 56%;
    background: linear-gradient(180deg, #3b82f6, #7c3aed);
    border-radius: 0 3px 3px 0;
}

/* ── Sidebar Footer ─────────────────────────────────────────── */
#sidebar .sidebar-footer {
    padding: 10px 10px 14px;
    border-top: 1px solid rgba(255,255,255,.05);
}
#sidebar .sidebar-footer .nav-item-link {
    color: #475569;
}
#sidebar .sidebar-footer .nav-item-link:hover {
    background: rgba(239, 68, 68, .1);
    color: #f87171;
}
#sidebar .sidebar-footer .nav-item-link:hover .nav-icon {
    transform: translateX(0);
}

/* ── Collapsed State ────────────────────────────────────────── */
#sidebar.collapsed .brand-text,
#sidebar.collapsed .nav-text,
#sidebar.collapsed .nav-label {
    opacity: 0;
    pointer-events: none;
}
#sidebar.collapsed .nav-item-link {
    justify-content: center;
    padding: 10px 0;
}
#sidebar.collapsed .sidebar-brand {
    justify-content: center;
    padding: 18px 0 16px;
}
#sidebar.collapsed .nav-item-link::before {
    display: none;
}

/* Tooltip on collapsed (desktop) */
@media (min-width: 769px) {
    #sidebar.collapsed .nav-item-link {
        position: relative;
    }
    #sidebar.collapsed .nav-item-link::after {
        content: attr(data-label);
        position: absolute;
        left: calc(100% + 10px);
        top: 50%;
        transform: translateY(-50%);
        background: #1e293b;
        color: #e2e8f0;
        font-size: .75rem;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity .15s ease;
        z-index: 9999;
        border: 1px solid rgba(255,255,255,.08);
        box-shadow: 0 4px 16px rgba(0,0,0,.3);
    }
    #sidebar.collapsed .nav-item-link:hover::after {
        opacity: 1;
    }
}
</style>

<nav id="sidebar">

    {{-- ── Brand ────────────────────────────────────────────── --}}
    <a class="sidebar-brand" href="#">
        <div class="brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="brand-text">
            OLC System
            <small>Learning Center</small>
        </div>
    </a>

    {{-- ── Navigation ──────────────────────────────────────── --}}
    <div class="sidebar-nav">

        @if(auth()->user()->role === 'admin')
        {{-- ====== MENU ADMIN ====== --}}
        <div class="nav-label">Main Menu</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           data-label="Dashboard">
            <i class="bi bi-grid-fill nav-icon"></i>
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="{{ route('admin.siswa') }}"
           class="nav-item-link {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}"
           data-label="Data Siswa">
            <i class="bi bi-people-fill nav-icon"></i>
            <span class="nav-text">Data Siswa</span>
        </a>

        <div class="nav-label">Sertifikat</div>

        <a href="{{ route('admin.sertifikat') }}"
           class="nav-item-link {{ request()->routeIs('admin.sertifikat*') ? 'active' : '' }}"
           data-label="Manajemen Sertifikat">
            <i class="bi bi-award-fill nav-icon"></i>
            <span class="nav-text">Manajemen Sertifikat</span>
        </a>

        <a href="{{ route('verifikasi') }}"
           class="nav-item-link {{ request()->routeIs('verifikasi*') ? 'active' : '' }}"
           data-label="Verifikasi">
            <i class="bi bi-patch-check-fill nav-icon"></i>
            <span class="nav-text">Verifikasi</span>
        </a>

        @else
        {{-- ====== MENU SISWA ====== --}}
        <div class="nav-label">Main Menu</div>

        <a href="{{ route('siswa.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}"
           data-label="Dashboard">
            <i class="bi bi-grid-fill nav-icon"></i>
            <span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-label">Sertifikat</div>

        <a href="{{ route('siswa.sertifikat') }}"
           class="nav-item-link {{ request()->routeIs('siswa.sertifikat*') ? 'active' : '' }}"
           data-label="Sertifikat Saya">
            <i class="bi bi-award-fill nav-icon"></i>
            <span class="nav-text">Sertifikat Saya</span>
        </a>

        <a href="{{ route('verifikasi') }}"
           class="nav-item-link {{ request()->routeIs('verifikasi*') ? 'active' : '' }}"
           data-label="Verifikasi Sertifikat">
            <i class="bi bi-patch-check-fill nav-icon"></i>
            <span class="nav-text">Verifikasi Sertifikat</span>
        </a>
        @endif

    </div>

    {{-- ── Sidebar Footer ───────────────────────────────────── --}}
    <div class="sidebar-footer">
        <a href="#" class="nav-item-link btn-logout"
           data-url="{{ route('logout') }}"
           data-label="Logout">
            <i class="bi bi-box-arrow-right nav-icon" style="color: #f87171;"></i>
            <span class="nav-text" style="color: #f87171;">Logout</span>
        </a>
    </div>

</nav>