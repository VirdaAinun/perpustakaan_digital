<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanSayaController extends Controller
{
    
    // 📌 LIST PEMINJAMAN SAYA
    public function index()
{
    // sementara pakai nama anggota dummy
    $namaAnggota = 'Budi Santoso'; // ganti sesuai yang kamu mau

    $peminjamans = Peminjaman::with('buku')
        ->where('nama_anggota', $namaAnggota)
        ->latest()
        ->paginate(10);

    return view('page.frontend.peminjamansaya.index', compact('peminjamans'));
}

    // 📌 DETAIL PEMINJAMAN
    public function show($id)
    {
        $namaAnggota = 'Budi Santoso';
        $peminjaman = Peminjaman::with('buku')
            ->where('id', $id)
            ->where('nama_anggota', $namaAnggota)
            ->firstOrFail();

        return view('page.frontend.peminjamansaya.show', compact('peminjaman'));
    }

    // 📌 AJUKAN PENGEMBALIAN
    public function ajukanPengembalian(Request $request, $id)
    {
        $namaAnggota = 'Budi Santoso';
        $peminjaman = Peminjaman::where('id', $id)
            ->where('nama_anggota', $namaAnggota)
            ->where('status', 'dipinjam')
            ->firstOrFail();

        $peminjaman->status = 'menunggu_verifikasi';
        $peminjaman->save();

        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim.');
    }
}