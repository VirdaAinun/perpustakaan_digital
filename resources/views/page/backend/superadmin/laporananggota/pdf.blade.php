<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Anggota</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #1a5da4; padding-bottom: 12px; }
        .header h2 { color: #1a5da4; margin: 0; font-size: 16px; }
        .header p { margin: 4px 0 0; color: #555; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a5da4; color: white; padding: 10px 8px; font-size: 11px; text-transform: uppercase; }
        td { padding: 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge-aktif { background: #e1f7ea; color: #27ae60; padding: 2px 8px; border-radius: 10px; font-weight: bold; }
        .badge-tidak { background: #fff4e0; color: #f39c12; padding: 2px 8px; border-radius: 10px; font-weight: bold; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #888; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA ANGGOTA PERPUSTAKAAN DIGITAL</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} &nbsp;|&nbsp; Total Anggota: {{ $anggota->count() }} orang</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">NO</th>
                <th width="30%">NAMA ANGGOTA</th>
                <th class="text-center" width="15%">NIS</th>
                <th class="text-center" width="15%">KELAS</th>
                <th class="text-center" width="20%">EMAIL</th>
                <th class="text-center" width="15%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggota as $row)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $row->nama }}</td>
                <td class="text-center">{{ $row->nis }}</td>
                <td class="text-center">{{ $row->kelas }}</td>
                <td class="text-center">{{ $row->user->email ?? '-' }}</td>
                <td class="text-center">
                    <span class="{{ $row->status == 'aktif' ? 'badge-aktif' : 'badge-tidak' }}">
                        {{ ucfirst($row->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data anggota.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Sistem Perpustakaan Digital &mdash; {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
