<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Denda; 

class PeminjamanSayaController extends Controller
{
    // 📌 LIST PEMINJAMAN SAYA
    public function index(Request $request)
    {
        $namaAnggota = $request->nama;

        $peminjamans = Peminjaman::with('buku')
            ->when($namaAnggota, function ($query) use ($namaAnggota) {
                $query->where('nama_anggota', $namaAnggota);
            })
            ->latest()
            ->paginate(10);

        return view('page.frontend.peminjamansaya.index', compact('peminjamans', 'namaAnggota'));
    }

    // 📌 DETAIL PEMINJAMAN
    public function show(Request $request, $id)
    {
        $namaAnggota = $request->nama;

        $peminjaman = Peminjaman::with('buku')
            ->where('id', $id)
            ->when($namaAnggota, function ($query) use ($namaAnggota) {
                $query->where('nama_anggota', $namaAnggota);
            })
            ->firstOrFail();

        return view('page.frontend.peminjamansaya.show', compact('peminjaman'));
    }

    public function ajukanPengembalian(Request $request, $id)
{
    $peminjaman = \App\Models\Peminjaman::findOrFail($id);

    // Pastikan membandingkan TANGGAL saja (tanpa jam agar presisi hari)
    $tglDeadline = \Carbon\Carbon::parse($peminjaman->tgl_kembali)->startOfDay();
    $tglSekarang = \Carbon\Carbon::now()->startOfDay();
    
    $peminjaman->update(['status' => 'menunggu_verifikasi']);

    \App\Models\Pengembalian::updateOrCreate(
        ['peminjaman_id' => $id],
        [
            'tgl_dikembalikan' => now(),
            'status' => 'menunggu_verifikasi'
        ]
    );

    // HITUNG SELISIH HARI
    // gt = Greater Than (Jika sekarang melewati deadline)
    if ($tglSekarang->gt($tglDeadline)) {
        $selisihHari = max(0, $tglSekarang->diffInDays($tglDeadline));
        $totalDenda = $selisihHari * 2000;
        \App\Models\Denda::updateOrCreate(
            ['peminjaman_id' => $id],
            [
                'hari_terlambat' => $selisihHari,
                'denda'          => $totalDenda,
                'status'         => 'menunggu'
            ]
        );
    } else {
        // OPSIONAL: Jika dikembalikan tepat waktu, pastikan denda dihapus atau diset 0
        // Agar tidak muncul di tabel denda
        \App\Models\Denda::where('peminjaman_id', $id)->delete();
    }

    return redirect()->back()->with('success', 'Berhasil diajukan!');
}
}

