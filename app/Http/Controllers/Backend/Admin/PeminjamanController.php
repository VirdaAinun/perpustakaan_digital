<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Notifikasi;

class PeminjamanController extends Controller
{
    public function index(Request $request) // <-- Tambahkan Request $request di sini
    {
        // 1. Mulai query dengan relasi buku
        $query = Peminjaman::with('buku', 'user');

        // 2. LOGIKA CARI: Jika input search diisi, saring berdasarkan NAMA ANGGOTA
        if ($request->filled('search')) {
            $query->where('nama_anggota', 'like', '%' . $request->search . '%');
        }

        // 3. Ambil datanya (yang sudah difilter maupun tidak)
        $data = $query->latest()->get();
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
            Notifikasi::create([
                'user_id' => $peminjaman->user_id,
                'judul'   => 'Peminjaman Disetujui',
                'pesan'   => 'Peminjaman buku "' . ($peminjaman->buku->judul ?? '-') . '" telah disetujui. Silakan ambil buku di perpustakaan.',
            ]);
        } else {
            $peminjaman->status = 'ditolak';
            Notifikasi::create([
                'user_id' => $peminjaman->user_id,
                'judul'   => 'Peminjaman Ditolak',
                'pesan'   => 'Peminjaman buku "' . ($peminjaman->buku->judul ?? '-') . '" ditolak oleh petugas.',
            ]);
        }

        $peminjaman->save();

        return back()->with('success', 'Status peminjaman berhasil diperbarui');
    }

    public function show($id)
{
    $data = \App\Models\Peminjaman::with('buku.kategori', 'user')->findOrFail($id);

    return view('page/backend/admin/peminjaman/show', compact('data'));
}
}