@extends('layouts.backend.admin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.container-custom { background: #f4f7fe; padding: 30px; min-height: 90vh; }
.page-title { font-weight: 700; color: #005fa8; font-size: 20px; margin: 0 0 20px 0; }

/* FILTER */
.filter-row { display: flex; gap: 10px; align-items: center; margin-bottom: 20px; }
.form-control-custom { padding: 8px 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; color: #495057; outline: none; }
.form-control-custom:focus { border-color: #005fa8; }
.btn-cari { background: #005fa8; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; }
.btn-reset { background: #f1f3f5; color: #555; border: 1px solid #dee2e6; padding: 8px 16px; border-radius: 8px; font-size: 13px; text-decoration: none; }

/* TABLE */
.table-container { background: #fff; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom thead { background: #1a5da4; }
.table-custom th { padding: 13px 15px; color: #fff; text-transform: uppercase; font-size: 11px; font-weight: 600; border: none; letter-spacing: 0.5px; }
.table-custom td { padding: 13px 15px; border-bottom: 1px solid #f0f0f0; font-size: 13px; vertical-align: middle; }
.table-custom tbody tr:last-child td { border-bottom: none; }
.table-custom tbody tr:hover { background: #f9fbff; }

.text-main { font-weight: 600; color: #333; display: block; }
.text-sub { font-size: 11px; color: #888; }

/* BADGE */
.badge-status { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }
.status-menunggu  { background: #fff4e0; color: #f39c12; border: 1px solid #fde8b4; }
.status-dipinjam  { background: #e1f7ea; color: #27ae60; border: 1px solid #c3e6cb; }
.status-terlambat { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6cb; }
.status-selesai   { background: #ebf5ff; color: #1a5da4; border: 1px solid #bee3f8; }
.status-ditolak   { background: #f8d7da; color: #dc3545; border: 1px solid #f5c6cb; }

/* AKSI */
.btn-aksi { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; transition: 0.2s; text-decoration: none; }
.btn-check { background: #d4edda; color: #27ae60; }
.btn-cross { background: #f8d7da; color: #c0392b; }
.btn-view  { background: #dbeafe; color: #1a5da4; }
.btn-aksi:hover { opacity: 0.8; transform: scale(1.05); }

/* PAGINATION */
.pagination-wrapper { display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; background: #fff; border-top: 1px solid #f0f0f0; }
.pagination-info { font-size: 13px; color: #6c757d; }
.pagination-links { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
.pagination-links li a,
.pagination-links li span { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; font-size: 13px; font-weight: 500; text-decoration: none; border: 1px solid #e2e8f0; color: #1a5da4; background: #fff; transition: 0.15s; }
.pagination-links li a:hover { background: #e8f0fe; border-color: #1a5da4; }
.pagination-links li.active span { background: #1a5da4; border-color: #1a5da4; color: #fff; }
.pagination-links li.disabled span { color: #cbd5e1; border-color: #f1f5f9; background: #f8f9fa; cursor: not-allowed; }
</style>

<div class="container-custom">
    <h4 class="page-title">Data Peminjaman Buku</h4>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 15px; border-radius:6px; margin-bottom:20px; font-size:13px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- FILTER --}}
    <form action="{{ route('peminjaman.index') }}" method="GET" class="filter-row">
        <input type="text" name="search" class="form-control-custom"
               placeholder="Cari nama anggota..." value="{{ request('search') }}" style="width:260px;">
        <select name="status" class="form-control-custom" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="menunggu"   {{ request('status') == 'menunggu'   ? 'selected' : '' }}>Menunggu</option>
            <option value="dipinjam"   {{ request('status') == 'dipinjam'   ? 'selected' : '' }}>Dipinjam</option>
            <option value="terlambat"  {{ request('status') == 'terlambat'  ? 'selected' : '' }}>Terlambat</option>
            <option value="selesai"    {{ request('status') == 'selesai'    ? 'selected' : '' }}>Selesai</option>
            <option value="ditolak"    {{ request('status') == 'ditolak'    ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="btn-cari"><i class="fas fa-search"></i> Cari</button>
        @if(request('search') || request('status'))
            <a href="{{ route('peminjaman.index') }}" class="btn-reset">Reset</a>
        @endif
    </form>

    <div class="table-container">
        <table class="table-custom text-center">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th style="text-align:left;">Nama Anggota</th>
                    <th style="text-align:left;">Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $data->firstItem() + $loop->index }}</td>
                    <td style="text-align:left;">
                        <span class="text-main">{{ $item->nama_anggota }}</span>
                        <span class="text-sub">{{ $item->user->email ?? '-' }}</span>
                    </td>
                    <td style="text-align:left;">
                        <span class="text-main">{{ $item->buku->judul ?? '-' }}</span>
                        <span class="text-sub">{{ $item->buku->penulis ?? '-' }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $item->jumlah_pinjam }} Buku</td>
                    <td>
                        @if($item->status == 'menunggu')
                            <span class="badge-status status-menunggu">Menunggu</span>
                        @elseif($item->status == 'dipinjam')
                            <span class="badge-status status-dipinjam">Dipinjam</span>
                        @elseif($item->status == 'terlambat')
                            <span class="badge-status status-terlambat">Terlambat</span>
                        @elseif($item->status == 'ditolak')
                            <span class="badge-status status-ditolak">Ditolak</span>
                        @else
                            <span class="badge-status status-selesai">
                                {{ ucwords(str_replace('_', ' ', $item->status)) }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'menunggu')
                            <form action="{{ route('peminjaman.verifikasi', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="aksi" value="setuju">
                                <button type="submit" class="btn-aksi btn-check" title="Setuju">✔</button>
                            </form>
                            <form action="{{ route('peminjaman.verifikasi', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="aksi" value="tolak">
                                <button type="submit" class="btn-aksi btn-cross" title="Tolak">✖</button>
                            </form>
                        @else
                            <a href="{{ route('peminjaman.show', $item->id) }}" class="btn-aksi btn-view" title="Detail">👁</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:40px; color:#999;">Belum ada data peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            <div class="pagination-info">
                @if($data->total() > 0)
                    Menampilkan <strong>{{ $data->firstItem() }}</strong> - <strong>{{ $data->lastItem() }}</strong> dari <strong>{{ $data->total() }}</strong> data
                @else
                    Tidak ada data
                @endif
            </div>
            <ul class="pagination-links">
                <li class="{{ $data->onFirstPage() ? 'disabled' : '' }}">
                    @if($data->onFirstPage())
                        <span>&#8592;</span>
                    @else
                        <a href="{{ $data->previousPageUrl() }}">&#8592;</a>
                    @endif
                </li>
                @foreach($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                    <li class="{{ $page == $data->currentPage() ? 'active' : '' }}">
                        @if($page == $data->currentPage())
                            <span>{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    </li>
                @endforeach
                <li class="{{ !$data->hasMorePages() ? 'disabled' : '' }}">
                    @if($data->hasMorePages())
                        <a href="{{ $data->nextPageUrl() }}">&#8594;</a>
                    @else
                        <span>&#8594;</span>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
