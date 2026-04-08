<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use Illuminate\Http\Request; // Tambahkan ini

class DendaController extends Controller
{
    public function index()
    {
        // Mengambil data denda beserta relasi peminjaman dan buku
        $data = Denda::with('peminjaman.buku')->latest()->get();

        return view('page.backend.admin.denda.index', compact('data'));
    }

    public function bayar($id)
    {
        $denda = Denda::findOrFail($id);

        if ($denda->status == 'selesai') {
            return back()->with('error', 'Denda sudah lunas!');
        }

        $denda->update([
            'status' => 'selesai'
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}