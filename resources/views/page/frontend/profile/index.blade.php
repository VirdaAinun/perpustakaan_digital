@extends('layouts.frontend.app')

@section('content')
<style>
    /* Styling khusus agar lebih aesthetic */
    .profile-wrapper {
        margin-top: 60px;
        display: flex;
        justify-content: center;
    }

    .profile-card {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.3);
        max-width: 900px;
        width: 100%;
    }

    .profile-left {
        flex: 0 0 250px;
        text-align: center;
        border-right: 1px solid #f0f0f0;
        padding-right: 20px;
    }

    .avatar-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .profile-right {
        flex: 1;
        min-width: 300px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #475569;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #1f5f99;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(31, 95, 153, 0.1);
    }

    /* Input readonly style */
    .form-control[readonly] {
        background-color: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }

    .btn-save {
        background: #1f5f99;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-save:hover {
        background: #164a7a;
        transform: translateY(-2px);
    }

    .btn-logout {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 15px;
        transition: 0.3s;
    }

    .btn-logout:hover {
        background: #dc2626;
        color: #fff;
    }

    @media (max-width: 768px) {
        .profile-left {
            flex: 1 1 100%;
            border-right: none;
            border-bottom: 1px solid #f0f0f0;
            padding-right: 0;
            padding-bottom: 30px;
        }
    }
</style>

<div class="container profile-wrapper">
    <div class="profile-card">

        {{-- LEFT SIDE --}}
        <div class="profile-left">
            @php
                $nama = $anggota->nama ?? Auth::user()->name ?? 'User';
            @endphp

            <img 
                src="https://ui-avatars.com/api/?name={{ urlencode($nama) }}&background=1f5f99&color=fff&size=180" 
                alt="Profile"
                class="avatar-img"
            >

            <h4 style="margin:0; color:#1e293b;">{{ $nama }}</h4>
            <p style="color:#64748b; font-size:0.85rem; margin-top:5px;">Siswa SMK Negeri 3 Banjar</p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </button>
            </form>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="profile-right">
            <h3 style="margin-bottom:25px; color:#1e293b; font-weight:700;">Pengaturan Profil</h3>

            @if(!$anggota)
                <div style="padding:20px; background:#fff1f2; color:#be123c; border-radius:10px; border:1px solid #fecdd3;">
                    <strong>⚠️ Data anggota belum terhubung!</strong><br>
                    Silahkan hubungi admin perpustakaan untuk sinkronisasi data NIS Anda.
                </div>
            @else

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom:18px;">
                    <label>Nama Lengkap</label>
                    <input type="text" value="{{ $anggota->nama }}" class="form-control" readonly>
                    
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div class="form-group" style="margin-bottom:18px;">
                        <label>NIS</label>
                        <input type="text" value="{{ $anggota->nis }}" class="form-control" readonly>
                    </div>

                    <div class="form-group" style="margin-bottom:18px;">
                        <label>Kelas</label>
                        <input type="text" name="kelas" value="{{ $anggota->kelas }}" class="form-control" placeholder="Contoh: XII PPLG 1">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:25px;">
                    <label>Status</label>
                    <input type="text" value="{{ strtoupper($anggota->status) }}" class="form-control" readonly>
                </div>

                <button type="submit" class="btn-save">
                    Simpan Perubahan
                </button>

            </form>
            @endif
        </div>

    </div>
</div>
@endsection