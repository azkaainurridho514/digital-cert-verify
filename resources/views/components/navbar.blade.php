<style>
/* ============================================================
   NAVBAR COMPONENT STYLES
============================================================ */

/* Right section wrapper */
.topbar-right {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: auto;
}

/* Role badge */
.role-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: .28em .85em;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    font-family: 'Sora', sans-serif;
}
.role-chip.admin {
    background: rgba(37, 99, 235, .1);
    color: #2563eb;
}
.role-chip.siswa {
    background: rgba(16, 185, 129, .1);
    color: #059669;
}

/* Divider */
.topbar-divider {
    width: 1px;
    height: 24px;
    background: var(--clr-border);
    margin: 0 4px;
}

/* User dropdown trigger */
.user-trigger {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 5px 10px 5px 6px;
    border-radius: 12px;
    border: 1px solid transparent;
    background: transparent;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease;
    position: relative;
}
.user-trigger:hover,
.user-trigger.show {
    background: var(--clr-bg);
    border-color: var(--clr-border);
}

/* Avatar */
.nav-avatar {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Sora', sans-serif;
    font-size: .72rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(37,99,235,.3);
}

/* User info text */
.nav-user-info { line-height: 1.25; text-align: left; }
.nav-user-name {
    font-size: .8rem;
    font-weight: 600;
    color: var(--clr-text-primary);
    white-space: nowrap;
    font-family: 'Sora', sans-serif;
}
.nav-user-email {
    font-size: .68rem;
    color: var(--clr-text-muted);
    white-space: nowrap;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
}
.nav-chevron {
    font-size: .65rem;
    color: var(--clr-text-muted);
    transition: transform .2s ease;
}
.user-trigger.show .nav-chevron {
    transform: rotate(180deg);
}

/* Dropdown menu */
.user-dropdown-menu {
    min-width: 220px;
    border: 1px solid var(--clr-border) !important;
    border-radius: 16px !important;
    box-shadow: 0 8px 32px rgba(15,23,42,.12) !important;
    padding: 6px !important;
    margin-top: 6px !important;
    background: var(--clr-surface);
}

/* Dropdown header */
.dd-header {
    padding: 10px 12px 12px;
    border-bottom: 1px solid var(--clr-border);
    margin-bottom: 4px;
}
.dd-header .dd-name {
    font-family: 'Sora', sans-serif;
    font-size: .82rem;
    font-weight: 700;
    color: var(--clr-text-primary);
    margin-bottom: 2px;
}
.dd-header .dd-email {
    font-size: .72rem;
    color: var(--clr-text-muted);
}
.dd-header .dd-role-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 6px;
    padding: .2em .7em;
    border-radius: 20px;
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.dd-role-pill.admin { background: rgba(37,99,235,.1); color: #2563eb; }
.dd-role-pill.siswa { background: rgba(16,185,129,.1); color: #059669; }

/* Dropdown items */
.dd-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: 10px;
    font-size: .825rem;
    color: var(--clr-text-primary);
    text-decoration: none;
    transition: background .15s ease;
    cursor: pointer;
    border: none;
    background: transparent;
    width: 100%;
}
.dd-item:hover {
    background: var(--clr-bg);
    color: var(--clr-text-primary);
}
.dd-item .dd-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}
.dd-item-label { font-weight: 500; }
.dd-item-sub { font-size: .68rem; color: var(--clr-text-muted); margin-top: 1px; }

.dd-divider {
    height: 1px;
    background: var(--clr-border);
    margin: 4px 6px;
}

/* Logout item */
.dd-item.logout { color: #ef4444; }
.dd-item.logout:hover { background: rgba(239,68,68,.07); }
.dd-item.logout .dd-icon { background: rgba(239,68,68,.1); color: #ef4444; }

@media (max-width: 576px) {
    .nav-user-info { display: none; }
    .topbar-divider { display: none; }
    .role-chip { display: none; }
}
</style>

{{-- Right Section --}}
<div class="topbar-right">

    {{-- Role Chip --}}
    <span class="role-chip d-none d-sm-inline-flex admin">
            <i class="bi bi-shield-fill-check" style="font-size:.65rem;"></i>
    </span>

    <div class="topbar-divider d-none d-sm-block"></div>

    {{-- User Dropdown --}}
    <div class="dropdown">
        <button class="user-trigger"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <div class="nav-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="nav-user-info d-none d-sm-block">
                <div class="nav-user-name">{{ auth()->user()->name }}</div>
                <div class="nav-user-email">{{ auth()->user()->email }}</div>
            </div>
            <i class="bi bi-chevron-down nav-chevron d-none d-sm-inline"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">

            {{-- Header --}}
            <li>
                <div class="dd-header">
                    <div class="dd-name">{{ auth()->user()->name }}</div>
                    <div class="dd-email">{{ auth()->user()->email }}</div>
                    <span class="dd-role-pill admin">
                            <i class="bi bi-shield-fill-check"></i>
                    </span>
                </div>
            </li>
            {{-- Logout --}}
            <li>
                <a class="dd-item logout btn-logout"
                   href="#"
                   data-url="{{ route('logout') }}">
                    <div class="dd-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div>
                        <div class="dd-item-label">Logout</div>
                        <div class="dd-item-sub" style="color: rgba(239,68,68,.6);">Akhiri sesi ini</div>
                    </div>
                </a>
            </li>

        </ul>
    </div>

</div>