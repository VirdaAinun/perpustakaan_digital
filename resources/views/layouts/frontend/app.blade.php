<!DOCTYPE html>
<html>
<head>
<title>halaman utama</title>
<link rel="stylesheet">
<style>

body{
margin:0;
font-family:Arial, Helvetica, sans-serif;
background-size:cover;
background-position:center;
background-repeat:no-repeat;
min-height:100vh;
position:relative;

}

/* CONTENT */
.container {
    color: #000000;
    background-size:cover;
    padding:50px;
    min-height:90vh;
}

/* HEADER */

.header{
background:#1f5f99;
color:white;
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 30px;
}

.logo{
display:flex;
align-items:center;
gap:10px;
}

.avatar{
width:45px;
height:45px;
border-radius:50%;
object-fit:cover;
}

/* NAVBAR */
.navbar{
background:#eaeaea;
padding:12px 30px;
}

.navbar a{
margin-right:25px;
text-decoration:none;
color:#333;
font-weight:bold;
}

.navbar a.active{
color:#1f5f99;
border-bottom:3px solid #1f5f99;
padding-bottom:5px;
}

/* BACKGROUND IMAGE */

.background{
background-image:url("../image/foto1.jpg.jpeg");
background-size:cover;
background-position:center;
background-repeat:no-repeat;
min-height:100vh;
position:relative;
}

/* OVERLAY */

.overlay{
background:rgba(0,0,0,0.55);
min-height:100vh;
}
/* BARIS ATAS (BIRU) */
.header-top {
    background: #1f5f99;
    color: white;
    padding: 30px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo-area {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-area h2 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}

/* BARIS BAWAH (PUTIH) */
.navbar-row {
    background: white;
    padding:12px 30px;
    display: flex;
    border-bottom: 1px solid #ddd;
}

.nav-link-custom {
    padding: 15px 20px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    font-size: 18px;
    border-bottom: 4px solid transparent;
    display: inline-block;
}

.nav-link-custom:hover {
    color: #1f5f99;
}

.nav-link-custom.active {
    color: #1f5f99;
    border-bottom: 4px solid #1f5f99; /* Garis biru di bawah menu aktif */
}
</style>
</head>

<body>
<div class="header-top">
    <div class="logo-area">
        <span>📚</span> 
        <h2>Perpustakaan Digital</h2>
    </div>
    
    <div style="display:flex; align-items:center; gap:20px;">
        @php
            $notifBelumDibaca = \App\Models\Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', false)->latest()->get();
            $semuaNotif = \App\Models\Notifikasi::where('user_id', auth()->id())->latest()->take(10)->get();
        @endphp

        {{-- BELL NOTIFIKASI --}}
        <div style="position:relative;">
            <button onclick="toggleNotif()" style="background:none;border:none;cursor:pointer;color:white;font-size:22px;position:relative;">
                🔔
                @if($notifBelumDibaca->count() > 0)
                    <span style="position:absolute;top:-5px;right:-5px;background:#ff4444;color:white;border-radius:50%;width:18px;height:18px;font-size:10px;display:flex;align-items:center;justify-content:center;font-weight:bold;">
                        {{ $notifBelumDibaca->count() }}
                    </span>
                @endif
            </button>

            <div id="notifDropdown" style="display:none;position:absolute;right:0;top:40px;width:320px;background:white;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.15);z-index:9999;overflow:hidden;">
                <div style="padding:15px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;color:#333;font-size:14px;">Notifikasi</span>
                    @if($notifBelumDibaca->count() > 0)
                        <form action="{{ route('notifikasi.bacaSemua') }}" method="POST">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:#1f5f99;font-size:12px;cursor:pointer;font-weight:600;">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
                <div style="max-height:300px;overflow-y:auto;">
                    @forelse($semuaNotif as $notif)
                        <div style="padding:12px 20px;border-bottom:1px solid #f8f8f8;background:{{ $notif->dibaca ? 'white' : '#f0f6ff' }};">
                            <div style="display:flex;justify-content:space-between;align-items:start;">
                                <div style="flex:1;">
                                    <div style="font-weight:700;font-size:13px;color:#333;">{{ $notif->judul }}</div>
                                    <div style="font-size:12px;color:#666;margin-top:3px;">{{ $notif->pesan }}</div>
                                    <div style="font-size:10px;color:#aaa;margin-top:5px;">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>
                                @if(!$notif->dibaca)
                                    <form action="{{ route('notifikasi.baca', $notif->id) }}" method="POST" style="margin-left:8px;">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;color:#1f5f99;font-size:11px;cursor:pointer;white-space:nowrap;">✓ Baca</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="padding:30px;text-align:center;color:#aaa;font-size:13px;">Belum ada notifikasi</div>
                    @endforelse
                </div>
            </div>
        </div>

        <a href="{{ route('profile.anggota') }}" style="text-decoration:none;color:white;">👤</a>
    </div>
</div>

<div class="navbar-row">

    <a href="{{ route('katalogbuku.index') }}" 
       class="nav-link-custom {{ in_array(Route::currentRouteName(), ['katalogbuku.index','katalogbuku.show']) ? 'active' : '' }}">
       Katalog Buku
    </a>

    <a href="{{ route('peminjamansaya.index') }}" 
       class="nav-item nav-link-custom {{ in_array(Route::currentRouteName(), ['peminjamansaya.index','peminjamansaya.show']) ? 'active' : '' }}">
       Peminjaman Saya
    </a>

    <a href="{{ route('frontend.denda') }}" 
       class="nav-link-custom {{ Route::currentRouteName() == 'frontend.denda' ? 'active' : '' }}">
       Denda
    </a>
</div>

@if(session('show_welcome'))
    {{ session()->forget('show_welcome') }}
    <div id="welcome-alert" style="
        position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
        background: #1f5f99; color: white;
        padding: 14px 30px; border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        z-index: 9999; font-size: 15px; font-weight: 600;
        display: flex; align-items: center; gap: 10px;
        animation: slideDown 0.4s ease;
    ">
        👋 Selamat datang, <span style="color:#a8d4ff; margin-left:5px;">{{ Auth::user()->name }}</span>!
    </div>
    <style>
        @keyframes slideDown {
            from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
    </style>
    <script>
        setTimeout(() => {
            const el = document.getElementById('welcome-alert');
            if(el){ el.style.transition='0.5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500); }
        }, 3000);
    </script>
@endif
   

    <div class="content">
        @yield('content')
    </div>

    @include('layouts.frontend.footer')

<script>
function toggleNotif(){
    const d = document.getElementById('notifDropdown');
    d.style.display = d.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e){
    const dropdown = document.getElementById('notifDropdown');
    if(dropdown && !dropdown.contains(e.target) && !e.target.closest('button[onclick="toggleNotif()"]')){
        dropdown.style.display = 'none';
    }
});
</script>

</body>
</html>