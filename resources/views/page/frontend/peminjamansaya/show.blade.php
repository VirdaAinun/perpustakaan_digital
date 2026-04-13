@extends('layouts.frontend.app')

@section('content')
<style>
    .detail-wrapper {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px 60px;
    }

    .detail-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f5f99;
        margin-bottom: 20px;
        border-left: 4px solid #1f5f99;
        padding-left: 12px;
    }

    .detail-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        display: flex;
        gap: 0;
    }

    .detail-cover {
        width: 180px;
        min-height: 260px;
        flex-shrink: 0;
        background: #f0f4f8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .detail-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        min-height: 260px;
    }

    .detail-body {
        padding: 28px;
        flex: 1;
    }

    .detail-book-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f5f99;
        margin-bottom: 4px;
    }

    .detail-book-author {
        font-size: 13px;
        color: #888;
        margin-bottom: 20px;
    }

    .detail-divider {
        border: none;
        border-top: 1px solid #f0f0f0;
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .detail-row .label {
        width: 150px;
        color: #888;
        font-weight: 600;
        flex-shrink: 0;
    }

    .detail-row .value {
        color: #333;
        font-weight: 500;
    }

    /* STATUS BADGE */
    .badge-status {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    .status-dipinjam          { background: #dbeafe; color: #1d4ed8; }
    .status-menunggu          { background: #fef3c7; color: #d97706; }
    .status-menunggu-verifikasi { background: #ede9fe; color: #7c3aed; }
    .status-selesai           { background: #dcfce7; color: #16a34a; }
    .status-terlambat         { background: #fee2e2; color: #dc2626; }
    .status-ditolak           { background: #fee2e2; color: #dc2626; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 24px;
        background: #1f5f99;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s;
    }
    .btn-back:hover { background: #164a7a; color: white; }

    @media (max-width: 600px) {
        .detail-card { flex-direction: column; }
        .detail-cover { width: 100%; min-height: 200px; }
        .detail-cover img { min-height: 200px; }
    }
</style>

<div class="detail-wrapper">
    <h2 class="detail-title">Detail Peminjaman</h2>

    <div class="detail-card">
        <div class="detail-cover">
            @if($peminjaman->buku->photo && file_exists(public_path('storage/'.$peminjaman->buku->photo)))
                <img src="{{ asset('storage/'.$peminjaman->buku->photo) }}" alt="{{ $peminjaman->buku->judul }}">
            @else
                <img src="https://via.placeholder.com/180x260?text=No+Image" alt="No Image">
            @endif
        </div>

        <div class="detail-body">
            <div class="detail-book-title">{{ $peminjaman->buku->judul }}</div>
            <div class="detail-book-author">{{ $peminjaman->buku->penulis }}</div>

            <hr class="detail-divider">

            <div class="detail-row">
                <span class="label">Kategori</span>
                <span class="value">{{ $peminjaman->buku->kategori->nama_kategori ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Peminjam</span>
                <span class="value">{{ $peminjaman->nama_anggota }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Tanggal Pinjam</span>
                <span class="value">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Tanggal Kembali</span>
                <span class="value">{{ $peminjaman->tgl_kembali ? \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d M Y') : '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Jumlah Pinjam</span>
                <span class="value">{{ $peminjaman->jumlah_pinjam }} Buku</span>
            </div>
            <div class="detail-row">
                <span class="label">Status</span>
                <span class="value">
                    <span class="badge-status status-{{ str_replace('_','-',$peminjaman->status) }}">
                        {{ ucfirst(str_replace('_',' ',$peminjaman->status)) }}
                    </span>
                </span>
            </div>

            <a href="{{ route('peminjamansaya.index') }}" class="btn-back">
                ← Kembali
            </a>
        </div>
    </div>
</div>
@endsection
