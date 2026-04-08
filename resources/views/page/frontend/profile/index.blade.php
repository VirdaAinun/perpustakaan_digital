@extends('layouts.frontend.app')

@section('content')
<style>
    /* Menggunakan Font yang sama dengan login agar konsisten */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');

    .profile-wrapper {
        min-height: 90vh;
        display: flex;
        justify-content: center;
        align-items: center;
        /* Ganti gambar dengan gradient navy yang elegan */
        background:silver;
        font-family: 'Inter', sans-serif;
        padding: 20px;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 800px;
        display: flex;
        flex-wrap: wrap; /* Agar responsif di HP */
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        position: relative;
    }

    /* Bagian Kiri (Foto) */
    .profile-left {
        flex: 1;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        min-width: 280px;
    }

    .avatar-wrapper {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: #e2e8f0;
        margin-bottom: 20px;
        overflow: hidden;
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-change-photo {
        background: #5dade2;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
    }

    .btn-change-photo:hover {
        background: #3498db;
    }

    /* Bagian Kanan (Form) */
    .profile-right {
        flex: 1.5;
        padding: 40px;
        min-width: 320px;
    }

    .profile-right h3 {
        color: #0f172a;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

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
        border-color: #5dade2;
        box-shadow: 0 0 0 3px rgba(93, 173, 226, 0.1);
    }

    .btn-save {
        background: #5dade2;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        margin-top: 10px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-save:hover {
        background: #3498db;
        transform: translateY(-2px);
    }

    /* Tombol Logout Pojok Kanan Atas */
    .logout-box {
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .btn-logout {
        background: #f1948a;
        color: white;
        padding: 8px 15px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-logout:hover {
        background: #e74c3c;
    }
</style>
<script>
function confirmLogout() {
    if (confirm("Apakah anda yakin ingin logout?")) {
        document.getElementById('logout-form').submit();
    }
}
</script>
<div class="profile-wrapper">
    <div class="profile-card">
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-box">
    @csrf
    <button type="button" class="btn-logout" onclick="confirmLogout()">
        <span>🚪</span> Log out
    </button>
</form>

        <div class="profile-left">
            <div class="avatar-wrapper">
                {{-- Ganti src dengan path foto anggota jika ada --}}
                <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama) }}&background=cbd5e1&color=0f172a&size=180" alt="Profile">
            </div>
            <button class="btn-change-photo">Ubah Foto Profil</button>
        </div>

        <div class="profile-right">
            <h3>Edit Profil</h3>

            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control-custom" value="{{ $anggota->nama ?? '' }}" placeholder="Nama Lengkap">
                </div>

                <div class="form-group">
                    <label>Email / NIS</label>
                    <input type="text" class="form-control-custom" value="{{ $anggota->nis ?? '' }}" placeholder="Email atau NIS">
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" class="form-control-custom" value="{{ $anggota->kelas ?? '' }}" placeholder="Kelas">
                </div>

                <div class="form-group">
                    <label>Password baru (optional)</label>
                    <input type="password" class="form-control-custom" placeholder="kosongkan jika tidak mengubah">
                </div>

                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </form>
        </div>

    </div>
</div>
@endsection