<?php

namespace App\Http\Controllers\Backend\superAdmin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ===============================
        // 📊 Statistik Ringkasan
        // ===============================
        $stats = [
            'totalBuku' => Buku::count(),
            'totalAnggota' => Anggota::count(),
            'totalPinjam' => Peminjaman::count(),
            'pinjamBulanIni' => Peminjaman::whereMonth('created_at', now()->month)->count(),
        ];

        // ===============================
        // 👥 Anggota Teraktif (Top 5)
        // ===============================
        $anggotaAktif = DB::table('peminjamans')
            ->select('nama_anggota', DB::raw('COUNT(*) as total'))
            ->groupBy('nama_anggota')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ===============================
        // 📈 Grafik Peminjaman per Bulan
        // ===============================
        $peminjaman = Peminjaman::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $dataGrafik = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataGrafik[] = $peminjaman[$i] ?? 0;
        }

        // ===============================
        // 🚀 Return View
        // ===============================
        return view(
            'page.backend.superadmin.dashboardkepala.index',
            compact('stats', 'anggotaAktif', 'dataGrafik')
        );
    }
}