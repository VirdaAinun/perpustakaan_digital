<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index(Request $request)
{
    $namaAnggota = $request->nama;
    $dendaPerHari = 2000;
    $userId = auth()->id();

    $query = Denda::with(['peminjaman.buku'])
        // ✅ FILTER USER LOGIN
        ->whereHas('peminjaman', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        // 🔍 SEARCH (opsional)
        ->when($namaAnggota, function ($query) use ($namaAnggota) {
            $query->whereHas('peminjaman', function ($q) use ($namaAnggota) {
                $q->where('nama_anggota', 'like', '%' . $namaAnggota . '%');
            });
        });

    $dendas = $query->latest()->paginate(10);

    // 🔥 NORMALISASI DATA
    $dendas->getCollection()->transform(function ($item) use ($dendaPerHari) {

        $hari = max(0, $item->hari_terlambat);
        $denda = $hari * $dendaPerHari;

        $item->hari_fix = $hari;
        $item->denda_fix = $denda;

        return $item;
    });

    // 🔥 TOTAL DENDA AKTIF (AMAN MESKI KOSONG)
    $totalDendaAktif = $dendas->getCollection()
        ->where('status', 'menunggu')
        ->sum('denda_fix');

    return view('page.frontend.denda.index', compact(
        'dendas',
        'namaAnggota',
        'totalDendaAktif'
    ));
}
}