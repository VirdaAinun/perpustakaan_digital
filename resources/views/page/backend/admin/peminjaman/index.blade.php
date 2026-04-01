@extends('layouts.backend.admin.app')

@section('content')
<div class="container">

    <h5 class="mb-3">Data Peminjaman</h5>

    {{-- SEARCH --}}
    <div class="mb-3">
        <input type="text" class="form-control w-25" placeholder="Cari Peminjam.....">
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead style="background:#2c5aa0;color:white;">
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                    <b>{{ $item->nama_anggota }}</b><br>
                    <small>-</small>
                </td>

                <td>
                    {{ $item->buku->judul ?? '-' }}<br>
                    <small>{{ $item->buku->penulis ?? '' }}</small>
                </td>

                <td>{{ $item->tgl_pinjam }}</td>

                <td>1</td>

                <td>
                    @if($item->status == 'menunggu_verifikasi')
                        <span class="badge bg-warning text-dark">menunggu verifikasi</span>
                    @elseif($item->status == 'dipinjam')
                        <span class="badge bg-success">dipinjam</span>
                    @elseif($item->status == 'ditolak')
                        <span class="badge bg-danger">ditolak</span>
                    @else
                        <span class="badge bg-secondary">{{ $item->status }}</span>
                    @endif
                </td>

                <td>
                    {{-- KALAU MENUNGGU VERIFIKASI --}}
                    @if($item->status == 'menunggu_verifikasi')

                        <form action="{{ route('peminjaman.verifikasi',$item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="aksi" value="setuju">
                            <button class="btn btn-success btn-sm">✔</button>
                        </form>

                        <form action="{{ route('peminjaman.verifikasi',$item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="aksi" value="tolak">
                            <button class="btn btn-danger btn-sm">✖</button>
                        </form>

                    @else
                        {{-- HANYA VIEW --}}
                        <a href="#" class="btn btn-primary btn-sm">👁</a>
                    @endif

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection