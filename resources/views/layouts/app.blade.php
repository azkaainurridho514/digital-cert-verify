<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — OLC System</title>

    {{-- SEMUA ASSET OFFLINE --}}
    <!-- <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/sweetalert/sweetalert2.min.css') }}"> -->

    {{-- [UI] Custom app stylesheet --}}
    <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">

    {{-- CSS CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- [UI] Google Fonts — Sora (headings) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    {{-- [UI] Design System & Layout Styles --}}
    <style>
        /* ============================================================
           DESIGN TOKENS — Edit warna/spacing di sini saja
        ============================================================ */
        :root {
            /* Brand palette */
            --clr-primary:        #2563eb;
            --clr-primary-hover:  #1d4ed8;
            --clr-primary-soft:   #eff6ff;
            --clr-accent:         #0ea5e9;

            /* Surface */
            --clr-bg:             #f1f5f9;
            --clr-surface:        #ffffff;
            --clr-surface-hover:  #f8fafc;
            --clr-border:         #e2e8f0;

            /* Text */
            --clr-text-primary:   #0f172a;
            --clr-text-secondary: #64748b;
            --clr-text-muted:     #94a3b8;

            /* Sidebar */
            --sidebar-bg:         #0f172a;
            --sidebar-text:       #94a3b8;
            --sidebar-text-hover: #f1f5f9;
            --sidebar-active-bg:  rgba(37, 99, 235, 0.18);
            --sidebar-active-txt: #60a5fa;
            --sidebar-width:      260px;
            --sidebar-collapsed:  72px;

            /* Navbar */
            --navbar-h:           60px;

            /* Misc */
            --radius-sm:   8px;
            --radius-md:   12px;
            --radius-lg:   16px;
            --shadow-card: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
            --shadow-navbar: 0 1px 0 var(--clr-border);
            --transition:  all .2s ease;
        }

        /* ============================================================
           RESET / BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            font-size: .9375rem;
            background-color: var(--clr-bg);
            color: var(--clr-text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .fw-semibold, .fw-bold {
            font-family: 'Sora', sans-serif;
        }

        a { color: inherit; text-decoration: none; }

        /* ============================================================
           OVERLAY (mobile)
        ============================================================ */
        #overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .45);
            backdrop-filter: blur(2px);
            z-index: 1040;
            transition: opacity .25s ease;
        }
        #overlay.show { display: block; }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: width .25s ease, transform .25s ease;
            overflow: hidden;
        }

        /* Brand / Logo area */
        #sidebar .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 16px;
            min-height: var(--navbar-h);
            border-bottom: 1px solid rgba(255,255,255,.06);
            white-space: nowrap;
            overflow: hidden;
        }
        #sidebar .sidebar-brand .brand-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            background: var(--clr-primary);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #fff;
        }
        #sidebar .sidebar-brand .brand-text {
            font-family: 'Sora', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            color: #f8fafc;
            letter-spacing: -.01em;
            line-height: 1.2;
        }
        #sidebar .sidebar-brand .brand-text span {
            display: block;
            font-size: .7rem;
            font-weight: 400;
            color: var(--sidebar-text);
            letter-spacing: .02em;
        }

        /* Nav list */
        #sidebar .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.08) transparent;
        }
        #sidebar .sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        /* Section label */
        #sidebar .nav-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--sidebar-text);
            padding: 16px 20px 6px;
            white-space: nowrap;
            overflow: hidden;
            opacity: 1;
            transition: opacity .2s;
        }

        /* Nav item */
        #sidebar .nav-item {
            padding: 2px 10px;
        }
        #sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: var(--sidebar-text);
            font-size: .875rem;
            font-weight: 400;
            white-space: nowrap;
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }
        #sidebar .nav-link i {
            font-size: 1.1rem;
            min-width: 20px;
            text-align: center;
            transition: var(--transition);
        }
        #sidebar .nav-link .link-text {
            opacity: 1;
            transition: opacity .2s;
        }
        #sidebar .nav-link:hover {
            background: rgba(255,255,255,.07);
            color: var(--sidebar-text-hover);
        }
        #sidebar .nav-link.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-txt);
            font-weight: 500;
        }
        #sidebar .nav-link.active i {
            color: var(--clr-primary);
        }
        /* Active indicator bar */
        #sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--clr-primary);
            border-radius: 0 3px 3px 0;
        }

        /* Sidebar footer */
        #sidebar .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        /* ---- COLLAPSED STATE (desktop) ---- */
        #sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        #sidebar.collapsed .brand-text,
        #sidebar.collapsed .link-text,
        #sidebar.collapsed .nav-label {
            opacity: 0;
            pointer-events: none;
        }
        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 9px 0;
        }
        #sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 20px 0 16px;
        }

        /* ---- MOBILE STATE ---- */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }
            #sidebar.mobile-show {
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(0,0,0,.25);
            }
        }

        /* ============================================================
           MAIN WRAPPER
        ============================================================ */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .25s ease;
        }
        #main-wrapper.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed);
        }
        @media (max-width: 768px) {
            #main-wrapper {
                margin-left: 0 !important;
            }
        }

        /* ============================================================
           NAVBAR (top bar)
        ============================================================ */
        #top-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            height: var(--navbar-h);
            background: var(--clr-surface);
            border-bottom: 1px solid var(--clr-border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
        }

        #toggle-sidebar {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            border-radius: var(--radius-sm);
            color: var(--clr-text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
        }
        #toggle-sidebar:hover {
            background: var(--clr-bg);
            color: var(--clr-text-primary);
        }

        /* Breadcrumb / page title area */
        .navbar-page-info {
            flex: 1;
            overflow: hidden;
        }
        .navbar-page-info .page-title {
            font-family: 'Sora', sans-serif;
            font-size: .9rem;
            font-weight: 600;
            color: var(--clr-text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .navbar-page-info .breadcrumb-nav {
            font-size: .75rem;
            color: var(--clr-text-muted);
        }

        /* Right side actions */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .navbar-icon-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            border-radius: var(--radius-sm);
            color: var(--clr-text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        .navbar-icon-btn:hover {
            background: var(--clr-bg);
            color: var(--clr-text-primary);
        }
        .navbar-icon-btn .badge-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 1.5px solid var(--clr-surface);
        }

        /* Avatar / user chip */
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 10px 5px 5px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: transparent;
        }
        .navbar-user:hover { background: var(--clr-bg); }
        .navbar-user .avatar {
            width: 32px;
            height: 32px;
            background: var(--clr-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Sora', sans-serif;
            font-size: .75rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .navbar-user .user-info { text-align: left; line-height: 1.3; }
        .navbar-user .user-name {
            font-size: .8rem;
            font-weight: 600;
            color: var(--clr-text-primary);
            white-space: nowrap;
        }
        .navbar-user .user-role {
            font-size: .7rem;
            color: var(--clr-text-muted);
            white-space: nowrap;
        }
        @media (max-width: 576px) {
            .navbar-user .user-info { display: none; }
        }

        /* ============================================================
           PAGE CONTENT
        ============================================================ */
        #page-content {
            flex: 1;
            padding: 28px 28px 40px;
        }
        @media (max-width: 768px) {
            #page-content { padding: 20px 16px 32px; }
            #top-navbar { padding: 0 16px; }
        }

        /* ============================================================
           CARD — Global override agar seragam
        ============================================================ */
        .card {
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            background: var(--clr-surface);
        }
        .card-header {
            background: var(--clr-surface);
            border-bottom: 1px solid var(--clr-border);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            padding: 16px 20px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: .9rem;
        }
        .card-body { padding: 20px; }
        .card-footer {
            background: var(--clr-surface-hover);
            border-top: 1px solid var(--clr-border);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg) !important;
            padding: 14px 20px;
        }

        /* ============================================================
           TABLE — Clean minimal style
        ============================================================ */
        .table {
            font-size: .875rem;
            color: var(--clr-text-primary);
            margin-bottom: 0;
        }
        .table thead th {
            font-family: 'Sora', sans-serif;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--clr-text-muted);
            background: var(--clr-bg);
            border-bottom: 1px solid var(--clr-border);
            padding: 10px 14px;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--clr-border);
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr { transition: background .15s; }
        .table tbody tr:hover td { background: var(--clr-surface-hover); }
        .table-responsive { border-radius: var(--radius-lg); }

        /* ============================================================
           BUTTONS
        ============================================================ */
        .btn {
            font-family: 'DM Sans', sans-serif;
            font-size: .875rem;
            font-weight: 500;
            border-radius: var(--radius-sm);
            padding: .45rem 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background: var(--clr-primary);
            border-color: var(--clr-primary);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--clr-primary-hover);
            border-color: var(--clr-primary-hover);
            color: #fff;
        }
        .btn-outline-primary {
            color: var(--clr-primary);
            border-color: var(--clr-primary);
        }
        .btn-outline-primary:hover {
            background: var(--clr-primary);
            color: #fff;
        }
        .btn-sm {
            font-size: .8rem;
            padding: .3rem .75rem;
            gap: 4px;
        }
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================================
           FORM CONTROLS
        ============================================================ */
        .form-label {
            font-size: .82rem;
            font-weight: 600;
            color: var(--clr-text-secondary);
            margin-bottom: .35rem;
            font-family: 'Sora', sans-serif;
        }
        .form-control, .form-select {
            font-size: .875rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--clr-border);
            background: var(--clr-surface);
            color: var(--clr-text-primary);
            padding: .5rem .85rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
            background: var(--clr-surface);
        }
        .form-control::placeholder { color: var(--clr-text-muted); }
        .input-group-text {
            background: var(--clr-bg);
            border-color: var(--clr-border);
            color: var(--clr-text-muted);
            font-size: .875rem;
        }
        .form-check-input:checked {
            background-color: var(--clr-primary);
            border-color: var(--clr-primary);
        }

        /* ============================================================
           BADGE
        ============================================================ */
        .badge {
            font-family: 'DM Sans', sans-serif;
            font-size: .72rem;
            font-weight: 500;
            padding: .3em .65em;
            border-radius: 6px;
        }

        /* ============================================================
           PAGINATION
        ============================================================ */
        .pagination .page-link {
            font-size: .82rem;
            border-radius: var(--radius-sm) !important;
            border-color: var(--clr-border);
            color: var(--clr-text-secondary);
            margin: 0 2px;
            padding: .35rem .65rem;
        }
        .pagination .page-item.active .page-link {
            background: var(--clr-primary);
            border-color: var(--clr-primary);
        }
        .pagination .page-link:hover {
            background: var(--clr-bg);
            color: var(--clr-primary);
        }

        /* ============================================================
           ALERTS
        ============================================================ */
        .alert {
            border-radius: var(--radius-md);
            border: none;
            font-size: .875rem;
            padding: 14px 18px;
        }
        .alert-success  { background: #f0fdf4; color: #166534; }
        .alert-danger   { background: #fef2f2; color: #991b1b; }
        .alert-warning  { background: #fffbeb; color: #92400e; }
        .alert-info     { background: var(--clr-primary-soft); color: #1e40af; }

        /* ============================================================
           MODALS
        ============================================================ */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .modal-header {
            border-bottom: 1px solid var(--clr-border);
            padding: 18px 22px;
        }
        .modal-title {
            font-family: 'Sora', sans-serif;
            font-size: .95rem;
            font-weight: 600;
        }
        .modal-body { padding: 22px; }
        .modal-footer {
            border-top: 1px solid var(--clr-border);
            padding: 14px 22px;
        }
        .btn-close { opacity: .5; }
        .btn-close:hover { opacity: 1; }

        /* ============================================================
           STAT CARD — Reusable utility
        ============================================================ */
        .stat-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 20px;
        }
        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .stat-card .stat-value {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card .stat-label {
            font-size: .8rem;
            color: var(--clr-text-secondary);
        }

        /* ============================================================
           SCROLLBAR — Global thin style
        ============================================================ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--clr-border); border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        /* ============================================================
           SWEETALERT2 overrides
        ============================================================ */
        .swal2-popup.swal2-toast {
            border-radius: var(--radius-md) !important;
            font-family: 'DM Sans', sans-serif !important;
            box-shadow: var(--shadow-card) !important;
        }
        .swal2-popup {
            border-radius: var(--radius-lg) !important;
            font-family: 'DM Sans', sans-serif !important;
        }
        .swal2-title { font-family: 'Sora', sans-serif !important; }
    </style>

    @stack('styles')
</head>
<body>

{{-- [LOGIC] Overlay untuk mobile sidebar — JANGAN dihapus --}}
<div id="overlay"></div>

{{-- [LOGIC] Sidebar component --}}
@include('components.sidebar')

{{-- [UI] Main content wrapper --}}
<div id="main-wrapper">

    {{-- [UI] Top Navbar wrapper — menggantikan navbar component langsung --}}
    <nav id="top-navbar">
        {{-- [LOGIC] Toggle button — id & fungsionalitas tidak diubah --}}
        <button id="toggle-sidebar" type="button" aria-label="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>

        {{-- [UI] Page title area (opsional, bisa dikosongkan di component navbar) --}}
        <div class="navbar-page-info d-none d-sm-block">
            <div class="page-title">@yield('title', 'Dashboard')</div>
        </div>

        {{-- [UI] Right actions --}}
        <div class="navbar-actions ms-auto">
            {{-- [LOGIC] Navbar component tetap diinclude untuk menjaga logic (dropdown, notif, dll) --}}
            @include('components.navbar')
        </div>
    </nav>

    {{-- [UI] Page content --}}
    <div id="page-content">
        @yield('content')
    </div>

</div>

{{-- SEMUA JS OFFLINE --}}
<!-- <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/sweetalert/sweetalert2.all.min.js') }}"></script> -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

{{-- [LOGIC] Semua script di bawah ini TIDAK diubah sama sekali --}}
<script>
$(function () {
    // ===== TOGGLE SIDEBAR =====
    $('#toggle-sidebar').on('click', function () {
        const isMobile = $(window).width() <= 768;

        if (isMobile) {
            $('#sidebar').toggleClass('mobile-show');
            $('#overlay').toggleClass('show');
        } else {
            $('#sidebar').toggleClass('collapsed');
            $('#main-wrapper').toggleClass('sidebar-collapsed');
            // Simpan state ke localStorage
            const isCollapsed = $('#sidebar').hasClass('collapsed');
            localStorage.setItem('sidebar_collapsed', isCollapsed);
        }
    });

    // Tutup sidebar saat klik overlay (mobile)
    $('#overlay').on('click', function () {
        $('#sidebar').removeClass('mobile-show');
        $(this).removeClass('show');
    });

    // Restore state sidebar dari localStorage
    if (localStorage.getItem('sidebar_collapsed') === 'true' && $(window).width() > 768) {
        $('#sidebar').addClass('collapsed');
        $('#main-wrapper').addClass('sidebar-collapsed');
    }

    // ===== LOGOUT KONFIRMASI =====
    $(document).on('click', '.btn-logout', function (e) {
        e.preventDefault();
        const logoutUrl = $(this).data('url') || '{{ route("logout") }}';

        Swal.fire({
            title: 'Keluar dari Sistem?',
            text: 'Sesi Anda akan diakhiri.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Ya, Logout',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'rounded-3 px-4',
                cancelButton: 'rounded-3 px-4',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form logout
                $('<form>', {
                    method: 'POST',
                    action: logoutUrl
                }).append(
                    $('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' })
                ).appendTo('body').submit();
            }
        });
    });

    // ===== FLASH MESSAGE via SweetAlert =====
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        timer: 2500,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        customClass: { popup: 'rounded-4' }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        confirmButtonColor: '#2563eb',
        customClass: { popup: 'rounded-4', confirmButton: 'rounded-3 px-4' }
    });
    @endif
});
</script>

@stack('scripts')
</body>
</html>