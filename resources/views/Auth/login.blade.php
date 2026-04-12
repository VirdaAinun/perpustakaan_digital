<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #045898;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            padding: 40px 35px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            user-select: none;
        }

        .toggle-password:hover {
            color: #3b82f6;
        }

        .btn-login {
            width: 100%;
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #2563eb;
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .login-footer p {
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <div class="login-header">
        <h2>📚 Perpustakaan Digital</h2>
        <p>Silahkan masuk ke akun Anda</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login.proses') }}">
        @csrf

        <div class="form-group">
            <label for="login">Email atau NIS</label>
            <input type="text" id="login" name="login" class="form-control" 
                   placeholder="Masukkan email atau NIS" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Masukkan password" required>
                <span class="toggle-password" onclick="togglePassword()" id="toggleText">Lihat</span>
            </div>
        </div>

        <button type="submit" class="btn-login">Masuk</button>
    </form>

    <div class="login-footer">
        <p>&copy; {{ date('Y') }} Perpustakaan Digital SMK Negeri 3 Banjar</p>
    </div>

</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleText = document.getElementById('toggleText');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleText.textContent = 'Sembunyikan';
    } else {
        passwordField.type = 'password';
        toggleText.textContent = 'Lihat';
    }
}
</script>

</body>
</html>