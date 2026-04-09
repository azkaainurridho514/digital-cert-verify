<div id="topbar">

    {{-- Toggle Button --}}
    <button id="toggle-sidebar" title="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    {{-- Page Title (opsional, dari @section) --}}
    <div class="d-none d-md-block">
        <span class="fw-semibold text-secondary" style="font-size: 14px;">
            @yield('page-title', 'Dashboard')
        </span>
    </div>

    {{-- Right Section --}}
    <div class="topbar-right">

        {{-- Badge Role --}}
        <span class="badge-role
            {{ auth()->user()->role === 'admin' ? 'bg-primary' : 'bg-success' }}
            text-white d-none d-sm-inline-block">
            {{ ucfirst(auth()->user()->role) }}
        </span>

        {{-- User Dropdown --}}
        <div class="dropdown">
            <button class="btn d-flex align-items-center gap-2 border-0 bg-transparent p-1 rounded-3"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="text-start d-none d-sm-block" style="line-height: 1.2;">
                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 11px; color: #94a3b8;">
                        {{ auth()->user()->email }}
                    </div>
                </div>
                <i class="bi bi-chevron-down text-secondary" style="font-size: 11px;"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1"
                style="min-width: 200px; font-size: 14px;">
                <li>
                    <div class="px-3 py-2 border-bottom">
                        <div class="fw-semibold text-dark" style="font-size: 13px;">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-muted" style="font-size: 12px;">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="#">
                        <i class="bi bi-person-circle me-2 text-primary"></i>
                        Profil Saya
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="#">
                        <i class="bi bi-gear me-2 text-secondary"></i>
                        Pengaturan
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a class="dropdown-item py-2 btn-logout text-danger"
                       href="#"
                       data-url="{{ route('logout') }}">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>