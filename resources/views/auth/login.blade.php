<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — OLC System</title>

    {{-- <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/sweetalert/sweetalert2.min.css') }}">  --}}
        {{-- CSS CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Background dekoratif */
        body::before {
            content: '';
            position: fixed;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -200px; left: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(124,58,237,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 16px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.4);
        }

        .login-logo {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: #fff;
        }

        .login-card h2 {
            font-size: 22px;
            font-weight: 700;
            color: #f1f5f9;
            text-align: center;
            margin-bottom: 6px;
        }

        .login-card .subtitle {
            font-size: 13px;
            color: #64748b;
            text-align: center;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .form-control {
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 10px;
            color: #e2e8f0;
            padding: 11px 14px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: #0f172a;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
            color: #e2e8f0;
        }

        .form-control::placeholder { color: #475569; }

        .input-group-text {
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 10px 0 0 10px;
            color: #64748b;
            padding-right: 10px;
            transition: all 0.2s;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: #2563eb;
            color: #2563eb;
        }

        .input-group:focus-within .form-control {
            border-color: #2563eb;
        }

        .btn-toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #475569;
            cursor: pointer;
            padding: 0;
            z-index: 10;
            transition: color 0.2s;
        }

        .btn-toggle-pass:hover { color: #94a3b8; }

        .btn-login {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 12px;
            width: 100%;
            transition: all 0.25s;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.35);
            color: #fff;
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login .spinner-border {
            width: 16px; height: 16px;
            border-width: 2px;
        }

        .divider-text {
            text-align: center;
            font-size: 12px;
            color: #475569;
            margin: 24px 0 18px;
            position: relative;
        }

        .divider-text::before, .divider-text::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #1e3a5f;
        }

        .divider-text::before { left: 0; }
        .divider-text::after { right: 0; }

        .demo-accounts {
            display: flex;
            gap: 8px;
        }

        .demo-btn {
            flex: 1;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #94a3b8;
            font-size: 12px;
        }

        .demo-btn:hover {
            background: rgba(255,255,255,0.08);
            color: #e2e8f0;
        }

        .demo-btn strong { display: block; color: #e2e8f0; font-size: 13px; }

        .is-invalid { border-color: #ef4444 !important; }
        .invalid-feedback { color: #f87171; font-size: 12px; }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #334155;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">

        <div class="login-logo">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <h2>Selamat Datang</h2>
        <p class="subtitle">Masuk ke OLC System untuk melanjutkan</p>

        <form id="loginForm" action="{{ route('login.post') }}" method="POST" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="nama@email.com"
                           value="{{ old('email') }}"
                           autocomplete="email">
                </div>
                @error('email')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="position-relative">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control pe-5 @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               autocomplete="current-password">
                    </div>
                    <button type="button" class="btn-toggle-pass" id="togglePass">
                        <i class="bi bi-eye-slash" id="passIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-login" id="btnLogin">
                <span id="btnText">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
                </span>
                <span id="btnLoading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Memproses...
                </span>
            </button>

        </form>

        {{-- Demo Accounts --}}
        <div class="divider-text">Akun Demo</div>
        <div class="demo-accounts">
            <div class="demo-btn" onclick="fillDemo('admin@olc.id', 'password')">
                <strong><i class="bi bi-shield-fill-check me-1"></i>Admin</strong>
                admin@olc.id
            </div>
            <div class="demo-btn" onclick="fillDemo('siswa@olc.id', 'password')">
                <strong><i class="bi bi-person-fill me-1"></i>Siswa</strong>
                siswa@olc.id
            </div>
        </div>

    </div>

    <div class="login-footer">
        © {{ date('Y') }} OLC Kampung Inggris — All rights reserved
    </div>
</div>

{{-- <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/sweetalert/sweetalert2.all.min.js') }}"></script> --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
$(function () {

    // ===== SHOW/HIDE PASSWORD =====
    $('#togglePass').on('click', function () {
        const passInput = $('#password');
        const icon = $('#passIcon');
        if (passInput.attr('type') === 'password') {
            passInput.attr('type', 'text');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            passInput.attr('type', 'password');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });

    // ===== FORM SUBMIT + VALIDASI =====
    $('#loginForm').on('submit', function (e) {
        let isValid = true;

        // Reset state
        $('#email, #password').removeClass('is-invalid');
        $('.swal-inline-error').remove();

        const email = $('#email').val().trim();
        const password = $('#password').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            showFieldError('email', 'Email tidak boleh kosong.');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showFieldError('email', 'Format email tidak valid.');
            isValid = false;
        }

        if (!password) {
            showFieldError('password', 'Password tidak boleh kosong.');
            isValid = false;
        } else if (password.length < 6) {
            showFieldError('password', 'Password minimal 6 karakter.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return;
        }

        // Loading state
        $('#btnText').addClass('d-none');
        $('#btnLoading').removeClass('d-none');
        $('#btnLogin').prop('disabled', true);
    });

    function showFieldError(fieldId, message) {
        $('#' + fieldId).addClass('is-invalid');
        $('<div class="invalid-feedback swal-inline-error d-block mt-1">' + message + '</div>')
            .insertAfter('#' + fieldId).closest('.input-group, .position-relative');
    }

    // ===== ERROR DARI SERVER (via session) =====
    @if(session('login_error'))
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: '{{ session("login_error") }}',
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Coba Lagi',
        customClass: {
            popup: 'rounded-4',
            confirmButton: 'rounded-3 px-4'
        }
    });
    @endif

});

// ===== DEMO ACCOUNT FILLER =====
function fillDemo(email, password) {
    $('#email').val(email).trigger('focus');
    $('#password').val(password);

    // Visual feedback
    Swal.fire({
        icon: 'info',
        title: 'Akun Demo Diisi',
        html: '<code>' + email + '</code>',
        timer: 1200,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        customClass: { popup: 'rounded-4' }
    });
}
</script>

</body>
</html>