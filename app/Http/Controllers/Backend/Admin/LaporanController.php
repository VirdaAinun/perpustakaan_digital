<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    $jenis = $request->get('jenis', 'Peminjaman');
    $periode = $request->get('periode', 'semua');

    $periodeMap = [
        '7hari'  => 7,
        '1bulan' => 30,
        '3bulan' => 90,
        '6bulan' => 180,
        '1tahun' => 365,
    ];

    switch ($jenis) {
        case 'Pengembalian':
            $query = \App\Models\Pengembalian::with('peminjaman.buku.kategori', 'peminjaman.user');
            if (isset($periodeMap[$periode])) {
                $query->where('tgl_dikembalikan', '>=', now()->subDays($periodeMap[$periode]));
            }
            break;

        case 'Denda':
            $query = \App\Models\Denda::with('peminjaman.buku.kategori', 'peminjaman.user');
            if (isset($periodeMap[$periode])) {
                $query->where('created_at', '>=', now()->subDays($periodeMap[$periode]));
            }
            break;

        default:
            $query = \App\Models\Peminjaman::with('buku.kategori', 'user');
            if (isset($periodeMap[$periode])) {
                $query->where('tgl_pinjam', '>=', now()->subDays($periodeMap[$periode]));
            }
            break;
    }

    $laporan = $query->latest()->get();

    return view('page.backend.admin.laporan.index', compact('laporan', 'jenis'));
}

    public function exportPdf(Request $request)
    {
        $jenis   = $request->get('jenis', 'Peminjaman');
        $periode = $request->get('periode', 'semua');

        $periodeMap = [
            '7hari'  => 7,
            '1bulan' => 30,
            '3bulan' => 90,
            '6bulan' => 180,
            '1tahun' => 365,
        ];

        $periodeLabel = [
            '7hari'  => '7 Hari Terakhir',
            '1bulan' => '1 Bulan Terakhir',
            '3bulan' => '3 Bulan Terakhir',
            '6bulan' => '6 Bulan Terakhir',
            '1tahun' => '1 Tahun Terakhir',
            'semua'  => 'Semua Data',
        ];

        switch ($jenis) {
            case 'Pengembalian':
                $query = \App\Models\Pengembalian::with('peminjaman.buku.kategori', 'peminjaman.user');
                if (isset($periodeMap[$periode])) {
                    $query->where('tgl_dikembalikan', '>=', now()->subDays($periodeMap[$periode]));
                }
                break;
            case 'Denda':
                $query = \App\Models\Denda::with('peminjaman.buku.kategori', 'peminjaman.user');
                if (isset($periodeMap[$periode])) {
                    $query->where('created_at', '>=', now()->subDays($periodeMap[$periode]));
                }
                break;
            default:
                $query = \App\Models\Peminjaman::with('buku.kategori', 'user');
                if (isset($periodeMap[$periode])) {
                    $query->where('tgl_pinjam', '>=', now()->subDays($periodeMap[$periode]));
                }
                break;
        }

        $laporan = $query->latest()->get();

        $pdf = Pdf::loadView('page.backend.admin.laporan.pdf', [
            'laporan'       => $laporan,
            'jenis'         => $jenis,
            'bulan'         => $periodeLabel[$periode] ?? 'Semua Data',
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
        ]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_' . $jenis . '_' . $periode . '_' . date('Y-m-d') . '.pdf');
    }
}