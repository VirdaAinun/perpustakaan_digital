@extends('layouts.backend.superadmin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* 1. Layout Utama */
    .report-page { padding: 30px; min-height: 100vh; font-family: 'Inter', sans-serif; }
    
    .header-title { color: #111111; font-weight: 700; font-size: 24px; margin-bottom: 2px; }
    .header-subtitle { color: #888; font-size: 15px; margin-bottom: 25px; }
    .search-container { position: relative; max-width: 350px; margin-bottom: 20px; }
    .search-container i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
    .form-search { padding: 12px 15px 12px 40px; border-radius: 10px; border: 1px solid #e0e0e0; background: #fff; width: 100%; font-size: 14px; transition: 0.3s; }
    .form-search:focus { outline: none; border-color: #94b9ff; box-shadow: 0 0 0 3px rgba(148, 185, 255, 0.2); }
    .card-filter { background: white; border-radius: 12px; padding: 20px; border: 1px solid #eeeeee; display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
    .filter-label { font-weight: 700; color: #333; font-size: 14px; letter-spacing: 0.5px; }
    .select-status { min-width: 250px; padding: 10px 15px; border-radius: 12px; border: 1px solid #dddddd; background: #fff; font-size: 14px; color: #555; }
    .btn-apply { background: #94b9ff; color: #2c3e50; font-weight: 700; border: none; padding: 10px 25px; border-radius: 10px; font-size: 13px; transition: 0.3s; }
    .btn-reset { background: #bdbdbd; color: white; font-weight: 700; border: none; padding: 10px 25px; border-radius: 10px; font-size: 13px; text-decoration: none; transition: 0.3s; }
    .btn-apply:hover { background: #7ba6f7; }
    .btn-reset:hover { background: #a5a5a5; color: white; }
    .btn-export-pdf { background: #bdd6ff; color: #2b5297; font-weight: 700; border: none; padding: 10px 25px; border-radius: 8px; font-size: 13px; float: right; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px; }
    .card-table { background: white; border-radius: 0; overflow: hidden; clear: both; border: 1px solid #eee; }
    .custom-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .custom-table thead th { background: #1a56a3; color: white; text-transform: uppercase; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; padding: 15px; border: none; text-align: center; }
    .custom-table thead th.text-left { text-align: left; }
    .custom-table tbody td { padding: 15px; color: #444; font-size: 13px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }
    .text-main-row { font-weight: 600; color: #333; display: block; }
    .text-sub-row { font-size: 11px; color: #888; }
    .badge-status { padding: 6px 16px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .badge-selesai { background: #d4f7e7; color: #28a745; }
    .badge-pinjam { background: #eef2ff; color: #4318ff; }

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
        .custom-table thead th { background: #1a56a3 !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-size: 10px; padding: 8px; }
        .custom-table tbody td { font-size: 10px; padding: 7px; border-bottom: 1px solid #ddd; }
        .badge-status { border: 1px solid #ccc; border-radius: 4px; padding: 2px 6px; font-size: 9px; }
        .text-sub-row { display: none; }
        .btn-export-pdf, .card-filter, .search-container, .header-title, .header-subtitle { display: none !important; }
    }
</style>

<div class="report-page">
    <h2 class="header-title">Laporan Operasional Perpustakaan</h2>
    <p class="header-subtitle">Rekap data peminjaman, pengembalian, dan denda perpustakaan.</p>

    <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" class="form-search" placeholder="Cari Anggota......">
    </div>

    <div class="card-filter shadow-sm">
        <span class="filter-label">JENIS LAPORAN :</span>
        <form action="{{ route('superadmin.laporanperpustakaan.index') }}" method="GET" class="d-flex gap-3 align-items-center flex-grow-1">
            <select name="jenis" class="select-status" onchange="this.form.submit()">
                <option value="peminjaman" {{ $jenis == 'peminjaman' ? 'selected' : '' }}>📥 Peminjaman</option>
                <option value="pengembalian" {{ $jenis == 'pengembalian' ? 'selected' : '' }}>📤 Pengembalian</option>
                <option value="denda" {{ $jenis == 'denda' ? 'selected' : '' }}>💰 Denda</option>
            </select>
            <a href="{{ route('superadmin.laporanperpustakaan.index') }}" class="btn-reset">RESET</a>
        </form>
    </div>

    <button onclick="window.print()" class="btn-export-pdf">
        <i class="fas fa-file-pdf me-1"></i> EKSPORT PDF
    </button>

    <div class="print-area">
    <div class="print-header">
        <h2>LAPORAN PERPUSTAKAAN DIGITAL</h2>
        <p>Jenis Laporan: <strong>{{ strtoupper($jenis) }}</strong></p>
        <p class="print-meta">Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} &nbsp;|&nbsp; Total Data: {{ $laporan->count() }} record</p>
        <hr style="border-top: 1px solid #000; margin: 8px 0 0 0;">
    </div>

    <div class="card-table shadow-sm">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    @if($jenis == 'denda')
                    <tr>
                        <th width="50">NO</th>
                        <th class="text-left">NAMA ANGGOTA</th>
                        <th class="text-left">JUDUL BUKU</th>
                        <th>HARI TERLAMBAT</th>
                        <th>JUMLAH DENDA</th>
                        <th>STATUS</th>
                    </tr>
                    @else
                    <tr>
                        <th width="50">NO</th>
                        <th class="text-left">NAMA ANGGOTA</th>
                        <th class="text-left">JUDUL BUKU</th>
                        <th>TANGGAL PINJAM</th>
                        <th>TANGGAL KEMBALI</th>
                        <th>STATUS</th>
                    </tr>
                    @endif
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                    @if($jenis == 'denda')
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <span class="text-main-row">{{ $row->peminjaman->nama_anggota ?? '-' }}</span>
                            <span class="text-sub-row">{{ $row->peminjaman->user->email ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-main-row">{{ $row->peminjaman->buku->judul ?? 'N/A' }}</span>
                            <span class="text-sub-row">{{ $row->peminjaman->buku->penulis ?? 'N/A' }}</span>
                        </td>
                        <td class="text-center"><b>{{ abs($row->hari_terlambat) }} Hari</b></td>
                        <td class="text-center"><b>Rp {{ number_format(abs($row->denda), 0, ',', '.') }}</b></td>
                        <td class="text-center">
                            @if($row->status == 'selesai')
                                <span class="badge-status badge-selesai">Lunas</span>
                            @else
                                <span class="badge-status badge-pinjam">Belum Bayar</span>
                            @endif
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <span class="text-main-row">{{ $row->nama_anggota }}</span>
                            <span class="text-sub-row">{{ $row->user->email ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-main-row">{{ $row->buku->judul ?? 'N/A' }}</span>
                            <span class="text-sub-row">{{ $row->buku->penulis ?? 'N/A' }}</span>
                        </td>
                        <td class="text-center"><b>{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d - m - Y') }}</b></td>
                        <td class="text-center"><b>{{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d - m - Y') : '-' }}</b></td>
                        <td class="text-center">
                            <span class="badge-status {{ $row->status == 'selesai' ? 'badge-selesai' : 'badge-pinjam' }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                    </tr>
                    @endif
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
</div>
@endsection