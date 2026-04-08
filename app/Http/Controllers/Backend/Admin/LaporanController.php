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
        $bulanName = $request->get('bulan');

        $bulanIndo = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        $monthNumber = $bulanIndo[$bulanName] ?? null;

        switch ($jenis) {

            case 'Pengembalian':
                $query = \App\Models\Pengembalian::with('peminjaman.buku.kategori', 'peminjaman.user');
                if ($monthNumber) {
                    $query->whereMonth('tgl_dikembalikan', $monthNumber);
                }
                break;

            case 'Denda':
                $query = \App\Models\Denda::with('peminjaman.buku.kategori', 'peminjaman.user');
                if ($monthNumber) {
                    $query->whereMonth('created_at', $monthNumber);
                }
                break;

            default: // Peminjaman
                $query = \App\Models\Peminjaman::with('buku.kategori', 'user');
                if ($monthNumber) {
                    $query->whereMonth('tgl_pinjam', $monthNumber);
                }
                break;
        }

        $laporan = $query->latest()->get();

        return view('page.backend.admin.laporan.index', compact('laporan', 'jenis'));
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->get('jenis', 'Peminjaman'); 
        $bulanName = $request->get('bulan');

        $bulanIndo = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        $monthNumber = $bulanIndo[$bulanName] ?? null;

        switch ($jenis) {
            case 'Pengembalian':
                $query = \App\Models\Pengembalian::with('peminjaman.buku.kategori', 'peminjaman.user');
                if ($monthNumber) {
                    $query->whereMonth('tgl_dikembalikan', $monthNumber);
                }
                break;

            case 'Denda':
                $query = \App\Models\Denda::with('peminjaman.buku.kategori', 'peminjaman.user');
                if ($monthNumber) {
                    $query->whereMonth('created_at', $monthNumber);
                }
                break;

            default: // Peminjaman
                $query = \App\Models\Peminjaman::with('buku.kategori', 'user');
                if ($monthNumber) {
                    $query->whereMonth('tgl_pinjam', $monthNumber);
                }
                break;
        }

        $laporan = $query->latest()->get();
        
        $data = [
            'laporan' => $laporan,
            'jenis' => $jenis,
            'bulan' => $bulanName,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('page.backend.admin.laporan.pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'Laporan_' . $jenis . '_' . ($bulanName ?? 'Semua') . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}