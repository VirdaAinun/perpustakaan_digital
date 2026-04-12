<style>
.sidebar {
    width: 220px;
    height: 100vh;
    background: #ffffff;
    position: fixed;
    top: 70px;
    left: 0;
    padding: 20px 10px;
    border-right: 1px solid #ddd;
}

/* MENU */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    margin: 4px 0;
    color: #2c3e50;
    text-decoration: none;
    border-radius: 6px;
    transition: 0.2s;
    font-size: 14px;
}

/* ICON */
.sidebar a i {
    font-size: 16px;
}

/* HOVER */
.sidebar a:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* ACTIVE */
.sidebar a.active {
    background: #005a9e; 
    font-weight: 500;
    color: white;
}

/* DIVIDER */
.sidebar hr {
    margin: 15px 10px;
    border: none;
    height: 1px;
    background: rgba(255,255,255,0.2);
}

/* LOGOUT */
.logout {
    margin-top: 10px;
    color: #e74c3c !important;
}

.logout:hover {
    background: rgba(231, 76, 60, 0.1);
}
</style>

<div class="sidebar">

<a href="{{ route('admin.dashboard') }}" 
   class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
    <i>🏠</i> Dashboard
</a>

<a href="{{ route('admin.dataanggota.index') }}" 
   class="{{ Route::is('admin.dataanggota.*') ? 'active' : '' }}">
    <i>👥</i> Data Anggota
</a>
    
<a href="{{ route('databuku.index') }}" class="{{ Route::is('databuku.*') ? 'active' : '' }}">
    <i>📚</i> Data Buku
</a>

<a href="{{ route('admin.kategori.index') }}" 
   class="{{ Route::is('admin.kategori.*') ? 'active' : '' }}">
    <i>🏷️</i> Kategori Buku
</a>

<a href="{{ route('peminjaman.index') }}"
   class="{{ Route::is('peminjaman.*') ? 'active' : '' }}">
    <i>📥</i> Peminjaman
</a>

<a href="{{ route('pengembalian.index') }}"
   class="{{ Route::is('pengembalian.*') ? 'active' : '' }}">
    <i>📤</i> Pengembalian
</a>

<a href="{{ route('denda.index') }}"
   class="{{ Route::is('denda.*') ? 'active' : '' }}">
    <i>💰</i> Denda
</a>

<a href="{{ route('admin.laporan.index') }}" 
   class="{{ Route::is('admin.laporan.*') ? 'active' : '' }}">
    <i>📊</i> Laporan
</a>

<hr>

</div>

