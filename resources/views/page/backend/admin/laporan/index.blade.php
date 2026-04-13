@extends('layouts.backend.admin.app')

@section('content')
<style>
    .laporan-wrapper {
        background: #f4f7fe;
        min-height: 100vh;
        padding: 25px;
    }

    /* HEADER */
    .laporan-header {
        margin-bottom: 20px;
    }
    .laporan-header h4 {
        font-weight: 700;
        color: #005fa8;
        font-size: 20px;
        margin: 0;
    }

    /* TAB JENIS - FULL WIDTH */
    .tab-jenis {
        display: flex;
        background: #fff;
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        margin-bottom: 16px;
    }
    .tab-jenis a {
        flex: 1;
        text-align: center;
        padding: 11px 0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        color: #64748b;
        transition: 0.2s;
    }
    .tab-jenis a:hover { background: #f1f5f9; color: #005fa8; }
    .tab-jenis a.active { background: #005fa8; color: #fff; box-shadow: 0 4px 10px rgba(27,37,89,0.25); }

    /* TAB PERIODE - COMPACT */
    .tab-periode {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .tab-periode a {
        padding: 5px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        text-decoration: none;
        color: #64748b;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        transition: 0.2s;
    }
    .tab-periode a:hover { background: #e2e8f0; color: #005fa8; }
    .tab-periode a.active { background: #005fa8; color: #fff; border-color: #005fa8; box-shadow: 0 2px 8px rgba(67,24,255,0.25); }

    /* TABLE CARD */
    .table-card {
        background: white;
        border-radius: 16px;
        padding: 0;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .table-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        border-bottom: 1px solid #f1f4f9;
    }
    .table-card-header span {
        font-weight: 700;
        color: #005fa8;
        font-size: 14px;
    }
    .btn-export {
        background: #005fa8;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 3px 8px rgba(27,37,89,0.25);
    }
    .table { margin: 0; }
    .table thead th {
        background: #005fa8;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border: none;
        padding: 14px 16px;
    }
    .table tbody td {
        padding: 13px 16px;
        vertical-align: middle;
        color: #005fa8;
        font-weight: 500;
        border-bottom: 1px solid #f1f4f9;
        font-size: 13px;
    }
    .badge-status { padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 10px; text-transform: uppercase; }
    .status-pinjam  { background: #D1FAE5; color: #065F46; }
    .status-kembali { background: #DBEAFE; color: #005fa8; }
    .status-denda   { background: #FEE2E2; color: #991B1B; }
</style>

<div class="laporan-wrapper">

    <div class="laporan-header">
        <h4> Laporan Operasional Perpustakaan</h4>
    </div>

    {{-- TAB JENIS LAPORAN --}}
    <div class="tab-jenis">
        <a href="{{ route('admin.laporan.index', array_merge(request()->except('jenis'), ['jenis' => 'Peminjaman'])) }}"
           class="{{ $jenis == 'Peminjaman' ? 'active' : '' }}">📥 Peminjaman</a>
        <a href="{{ route('admin.laporan.index', array_merge(request()->except('jenis'), ['jenis' => 'Pengembalian'])) }}"
           class="{{ $jenis == 'Pengembalian' ? 'active' : '' }}">📤 Pengembalian</a>
        <a href="{{ route('admin.laporan.index', array_merge(request()->except('jenis'), ['jenis' => 'Denda'])) }}"
           class="{{ $jenis == 'Denda' ? 'active' : '' }}">💰 Denda</a>
    </div>

    {{-- TAB PERIODE --}}
    @php
        $periode = request('periode', 'semua');
        $periodeList = [
            '7hari'  => '7 Hari',
            '1bulan' => '1 Bulan',
            '3bulan' => '3 Bulan',
            '6bulan' => '6 Bulan',
            '1tahun' => '1 Tahun',
            'semua'  => 'Semua',
        ];
    @endphp

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-card-header">
            <span>
                Data {{ $jenis }}
                <span style="color:#a3aed0; font-weight:400;">({{ $laporan->count() }} data)</span>
            </span>
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                {{-- TAB PERIODE DI SINI --}}
                <div class="tab-periode">
                    @foreach($periodeList as $key => $label)
                        <a href="{{ route('admin.laporan.index', array_merge(request()->except('periode'), ['jenis' => $jenis, 'periode' => $key])) }}"
                           class="{{ $periode == $key ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
                <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="btn-export">
                    <i class="fas fa-file-pdf"></i> Eksport PDF
                    @if($periode != 'semua')
                        ({{ $periodeList[$periode] }})
                    @endif
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>@if($jenis == 'Denda') Tgl Laporan @else Tgl Pinjam @endif</th>
                        <th>@if($jenis == 'Pengembalian') Tgl Kembali @elseif($jenis == 'Denda') Nominal Denda @else Estimasi Kembali @endif</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                        @php
                            $dataPeminjaman = ($jenis == 'Peminjaman') ? $row : $row->peminjaman;
                            $buku           = $dataPeminjaman->buku ?? null;
                            $namaAnggota    = optional($dataPeminjaman->user)->name ?? $dataPeminjaman->nama_anggota ?? 'Umum';
                            $email          = optional($dataPeminjaman->user)->email ?? '-';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $namaAnggota }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $email }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $buku->judul ?? 'Data Buku Dihapus' }}</div>
                                <div class="text-muted small">{{ optional(optional($buku)->kategori)->nama_kategori ?? 'Tanpa Kategori' }}</div>
                            </td>
                            <td>
                                @if($jenis == 'Denda')
                                    {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($dataPeminjaman->tgl_pinjam)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>
                                @if($jenis == 'Pengembalian')
                                    <span class="text-primary">{{ \Carbon\Carbon::parse($row->tgl_dikembalikan)->format('d/m/Y') }}</span>
                                @elseif($jenis == 'Denda')
                                    <span class="text-danger fw-bold">Rp {{ number_format($row->total_denda ?? 0, 0, ',', '.') }}</span>
                                @else
                                    {{ \Carbon\Carbon::parse($dataPeminjaman->tgl_kembali)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($jenis == 'Peminjaman')
                                    <span class="badge-status status-pinjam">Dipinjam</span>
                                @elseif($jenis == 'Pengembalian')
                                    <span class="badge-status status-kembali">Sudah Kembali</span>
                                @elseif($jenis == 'Denda')
                                    <span class="badge-status status-denda">{{ $row->status == 'menunggu' ? 'Belum Lunas' : 'Lunas' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Tidak ada data {{ $jenis }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
