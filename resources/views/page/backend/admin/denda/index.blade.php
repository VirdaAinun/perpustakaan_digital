@extends('layouts.backend.admin.app')

@section('content')
<style>
/* CONTAINER */
.container-custom {
    background: #f4f7fe;
    padding: 30px;
    min-height: 90vh;
}

/* TITLE */
.page-title {
    font-weight: 700;
    color: #000;
    font-size: 22px;
    margin-bottom: 25px;
}

/* SEARCH SYSTEM */
.search-wrapper {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}

.input-search-custom {
    border: 1px solid #e0e0e0;
    border-right: none;
    background: #fafafa;
    padding: 12px 20px;
    border-radius: 10px 0 0 10px;
    width: 300px;
    outline: none;
    font-size: 14px;
}

.btn-search-custom {
    background: #1a5da4;
    color: white;
    border: 1px solid #1a5da4;
    padding: 0 20px;
    height: 47px;
    border-radius: 0 10px 10px 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.btn-reset-custom {
    color: #8e44ad;
    text-decoration: none;
    font-weight: 600;
    margin-left: 15px;
    font-size: 14px;
}

/* TABLE STYLING */
.table-custom {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #f0f0f0;
}

.table-custom thead {
    background: #1a5da4; /* Header Biru sesuai desain */
}

.table-custom th {
    color: white;
    text-align: center;
    font-size: 12px;
    text-transform: uppercase;
    padding: 15px;
}

.table-custom td {
    padding: 18px 15px;
    border-bottom: 1px solid #f1f1f1;
    font-size: 13px;
    vertical-align: middle;
}

/* TEXT DETAILS */
.text-main { font-weight: 600; color: #333; display: block; }
.text-sub { font-size: 11px; color: #888; }

/* BADGE STATUS (PILL STYLE) */
.badge-pill-custom {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-belum-bayar { 
    background: #fef2f2; 
    color: #dc2626; 
    border: 1px solid #fecaca;
} 
.status-lunas { 
    background: #f0fdf4; 
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

/* TOMBOL AKSI */
.btn-bayar {
    background: #badcfc;
    color: #1a5da4;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    transition: 0.3s;
    width: 100%;
}

.btn-bayar:hover { background: #96b9f9; }

.btn-selesai {
    background: #eeeeee;
    color: #999999;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: not-allowed;
    width: 100%;
}

/* NOTIFIKASI */
.notif-popup {
    position: fixed; top: 20px; right: 20px;
    background: #1a5da4; color: white;
    padding: 15px 25px; border-radius: 8px;
    z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>

<div class="container-custom">
    <h4 class="page-title">Denda & Pembayaran</h4>

    {{-- Alert Notification --}}
    @if(session('success'))
        <div id="notif" class="notif-popup">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH SYSTEM --}}
    <form action="{{ route('denda.index') }}" method="GET" class="search-wrapper">
        <input type="text" name="search" class="input-search-custom" 
               placeholder="Cari Anggota..." value="{{ request('search') }}">
        <button type="submit" class="btn-search-custom">
            <i class="fas fa-search me-2"></i> Cari
        </button>
        
        @if(request('search'))
            <a href="{{ route('denda.index') }}" class="btn-reset-custom">Reset</a>
        @endif
    </form>

    <table class="table-custom text-center">
        <thead>
            <tr>
                <th width="50">NO</th>
                <th style="text-align: left;">NAMA ANGGOTA</th>
                <th style="text-align: left;">JUDUL BUKU</th>
                <th>TGL KEMBALI</th>
                <th>JUMLAH DENDA</th>
                <th>STATUS</th>
                <th width="180">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="text-align: left;">
                    <span class="text-main">{{ $item->peminjaman->nama_anggota }}</span>
                    <span class="text-sub">{{ $item->peminjaman->user->email ?? '-' }}</span>
                </td>
                <td style="text-align: left;">
                    <span class="text-main">{{ $item->peminjaman->buku->judul ?? '-' }}</span>
                    <span class="text-sub">{{ $item->peminjaman->buku->penulis ?? 'Penulis' }}</span>
                </td>
                <td>
                    <span style="font-weight: 500; color: #444;">
                        {{ $item->peminjaman->tgl_kembali ? \Carbon\Carbon::parse($item->peminjaman->tgl_kembali)->format('d - m - Y') : '-' }}
                    </span>
                </td>
                <td>
                    <span style="font-weight: 700; color: #333;">
                        Rp {{ number_format($item->denda_fix, 0, ',', '.') }}
                    </span>
                </td>
                <td>
                    @if($item->status == 'menunggu')
                        <span class="badge-pill-custom status-belum-bayar">
                             Belum Bayar
                        </span>
                    @else
                        <span class="badge-pill-custom status-lunas">
                             Lunas
                        </span>
                    @endif
                </td>
                <td>
                    @if($item->status == 'menunggu')
                        <form action="{{ route('denda.bayar', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-bayar">Tandai sudah bayar</button>
                        </form>
                    @else
                        <button class="btn-selesai" disabled>Tandai sudah bayar</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-5 text-muted">Belum ada data denda yang tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
// Auto-hide notifikasi
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