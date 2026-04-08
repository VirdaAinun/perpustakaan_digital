<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ $jenis }} Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1B2559;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #1B2559;
            margin: 0;
            font-size: 18px;
        }
        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #1B2559;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-pinjam { background: #D1FAE5; color: #065F46; }
        .badge-kembali { background: #DBEAFE; color: #1E40AF; }
        .badge-denda { background: #FEE2E2; color: #991B1B; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #0d6efd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN {{ strtoupper($jenis) }} PERPUSTAKAAN</h1>
        <h2>Sistem Informasi Perpustakaan Digital</h2>
    </div>

    <div class="info-box">
        <table style="border: none; margin: 0;">
            <tr style="background: none;">
                <td style="border: none; width: 150px;"><strong>Jenis Laporan:</strong></td>
                <td style="border: none;">{{ $jenis }}</td>
                <td style="border: none; width: 150px;"><strong>Tanggal Cetak:</strong></td>
                <td style="border: none;">{{ $tanggal_cetak }}</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none;"><strong>Periode:</strong></td>
                <td style="border: none;">{{ $bulan ?? 'Semua Bulan' }}</td>
                <td style="border: none;"><strong>Total Data:</strong></td>
                <td style="border: none;">{{ $laporan->count() }} record</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Anggota</th>
                <th width="25%">Judul Buku</th>
                <th width="15%">
                    @if($jenis == 'Denda') Tanggal Laporan @else Tanggal Pinjam @endif
                </th>
                <th width="15%">
                    @if($jenis == 'Pengembalian') Tanggal Kembali @elseif($jenis == 'Denda') Nominal Denda @else Estimasi Kembali @endif
                </th>
                <th width="20%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $row)
                @php
                    $dataPeminjaman = ($jenis == 'Peminjaman') ? $row : $row->peminjaman;
                    $buku = $dataPeminjaman->buku ?? null;
                    $namaAnggota = optional($dataPeminjaman->user)->name 
                                    ?? $dataPeminjaman->nama_anggota 
                                    ?? 'Umum';
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <div class="fw-bold">{{ $namaAnggota }}</div>
                        <div style="font-size: 9px; color: #666;">
                            {{ optional($dataPeminjaman->user)->email ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $buku->judul ?? 'Data Buku Dihapus' }}</div>
                        <div style="font-size: 9px; color: #666;">
                            {{ optional(optional($buku)->kategori)->nama_kategori ?? 'Tanpa Kategori' }}
                        </div>
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
                    <td class="text-center">
                        @if($jenis == 'Peminjaman')
                            <span class="badge badge-pinjam">DIPINJAM</span>
                        @elseif($jenis == 'Pengembalian')
                            <span class="badge badge-kembali">SUDAH KEMBALI</span>
                        @elseif($jenis == 'Denda')
                            <span class="badge badge-denda">
                                {{ $row->status == 'menunggu' ? 'BELUM LUNAS' : 'LUNAS' }}
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; color: #999;">
                        Tidak ada data laporan {{ $jenis }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }} | Sistem Perpustakaan Digital</p>
    </div>
</body>
</html>