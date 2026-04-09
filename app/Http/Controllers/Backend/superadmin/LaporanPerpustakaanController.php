<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;

class LaporanPerpustakaanController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'peminjaman');
        $laporan = collect();

        if ($jenis == 'peminjaman') {
            $laporan = Peminjaman::with(['buku', 'user'])
                ->whereIn('status', ['menunggu', 'dipinjam', 'ditolak', 'terlambat'])
                ->latest()->get();

        } elseif ($jenis == 'pengembalian') {
            $laporan = Peminjaman::with(['buku', 'user'])
                ->whereIn('status', ['menunggu_verifikasi', 'selesai'])
                ->latest()->get();

        } elseif ($jenis == 'denda') {
            $laporan = Denda::with(['peminjaman.buku', 'peminjaman.user'])
                ->latest()->get();
        }

        return view('page.backend.superadmin.laporanperpustakaan.index', compact('laporan', 'jenis'));
    }
}
