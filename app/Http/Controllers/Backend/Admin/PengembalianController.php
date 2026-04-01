<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;

class PengembalianController extends Controller
{
    /**
     * Tampilkan halaman pengembalian
     */
    public function index()
    {
        $data = Pengembalian::with('peminjaman.buku')->latest()->get();

        return view('page.backend.admin.pengembalian.index', compact('data'));
    }

    /**
     * Verifikasi pengembalian
     */
    public function verifikasi($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        if ($pengembalian->status == 'dikembalikan') {
            return back()->with('error', 'Sudah diverifikasi');
        }

        $pengembalian->status = 'dikembalikan';
        $pengembalian->save();

        // update peminjaman jadi selesai
        $pengembalian->peminjaman->update([
            'status' => 'selesai'
        ]);

        return back()->with('success', 'Pengembalian berhasil diverifikasi');
    }
}