@extends('layouts.backend.admin.app')

@section('content')
<div class="container">

    <h5 class="mb-3 fw-bold">Data Pengembalian</h5>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- SEARCH UI --}}
    <div class="mb-3">
        <input type="text" class="form-control w-25" placeholder="Cari Pengembalian...">
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead style="background:#198754;color:white;">
                    <tr class="text-center">
                        <th>No</th>
                        <th class="text-start">Nama Anggota</th>
                        <th class="text-start">Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Dikembalikan</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>

                        {{-- NAMA --}}
                        <td>
                            <b>{{ $item->peminjaman->nama_anggota }}</b>
                        </td>

                        {{-- BUKU --}}
                        <td>
                            {{ $item->peminjaman->buku->judul ?? '-' }}<br>
                            <small class="text-muted">
                                {{ $item->peminjaman->buku->penulis ?? '' }}
                            </small>
                        </td>

                        {{-- TGL PINJAM --}}
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->peminjaman->tgl_pinjam)->format('d M Y') }}
                        </td>

                        {{-- TGL KEMBALI --}}
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->tgl_dikembalikan)->format('d M Y') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">
                            @if($item->status == 'menunggu_verifikasi')
                                <span class="badge bg-warning text-dark">menunggu verifikasi</span>
                            @else
                                <span class="badge bg-success">dikembalikan</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="text-center">

                            @if($item->status == 'menunggu_verifikasi')

                                <form action="{{ route('pengembalian.verifikasi',$item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm">
                                        ✔ Verifikasi
                                    </button>
                                </form>

                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    ✔ Selesai
                                </button>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Tidak ada data pengembalian
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection