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
    <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">
    {{-- CSS CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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