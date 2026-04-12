<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use Illuminate\Http\Request; // Tambahkan ini

class DendaController extends Controller
{
    public function index()
    {
        $dendaPerHari = 2000;
        $data = Denda::with('peminjaman.buku', 'peminjaman.user')->latest()->get();

        $data->transform(function ($item) {
            $item->hari_fix = abs($item->hari_terlambat);
            $item->denda_fix = abs($item->denda);
            return $item;
        });

        return view('page.backend.admin.denda.index', compact('data'));
    }

    public function bayar($id)
    {
        $denda = Denda::findOrFail($id);

        if ($denda->status == 'selesai') {
            return back()->with('error', 'Denda sudah lunas!');
        }

        $denda->update(['status' => 'selesai']);

        // Update status peminjaman jadi selesai setelah denda lunas
        \App\Models\Peminjaman::where('id', $denda->peminjaman_id)
            ->where('status', 'terlambat')
            ->update(['status' => 'selesai']);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}