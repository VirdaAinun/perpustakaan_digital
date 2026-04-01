@extends('layouts.backend.admin.app')

@section('content')
<div class="container">

    <h5 class="mb-3 fw-bold">Data Denda</h5>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead style="background:#dc3545;color:white;">
                    <tr class="text-center">
                        <th>No</th>
                        <th class="text-start">Nama Anggota</th>
                        <th class="text-start">Buku</th>
                        <th>Hari Terlambat</th>
                        <th>Total Denda</th>
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

                        {{-- TERLAMBAT --}}
                        <td class="text-center">
                            {{ $item->hari_terlambat }} hari
                        </td>

                        {{-- DENDA --}}
                        <td class="text-center">
                            Rp {{ number_format($item->denda,0,',','.') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">
                            @if($item->status == 'menunggu')
                                <span class="badge bg-warning text-dark">menunggu</span>
                            @else
                                <span class="badge bg-success">selesai</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="text-center">

                            @if($item->status == 'menunggu')

                                <form action="{{ route('denda.bayar',$item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm">
                                        ✔ Bayar
                                    </button>
                                </form>

                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    ✔ Lunas
                                </button>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Tidak ada data denda
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection