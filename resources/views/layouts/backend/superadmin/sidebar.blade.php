<style>
    /* 1. Impor Font Inter - Ini adalah font yang paling mirip dengan desain tersebut */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --active-bg: #4c6fff; /* Warna biru cerah pada tombol aktif */
        --text-active: #ffffff; /* Warna teks putih pada tombol aktif */
        --text-inactive: #8c8c8c; /* Warna teks abu-abu pudar */
        --icon-inactive: #8c8c8c; /* Warna ikon abu-abu */
        --sidebar-bg: #ffffff;
        --sidebar-width: 260px; /* Lebar sidebar yang disesuaikan */
    }

    /* Reset dasar agar konsisten */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif; /* Menggunakan Inter */
        margin: 0;
        background-color: #f8f9fa; /* Latar belakang body sedikit abu-abu */
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--sidebar-bg);
        position: fixed;
        left: 0;
        top: 0;
        padding: 24px 16px; /* Padding sisi luar */
        display: flex;
        flex-direction: column;
        /* Bayangan sidebar sangat tipis seperti di gambar */
        box-shadow: 1px 0px 10px rgba(0, 0, 0, 0.03); 
        z-index: 1000;
    }

    /* Perapihan Brand Area */
    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 8px; /* Padding dalam brand agar sejajar menu */
        margin-bottom: 40px; /* Jarak ke menu pertama */
    }

    .sidebar-brand img { 
        width: 26px; /* Ukuran logo disesuaikan kecil */
        height: auto;
    }

    .sidebar-brand span {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 16px; /* Ukuran teks brand */
        letter-spacing: -0.3px;
    }

    /* Perapihan Menu List */
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1; /* Biar logout nempel bawah */
    }

    .sidebar-menu li { 
        margin-bottom: 4px; /* Jarak antar menu rapat */
    }

    /* Gaya dasar link menu */
    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px; /* Jarak ikon ke teks */
        padding: 10px 16px; /* Padding dalam tombol */
        text-decoration: none;
        color: var(--text-inactive);
        font-weight: 500; /* Medium weight */
        font-size: 13px; /* Ukuran teks menu kecil cerah */
        border-radius: 8px; /* Rounding tipis pada tombol */
        transition: all 0.2s ease;
    }

    /* Gaya Ikon */
    .sidebar-menu a i { 
        font-size: 16px; /* Ukuran ikon disesuaikan */
        color: var(--icon-inactive);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px; /* Memastikan ikon sejajar vertikal */
    }

    /* --- GAYA MENU AKTIF (INI KUNCINYA) --- */
    /* Berbeda dengan Horizon UI, desain ini menggunakan background biru penuh */
    .sidebar-menu a.active {
        background-color: var(--active-bg);
        color: var(--text-active);
        /* Bayangan biru lembut di bawah tombol aktif */
        box-shadow: 0px 4px 8px rgba(76, 111, 255, 0.2); 
    }

    /* Ikon saat aktif jadi putih */
    .sidebar-menu a.active i {
        color: var(--text-active);
    }

    /* --- HOVER EFFECT --- */
    .sidebar-menu a:hover:not(.active) {
        background-color: #f0f2f5; /* Abu-abu sangat muda saat hover */
        color: #1a1a1a;
    }
    
    .sidebar-menu a:hover:not(.active) i {
        color: #1a1a1a;
    }

    /* Bagian Logout */
    .logout-item { 
        margin-top: auto; /* Mendorong ke paling bawah */
        padding-top: 20px;
        border-top: 1px solid #f0f2f5; /* Garis pembatas tipis */
    }

    /* Jika pakai Bootstrap Icons, pastikan vertical align-nya pas */
    .bi {
        line-height: 1;
        margin-top: -1px; /* Micro adjustment */
    }
</style>

<div class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('path/to/logo.png') }}" alt="Logo">
        <span>Perpustakaan Digital</span>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('superadmin.dashboardkepala') }}" class="{{ Route::is('superadmin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
    <a href="{{ route('superadmin.datauser.index') }}" 
       class="{{ Route::is('superadmin.datauser.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i>
        <span>Data User</span>
    </a>
</li>
        <li>
            <a href="{{ route('superadmin.laporanperpustakaan.index') }}" class="{{ Route::is('superadmin.laporanperpustakaan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span>Laporan Perpustakaan</span>
            </a>
        </li>
        <li>
        <a href="{{ route('superadmin.laporananggota.index') }}" 
        class="{{ Route::is('superadmin.laporananggota.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Laporan Data Anggota</span>
    </a>
</li>
        <li>
            <a href="#">
                <i class="bi bi-person"></i>
                <span>Akun</span>
            </a>
        </li>
        <li class="logout-item">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>