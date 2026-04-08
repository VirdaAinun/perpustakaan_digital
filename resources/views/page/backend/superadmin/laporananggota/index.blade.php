@extends('layouts.backend.superadmin.app')

@section('content')
<style>
    /* Jarak antar kontainer utama */
    .header-container {
        margin-bottom: 20px; /* Memberikan jarak ke kartu filter/tabel di bawahnya */
    }

    .header-title { 
        color: #2b3674; 
        font-weight: 800;
        margin-bottom: 5px; 
    }

    /* Memberikan jarak khusus pada tombol cetak agar tidak menempel */
    .btn-print {
        background: #4318ff;
        color: white;
        border-radius: 12px;
        padding: 12px 28px; /* Sedikit lebih besar agar lebih click-friendly */
        font-weight: 700;
        border: none;
        transition: 0.3s;
        white-space: nowrap; /* Mencegah teks tombol terpotong */
        margin-left: 15px; /* Jarak horizontal dari judul */
    }
    .report-page { background: #f4f7fe; padding: 25px; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; }
    .header-title { color: #2b3674; font-weight: 800; }
    
    .card-custom {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 14px 17px 40px 4px rgba(112, 144, 176, 0.08);
        border: none;
    }

    /* Search Bar Styling */
    .form-search {
        background-color: #f4f7fe !important;
        border: 2px solid transparent !important;
        border-radius: 12px;
        padding: 10px 20px;
        color: #2b3674;
        font-weight: 600;
        transition: 0.3s;
    }
    .form-search:focus {
        border-color: #4318ff !important;
        background-color: #fff !important;
        box-shadow: none;
    }

    /* Table Styling agar Sejajar */
    .custom-table { border-collapse: separate; border-spacing: 0 10px; width: 100%; }
    .custom-table thead th {
        background: #f4f7fe;
        color: #a3aed0;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        padding: 15px;
        border: none;
    }
    .custom-table tbody tr { background: white; transition: 0.3s; }
    .custom-table tbody td {
        padding: 15px;
        color: #2b3674;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 1px solid #f1f4f9;
    }

    /* Inisial Nama */
    .user-icon {
        width: 45px;
        height: 45px;
        background: #eef2ff;
        color: #4318ff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 18px;
    }

    .badge-count {
        background: #e6faf5;
        color: #05cd99;
        padding: 6px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .btn-print {
        background: #4318ff;
        color: white;
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
    }
    .btn-print:hover { background: #3311cc; transform: translateY(-2px); }
</style>

<div class="report-page">
    <div class="d-flex justify-content-between align-items-start header-container">
        <div>
            <h2 class="header-title">Laporan Data Anggota</h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Daftar seluruh anggota perpustakaan yang terdaftar dalam sistem.
            </p>
        </div>
        
        <button onclick="window.print()" class="btn-print shadow-sm">
            <i class="bi bi-printer-fill me-2"></i> Cetak Data
        </button>
    </div>

    <div class="card-custom">
        <form action="{{ route('superadmin.laporananggota.index') }}" method="GET" class="mb-4">
            <div class="input-group" style="max-width: 400px;">
                <input type="text" name="search" class="form-search form-control" placeholder="Cari nama atau NIS..." value="{{ request('search') }}">
                <button class="btn btn-primary ms-2 rounded-3" type="submit" style="background: #2b3674; border: none;">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th class="text-center">Total Pinjam</th>
                        <th class="text-center">Status Akun</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $row)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-icon">{{ strtoupper(substr($row->nama, 0, 1)) }}</div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $row->nama }}</div>
                                    <small class="text-muted" style="font-size: 11px;">Anggota Aktif</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-muted">{{ $row->nis }}</span></td>
                        <td><span class="text-muted">{{ $row->kelas }}</span></td>
                        <td class="text-center">
                            <span class="badge-count">{{ $row->peminjaman_count ?? 0 }} Kali</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-status {{ $row->status == 'aktif' ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}" 
                                  style="background: {{ $row->status == 'aktif' ? '#e6faf5' : '#fff5f5' }}; padding: 8px 15px; border-radius: 8px;">
                                {{ $row->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Data anggota tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection