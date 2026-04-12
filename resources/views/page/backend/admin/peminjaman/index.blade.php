@extends('layouts.backend.admin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    /* CONTAINER UTAMA */
    .container-main {
        background: #ffffff;
        padding: 30px;
        min-height: 90vh;
        border-radius: 8px;
    }

    /* JUDUL HALAMAN */
    h5 {
        font-weight: 700;
        color: #2c3e50;
        font-size: 22px;
        margin-bottom: 25px;
    }

    /* STYLING TABEL CUSTOM */
    .table-custom {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #f0f0f0;
    }

    .table-custom thead {
        background: #1a5da4 !important; /* Warna Biru Header */
    }

    .table-custom th {
        color: white !important;
        text-align: center;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 15px;
        border: none;
    }

    .table-custom td {
        padding: 15px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 13px;
        color: #333;
        vertical-align: middle;
    }

    /* TEKS DETAIL DALAM TABEL */
    .text-name { font-weight: 600; color: #444; display: block; }
    .text-email { font-size: 11px; color: #999; }
    .text-book { font-weight: 600; color: #444; display: block; }
    .text-author { font-size: 11px; color: #888; }
    .text-date { font-weight: 800; color: #333; }

    /* BADGE STATUS (PILL STYLE) */
    .badge-pill-custom {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .status-menunggu { background: #fff4e0; color: #f39c12; }
    .status-dipinjam { background: #e1f7ea; color: #27ae60; }
    .status-selesai { background: #ebf5ff; color: #1a5da4; }

    /* TOMBOL AKSI */
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        margin: 0 2px;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-check { background: #bdf9b1; color: #27ae60; }
    .btn-cross { background: #f9b1b1; color: #c0392b; }
    .btn-view { background: #96b9f9; color: #1a5da4; }
    .btn-action:hover { opacity: 0.8; transform: scale(1.08); }

    /* PENYESUAIAN TAMPILAN PLUGIN DATATABLES & PAGINATION */
    .dataTables_wrapper .pagination {
        display: flex !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 15px 0 0 0 !important;
        justify-content: flex-end;
    }

    .dataTables_wrapper .pagination li {
        margin: 0 2px;
        display: inline-block !important;
    }

    .dataTables_wrapper .pagination li a {
        text-decoration: none !important;
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        display: block;
        color: #1a5da4;
    }

    .page-item.active .page-link {
        background-color: #1a5da4 !important;
        border-color: #1a5da4 !important;
        color: white !important;
    }

    .dataTables_info {
        padding-top: 20px !important;
        font-size: 13px;
        color: #777;
    }

    /* NOTIFIKASI */
    .notif-toast {
        position: fixed; top: 20px; right: 20px;
        background: #1a5da4; color: white;
        padding: 15px 25px; border-radius: 10px;
        z-index: 9999; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .terlambat {
    background: #f3a2a2;
    color: #a00000;
}
</style>

@if(session('success'))
    <div id="notif-box" class="notif-toast">
        {{ session('success') }}
    </div>
@endif

<div class="container-main">
    <h5>Data Peminjaman Buku</h5>

    <div class="table-responsive">
        <table id="peminjamanTable" class="table table-hover table-custom text-center" style="width:100%">
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th style="text-align: left;">NAMA ANGGOTA</th>
                    <th style="text-align: left;">JUDUL BUKU</th>
                    <th>TGL PINJAM</th>
                    <th>JUMLAH</th>
                    <th>STATUS</th>
                    <th width="120">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: left;">
                        <span class="text-name">{{ $item->nama_anggota }}</span>
                        <span class="text-email">{{ $item->user->email ?? '-' }}</span>
                    </td>
                    <td style="text-align: left;">
                        <span class="text-book">{{ $item->buku->judul ?? '-' }}</span>
                        <span class="text-author">{{ $item->buku->penulis ?? 'Penulis Tidak Diketahui' }}</span>
                    </td>
                    <td>
                        <span class="text-date">
                            {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d - m - Y') }}
                        </span>
                    </td>
                    <td><span style="font-weight: 800;">{{ $item->jumlah_pinjam }} Buku</span></td>
                    <td>
                        @if($item->status == 'menunggu')
                            <span class="badge-pill-custom status-menunggu">menunggu verifikasi</span>
                        @elseif($item->status == 'dipinjam')
                            <span class="badge-pill-custom status-dipinjam">dipinjam</span>
                        @elseif($item->status == 'terlambat')
                            <span class="badge-pill-custom" style="background:#fde8e8;color:#c0392b;">terlambat</span>
                        @else
                            <span class="badge-pill-custom status-selesai">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'menunggu')
                            <form action="{{ route('peminjaman.verifikasi', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="aksi" value="setuju">
                                <button type="submit" class="btn-action btn-check" title="Setuju">✔</button>
                            </form>
                            <form action="{{ route('peminjaman.verifikasi', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="aksi" value="tolak">
                                <button type="submit" class="btn-action btn-cross" title="Tolak">✖</button>
                            </form>
                        @else
                            <a href="{{ route('peminjaman.show', $item->id) }}" class="btn-action btn-view" title="Detail">👁</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables (HANYA SATU KALI)
        $('#peminjamanTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50],
            "ordering": true,
            "responsive": true,
            "language": {
                "search": "Cari data:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": '<i class="fas fa-chevron-right"></i>',
                    "previous": '<i class="fas fa-chevron-left"></i>'
                }
            }
        });

        // Auto hide notifikasi sukses
        setTimeout(() => {
            let notif = document.getElementById('notif-box');
            if(notif){
                notif.style.transition = "0.5s";
                notif.style.opacity = "0";
                setTimeout(()=> notif.remove(), 500);
            }
        }, 3000);
    });
</script>
@endsection