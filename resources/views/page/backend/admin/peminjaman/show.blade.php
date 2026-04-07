@extends('layouts.backend.admin.app')

@section('content')
<style>
    .card-detail {
        background: #fff;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .detail-title {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 25px;
        color: #2c3e50;
    }

    .detail-item {
        margin-bottom: 20px;
    }

    .detail-label {
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
        font-weight: 600;
    }

    .detail-value {
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }

    .badge-status {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-menunggu { background: #fff4e0; color: #f39c12; }
    .status-dipinjam { background: #e1f7ea; color: #27ae60; }
    .status-selesai { background: #ebf5ff; color: #1a5da4; }

    .btn-back {
        background: #1a5da4;
        color: #fff;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }

    .btn-back:hover {
        background: #144a82;
        color: #fff;
    }
</style>

<div class="container-main">
    <div class="card-detail">
        <div class="detail-title">Detail Peminjaman Buku</div>

        <div class="row">
            <div class="col-md-6">

                <div class="detail-item">
                    <div class="detail-label">Nama Anggota</div>
                    <div class="detail-value">{{ $data->nama_anggota }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $data->email_anggota ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Judul Buku</div>
                    <div class="detail-value">{{ $data->buku->judul ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Penulis</div>
                    <div class="detail-value">{{ $data->buku->penulis ?? '-' }}</div>
                </div>

            </div>

            <div class="col-md-6">

                <div class="detail-item">
                    <div class="detail-label">Tanggal Pinjam</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($data->tgl_pinjam)->format('d - m - Y') }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Jumlah Pinjam</div>
                    <div class="detail-value">{{ $data->jumlah_pinjam }} Buku</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        @if($data->status == 'menunggu')
                            <span class="badge-status status-menunggu">Menunggu Verifikasi</span>
                        @elseif($data->status == 'dipinjam')
                            <span class="badge-status status-dipinjam">Dipinjam</span>
                        @else
                            <span class="badge-status status-selesai">{{ $data->status }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Keterangan</div>
                    <div class="detail-value">
                        {{ $data->keterangan ?? 'Tidak ada keterangan' }}
                    </div>
                </div>

            </div>
        </div>

        <a href="{{ route('peminjaman.index') }}" class="btn-back">
            ← Kembali ke Data
        </a>
    </div>
</div>
@endsection