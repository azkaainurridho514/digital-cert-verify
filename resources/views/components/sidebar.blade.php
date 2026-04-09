<nav id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <span class="brand-text">OLC System</span>
    </div>

    {{-- Navigation --}}
    <div class="sidebar-nav">

        @if(auth()->user()->role === 'admin')
        {{-- ====== MENU ADMIN ====== --}}
        <div class="nav-label">Main Menu</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill nav-icon"></i>
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="{{ route('admin.siswa') }}"
           class="nav-item-link {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
            <i class="bi bi-people-fill nav-icon"></i>
            <span class="nav-text">Data Siswa</span>
        </a>

        <div class="nav-label">Sertifikat</div>

        <a href="{{ route('admin.sertifikat') }}"
           class="nav-item-link {{ request()->routeIs('admin.sertifikat*') ? 'active' : '' }}">
            <i class="bi bi-award-fill nav-icon"></i>
            <span class="nav-text">Manajemen Sertifikat</span>
        </a>

        <a href="{{ route('admin.verifikasi') }}"
           class="nav-item-link {{ request()->routeIs('admin.verifikasi*') ? 'active' : '' }}">
            <i class="bi bi-patch-check-fill nav-icon"></i>
            <span class="nav-text">Verifikasi</span>
        </a>

        @else
        {{-- ====== MENU SISWA ====== --}}
        <div class="nav-label">Main Menu</div>

        <a href="{{ route('siswa.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill nav-icon"></i>
            <span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-label">Sertifikat</div>

        <a href="{{ route('siswa.sertifikat') }}"
           class="nav-item-link {{ request()->routeIs('siswa.sertifikat*') ? 'active' : '' }}">
            <i class="bi bi-award-fill nav-icon"></i>
            <span class="nav-text">Sertifikat Saya</span>
        </a>

        <a href="{{ route('siswa.verifikasi') }}"
           class="nav-item-link {{ request()->routeIs('siswa.verifikasi*') ? 'active' : '' }}">
            <i class="bi bi-patch-check-fill nav-icon"></i>
            <span class="nav-text">Verifikasi Sertifikat</span>
        </a>
        @endif

    </div>

    {{-- Footer Sidebar --}}
    <div class="sidebar-footer">
        <a href="#" class="nav-item-link btn-logout" data-url="{{ route('logout') }}">
            <i class="bi bi-box-arrow-right nav-icon" style="color: #f87171;"></i>
            <span class="nav-text" style="color: #f87171;">Logout</span>
        </a>
    </div>

</nav>