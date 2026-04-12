@extends('layouts.backend.admin.app')

@section('content')

<style>
.container-custom {
    padding: 30px;
    background-color: #f8f9fa; /* Background halaman sedikit abu-abu */
}

/* JUDUL & HEADER */
.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.title {
    color: #000;
    font-weight: 700;
    font-size: 24px;
    margin: 0;
}

/* FILTER & SEARCH */
.filter-row {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    align-items: center;
}

.form-select-custom, .form-control-custom {
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    font-size: 14px;
    color: #495057;
}

/* BUTTON TAMBAH */
.btn-add-data {
    background: #005fa8; /* Biru pekat */
    color: #fff !important;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 14px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* TABLE STYLING */
.table-container {
    background: #fff;
    border-radius: 0; 
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
}

.table-custom thead {
    background: #005fa8; 
}

.table-custom th {
    padding: 15px;
    color: #ffffff !important;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #004a82;
}

.table-custom td {
    padding: 15px;
    border: 1px solid #f0f0f0;
    vertical-align: middle;
    font-size: 14px;
    color: #333;
}

/* BADGE STATUS */
.badge-status {
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
    display: inline-block;
}

.status-aktif {
    background-color: #d4edda;
    color: #28a745;
    border: 1px solid #c3e6cb;
}

.status-nonaktif {
    background-color: #f8d7da;
    color: #dc3545;
    border: 1px solid #f5c6cb;
}

/* ACTION BUTTONS */
.btn-action {
    padding: 5px;
    margin: 0 2px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    text-decoration: none !important;
}
</style>

<div class="container-custom">
    <h2 class="title">Data Anggota</h2>
    <br>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="header-flex">
        <form action="{{ route('admin.dataanggota.index') }}" method="GET" class="filter-row">
            
            <select name="kelas" class="form-select-custom" onchange="this.form.submit()">
                <option value="">Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->kelas }}" {{ request('kelas') == $k->kelas ? 'selected' : '' }}>
                        {{ $k->kelas }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" class="form-control-custom" 
                   placeholder="Cari nama atau NIS...." value="{{ request('search') }}">
            
            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="background-color: #045898">Cari</button>
            
            @if(request('kelas') || request('search'))
                <a href="{{ route('admin.dataanggota.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill" style="text-decoration: none">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.dataanggota.create') }}" class="btn-add-data">
            + Tambah Anggota
        </a>
    </div>

    <div class="table-container">
        <table class="table-custom text-center">
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th>NIS</th>
                    <th>NAMA ANGGOTA</th>
                    <th>KELAS</th>
                    <th>STATUS</th>
                    <th width="200">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggota as $index => $row)
                <tr>
                    <td>{{ $anggota->firstItem() + $index }}</td>
                    <td style="font-weight: 600;">{{ $row->nis }}</td>
                    <td class="text-primary" style="font-weight: 500; text-align: left;">
                        {{ $row->nama }}
                    </td>
                    <td>{{ $row->kelas }}</td>
                    <td>
                        @if($row->status == 'aktif')
                            <span class="badge-status status-aktif">AKTIF</span>
                        @else
                            <span class="badge-status status-nonaktif">NON-AKTIF</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.dataanggota.edit', $row->id) }}" class="text-primary btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        
                        <form action="{{ route('admin.dataanggota.destroy', $row->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-danger border-0 bg-transparent btn-action" onclick="return confirm('Hapus anggota ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-muted">Data anggota tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $anggota->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection