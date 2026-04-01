<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;

class KatalogController extends Controller
{
    // 🔥 TAMPIL KATALOG
    public function index()
    {
        $bukus = Buku::latest()->get(); // bisa juga ->where('status','Tersedia')
        return view('page.frontend.katalogbuku.index', compact('bukus'));
    }

    // 🔥 PROSES AJUKAN PEMINJAMAN
    public function pinjam(Request $request, $id)
    {
        // validasi
        $request->validate([
            'buku_id' => 'required',
            'nama'   => 'required|string|max:255',
            'tgl_pinjam'  => 'required|date',
            'tgl_kembali' => 'required|date|after:tgl_pinjam',
        ]);

        // cek buku
        $buku = Buku::findOrFail($id);

        // simpan ke tabel peminjaman
        Peminjaman::create([
            'buku_id'         => $buku->id,
            'nama_anggota'   => $request->nama_peminjam,
            'judul_buku' => $request->judul_buku,
            'tgl_pinjam'  => $request->tanggal_pinjam,
            'tgl_kembali' => $request->tanggal_kembali,
            'status'          => 'menunggu', // nanti diverifikasi petugas
        ]);

        // redirect + popup
        return redirect()->route('katalogbuku.index')
                         ->with('success', 'Pengajuan berhasil dilakukan');
    }
}