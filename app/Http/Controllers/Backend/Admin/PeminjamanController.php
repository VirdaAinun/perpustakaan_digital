<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index(Request $request) // <-- Tambahkan Request $request di sini
    {
        // 1. Mulai query dengan relasi buku
        $query = Peminjaman::with('buku');

        // 2. LOGIKA CARI: Jika input search diisi, saring berdasarkan NAMA ANGGOTA
        if ($request->filled('search')) {
            $query->where('nama_anggota', 'like', '%' . $request->search . '%');
        }

        // 3. Ambil datanya (yang sudah difilter maupun tidak)
        $data = $query->latest()->get();

        // 4. Kirim ke view
        return view('page/backend/admin/peminjaman.index', compact('data'));
    }
    /**
     * Verifikasi peminjaman (Setuju / Tolak)
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:setuju,tolak'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        // Hanya bisa diverifikasi jika status menunggu
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Data sudah diverifikasi sebelumnya');
        }

        if ($request->aksi == 'setuju') {
            $peminjaman->status = 'dipinjam';
        } else {
            $peminjaman->status = 'ditolak';
        }

        $peminjaman->save();

        return back()->with('success', 'Status peminjaman berhasil diperbarui');
    }
}