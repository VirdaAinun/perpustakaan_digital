@extends('layouts.backend.superadmin.app')

@section('content')
<style>
    /* Wrapper Utama agar Card berada di Tengah */
    .profile-wrapper { 
        min-height: 80vh; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        background: #f0f2f5; 
        padding: 20px; 
    }

    /* Card Utama dengan efek Split */
    .profile-card { 
        background: white; 
        border-radius: 20px; 
        width: 100%; 
        max-width: 800px; 
        display: flex; 
        flex-wrap: wrap; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.15); 
        position: relative; 
        overflow: hidden; 
        border: none;
    }

    /* Bagian Kiri (Foto & Nama) */
    .profile-left { 
        flex: 1; 
        background: #e8f0fe; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        padding: 40px; 
        min-width: 280px; 
    }

    .avatar-wrapper { 
        width: 150px; 
        height: 150px; 
        border-radius: 50%; 
        background: #dbeafe; 
        margin-bottom: 20px; 
        overflow: hidden; 
        border: 4px solid white; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }

    /* Bagian Kanan (Form Informasi) */
    .profile-right { 
        flex: 1.5; 
        padding: 40px; 
        min-width: 320px; 
    }

    .profile-right h3 { 
        margin-bottom: 25px; 
        font-weight: 700; 
        color: #1e293b; 
    }

    /* Form Styling */
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #475569; }
    
    .form-control-custom { 
        width: 100%; 
        padding: 12px 15px; 
        border: 1.5px solid #e2e8f0; 
        border-radius: 10px; 
        font-size: 14px; 
        outline: none; 
        transition: 0.3s;
    }

    .form-control-custom:focus { 
        border-color: #3b82f6; 
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1); 
    }

    /* Tombol-Tombol */
    .btn-save-profile { 
        background: #3b82f6; 
        color: white; 
        width: 100%; 
        border: none; 
        padding: 12px; 
        border-radius: 10px; 
        font-weight: 600; 
        margin-bottom: 10px;
        transition: 0.3s;
    }

    .btn-save-profile:hover { background: #2563eb; transform: translateY(-1px); }

    .btn-password { 
        background: #64748b; 
        color: white; 
        border: none; 
        padding: 10px 20px; 
        border-radius: 10px; 
        font-weight: 600; 
        cursor: pointer; 
        transition: 0.3s; 
        width: 100%; 
    }

    .btn-password:hover { background: #475569; }

    /* Section Password yang bisa di-toggle */
    .password-section { 
        display: none; 
        margin-top: 20px; 
        padding-top: 20px; 
        border-top: 1px dashed #e2e8f0; 
    }

    .btn-save-pw { 
        background: #1e293b; 
        color: white; 
        width: 100%; 
        border: none; 
        padding: 12px; 
        border-radius: 10px; 
        font-weight: 600; 
        cursor: pointer; 
    }

    .text-danger { color: #ef4444; font-size: 12px; margin-top: 5px; display: block; }

    /* Status Badge */
    .role-badge {
        background: #dcfce7;
        color: #166534;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        margin-top: 10px;
    }
</style>

<div class="profile-wrapper">
    <div class="profile-card">
        
        <div class="profile-left">
            <div class="avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($kepala->name) }}&background=bfdbfe&color=1e3a8a&size=150" alt="Avatar">
            </div>
            <h4 style="margin:0; font-weight:700; color: #1e293b;">{{ $kepala->name }}</h4>
            <div class="role-badge">SUPERADMIN</div>
        </div>

        <div class="profile-right">
            <h3>Informasi Akun</h3>

            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 14px;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control-custom" value="{{ $kepala->name }}">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control-custom" value="{{ $kepala->email }}">
                </div>

                <button type="submit" class="btn-save-profile">Simpan Perubahan Profil</button>
            </form>

            <button type="button" class="btn-password" id="togglePasswordBtn">
                🔑 Ganti Password Keamanan
            </button>

            <div class="password-section" id="passwordSection">
                <form action="{{ route('admin.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control-custom" required placeholder="Masukkan password lama">
                        @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="new_password" class="form-control-custom" required placeholder="Minimal 8 karakter">
                        @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control-custom" required placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn-save-pw">Update Keamanan Akun</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Logic untuk memunculkan/menyembunyikan form password
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        var section = document.getElementById('passwordSection');
        if (section.style.display === "none" || section.style.display === "") {
            section.style.display = "block";
            this.innerHTML = "✖ Batal Ganti Password";
            this.style.background = "#94a3b8";
        } else {
            section.style.display = "none";
            this.innerHTML = "🔑 Ganti Password Keamanan";
            this.style.background = "#64748b";
        }
    });

    // Tetap buka form password jika ada error dari server
    @if($errors->any())
        document.getElementById('passwordSection').style.display = "block";
        document.getElementById('togglePasswordBtn').innerHTML = "✖ Batal Ganti Password";
    @endif
</script>
@endsection