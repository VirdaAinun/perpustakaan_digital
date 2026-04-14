@extends('layouts.backend.admin.app')

@section('content')

<style>
.container-custom {
    padding: 30px;
    background-color: #f4f7fe; /* Background halaman sedikit abu-abu */
}

/* JUDUL & HEADER */
.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title-text {
        font-weight: 700;
        color: #005fa8;
        font-size: 20px;
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

/* PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding: 15px 20px;
    background: #fff;
    border-top: 1px solid #f0f0f0;
}
.pagination-info {
    font-size: 13px;
    color: #6c757d;
}
.pagination-links {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.pagination-links li a,
.pagination-links li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    color: #005fa8;
    background: #fff;
    transition: 0.15s;
}
.pagination-links li a:hover {
    background: #e8f0fe;
    border-color: #005fa8;
}
.pagination-links li.active span {
    background: #005fa8;
    border-color: #005fa8;
    color: #fff;
}
.pagination-links li.disabled span {
    color: #cbd5e1;
    border-color: #f1f5f9;
    background: #f8f9fa;
    cursor: not-allowed;
}
</style>

<div class="container-custom">
    <h4 class="page-title-text">Data Buku</h4>
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
            <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
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
                 
    <td>{{ $bukus->firstItem() + $index }}</td>
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
                        <a href="{{ route('databuku.edit', $item->id) }}" class="text-primary btn-action" style="text-decoration:none">
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

    <div class="pagination-wrapper">
        <div class="pagination-info">
            @if($bukus->total() > 0)
                Menampilkan <strong>{{ $bukus->firstItem() }}</strong> - <strong>{{ $bukus->lastItem() }}</strong> dari <strong>{{ $bukus->total() }}</strong> data
            @else
                Tidak ada data
            @endif
        </div>
        <ul class="pagination-links">
            {{-- Prev --}}
            <li class="{{ $bukus->onFirstPage() ? 'disabled' : '' }}">
                @if($bukus->onFirstPage())
                    <span>&#8592;</span>
                @else
                    <a href="{{ $bukus->previousPageUrl() }}">&#8592;</a>
                @endif
            </li>

            {{-- Pages --}}
            @foreach($bukus->getUrlRange(1, $bukus->lastPage()) as $page => $url)
                <li class="{{ $page == $bukus->currentPage() ? 'active' : '' }}">
                    @if($page == $bukus->currentPage())
                        <span>{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                </li>
            @endforeach

            {{-- Next --}}
            <li class="{{ !$bukus->hasMorePages() ? 'disabled' : '' }}">
                @if($bukus->hasMorePages())
                    <a href="{{ $bukus->nextPageUrl() }}">&#8594;</a>
                @else
                    <span>&#8594;</span>
                @endif
            </li>
        </ul>
    </div>

@endsection
