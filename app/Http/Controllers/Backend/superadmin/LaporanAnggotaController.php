<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%")
                  ->orWhere('kelas', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $anggota = $query->with('user')->latest()->get();

        $anggota->transform(function ($row) {
            $row->peminjaman_count = Peminjaman::where('nama_anggota', $row->nama)->count();
            return $row;
        });

        return view('page.backend.superadmin.laporananggota.index', compact('anggota'));
    }

    public function exportPdf(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%")
                  ->orWhere('kelas', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $anggota = $query->with('user')->latest()->get();

        $pdf = Pdf::loadView('page.backend.superadmin.laporananggota.pdf', compact('anggota'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Anggota_' . date('Y-m-d') . '.pdf');
    }
}