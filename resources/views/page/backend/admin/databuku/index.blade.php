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

/* FILTER & SEARCH (Simulasi Baris Atas) */
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
.btn-add-book {
    background: #005fa8; /* Biru sesuai gambar */
    color: #fff;
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
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
}

.table-custom thead {
    background: #005fa8; /* Header biru pekat */
}

.table-custom th {
    padding: 15px;
    color: #ffffff;
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
}

.status-tersedia {
    background-color: #d4edda;
    color: #28a745;
    border: 1px solid #c3e6cb;
}

.status-dipinjam {
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
}
</style>

<div class="container-custom">
    <h2 class="title">Data Buku</h2>
    <br>

    <div class="header-flex">
    <form action="{{ route('databuku.index') }}" method="GET" class="filter-row">
        
        <select name="penerbit" class="form-select-custom" onchange="this.form.submit()">
            <option value="">Penerbit</option>
            @foreach($penerbitList as $p)
                <option value="{{ $p->penerbit }}" {{ request('penerbit') == $p->penerbit ? 'selected' : '' }}>
                    {{ $p->penerbit }}
                </option>
            @endforeach
        </select>

        <select name="status" class="form-select-custom" onchange="this.form.submit()">
            <option value="">Status Buku</option>
            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Dipinjam/Habis</option>
        </select>

        <input type="text" name="search" class="form-control-custom" 
               placeholder="Cari judul buku...." value="{{ request('search') }}">
    </form>

    <a href="{{ route('databuku.create') }}" class="btn-add-book">
        + Tambah Buku
    </a>
</div>

    <div class="table-container">
        <table class="table-custom text-center">
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th>JUDUL BUKU</th>
                    <th>PENULIS</th>
                    <th>KATEGORI</th>
                    <th>PENERBIT</th>
                    <th>STOK</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bukus as $index => $item)
                <tr>
                 
    <td>{{ $index + 1 }}</td>
    <td class="text-primary" style="font-weight: 500; text-align: left;">
        {{ $item->judul }}
    </td>
    <td>{{ $item->penulis }}</td>
    
    <td>{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}</td>
    
    <td>{{ $item->penerbit }}</td>
    <td>{{ $item->stok }}</td>
    <td>
        @if($item->stok > 0)
            <span class="badge-status status-tersedia">TERSEDIA</span>
        @else
            <span class="badge-status status-dipinjam">HABIS</span>
        @endif
    </td>
                    <td>
                        <a href="{{ route('databuku.edit', $item->id) }}" class="text-primary btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('databuku.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-danger border-0 bg-transparent btn-action" onclick="return confirm('Hapus?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
