@extends('layouts.backend.admin.app')

@section('content')
<style>
    /* Global Styles */
    .report-wrapper { 
        background: #f4f7fe; 
        padding: 2.5rem; 
        min-height: 100vh; 
        font-family: 'DM Sans', sans-serif;
    }

    /* Filter Card */
    .content-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 14px 17px 40px 4px rgba(112, 144, 176, 0.08);
    }

    /* Table Styles */
    .table-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 14px 17px 40px 4px rgba(112, 144, 176, 0.08);
        border: none;
    }
    .table thead th {
        background: #1B2559;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border: none;
        padding: 15px;
    }
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #2b3674;
        font-weight: 500;
        border-bottom: 1px solid #f1f4f9;
        font-size: 13px;
    }

    /* Badge Customization */
    .badge-status {
        padding: 6px 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 10px;
        text-transform: uppercase;
    }
    .status-pinjam { background-color: #D1FAE5; color: #065F46; }
    .status-kembali { background-color: #DBEAFE; color: #1E40AF; }
    .status-denda { background-color: #FEE2E2; color: #991B1B; }

    /* Button Customization */
    .btn-apply { background: #4318ff; color: white; border-radius: 10px; border: none; padding: 10px 20px; font-weight: 600; }
    .btn-export { background: #BEE3F8; color: #2B6CB0; border-radius: 8px; border: none; padding: 8px 20px; font-weight: 700; margin-bottom: 15px; }
</style>

<div class="report-wrapper">
    <div class="header-box mb-4">
        <h4 class="fw-bold" style="color: #1B2559;">Laporan Operasional Perpustakaan</h4>
        
    </div>

    <div class="content-card">
        <form action="{{ route('admin.laporan.index') }}" method="GET">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="input-group bg-light rounded-pill px-3">
                        <span class="input-group-text bg-transparent border-0 small fw-bold">Bulan :</span>
                        <select name="bulan" class="form-select bg-transparent border-0 shadow-none small">
                            <option value="">Pilih Bulan</option>
                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $m)
                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="input-group bg-light rounded-pill px-3">
                        <span class="input-group-text bg-transparent border-0 small fw-bold">Jenis Laporan :</span>
                        <select name="jenis" class="form-select bg-transparent border-0 shadow-none small">
                            <option value="Peminjaman" {{ $jenis == 'Peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                            <option value="Pengembalian" {{ $jenis == 'Pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                            <option value="Denda" {{ $jenis == 'Denda' ? 'selected' : '' }}>Denda</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-apply px-4 py-2 small">TERAPKAN FILTER</button>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary bg-secondary border-0 px-4 py-2 small opacity-50 text-white" style="text-decoration:none">RESET</a>
                </div>
            </div>
        </form>
    </div>

    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="btn btn-export shadow-sm" style="text-decoration:none">
            <i class="fas fa-file-pdf me-1"></i> EKSPORT PDF
        </a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Anggota</th>
                        <th width="20%">Judul Buku</th>
                        <th>
                            @if($jenis == 'Denda') Tanggal Laporan @else Tanggal Pinjam @endif
                        </th>
                        <th>
                            @if($jenis == 'Pengembalian') Tanggal Kembali @elseif($jenis == 'Denda') Nominal Denda @else Estimasi Kembali @endif
                        </th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
               <tbody>
@forelse($laporan as $row)
    @php
        $dataPeminjaman = ($jenis == 'Peminjaman') ? $row : $row->peminjaman;
        $buku = $dataPeminjaman->buku ?? null;

        // ✅ FIX NAMA
        $namaAnggota = optional($dataPeminjaman->user)->name 
                        ?? $dataPeminjaman->nama_anggota 
                        ?? 'Umum';

        $email = optional($dataPeminjaman->user)->email ?? '-';
    @endphp

    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>

        {{-- NAMA --}}
        <td>
            <div class="fw-bold">{{ $namaAnggota }}</div>
            <div class="text-muted" style="font-size: 11px;">{{ $email }}</div>
        </td>

        {{-- BUKU --}}
        <td>
    <div class="fw-bold">
        {{ $buku->judul ?? 'Data Buku Dihapus' }}
    </div>
    <div class="text-muted small">
        {{ optional(optional($buku)->kategori)->nama_kategori ?? 'Tanpa Kategori' }}
    </div>
</td>

        {{-- TANGGAL 1 --}}
        <td>
            @if($jenis == 'Denda')
                {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
            @else
                {{ \Carbon\Carbon::parse($dataPeminjaman->tgl_pinjam)->format('d/m/Y') }}
            @endif
        </td>

        {{-- TANGGAL 2 / NOMINAL --}}
        <td>
            @if($jenis == 'Pengembalian')
                <span class="text-primary">
                    {{ \Carbon\Carbon::parse($row->tgl_dikembalikan)->format('d/m/Y') }}
                </span>

            @elseif($jenis == 'Denda')
                <span class="text-danger fw-bold">
                    Rp {{ number_format($row->total_denda ?? 0, 0, ',', '.') }}
                </span>

            @else
                {{ \Carbon\Carbon::parse($dataPeminjaman->tgl_kembali)->format('d/m/Y') }}
            @endif
        </td>

        {{-- STATUS --}}
        <td class="text-center">
            @if($jenis == 'Peminjaman')
                <span class="badge-status status-pinjam">Dipinjam</span>

            @elseif($jenis == 'Pengembalian')
                <span class="badge-status status-kembali">Sudah Kembali</span>

            @elseif($jenis == 'Denda')
                <span class="badge-status status-denda">
                    {{ $row->status == 'menunggu' ? 'Belum Lunas' : 'Lunas' }}
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-5">
            Tidak ada data laporan {{ $jenis }}
        </td>
    </tr>
@endforelse
</tbody>
            </table>
        </div>
    </div>
</div>
@endsection