@extends('layouts.backend.superadmin.app')

@section('content')
<style>
    .report-page { background: #f4f7fe; padding: 25px; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; }
    .header-title { color: #2b3674; font-weight: 800; }
    
    /* Styling khusus Form Filter agar Modern */
    .card-filter form .col-md-3 {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .card-filter .form-control, 
    .card-filter .form-select {
        height: 45px;
        background-color: #f4f7fe !important;
        border: 2px solid transparent !important;
        color: #2b3674;
        font-weight: 600;
        font-size: 14px;
        padding-left: 15px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    /* Efek saat input diklik (Focus) */
    .card-filter .form-control:focus, 
    .card-filter .form-select:focus {
        background-color: #ffffff !important;
        border-color: #4318ff !important;
        box-shadow: 0px 10px 20px rgba(67, 24, 255, 0.05);
        outline: none;
    }

    /* Tombol Terapkan Filter */
    .btn-filter {
        height: 45px;
        background: #2b3674; /* Warna gelap agar kontras dengan Cetak Laporan */
        color: white;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-filter:hover {
        background: #1b2559;
        transform: translateY(-2px);
        color: white;
    }

    /* Label Styling */
    .card-filter label {
        margin-bottom: 0;
        padding-left: 5px;
        background: #ffffff;
    }

    /* Table Styling */
    .card-table {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 14px 17px 40px 4px rgba(112, 144, 176, 0.08);
    }

    .custom-table thead th {
        background: #2b3674;
        color: #ffffff;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        padding: 15px;
        border: none;
    }

    .custom-table tbody td {
        padding: 15px;
        color: #2b3674;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 1px solid #f1f4f9;
    }

    .btn-export {
        background: #2b3674;
        color: white;
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
    }

    .btn-export:hover { background: #3311cc; color: white; transform: translateY(-2px); }

    .status-badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
    }
    .bg-success-light { background: #e6faf5; color: #05cd99; }
    .bg-primary-light { background: #eef2ff; color: #4318ff; }
</style>

<div class="report-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="header-title mb-1">Laporan Perpustakaan</h2>
            <p class="text-muted small">Rekapitulasi data transaksi buku secara keseluruhan</p>
        </div>
        <button onclick="window.print()" class="btn-export">
            <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
        </button>
    </div>

    <div class="card-filter">
        <form action="{{ route('superadmin.laporanperpustakaan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Mulai Tanggal</label>
                <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn-filter w-100">
                    <i class="bi bi-filter"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-table">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                    <tr>
                        <td>#{{ $row->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $row->nama_anggota }}</div>
                            <small class="text-muted" style="font-size: 10px;">{{ $row->user->email ?? '-' }}</small>
                        </td>
                        <td>{{ $row->buku->judul ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tgl_kembali)->format('d M Y') }}</td>
                        <td class="text-center">
                            @if($row->status == 'selesai')
                                <span class="status-badge bg-success-light">SELESAI</span>
                            @else
                                <span class="status-badge bg-primary-light">DIPINJAM</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Data laporan tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection