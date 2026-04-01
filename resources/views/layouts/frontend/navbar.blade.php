<style>
/* WRAPPER */
.navbar-wrapper {
    width: 100%;
    font-family: Arial, sans-serif;
}

/* TOP BAR (BIRU) */
.navbar-top {
    background: #2c5d8f;
    color: white;
    padding: 15px 25px;
    font-size: 22px;
    font-weight: bold;
}

/* MENU BAR */
.navbar-menu {
    background: #f5f5f5;
    padding: 10px 25px;
    display: flex;
    gap: 25px;
    border-bottom: 2px solid #ddd;
}

/* LINK MENU */
.navbar-menu a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    position: relative;
    padding-bottom: 5px;
}

/* ACTIVE / HOVER GOLD LINE */
.navbar-menu a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0%;
    height: 2px;
    background: gold;
    transition: 0.3s;
}

.navbar-menu a:hover::after,
.navbar-menu a.active::after {
    width: 100%;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .navbar-menu {
        flex-wrap: wrap;
        gap: 15px;
    }
}
</style>

<div class="navbar-wrapper">

    <!-- TOP -->
    <div class="navbar-top">
        📚 Perpustakaan Digital
    </div>

    <!-- MENU -->
    <div class="navbar-menu">
        <a href="{{ route('databuku.index') }}" 
           class="{{ Route::currentRouteName() == 'databuku.index' ? 'active' : '' }}">Dashboard</a>

        <a href="{{ route('index') }}" 
           class="{{ in_array(Route::currentRouteName(), ['katalogbuku.index','katalogbuku.show']) ? 'active' : '' }}">
           Katalog Buku
        </a>

        <a href="{{ route('peminjamansaya.index') }}" 
           class="{{ in_array(Route::currentRouteName(), ['peminjamansaya.index','peminjamansaya.show']) ? 'active' : '' }}">
           Peminjaman Saya
        </a>

        <a href="{{ route('denda.index') }}" 
           class="{{ Route::currentRouteName() == 'denda.index' ? 'active' : '' }}">Denda</a>
    </div>

</div>