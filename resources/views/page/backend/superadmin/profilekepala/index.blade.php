@extends('layouts.backend.superadmin.app')

@section('content')
<style>
    body { background-color: #f8fafc; }

    /* Pengaturan Card agar tidak kepanjangan */
    .profile-card {
        background-color: #e3effa; 
        border-radius: 16px;
        padding: 25px; /* Padding dikurangi agar lebih compact */
        border: none;
        margin-bottom: 20px;
        /* Menghapus height 100% agar tinggi mengikuti isi saja */
    }

    .card-title-custom {
        font-weight: 800;
        color: #1e293b;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    /* Input Styling */
    .form-label-custom {
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
        margin-bottom: 5px;
        display: block;
    }

    .form-control-custom {
        background-color: #adcfea !important; 
        border: 1px solid #9dbddd;
        border-radius: 8px;
        padding: 8px 12px; /* Ukuran input lebih ramping */
        color: #1e293b;
        font-size: 0.9rem;
        width: 100%;
    }

    /* Tombol lebih kecil dan pas */
    .btn-save {
        background-color: #25479b;
        border: none;
        border-radius: 8px;
        color: #ffffff;
        font-weight: 600;
        padding: 8px 20px;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-save:hover {
        background-color: #1a3370;
        color: #fff;
    }

    /* Pembatas lebar agar tidak terlalu lebar ke samping di layar besar */
    .content-wrapper-custom {
        max-width: 900px; /* Membatasi lebar total form */
        margin: 0 auto;
    }
</style>

<div class="container py-4">
    <div class="content-wrapper-custom">
        <div class="mb-4 text-center">
            <h4 class="fw-bold text-dark mb-1">Pengaturan Akun</h4>
            <p class="text-muted small">Kelola informasi profil dan keamanan akun Anda.</p>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="profile-card shadow-sm">
                    <h5 class="card-title-custom">Edit Profil</h5>
                    
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-custom">Nama Petugas</label>
                            <input type="text" name="name" class="form-control-custom" value="{{ $kepala->name }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Email</label>
                            <input type="email" name="email" class="form-control-custom" value="{{ $kepala->email }}">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-save w-100">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="profile-card shadow-sm">
                    <h5 class="card-title-custom">Keamanan Akun</h5>
                    
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label-custom">Password Baru</label>
                            <input type="password" name="password" class="form-control-custom">
                        </div>

                        <div class="mb-2">
                            <label class="form-label-custom">Password Lama</label>
                            <input type="password" name="current_password" class="form-control-custom">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Konfirmasi</label>
                            <input type="password" name="password_confirmation" class="form-control-custom">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-save w-100">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection