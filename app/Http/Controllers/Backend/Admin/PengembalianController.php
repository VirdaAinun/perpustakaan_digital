<?php

namespace App\Http\Controllers\Backend\Admin;

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
            ->where(function($q) {
                $q->whereIn('status', ['menunggu_verifikasi', 'selesai', 'terlambat'])
                  ->orWhere(function($q2) {
                      $q2->where('status', 'dipinjam')
                         ->whereNotNull('alasan_tolak_pengembalian');
                  });
            });

        if ($request->filled('search')) {
            $query->where('nama_anggota', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'ditolak') {
                $query->where('status', 'dipinjam')->whereNotNull('alasan_tolak_pengembalian');
            } else {
                $query->where('status', $request->status);
            }
        }

        $data = $query->latest()->paginate(10)->withQueryString();

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

        // Kembalikan stok buku
        if ($data->buku) {
            $data->buku->stok += $data->jumlah_pinjam;
            $data->buku->status = 'Tersedia';
            $data->buku->save();
        }

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

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan'          => 'required|string|max:500',
            'denda_kerusakan' => 'nullable|numeric|min:0',
        ]);

        $data = Peminjaman::with('buku')->findOrFail($id);

        $data->update([
            'status'                    => 'dipinjam',
            'alasan_tolak_pengembalian' => $request->alasan,
        ]);

        // Jika ada denda kerusakan
        if ($request->filled('denda_kerusakan') && $request->denda_kerusakan > 0) {
            Denda::updateOrCreate(
                ['peminjaman_id' => $data->id, 'jenis' => 'kerusakan'],
                [
                    'jenis'         => 'kerusakan',
                    'hari_terlambat'=> 0,
                    'denda'         => $request->denda_kerusakan,
                    'status'        => 'menunggu',
                ]
            );

            $pesanDenda = ' Denda kerusakan: Rp ' . number_format($request->denda_kerusakan, 0, ',', '.');
        } else {
            $pesanDenda = '';
        }

        Notifikasi::create([
            'user_id' => $data->user_id,
            'judul'   => 'Pengembalian Ditolak',
            'pesan'   => 'Pengajuan pengembalian buku "' . ($data->buku->judul ?? '-') . '" ditolak. Alasan: ' . $request->alasan . $pesanDenda . '. Silakan ajukan kembali.',
        ]);

        return back()->with('success', 'Pengajuan pengembalian berhasil ditolak.');
    }

    public function show($id)
    {
        $item = Peminjaman::with('buku', 'user', 'denda')->findOrFail($id);

        return view('page.backend.admin.pengembalian.show', compact('item'));
    }
}