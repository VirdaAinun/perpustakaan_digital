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
.status-menunggu { background: #fff4e0; color: #f39c12; border: 1px solid #fde8b4; }
.status-terlambat { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6cb; }
.status-selesai { background: #e1f7ea; color: #27ae60; border: 1px solid #c3e6cb; }
.status-ditolak-kembali { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6cb; }
.status-ditolak { background: #fde8e8; color: #7b1d1d; border: 1px solid #f5c6cb; }

/* MODAL TOLAK */
.modal-tolak { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; justify-content:center; align-items:center; }
.modal-tolak.active { display:flex; }
.modal-tolak-box { background:#fff; border-radius:12px; padding:28px; width:420px; box-shadow:0 10px 30px rgba(0,0,0,0.15); }
.modal-tolak-box h5 { font-weight:700; font-size:16px; color:#c0392b; margin-bottom:16px; }
.modal-tolak-box textarea { width:100%; padding:10px 12px; border:1.5px solid #dde3ec; border-radius:7px; font-size:13px; resize:vertical; min-height:100px; outline:none; font-family:inherit; }
.modal-tolak-box textarea:focus { border-color:#c0392b; }
.modal-footer-btn { display:flex; justify-content:flex-end; gap:8px; margin-top:16px; }
.btn-cancel-modal { background:#f1f3f5; color:#555; border:none; padding:9px 20px; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer; }
.btn-tolak-submit { background:#c0392b; color:#fff; border:none; padding:9px 22px; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer; }

/* BUTTONS */
.btn-verifikasi { background: #1a5da4; color: #fff; border: none; padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; }
.btn-verifikasi:hover { background: #14467a; }
.btn-detail { background: #f8f9fa; color: #1a5da4; border: 1px solid #1a5da4; padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-block; }
.btn-detail:hover { background: #e8f0fe; }

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
    <h4 class="page-title">Data Pengembalian</h4>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 15px; border-radius:6px; margin-bottom:20px; font-size:13px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- FILTER --}}
    <form action="{{ route('pengembalian.index') }}" method="GET" class="filter-row">
        <input type="text" name="search" class="form-control-custom" 
               placeholder="Cari nama anggota..." value="{{ request('search') }}" style="width:260px;">
        <select name="status" class="form-control-custom" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
            <option value="terlambat"           {{ request('status') == 'terlambat'           ? 'selected' : '' }}>Terlambat</option>
            <option value="selesai"             {{ request('status') == 'selesai'             ? 'selected' : '' }}>Selesai</option>
            <option value="ditolak"             {{ request('status') == 'ditolak'             ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="btn-cari"><i class="fas fa-search"></i> Cari</button>
        @if(request('search') || request('status'))
            <a href="{{ route('pengembalian.index') }}" class="btn-reset">Reset</a>
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
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th width="130">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align:left;">
                        <span class="text-main">{{ $item->nama_anggota }}</span>
                        <span class="text-sub">{{ $item->user->email ?? '-' }}</span>
                    </td>
                    <td style="text-align:left;">
                        <span class="text-main">{{ $item->buku->judul ?? '-' }}</span>
                        <span class="text-sub">{{ $item->buku->penulis ?? '-' }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($item->status == 'menunggu_verifikasi')
                            <span class="badge-status status-menunggu">Menunggu</span>
                        @elseif($item->status == 'terlambat')
                            <span class="badge-status status-terlambat">Terlambat</span>
                        @elseif($item->status == 'dipinjam' && $item->alasan_tolak_pengembalian)
                            <span class="badge-status status-ditolak-kembali">Ditolak</span>
                        @else
                            <span class="badge-status status-selesai">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'menunggu_verifikasi')
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <form action="{{ route('pengembalian.verifikasi', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-verifikasi">✔ Verifikasi</button>
                                </form>
                                <button type="button" class="btn-detail" style="color:#c0392b;border-color:#c0392b;" onclick="openTolak({{ $item->id }}, '{{ addslashes($item->nama_anggota) }}', '{{ addslashes($item->buku->judul ?? '-') }}')">✖ Tolak</button>
                            </div>
                        @elseif($item->status == 'dipinjam' && $item->alasan_tolak_pengembalian)
                            <div style="font-size:11px;color:#c0392b;text-align:left;">
                                <b>Alasan:</b> {{ $item->alasan_tolak_pengembalian }}<br>
                                <span style="color:#888;">Anggota perlu ajukan ulang</span>
                            </div>
                        @else
                            <a href="{{ route('pengembalian.show', $item->id) }}" class="btn-detail">Detail</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:40px; color:#999;">Belum ada data pengembalian.</td>
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

{{-- MODAL TOLAK --}}
<div class="modal-tolak" id="modalTolak">
    <div class="modal-tolak-box">
        <h5>✖ Tolak Pengajuan Pengembalian</h5>
        <p style="font-size:13px;color:#555;margin-bottom:12px;">Anggota: <b id="tolakNama"></b> &mdash; Buku: <b id="tolakBuku"></b></p>
        <form id="formTolak" method="POST">
            @csrf
            <label style="font-size:13px;font-weight:600;color:#444;display:block;margin-bottom:6px;">Alasan Penolakan <span style="color:#c0392b;">*</span></label>
            <textarea name="alasan" id="alasanInput" placeholder="Contoh: Buku belum dikembalikan ke rak, kondisi buku rusak, dll..." required></textarea>

            {{-- CHECKBOX BUKU RUSAK --}}
            <div style="margin-top:14px;padding:12px;background:#fff5f5;border-radius:8px;border:1px solid #fecaca;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;color:#c0392b;">
                    <input type="checkbox" id="bukuRusakCheck" onchange="toggleDendaKerusakan()" style="width:16px;height:16px;cursor:pointer;">
                    Buku dalam kondisi rusak (kenakan denda kerusakan)
                </label>
                <div id="dendaKerusakanBox" style="display:none;margin-top:12px;">
                    <label style="font-size:12px;font-weight:600;color:#444;display:block;margin-bottom:6px;">Nominal Denda Kerusakan (Rp)</label>
                    <input type="number" name="denda_kerusakan" id="dendaKerusakanInput" placeholder="Contoh: 50000" min="0"
                        style="width:100%;padding:9px 12px;border:1.5px solid #fecaca;border-radius:7px;font-size:13px;outline:none;">
                    <p style="font-size:11px;color:#888;margin-top:5px;">Denda akan otomatis muncul di halaman denda anggota.</p>
                </div>
            </div>

            <div class="modal-footer-btn">
                <button type="button" class="btn-cancel-modal" onclick="closeTolak()">Batal</button>
                <button type="submit" class="btn-tolak-submit">✖ Tolak Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTolak(id, nama, buku) {
    document.getElementById('tolakNama').textContent = nama;
    document.getElementById('tolakBuku').textContent = buku;
    document.getElementById('formTolak').action = '/admin/pengembalian/' + id + '/tolak';
    document.getElementById('bukuRusakCheck').checked = false;
    document.getElementById('dendaKerusakanBox').style.display = 'none';
    document.getElementById('dendaKerusakanInput').value = '';
    document.getElementById('alasanInput').value = '';
    document.getElementById('modalTolak').classList.add('active');
}
function closeTolak() {
    document.getElementById('modalTolak').classList.remove('active');
}
function toggleDendaKerusakan() {
    const checked = document.getElementById('bukuRusakCheck').checked;
    document.getElementById('dendaKerusakanBox').style.display = checked ? 'block' : 'none';
    if (checked && document.getElementById('alasanInput').value === '') {
        document.getElementById('alasanInput').value = 'Buku dikembalikan dalam kondisi rusak';
    }
}
document.getElementById('modalTolak').addEventListener('click', function(e) {
    if (e.target === this) closeTolak();
});
</script>
@endsection
