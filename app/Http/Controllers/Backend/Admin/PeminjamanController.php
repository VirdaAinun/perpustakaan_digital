<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan semua data peminjaman
     */
    public function index()
    {
        $data = Peminjaman::with('buku')->latest()->get();

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
        if ($peminjaman->status !== 'menunggu_verifikasi') {
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