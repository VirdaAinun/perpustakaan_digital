<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // LaporanController.php
public function index(Request $request)
{
    // 1. Ambil input dari filter
    $jenis = $request->get('jenis', 'Peminjaman'); 
    $bulanName = $request->get('bulan');
    
    // 2. Daftar bulan
    $bulanIndo = [
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
    ];
    $monthNumber = $bulanIndo[$bulanName] ?? null;

    // 3. Ambil data berdasarkan jenis
    if ($jenis == 'Pengembalian') {
        $query = \App\Models\Pengembalian::with(['peminjaman.buku', 'peminjaman.anggota']);
        if ($monthNumber) $query->whereMonth('tgl_dikembalikan', $monthNumber);
        $laporan = $query->latest()->get();
    } 
    elseif ($jenis == 'Denda') {
        $query = \App\Models\Denda::with(['peminjaman.buku', 'peminjaman.anggota']);
        if ($monthNumber) $query->whereMonth('created_at', $monthNumber);
        $laporan = $query->latest()->get();
    } 
    else {
        // Bagian Peminjaman
        $query = \App\Models\Peminjaman::with(['buku', 'anggota']);
        
        // Filter bulan kalau dipilih
        if ($monthNumber) {
            $query->whereMonth('tgl_pinjam', $monthNumber);
        }
        
        // TIPS: Saya hapus ->where('status', 'dipinjam') 
        // supaya SEMUA data peminjaman muncul dulu. Kalau sudah muncul, baru kita filter lagi nanti.
        $laporan = $query->latest()->get();
    }

    return view('page.backend.admin.laporan.index', compact('laporan', 'jenis'));
}
}