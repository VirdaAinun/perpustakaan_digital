@extends('layouts.backend.admin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .container-custom {
        background: #ffffff;
        padding: 30px;
        min-height: 80vh;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .page-title {
        font-weight: 700;
        color: #2c3e50;
        font-size: 22px;
        margin-bottom: 25px;
        border-left: 5px solid #1a5da4;
        padding-left: 15px;
    }

    .badge-pill-custom {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .status-menunggu { background: #fff4e0; color: #f39c12; }
    .status-selesai { background: #e1f7ea; color: #27ae60; }

    .btn-back {
        background: #f8f9fa;
        color: #1a5da4;
        border: 1px solid #1a5da4;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .info-group {
        margin-bottom: 20px;
    }

    .info-title {
        font-weight: 600;
        color: #1a5da4;
        margin-bottom: 5px;
    }

    .info-content {
        font-size: 14px;
        color: #333;
    }
</style>

<div class="container-custom">
    <h5 class="page-title">Detail Pengembalian</h5>

    <div class="info-group">
        <div class="info-title">Informasi Anggota</div>
        <div class="info-content">
            <strong>Nama:</strong> {{ $item->nama_anggota }} <br>
            <strong>Email:</strong> {{ $item->user->email ?? '-' }}
        </div>
    </div>

    <div class="info-group">
        <div class="info-title">Informasi Buku</div>
        <div class="info-content">
            <strong>Judul:</strong> {{ $item->buku->judul ?? '-' }} <br>
            <strong>Penulis:</strong> {{ $item->buku->penulis ?? '-' }}
        </div>
    </div>

    <div class="info-group">
        <div class="info-title">Detail Peminjaman</div>
        <div class="info-content">
            <strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d - m - Y') }} <br>
            <strong>Tanggal Kembali:</strong> {{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d - m - Y') : '-' }} <br>
            <strong>Status:</strong> 
            @if($item->status == 'menunggu_verifikasi')
                <span class="badge-pill-custom status-menunggu">Menunggu Verifikasi</span>
            @else
                <span class="badge-pill-custom status-selesai">Selesai</span>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('pengembalian.index') }}" class="btn-back"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    </div>
</div>
@endsection