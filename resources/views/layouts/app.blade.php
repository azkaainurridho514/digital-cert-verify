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
    {{-- CSS CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --navbar-height: 60px;
            --transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1040;
            transition: var(--transition);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        #sidebar .sidebar-brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            padding: 0 18px;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        #sidebar .sidebar-brand .brand-icon {
            width: 34px; height: 34px;
            background: var(--sidebar-active);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
            color: #fff;
        }

        #sidebar .brand-text {
            font-weight: 700;
            font-size: 15px;
            color: #fff;
            letter-spacing: 0.3px;
            transition: var(--transition);
            opacity: 1;
        }

        #sidebar.collapsed .brand-text { opacity: 0; width: 0; }

        #sidebar .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
        }

        #sidebar .sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
        }

        #sidebar .nav-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(148,163,184,0.5);
            padding: 14px 20px 6px;
            white-space: nowrap;
            overflow: hidden;
            transition: var(--transition);
        }

        #sidebar.collapsed .nav-label { opacity: 0; }

        #sidebar .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 18px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            margin: 2px 10px;
            transition: var(--transition);
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        #sidebar .nav-item-link:hover {
            background: var(--sidebar-hover);
            color: #e2e8f0;
        }

        #sidebar .nav-item-link.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text-active);
        }

        #sidebar .nav-item-link .nav-icon {
            font-size: 18px;
            flex-shrink: 0;
            width: 22px;
            text-align: center;
        }

        #sidebar .nav-item-link .nav-text {
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
            opacity: 1;
        }

        #sidebar.collapsed .nav-item-link .nav-text { opacity: 0; width: 0; }

        /* Tooltip saat collapsed */
        #sidebar.collapsed .nav-item-link {
            justify-content: center;
            padding: 12px;
            margin: 2px 8px;
        }

        #sidebar .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }

        /* ===== MAIN WRAPPER ===== */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #main-wrapper.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ===== NAVBAR ===== */
        #topbar {
            height: var(--navbar-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        #toggle-sidebar {
            background: none;
            border: none;
            padding: 6px 8px;
            border-radius: 8px;
            color: #64748b;
            cursor: pointer;
            font-size: 20px;
            transition: var(--transition);
            line-height: 1;
        }

        #toggle-sidebar:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            flex-shrink: 0;
        }

        /* ===== CONTENT ===== */
        #page-content {
            flex: 1;
            padding: 28px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header h4 {
            font-weight: 700;
            font-size: 22px;
            color: #0f172a;
            margin: 0 0 4px;
        }

        .page-header p {
            color: #64748b;
            margin: 0;
            font-size: 14px;
        }

        /* ===== CARDS ===== */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px 24px;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .stat-card .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .stat-card .stat-change {
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .stat-card .stat-change.up { color: #10b981; }
        .stat-card .stat-change.down { color: #ef4444; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }
            #sidebar.mobile-show {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0 !important;
            }
            #overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1035;
            }
            #overlay.show { display: block; }
        }

        /* ===== UTILS ===== */
        .card-modern {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-modern .card-header-modern {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .badge-role {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Overlay mobile --}}
<div id="overlay"></div>

{{-- Sidebar --}}
@include('components.sidebar')

{{-- Main Wrapper --}}
<div id="main-wrapper">
    @include('components.navbar')

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