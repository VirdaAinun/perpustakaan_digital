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
        ->where('status', 'menunggu')
        ->whereHas('peminjaman', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

    $totalDendaAktif = (clone $query)->get()->sum(fn($item) => abs($item->denda));

    $dendas = $query->latest()->paginate(10);

    $dendas->getCollection()->transform(function ($item) {
        $item->hari_fix  = abs($item->hari_terlambat);
        $item->denda_fix = abs($item->denda);
        return $item;
    });

    // Riwayat lunas
    $riwayat = Denda::with(['peminjaman.buku'])
        ->where('status', 'selesai')
        ->whereHas('peminjaman', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->latest()->get()
        ->map(function ($item) {
            $item->hari_fix  = abs($item->hari_terlambat);
            $item->denda_fix = abs($item->denda);
            return $item;
        });

    return view('page.frontend.denda.index', compact(
        'dendas',
        'namaAnggota',
        'totalDendaAktif',
        'riwayat'
    ));
}
}