@extends('layouts.backend.superadmin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .page-wrapper { padding: 30px; min-height: 100vh; }
    .page-title {
        font-weight: 700; color: #2c3e50; font-size: 22px;
        margin-bottom: 5px; border-left: 5px solid #1a5da4; padding-left: 15px;
    }
    .table-custom { width: 100% !important; border: 1px solid #f0f0f0; }
    .table-custom thead { background: #1a5da4 !important; }
    .table-custom th {
        color: white !important; text-align: center; font-size: 11px;
        text-transform: uppercase; padding: 15px; border: none !important;
    }
    .table-custom td { padding: 15px; border-bottom: 1px solid #f8f9fa; font-size: 13px; vertical-align: middle; background: white;}
    
    .badge-aktif { background: #e1f7ea; color: #27ae60; padding: 5px 15px; border-radius: 50px; font-weight: 600; }
    .badge-tidak { background: #fff4e0; color: #f39c12; padding: 5px 15px; border-radius: 50px; font-weight: 600; }
    .dataTables_wrapper .pagination { display: flex !important; justify-content: flex-end; list-style: none; }
    .dataTables_wrapper .pagination li a { border: 1px solid #dee2e6 !important; padding: 8px 14px !important; color: #1a5da4 !important; text-decoration: none !important; border-radius: 6px !important; margin: 0 2px; }
    .page-item.active .page-link { background-color: #1a5da4 !important; color: white !important; border: none; }

    /* PRINT STYLES */
    .print-header { display: none; }
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; top: 0; left: 0; width: 100%; padding: 20px; }
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 15px; }
        .print-header h2 { font-size: 18px; font-weight: 800; margin: 0; }
        .print-header p { font-size: 12px; margin: 3px 0; color: #333; }
        .print-header .print-meta { font-size: 11px; color: #555; margin-top: 8px; }
        .table-custom thead { background: #1a5da4 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .table-custom th { color: white !important; font-size: 10px; padding: 8px; }
        .table-custom td { font-size: 10px; padding: 7px; }
        .badge-aktif, .badge-tidak { border: 1px solid #ccc; border-radius: 4px; padding: 2px 6px; font-size: 9px; }
        .d-flex, #anggotaTable_wrapper .row { display: none !important; }
        .print-area table { display: table !important; }
        .print-area thead, .print-area tbody, .print-area tr, .print-area th, .print-area td { display: revert !important; }
    }
</style>

<div class="page-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="page-title">Laporan Data Anggota</h5>
            
        </div>
        <a href="{{ route('superadmin.laporananggota.export-pdf', request()->query()) }}" 
           style="background: #1a5da4; color: #fff; text-decoration: none; padding: 10px 22px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 3px 8px rgba(26,93,164,0.3); letter-spacing: 0.3px;">
            <i class="fas fa-file-pdf"></i> Eksport PDF
        </a>
        </div>

    <div class="print-area">
    <div class="print-header">
        <h2>LAPORAN DATA ANGGOTA PERPUSTAKAAN DIGITAL</h2>
        <p class="print-meta">Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} &nbsp;|&nbsp; Total Anggota: {{ count($anggota) }} orang</p>
        <hr style="border-top: 1px solid #000; margin: 8px 0 0 0;">
    </div>

    <div class="table-responsive">
        <table id="anggotaTable" class="table table-hover table-custom">
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th style="text-align: left;">NAMA ANGGOTA</th>
                    <th>NIS</th>
                    <th>KELAS</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td style="text-align: left;">
                        <div class="fw-bold">{{ $row->nama }}</div>
                        <div class="text-muted" style="font-size: 11px;">{{ $row->user->email ?? '-' }}</div>
                    </td>
                    <td class="text-center">{{ $row->nis }}</td>
                    <td class="text-center">{{ $row->kelas }}</td>
                    <td class="text-center">
                        <span class="{{ $row->status == 'aktif' ? 'badge-aktif' : 'badge-tidak' }}">
                            {{ ucfirst($row->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#anggotaTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "paging": true,
            "drawCallback": function(settings) {
                var api = this.api();
                $(api.table().container()).find('.dataTables_paginate').show();
            },
            "language": {
                "search": "Cari Anggota:",
                "lengthMenu": "Tampilkan _MENU_ data",
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

        window.printAll = function() {
            // Tampilkan semua baris
            table.page.len(-1).draw();
            setTimeout(function() {
                window.print();
                // Kembalikan ke 10 per halaman setelah print
                setTimeout(function() {
                    table.page.len(10).draw();
                }, 1000);
            }, 500);
        };
    });
</script>
@endsection