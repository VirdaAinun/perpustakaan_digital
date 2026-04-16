<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f0f4f8;
        }

        /* ===== KIRI ===== */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #0f4c81 0%, #1a7abf 60%, #0d9488 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -100px; left: -100px;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            bottom: -80px; right: -80px;
        }

        .left-content { position: relative; z-index: 1; text-align: center; color: white; }

        .left-icon {
            font-size: 72px;
            margin-bottom: 24px;
            display: block;
            filter: drop-shadow(0 8px 16px rgba(0,0,0,0.2));
        }

        .left-content h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .left-content p {
            font-size: 15px;
            opacity: 0.85;
            line-height: 1.7;
            max-width: 320px;
        }

        .left-features {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            width: 100%;
            max-width: 320px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.1);
            padding: 12px 16px;
            border-radius: 10px;
            backdrop-filter: blur(4px);
        }

        .feature-item span:first-child { font-size: 20px; }
        .feature-item span:last-child { font-size: 13px; color: rgba(255,255,255,0.9); font-weight: 500; }

        /* ===== KANAN ===== */
        .right-panel {
            width: 460px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            box-shadow: -10px 0 40px rgba(0,0,0,0.06);
        }

        .login-header { margin-bottom: 36px; }
        .login-header h2 { font-size: 26px; font-weight: 800; color: #1e293b; margin-bottom: 6px; }
        .login-header p { font-size: 14px; color: #64748b; }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #94a3b8;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px 13px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s;
            color: #1e293b;
        }

        .form-control:focus {
            border-color: #0f4c81;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15, 76, 129, 0.1);
        }

        .form-control::placeholder { color: #cbd5e1; }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            user-select: none;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: #0f4c81; }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #0f4c81, #1a7abf);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0d3f6e, #1568a8);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(15, 76, 129, 0.3);
        }

        .btn-login:active { transform: translateY(0); }

        /* ALERT */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-error  { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

        /* DIVIDER */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #cbd5e1;
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ROLE BADGES */
        .role-info {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .role-admin    { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
        .role-petugas  { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .role-anggota  { background: #fdf4ff; color: #9333ea; border: 1px solid #e9d5ff; }

        /* FOOTER */
        .login-footer {
            margin-top: 30px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 30px; box-shadow: none; }
        }
    </style>
</head>
<body>

<!-- KIRI -->
<div class="left-panel">
    <div class="left-content">
        <span class="left-icon">📚</span>
        <h1>Perpustakaan Digital</h1>
        <p>SMK Negeri 3 Banjar — Sistem manajemen perpustakaan digital yang modern dan mudah digunakan.</p>

        </div>
    </div>
</div>

<!-- KANAN -->
<div class="right-panel">
    <div class="login-header">
        <h2>Selamat Datang 👋</h2>
        <p>Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error">⚠ {{ $errors->first() }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">⚠ {{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login.proses') }}">
        @csrf

        <div class="form-group">
            <label>Email atau NIS</label>
            <div class="input-wrapper">
                <span class="input-icon">👤</span>
                <input type="text" name="login" class="form-control"
                       placeholder="Masukkan email atau NIS" value="{{ old('login') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="input-wrapper">
                <span class="input-icon">🔒</span>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Masukkan password" required>
                <span class="toggle-password" onclick="togglePassword()" id="toggleText">Lihat</span>
            </div>
        </div>

        <button type="submit" class="btn-login">Masuk</button>
    </form>


    <div class="login-footer">
        &copy; {{ date('Y') }} Perpustakaan Digital SMK Negeri 3 Banjar
    </div>
</div>

<script>
function togglePassword() {
    const field = document.getElementById('password');
    const text  = document.getElementById('toggleText');
    if (field.type === 'password') {
        field.type = 'text';
        text.textContent = 'Sembunyikan';
    } else {
        field.type = 'password';
        text.textContent = 'Lihat';
    }
}
</script>

</body>
</html>
