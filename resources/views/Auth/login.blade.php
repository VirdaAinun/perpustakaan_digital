<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun</title>
    <style>
        /* Menggunakan Font yang lebih modern */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); /* Deep Navy Gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #334155;
        }

        .login-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        h3 {
            text-align: center;
            margin-bottom: 8px;
            color: #0f172a;
            font-weight: 600;
            font-size: 24px;
        }

        p.subtitle {
            text-align: center;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: #1e293b;
            box-shadow: 0 0 0 4px rgba(30, 41, 59, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0f172a; /* Solid Navy */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background: #1e293b;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Styling Notifikasi */
        .error, .success {
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: center;
        }

        .error { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }
        .success { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            cursor: pointer;
            color: #64748b;
            user-select: none;
        }

        .footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .footer p {
            font-size: 14px;
            color: #64748b;
        }

        .footer a {
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3>Selamat Datang</h3>
    <p class="subtitle">Silakan login untuk mengakses akun Anda</p>

    {{-- Notifikasi --}}
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login.proses') }}">
        @csrf

        <div class="form-group">
            <input type="text" name="login" placeholder="Email atau NIS" required>
        </div>

        <div class="form-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword()" id="toggleText">Lihat</span>
        </div>

        <button type="submit">Masuk ke Akun</button>
    </form>

</div>

<script>
function togglePassword() {
    var pass = document.getElementById("password");
    var toggleText = document.getElementById("toggleText");
    if (pass.type === "password") {
        pass.type = "text";
        toggleText.innerText = "";
    } else {
        pass.type = "password";
        toggleText.innerText = "";
    }
}
</script>

</body>
</html>