<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalBuku = Buku::count();
        $dipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $anggota = Anggota::where('status', 'aktif')->count();
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tgl_kembali', '<', now()->toDateString())
            ->count();

        // Data Buku Terpopuler 
        $bukuPopuler = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(3)
            ->get();

        // Data Peminjaman Terbaru 
        $peminjamanTerbaru = Peminjaman::with('buku')
            ->latest()
            ->take(3)
            ->get();

        // Pengajuan menunggu verifikasi
        $peminjamanMenunggu   = Peminjaman::where('status', 'menunggu')->count();
        $pengembalianMenunggu = Peminjaman::where('status', 'menunggu_verifikasi')->count();

        // Data Grafik Statistik 
        $peminjamanPerBulan = Peminjaman::select(DB::raw('MONTH(created_at) as bulan'), DB::raw('count(*) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Menyiapkan array 12 bulan (isi 0 jika bulan tersebut tidak ada data)
        $dataGrafik = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataGrafik[] = $peminjamanPerBulan[$i] ?? 0;
        }

        return view('page.backend.admin.dashboard.index', compact(
            'totalBuku', 
            'dipinjam', 
            'anggota', 
            'terlambat',
            'bukuPopuler',
            'peminjamanTerbaru',
            'peminjamanMenunggu',
            'pengembalianMenunggu',
            'dataGrafik'
        ));
    }
}