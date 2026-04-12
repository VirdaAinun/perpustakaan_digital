<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use App\Models\Denda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with('buku', 'user', 'denda')
            ->whereIn('status', ['menunggu_verifikasi', 'selesai', 'terlambat']);

        if ($request->filled('search')) {
            $query->where('nama_anggota', 'like', '%' . $request->search . '%');
        }

        $data = $query->latest()->get();

        return view('page.backend.admin.pengembalian.index', compact('data'));
    }

    public function verifikasi($id)
    {
        $data = Peminjaman::with('buku', 'denda')->findOrFail($id);

        if (in_array($data->status, ['selesai', 'terlambat'])) {
            return back()->with('error', 'Data ini sudah diverifikasi sebelumnya!');
        }

        $today      = Carbon::now()->startOfDay();
        $jatuhTempo = Carbon::parse($data->tgl_kembali)->startOfDay();

        $denda         = 0;
        $terlambatHari = 0;

        if ($today->gt($jatuhTempo)) {
            $terlambatHari = $jatuhTempo->diffInDays($today);
            $denda         = $terlambatHari * 2000;
        }

        if ($denda > 0) {
            Denda::updateOrCreate(
                ['peminjaman_id' => $data->id],
                [
                    'hari_terlambat' => $terlambatHari,
                    'denda'          => $denda,
                    'status'         => 'menunggu'
                ]
            );
            $status = 'terlambat';
        } else {
            $status = 'selesai';
        }

        $data->update(['status' => $status]);

        // notifikasi
        Notifikasi::create([
            'user_id' => $data->user_id,
            'judul'   => 'Pengembalian Dikonfirmasi',
            'pesan'   => $denda > 0
                ? 'Pengembalian buku "' . ($data->buku->judul ?? '-') . '" terlambat. Denda: Rp ' . number_format($denda)
                : 'Pengembalian buku "' . ($data->buku->judul ?? '-') . '" telah dikonfirmasi. Terima kasih!',
        ]);

        return back()->with('success', 'Pengembalian berhasil diverifikasi!');
    }

    public function show($id)
    {
        $item = Peminjaman::with('buku', 'user', 'denda')->findOrFail($id);

        return view('page.backend.admin.pengembalian.show', compact('item'));
    }
}