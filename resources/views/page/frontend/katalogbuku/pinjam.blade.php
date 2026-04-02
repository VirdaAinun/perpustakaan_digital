@extends('layouts.frontend.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center mb-4">📦 Form Peminjaman</h3>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body">

                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/'.$buku->photo) }}" 
                             style="height:150px;">
                    </div>

                    <h5 class="text-center">{{ $buku->judul }}</h5>

                    <form method="POST" action="{{ route('pinjam.store') }}">
                        @csrf

                        <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                        <input type="hidden" name="judul_buku" value="{{ $buku->judul }}">

                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" 
                                   class="form-control" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" required>
                        </div>

                        <button class="btn btn-success w-100">
                            ✅ Pinjam Sekarang
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection