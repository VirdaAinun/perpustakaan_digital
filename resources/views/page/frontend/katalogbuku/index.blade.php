@extends('layouts.frontend.app')

@section('content')

<style>
.overlay{
background:rgba(0,0,0,0.55);
min-height:100vh;
padding-bottom:50px;
}

.container {
background-color: white;
padding: 30px 40px;
min-height: 100vh;
color: black;
}

/* Container Utama Filter */
.filter-box {
    display: flex;
    gap: 12px;
    align-items: center;
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    flex-wrap: wrap; /* Supaya rapi di layar HP */
}

/* Styling Input Pencarian */
.filter-box input[type="text"] {
    flex: 1; /* Mengambil ruang sisa agar panjang */
    min-width: 200px;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.filter-box input[type="text"]:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

/* Styling Dropdown (Select) */
.filter-box select {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: white;
    cursor: pointer;
    font-size: 14px;
    min-width: 150px;
}

/* Styling Tombol Filter */
.filter-box button {
    padding: 10px 25px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.filter-box button:hover {
    background-color: #2980b9;
}

/* Responsif untuk HP */
@media (max-width: 600px) {
    .filter-box {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-box input[type="text"], 
    .filter-box select, 
    .filter-box button {
        width: 100%;
    }
}
.book-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:30px;
}

.book-card{
background:white;
color:black;
padding:20px;
border-radius:10px;
text-align:center;
box-shadow:0 6px 15px rgba(0,0,0,0.3);
}

.book-card img{
width:120px;
height:160px;
object-fit:cover;
margin-bottom:10px;
}

.book-card h3{
margin:10px 0 5px;
color:#1f5f99;
}

.penulis{
color:#777;
font-size:14px;
}

.kategori{
font-size:13px;
color:#555;
margin-bottom:5px;
}

.book-info{
display:flex;
justify-content:space-between;
margin:10px 0;
}

.badge{
padding:4px 10px;
border-radius:6px;
font-size:12px;
font-weight:bold;
}

.tersedia{
background:#9dd89d;
color:#0c6b0c;
}

.dipinjam{
background:#ccc;
}

.btn-pinjam{
background:#1f5f99;
color:white;
border:none;
padding:8px 20px;
border-radius:6px;
cursor:pointer;
}

.btn-disabled{
background:#aaa;
color:white;
border:none;
padding:8px 20px;
border-radius:6px;
}

.modal{
display:none;
position:fixed;
top:0;left:0;
width:100%;height:100%;
background:rgba(0,0,0,0.5);
justify-content:center;
align-items:center;
z-index:999;
}

.modal-content{
background:#1f5f99;
color:white;
padding:25px;
border-radius:10px;
width:350px;
text-align:center;
}

.modal-content input{
width:100%;
padding:10px;
margin-bottom:10px;
border-radius:6px;
border:none;
}

.notif{
position:fixed;
top:20px;
right:20px;
background:linear-gradient(45deg,#00c851,#007e33);
color:white;
padding:15px 25px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.3);
z-index:9999;
animation:slideIn 0.5s ease;
}

@keyframes slideIn{
from{opacity:0;transform:translateX(100px);}
to{opacity:1;transform:translateX(0);}
}
</style>

{{-- NOTIF --}}
@if(session('success'))
<div id="notif" class="notif">
    {{ session('success') }}
</div>
@endif

<div class="overlay">
<div class="container">

<h2>Katalog Buku</h2>

{{-- FILTER --}}
<form method="GET" action="{{ route('katalogbuku.index') }}">
<div class="filter-box">

    {{-- SEARCH --}}
    <input type="text" name="search" placeholder="Cari judul buku...."
        value="{{ request('search') }}">

    {{-- KATEGORI --}}
    <select name="kategori_id" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach($kategoris as $k)
            <option value="{{ $k->id }}"
                {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kategori }}
            </option>
        @endforeach
    </select>

    <button type="submit">Filter</button>

</div>
</form>

{{-- GRID --}}
<div class="book-grid">

@forelse($bukus as $buku)
<div class="book-card">

    {{-- FOTO --}}
    @if($buku->photo)
        <img src="{{ asset('storage/'.$buku->photo) }}">
    @else
        <img src="https://via.placeholder.com/120x160">
    @endif

    <h3>{{ $buku->judul }}</h3>
    <p class="penulis">{{ $buku->penulis }}</p>

    {{-- ✅ KATEGORI --}}
    <p class="kategori">
        {{ $buku->kategori->nama_kategori ?? '-' }}
    </p>

    <div class="book-info">
        @if($buku->stok > 0)
            <span class="badge tersedia">Tersedia</span>
        @else
            <span class="badge dipinjam">Habis</span>
        @endif

        <span>Stok: {{ $buku->stok }}</span>
    </div>

    {{-- BUTTON --}}
    @if($buku->stok > 0)
        <button class="btn-pinjam"
            onclick="openModal('{{ $buku->id }}','{{ $buku->judul }}','{{ $buku->penulis }}')">
            Pinjam Buku
        </button>
    @else
        <button class="btn-disabled">Stok Habis</button>
    @endif

</div>
@empty
<p>Tidak ada buku</p>
@endforelse

</div>

</div>

{{-- MODAL --}}
<div id="modalPinjam" class="modal">
<div class="modal-content">

<h3>Ajukan Peminjaman</h3>

<p><b>Judul:</b> <span id="mJudul"></span></p>
<p><b>Penulis:</b> <span id="mPenulis"></span></p>

<form action="{{ route('katalog.store') }}" method="POST">
@csrf

<input type="hidden" name="buku_id" id="mIdBuku">
<p style="margin-bottom:10px;"><b>Peminjam:</b> {{ Auth::user()->name }}</p>
<input type="number" name="jumlah_pinjam" placeholder="Jumlah Pinjam" required>
<input type="date" name="tgl_pinjam" required>
<input type="date" name="tgl_kembali" required>

<button type="button" onclick="tutupModal()">Batal</button>
<button type="submit">Ajukan</button>

</form>

</div>
</div>

{{-- SCRIPT --}}
<script>
function openModal(id,judul,penulis){
document.getElementById("modalPinjam").style.display="flex";
document.getElementById("mIdBuku").value=id;
document.getElementById("mJudul").innerText=judul;
document.getElementById("mPenulis").innerText=penulis;
}

function tutupModal(){
document.getElementById("modalPinjam").style.display="none";
}

setTimeout(() => {
let notif = document.getElementById('notif');
if(notif){
notif.style.transition = "0.5s";
notif.style.opacity = "0";
setTimeout(()=> notif.remove(), 500);
}
}, 3000);
</script>

@endsection