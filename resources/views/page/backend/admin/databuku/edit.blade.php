@extends('layouts.backend.admin.app')

@section('content')

<style>
.form-card {
    background: #ffffff;
    padding: 25px;
    border-radius: 10px;
    border: 1px solid #ddd;
    max-width: 650px;
    margin: auto;
}

/* TITLE */
.title {
    color: #2c3e50;
    margin-bottom: 20px;
    text-align: center;
    font-size: 20px;
    font-weight: 600;
}

/* ERROR */
.error-box {
    background: #fff3f3;
    border: 1px solid #e74c3c;
    color: #c0392b;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

/* INPUT */
.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    color: #2c3e50;
}

.input-group input {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    outline: none;
}

.input-group input:focus {
    border-color: #34495e;
}

/* BUTTON */
.btn-primary {
    background: #34495e;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 6px;
    width: 100%;
    font-weight: 500;
    cursor: pointer;
}

.btn-primary:hover {
    background: #2c3e50;
}

/* PREVIEW */
.preview {
    text-align: center;
    margin-top: 10px;
}

.preview img {
    width: 110px;
    height: 150px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ddd;
}
</style>

<div class="form-card">
    
<h2 class="title">Edit Data Buku</h2>

{{-- ERROR --}}
@if ($errors->any())
    <div class="error-box">
        @foreach ($errors->all() as $error)
            <p style="margin:0;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('databuku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- FOTO --}}
    <div class="input-group">
        <label>Foto Buku</label>
        <input type="file" name="photo">

        <div class="preview">
            @if($buku->photo)
                <img src="{{ asset('storage/'.$buku->photo) }}">
            @else
                <p style="font-size:13px;color:#888;">Tidak ada gambar</p>
            @endif
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="input-group">
        <label>Judul Buku</label>
        <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" required>
    </div>

    {{-- PENULIS --}}
    <div class="input-group">
        <label>Penulis</label>
        <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" required>
    </div>

    {{-- PENERBIT --}}
    <div class="input-group">
        <label>Penerbit</label>
        <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" required>
    </div>

    {{-- KATEGORI --}}
    <div class="input-group">
        <label>Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori', $buku->kategori) }}" required>
    </div>

    {{-- STOK --}}
    <div class="input-group">
        <label>Stok</label>
        <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" required>
    </div>

    <button type="submit" class="btn-primary">Update Data</button>

</form>

</div>

@endsection
