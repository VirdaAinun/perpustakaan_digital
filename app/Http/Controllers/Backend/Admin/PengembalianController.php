<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman; // Kita fokus pakai model ini saja jika data pengembalian ada di sini
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data dari Peminjaman yang statusnya terkait pengembalian
        $query = Peminjaman::with('buku')
            ->whereIn('status', ['menunggu_verifikasi', 'selesai']);

        // 2. Fitur Cari Nama Anggota (Beneran Fungsi & Dinamis)
        if ($request->filled('search')) {
            $query->where('nama_anggota', 'like', '%' . $request->search . '%');
        }

        $data = $query->latest()->get();

        return view('page.backend.admin.pengembalian.index', compact('data'));
    }

    /**
     * Verifikasi pengembalian
     */
    public function verifikasi($id)
    {
        // Ambil data dari Peminjaman, bukan Pengembalian (sesuaikan dengan index)
        $data = Peminjaman::findOrFail($id);

        // Cek jika sudah diverifikasi sebelumnya
        if ($data->status == 'selesai') {
            return back()->with('error', 'Data ini sudah diverifikasi sebelumnya!');
        }

        // Update status langsung di tabel peminjaman
        $data->update([
            'status' => 'selesai',
            'tgl_kembali' => now() // Mencatat tanggal pengembalian hari ini
        ]);

        return back()->with('success', 'Pengembalian berhasil diverifikasi!');
    }
}