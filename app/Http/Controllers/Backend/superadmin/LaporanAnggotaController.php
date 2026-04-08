<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Peminjaman; // Pastikan ini di-import
use Illuminate\Http\Request;

class LaporanAnggotaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data anggota dasar
        $query = Anggota::query();

        // 2. Filter Pencarian (Nama, NIS, atau Kelas)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%")
                  ->orWhere('kelas', 'like', "%$search%");
            });
        }

        $anggota = $query->latest()->get();

        // 3. Hitung jumlah pinjaman manual berdasarkan NAMA (Sesuai database kamu)
        foreach ($anggota as $row) {
            $row->peminjaman_count = Peminjaman::where('nama_anggota', $row->nama)->count();
        }

        return view('page.backend.superadmin.laporananggota.index', compact('anggota'));
    }
}