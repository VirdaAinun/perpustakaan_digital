@extends('layouts.backend.admin.app')

@section('content')
<style>
    /* ... CSS yang sudah ada tetap dipertahankan ... */
    .profile-wrapper { min-height: 80vh; display: flex; justify-content: center; align-items: center; background: #f4f7fe; padding: 20px; }
    .profile-card { background: white; border-radius: 20px; width: 100%; max-width: 750px; display: flex; flex-wrap: wrap; box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden; }
    .profile-left { flex: 1; background: #e8f0fe; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; min-width: 250px; }
    .avatar-wrapper { width: 150px; height: 150px; border-radius: 50%; background: #dbeafe; margin-bottom: 20px; overflow: hidden; border: 4px solid white; }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .profile-right { flex: 1.5; padding: 40px; min-width: 300px; }
    .profile-right h3 { margin-bottom: 25px; font-weight: 600; color: #0f172a; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; }
    .form-control-custom { width: 100%; padding: 12px 15px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; }
    .form-control-custom:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    
    /* CSS Tambahan untuk Reset Password */
    .btn-password { background: #64748b; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; width: 100%; margin-top: 10px; }
    .btn-password:hover { background: #475569; }
    .password-section { display: none; margin-top: 20px; padding-top: 20px; border-top: 1px dashed #e2e8f0; }
    .btn-save-pw { background: #3b82f6; color: white; width: 100%; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; }
    .text-danger { color: #ef4444; font-size: 12px; margin-top: 5px; display: block; }
    .logout-box { position: absolute; top: 20px; right: 20px; z-index: 10; }
    .btn-logout { background: #f87171; color: white; border:none; padding: 8px 15px; border-radius: 12px; font-weight: 600; cursor: pointer; }
</style>

<div class="profile-wrapper">
    <div class="profile-card">
        <div class="logout-box">
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirmLogout()">
                @csrf
                <button type="submit" class="btn-logout">🚪 Logout</button>
            </form>
        </div>

        <div class="profile-left">
            <div class="avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=93c5fd&color=0f172a&size=150" alt="Avatar">
            </div>
            <h4 style="margin:0; font-weight:700;">{{ $user->name }}</h4>
            
        </div>

        <div class="profile-right">
            <h3>Informasi Akun</h3>

            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 14px;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control-custom" value="{{ $user->name }}" readonly style="background: #f8fafc;">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control-custom" value="{{ $user->email }}" readonly style="background: #f8fafc;">
            </div>

            <button type="button" class="btn-password" id="togglePasswordBtn">
                🔑 Ganti Password
            </button>

            <div class="password-section" id="passwordSection">
                <form action="{{ route('admin.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control-custom" required>
                        @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="new_password" class="form-control-custom" required>
                        @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control-custom" required>
                    </div>

                    <button type="submit" class="btn-save-pw">Simpan Password Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle tampilkan form password
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        var section = document.getElementById('passwordSection');
        if (section.style.display === "none" || section.style.display === "") {
            section.style.display = "block";
            this.innerHTML = "✖ Batal Ganti Password";
            this.style.background = "#94a3b8";
        } else {
            section.style.display = "none";
            this.innerHTML = "🔑 Ganti Password";
            this.style.background = "#64748b";
        }
    });

    function confirmLogout() {
        return confirm('Apakah Anda yakin ingin logout?');
    }

    // Tetap buka form jika ada error validasi
    @if($errors->any())
        document.getElementById('passwordSection').style.display = "block";
        document.getElementById('togglePasswordBtn').innerHTML = "✖ Batal Ganti Password";
    @endif
</script>
@endsection