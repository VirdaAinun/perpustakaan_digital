@extends('layouts.backend.admin.app')

@section('content')
<style>
.container-custom { padding: 30px; background-color: #f4f7fe; }
.header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.page-title-text { font-weight: 700; color: #005fa8; font-size: 20px; margin: 0; }
.filter-row { display: flex; gap: 15px; margin-bottom: 20px; align-items: center; }
.form-select-custom, .form-control-custom { padding: 8px 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; color: #495057; }
.btn-add-data { background: #005fa8; color: #fff !important; border: none; padding: 10px 20px; border-radius: 4px; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px; }
.btn-cari { background: #005fa8; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; }
.btn-reset { background: #f1f3f5; color: #555; border: 1px solid #dee2e6; padding: 8px 16px; border-radius: 8px; font-size: 13px; text-decoration: none; }

/* TABLE */
.table-container { background: #fff; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom thead { background: #005fa8; }
.table-custom th { padding: 13px 15px; color: #fff; text-transform: uppercase; font-size: 11px; font-weight: 600; border: none; }
.table-custom td { padding: 13px 15px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; font-size: 13px; color: #333; }
.table-custom tbody tr:last-child td { border-bottom: none; }
.table-custom tbody tr:hover { background: #f9fbff; }

/* BADGE */
.badge-status { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; display: inline-block; }
.status-aktif { background: #d4edda; color: #28a745; border: 1px solid #c3e6cb; }
.status-nonaktif { background: #f8d7da; color: #dc3545; border: 1px solid #f5c6cb; }

/* ACTION */
.btn-action { padding: 5px; margin: 0 2px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; font-size: 13px; text-decoration: none !important; }

/* PAGINATION */
.pagination-wrapper { display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; background: #fff; border-top: 1px solid #f0f0f0; }
.pagination-info { font-size: 13px; color: #6c757d; }
.pagination-links { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
.pagination-links li a,
.pagination-links li span { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; font-size: 13px; font-weight: 500; text-decoration: none; border: 1px solid #e2e8f0; color: #005fa8; background: #fff; transition: 0.15s; }
.pagination-links li a:hover { background: #e8f0fe; border-color: #005fa8; }
.pagination-links li.active span { background: #005fa8; border-color: #005fa8; color: #fff; }
.pagination-links li.disabled span { color: #cbd5e1; border-color: #f1f5f9; background: #f8f9fa; cursor: not-allowed; }
</style>

<div class="container-custom">
    <h4 class="page-title-text">Data Anggota</h4>
    <br>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 15px; border-radius:6px; margin-bottom:20px; font-size:13px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="header-flex">
        <form action="{{ route('admin.dataanggota.index') }}" method="GET" class="filter-row">
            <select name="kelas" class="form-select-custom" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->kelas }}" {{ request('kelas') == $k->kelas ? 'selected' : '' }}>
                        {{ $k->kelas }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control-custom" placeholder="Cari nama atau NIS..." value="{{ request('search') }}">
            <button type="submit" class="btn-cari">Cari</button>
            @if(request('kelas') || request('search'))
                <a href="{{ route('admin.dataanggota.index') }}" class="btn-reset">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.dataanggota.create') }}" class="btn-add-data">+ Tambah Anggota</a>
    </div>

    <div class="table-container">
        <table class="table-custom text-center">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>NIS</th>
                    <th style="text-align:left;">Nama Anggota</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggota as $index => $row)
                <tr>
                    <td>{{ $anggota->firstItem() + $index }}</td>
                    <td style="font-weight:600;">{{ $row->nis }}</td>
                    <td style="font-weight:500; text-align:left; color:#005fa8;">{{ $row->nama }}</td>
                    <td>{{ $row->kelas }}</td>
                    <td>
                        @if($row->status == 'aktif')
                            <span class="badge-status status-aktif">Aktif</span>
                        @else
                            <span class="badge-status status-nonaktif">Non-Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.dataanggota.edit', $row->id) }}" class="text-primary btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.dataanggota.destroy', $row->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-danger border-0 bg-transparent btn-action" onclick="return confirm('Hapus anggota ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:40px; color:#999;">Data anggota tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            <div class="pagination-info">
                @if($anggota->total() > 0)
                    Menampilkan <strong>{{ $anggota->firstItem() }}</strong> - <strong>{{ $anggota->lastItem() }}</strong> dari <strong>{{ $anggota->total() }}</strong> data
                @else
                    Tidak ada data
                @endif
            </div>
            <ul class="pagination-links">
                <li class="{{ $anggota->onFirstPage() ? 'disabled' : '' }}">
                    @if($anggota->onFirstPage())
                        <span>&#8592;</span>
                    @else
                        <a href="{{ $anggota->previousPageUrl() }}">&#8592;</a>
                    @endif
                </li>
                @foreach($anggota->getUrlRange(1, $anggota->lastPage()) as $page => $url)
                    <li class="{{ $page == $anggota->currentPage() ? 'active' : '' }}">
                        @if($page == $anggota->currentPage())
                            <span>{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    </li>
                @endforeach
                <li class="{{ !$anggota->hasMorePages() ? 'disabled' : '' }}">
                    @if($anggota->hasMorePages())
                        <a href="{{ $anggota->nextPageUrl() }}">&#8594;</a>
                    @else
                        <span>&#8594;</span>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>

@endsection
