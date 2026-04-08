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
    
   <a href="{{ route('profile') }}" style="text-decoration: none; color: white;">
    👤
</a>
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
   

    <div class="content">
        @yield('content')
    </div>

    @include('layouts.frontend.footer')

</body>
</html>