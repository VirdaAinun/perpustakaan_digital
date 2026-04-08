<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanPerpustakaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['buku', 'anggota']);

        // Filter Rentang Tanggal
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tgl_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporan = $query->latest()->get();

        return view('page.backend.superadmin.laporanperpustakaan.index', compact('laporan'));
    }
}
