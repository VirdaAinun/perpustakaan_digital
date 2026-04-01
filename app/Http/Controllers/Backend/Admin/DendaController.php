<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;

class DendaController extends Controller
{
    /**
     * Tampilkan halaman denda
     */
    public function index()
    {
        $data = Denda::with('peminjaman.buku')->latest()->get();

        return view('page/backend/admin/denda.index', compact('data'));
    }

    /**
     * Aksi bayar denda
     */
    public function bayar($id)
    {
        $denda = Denda::findOrFail($id);

        if ($denda->status == 'selesai') {
            return back()->with('error', 'Denda sudah dibayar');
        }

        $denda->status = 'selesai';
        $denda->save();

        return back()->with('success', 'Denda berhasil dibayar');
    }
}