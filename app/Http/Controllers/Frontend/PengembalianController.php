<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman; 
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        // Ambil data Peminjaman yang statusnya diajukan atau sudah beres
        // Variabel diubah jadi $data agar nyambung dengan Blade kamu (@forelse($data as $item))
        $data = Peminjaman::with('buku') 
            ->whereIn('status', ['menunggu_verifikasi', 'selesai'])
            ->latest()
            ->get();

        return view('backend.admin.pengembalian.index', compact('data'));
    }

    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'selesai',
            'tgl_kembali' => now() 
        ]);

        return redirect()->route('pengembalian.index')
                         ->with('success', 'Berhasil verifikasi pengembalian buku!');
    }
}