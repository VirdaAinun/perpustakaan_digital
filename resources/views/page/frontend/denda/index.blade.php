@extends('layouts.frontend.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Menggunakan style baru yang Anda berikan */
    .container-custom {
        padding: 50px;
        min-height: 90vh;
    }

    .line {
        border: 1px solid #5da8ff;
        margin: 15px 0 25px 0;
    }

    /* ===== BOX TOTAL DENDA ===== */
    .denda-box {
        background: white;
        color: black;
        display: flex;
        align-items: center;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .denda-icon {
        font-size: 40px;
        margin-right: 15px;
    }

    .denda-text {
        font-size: 22px;
    }

    /* ===== TABLE ===== */
    .table-box {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        color: black;
    }

    .table-custom thead {
        background: #1f5f99;
        color: white;
    }

    .table-custom th, .table-custom td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table-custom td small {
        color: #777;
    }

    /* ===== STATUS ===== */
    .status-belum {
        background: #f3b3b3;
        color: #b30000;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 13px;
    }
    
    .status-lunas {
        background: #d4edda;
        color: #155724;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 13px;
    }

    /* ===== BUTTON ===== */
    .btn-bayar {
        background: #1f5f99;
        color: white;
        border: none;
        padding: 7px 15px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-bayar:hover {
        background: #174a78;
        color: white;
    }

    /* Form Pencarian Styling agar tetap rapi */
    .search-section {
        margin-bottom: 20px;
    }
    .notif-popup {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #017927;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>
@if(session('success'))
    <div id="notif" class="notif-popup">
        {{ session('success') }}
    </div>
@endif
<div class="container-custom">
    <h2>Denda & Pembayaran</h2>


    <hr class="line">

    <div class="denda-box">
    <div class="denda-icon">💰</div>
    <div class="denda-text">
        Total Denda Aktif : 
    <strong>Rp {{ number_format($totalDendaAktif, 0, ',', '.') }}</strong>
    </div>
</div>

    <div class="table-box">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Terlambat</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dendas as $index => $item)
                <tr>
                    <td>{{ $dendas->firstItem() + $index }}</td>
                    <td>
                        {{ $item->peminjaman->buku->judul ?? 'Buku telah dihapus' }}
                        <br>
                        <small>Peminjam: {{ $item->peminjaman->nama_anggota }}</small>
                    </td>
                    <td>{{ $item->hari_fix }} Hari</td>
                    <td>Rp {{ number_format($item->denda_fix, 0, ',', '.') }}</td>
                    <td>
                        @if($item->status == 'menunggu')
                            <span class="status-belum">Belum Dibayar</span>
                        @else
                            <span class="status-lunas">Lunas</span>
                        @endif
                    </td>
                </tr>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #999;">
                        Data denda tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $dendas->appends(['nama' => $namaAnggota])->links() }}
    </div>
</div>
@endsection