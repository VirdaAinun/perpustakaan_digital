<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanSayaController extends Controller
{
    // 📌 LIST PEMINJAMAN SAYA
    public function index(Request $request)
    {
        $query = Peminjaman::with('buku')
            ->where('user_id', Auth::id());

        // 🔍 SEARCH
        if ($request->search) {
            $query->whereHas('buku', function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%');
            });
        }

        $peminjamans = $query->latest()->paginate(10);

        return view('page.frontend.peminjamansaya.index', compact('peminjamans'));
    }

    // 📌 DETAIL
    public function show($id)
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('page.frontend.peminjamansaya.show', compact('peminjaman'));
    }

    // 📌 AJUKAN PENGEMBALIAN
    public function ajukanPengembalian($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $tglDeadline = Carbon::parse($peminjaman->tgl_kembali)->startOfDay();
        $tglSekarang = Carbon::now()->startOfDay();

        $peminjaman->update(['status' => 'menunggu_verifikasi']);

        \App\Models\Pengembalian::updateOrCreate(
            ['peminjaman_id' => $id],
            [
                'tgl_dikembalikan' => now(),
                'status' => 'menunggu_verifikasi'
            ]
        );

        if ($tglSekarang->gt($tglDeadline)) {
            $selisihHari = $tglSekarang->diffInDays($tglDeadline);
            $totalDenda = $selisihHari * 2000;

            \App\Models\Denda::updateOrCreate(
                ['peminjaman_id' => $id],
                [
                    'hari_terlambat' => $selisihHari,
                    'denda' => $totalDenda,
                    'status' => 'menunggu'
                ]
            );
        } else {
            \App\Models\Denda::where('peminjaman_id', $id)->delete();
        }

        return redirect()->back()->with('success', 'Berhasil diajukan!');
    }
}