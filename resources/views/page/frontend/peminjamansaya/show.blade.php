@extends('layouts.frontend.app')

@section('content')
<style>
.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 0 15px;
}
h1 { font-size: 1.8rem; margin-bottom: 20px; text-align: left; color: #2c3e50; }
.card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    padding: 20px;
}
.card img {
    width: 150px;
    height: 200px;
    object-fit: cover;
    float: left;
    margin-right: 20px;
}
.card-body { overflow: hidden; }
.card-body p { margin: 6px 0; }
.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    color: white;
}
.status-dipinjam { background-color: #2980b9; }
.status-mengajukan_pengembalian { background-color: #f39c12; }
.status-selesai { background-color: #27ae60; }
.status-ditolak { background-color: #c0392b; }
.status-menunggu_verifikasi { background-color: #8e44ad; }
.btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; color: white; margin-top:10px; display:inline-block; }
.btn-back { background-color:#7f8c8d; }
.btn-back:hover { background-color:#636e72; }
</style>

<div class="container">
    <h1>Detail Peminjaman</h1>
    <div class="card">
        @if($peminjaman->buku->photo && file_exists(public_path('storage/'.$peminjaman->buku->photo)))
            <img src="{{ asset('storage/'.$peminjaman->buku->photo) }}" alt="{{ $peminjaman->buku->judul }}">
        @else
            <img src="{{ asset('images/no-image.png') }}" alt="No Image">
        @endif
        <div class="card-body">
            <p><strong>Judul Buku:</strong> {{ $peminjaman->buku->judul }}</p>
            <p><strong>Penulis:</strong> {{ $peminjaman->buku->penulis }}</p>
            <p><strong>Kategori:</strong> {{ $peminjaman->buku->kategori }}</p>
            <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam }}</p>
            <p><strong>Tanggal Kembali:</strong> {{ $peminjaman->tanggal_kembali }}</p>
            <p><strong>Status:</strong> <span class="status status-{{ str_replace('_','-',$peminjaman->status) }}">{{ ucfirst(str_replace('_',' ',$peminjaman->status)) }}</span></p>
            <a href="{{ route('peminjamansaya.index') }}" class="btn btn-back">Kembali</a>
        </div>
    </div>
</div>
@endsection