@extends('layouts.frontend.app')

@section('content')

<style>
    .pinjam-wrapper {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .judul {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .buku-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .buku-info h4 {
        margin: 0;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: 600;
        margin-bottom: 5px;
        display: block;
    }

    input, textarea {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-submit:hover {
        background: #218838;
    }

    .btn-back {
        display: block;
        text-align: center;
        margin-top: 10px;
        color: #555;
        text-decoration: none;
    }
</style>

<div class="pinjam-wrapper">

    <div class="judul">
        📚 Form Peminjaman Buku
    </div>

    {{-- INFO BUKU --}}
    <div class="buku-info">
        <h4>{{ $buku->judul }}</h4>
        <small>{{ $buku->penulis }} - {{ $buku->penerbit }}</small>
    </div>

    {{-- FORM PINJAM --}}
    <form action="{{ route('katalog.storePinjam', $buku->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" required>
        </div>

        <div class="form-group">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" required>
        </div>

        <div class="form-group">
            <label>Catatan (opsional)</label>
            <textarea name="catatan" rows="3" placeholder="Contoh: untuk tugas sekolah"></textarea>
        </div>

        <button type="submit" class="btn-submit">
            Kirim Pengajuan Pinjam
        </button>

    </form>

    <a href="{{ route('katalogbuku.index') }}" class="btn-back">
        ← Kembali ke Katalog
    </a>

</div>

@endsection