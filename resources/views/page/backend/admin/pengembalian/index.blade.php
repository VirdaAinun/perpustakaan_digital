@extends('layouts.backend.admin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    /* 1. CONTAINER & TYPOGRAPHY */
    .container-custom {
        background: #f4f7fe;
        padding: 30px;
        min-height: 90vh;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .page-title {
        font-weight: 700;
        color: #005fa8;
        font-size: 20px;
        margin: 0;
    }

    /* 2. TABLE STYLING */
    .table-custom {
        width: 100% !important;
        border: 1px solid #f0f0f0;
    }

    .table-custom thead {
        background: #1a5da4 !important;
    }

    .table-custom th {
        color: white !important;
        text-align: center;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 15px;
        border: none !important;
    }

    .table-custom td {
        padding: 15px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 13px;
        vertical-align: middle;
    }

    .text-main { font-weight: 600; color: #333; display: block; }
    .text-sub { font-size: 11px; color: #888; }

    /* 3. BADGE & BUTTONS */
    .badge-pill-custom {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .status-menunggu { background: #fff4e0; color: #f39c12; }
    .status-selesai { background: #e1f7ea; color: #27ae60; }

    .btn-verifikasi {
        background: #1a5da4;
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-detail {
        background: #f8f9fa;
        color: #1a5da4;
        border: 1px solid #1a5da4;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-verifikasi:hover { background: #14467a; color: white; transform: translateY(-2px); }

    /* 4. FIX PAGINATION (HILANGKAN TITIK/BULLET) */
    .dataTables_wrapper .pagination {
        display: flex !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 20px 0 0 0 !important;
        justify-content: flex-end;
    }

    .dataTables_wrapper .pagination li {
        margin: 0 2px !important;
        display: inline-block !important;
    }

    .dataTables_wrapper .pagination li a {
        border: 1px solid #dee2e6 !important;
        padding: 8px 14px !important;
        color: #1a5da4 !important;
        text-decoration: none !important;
        border-radius: 6px !important;
        display: block !important;
        background: #fff !important;
    }

    .page-item.active .page-link {
        background-color: #1a5da4 !important;
        color: white !important;
        border-color: #1a5da4 !important;
    }

    /* 5. SEARCH & LENGTH UI (SAMA SEPERTI PEMINJAMAN) */
    .dataTables_wrapper .dataTables_filter {
        float: right;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 6px 12px;
        background: #fafafa;
        margin-left: 10px;
        outline: none;
        width: 200px;
    }

    .dataTables_info {
        padding-top: 20px !important;
        font-size: 13px;
        color: #777;
    }
</style>

<div class="container-custom">
    <h4 class="page-title">Data Pengembalian</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table id="pengembalianTable" class="table table-hover table-custom text-center">
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th style="text-align: left;">NAMA ANGGOTA</th>
                    <th style="text-align: left;">JUDUL BUKU</th>
                    <th>TGL PINJAM</th>
                    <th>TGL KEMBALI</th>
                    <th>STATUS</th>
                    <th width="150">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: left;">
                        <span class="text-main">{{ $item->nama_anggota }}</span>
                        <span class="text-sub">{{ $item->user->email ?? '-' }}</span>
                    </td>
                    <td style="text-align: left;">
                        <span class="text-main">{{ $item->buku->judul ?? '-' }}</span>
                        <span class="text-sub">{{ $item->buku->penulis ?? 'Penulis' }}</span>
                    </td>
                    <td><span style="font-weight: 700;">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d - m - Y') }}</span></td>
                    <td><span style="font-weight: 700;">{{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d - m - Y') : '-' }}</span></td>
                    <td>
                        @if($item->status == 'menunggu_verifikasi')
                            <span class="badge-pill-custom status-menunggu">menunggu verifikasi</span>
                        @elseif($item->status == 'terlambat')
                            <span class="badge-pill-custom" style="background:#fde8e8;color:#c0392b;">terlambat</span>
                        @else
                            <span class="badge-pill-custom status-selesai">selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'menunggu_verifikasi')
                            <form action="{{ route('pengembalian.verifikasi', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-verifikasi">Verifikasi</button>
                            </form>
                        @else
                           <a href="{{ route('pengembalian.show', $item->id) }}" class="btn-detail">Lihat Detail</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#pengembalianTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50],
            "ordering": true,
            "responsive": true,
            "language": {
                "search": "Cari data:", // Menyamakan label dengan peminjaman
                "searchPlaceholder": "Ketik nama atau buku...",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": '<i class="fas fa-chevron-right"></i>',
                    "previous": '<i class="fas fa-chevron-left"></i>'
                }
            },
            "dom": "<'row mb-3'<'col-md-6'l><'col-md-6'f>>" +
                   "<'row'<'col-md-12'tr>>" +
                   "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
        });
    });
</script>
@endsection