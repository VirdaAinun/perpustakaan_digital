@extends('layouts.backend.admin.app')

@section('content')

<style>
.form-card {
    background: #ffffff;
    padding: 25px;
    border-radius: 10px;
    border: 1px solid #ddd;
    max-width: 600px;
}

.title {
    font-size: 20px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
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
    display: flex;
    flex-direction: column;
}

.input-group label {
    margin-bottom: 5px;
    font-size: 14px;
    color: #2c3e50;
}

.input-group input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
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
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary:hover {
    background: #2c3e50;
}
</style>

<div class="form-card">
    
<h2 class="title">Tambah Buku</h2>

{{-- ERROR --}}
@if ($errors->any())
    <div class="error-box">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('databuku.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="input-group">
        <label>Foto Buku</label>
        <input type="file" name="photo">
    </div>

    <div class="input-group">
        <label>Judul Buku</label>
        <input type="text" name="judul" value="{{ old('judul') }}">
    </div>

    <div class="input-group">
        <label>Penulis</label>
        <input type="text" name="penulis" value="{{ old('penulis') }}">
    </div>

    <div class="input-group">
        <label>Penerbit</label>
        <input type="text" name="penerbit" value="{{ old('penerbit') }}">
    </div>

    <div class="input-group">
    <label>Kategori</label>
    <select name="kategori_id" class="form-control">
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoris as $k)
            <option value="{{ $k->id }}">
                {{ $k->nama_kategori }}
            </option>
        @endforeach
    </select>
</div>

    <div class="input-group">
        <label>Stok</label>
        <input type="number" name="stok" value="{{ old('stok') }}">
    </div>

    <button type="submit" class="btn-primary">Simpan Data</button>

</form>

</div>

@endsection
