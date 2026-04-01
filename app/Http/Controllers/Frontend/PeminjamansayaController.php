<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamanSayaController extends Controller
{
    /**
     * Menampilkan daftar peminjaman anggota yang sedang login
     */
    public function index()
    {
        // ambil semua peminjaman, beserta data buku
        $peminjamans = Peminjaman::with('buku')
                        ->latest()
                        ->paginate(10);

        return view('page.frontend.peminjamansaya.index', compact('peminjamans'));
    }

    /**
     * Menampilkan detail peminjaman
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with('buku')
                        ->where('user_id', Auth::id())
                        ->findOrFail($id);

        return view('page.frontend.peminjamansaya.show', compact('peminjaman'));
    }

    /**
     * Ajukan pengembalian buku
     */
    public function ajukanPengembalian(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
                        ->where('status', 'dipinjam') // hanya yang sedang dipinjam
                        ->findOrFail($id);

        // ubah status menjadi "mengajukan_pengembalian"
        $peminjaman->status = 'mengajukan_pengembalian';
        $peminjaman->save();

        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim. Tunggu konfirmasi petugas.');
    }
}