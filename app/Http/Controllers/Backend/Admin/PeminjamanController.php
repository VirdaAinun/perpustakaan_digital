<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Notifikasi;

class PeminjamanController extends Controller
{
    public function index(Request $request) 
    {
        // 1. Mulai query dengan relasi buku
        $query = Peminjaman::with('buku', 'user');

        // 2. LOGIKA CARI: Jika input search diisi, saring berdasarkan NAMA ANGGOTA
        if ($request->filled('search')) {
            $query->where('nama_anggota', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->paginate(10)->withQueryString();
        return view('page.backend.admin.peminjaman.index', compact('data'));
    }
    /**
     * Verifikasi peminjaman 
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:setuju,tolak'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        // Hanya bisa diverifikasi jika status menunggu
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Data sudah diverifikasi sebelumnya');
        }

        if ($request->aksi == 'setuju') {
            // Cek stok buku
            $buku = $peminjaman->buku;
            if (!$buku || $buku->stok < $peminjaman->jumlah_pinjam) {
                $stokTersedia = $buku ? $buku->stok : 0;
                return back()->with('warning', 
                    'Stok buku "' . ($buku->judul ?? '-') . '" tidak mencukupi! ' .
                    'Diminta: ' . $peminjaman->jumlah_pinjam . ' buku, ' .
                    'Tersedia: ' . $stokTersedia . ' buku. Silakan tolak atau hubungi anggota.');
            }

            // Kurangi stok saat disetujui
            $buku->stok -= $peminjaman->jumlah_pinjam;
            $buku->status = $buku->stok == 0 ? 'Habis' : 'Tersedia';
            $buku->save();

            $peminjaman->status = 'dipinjam';
            Notifikasi::create([
                'user_id' => $peminjaman->user_id,
                'judul'   => 'Peminjaman Disetujui',
                'pesan'   => 'Peminjaman buku "' . ($peminjaman->buku->judul ?? '-') . '" telah disetujui. Silakan ambil buku di perpustakaan.',
            ]);
        } else {
            $peminjaman->status = 'ditolak';

            // Stok tidak perlu dikembalikan karena belum dikurangi saat pengajuan

            $alasan = $request->filled('alasan') ? $request->alasan : 'Tidak ada keterangan dari petugas.';

            Notifikasi::create([
                'user_id' => $peminjaman->user_id,
                'judul'   => '❌ Peminjaman Ditolak',
                'pesan'   => 'Peminjaman buku "' . ($peminjaman->buku->judul ?? '-') . '" ditolak oleh petugas. Alasan: ' . $alasan,
            ]);
        }

        $peminjaman->save();

        return back()->with('success', 'Status peminjaman berhasil diperbarui');
    }

    public function show($id)
{
    $data = \App\Models\Peminjaman::with('buku.kategori', 'user')->findOrFail($id);

    return view('page.backend.admin.peminjaman.show', compact('data'));
}
}