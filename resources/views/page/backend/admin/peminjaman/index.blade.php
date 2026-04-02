@extends('layouts.backend.admin.app')

@section('content')

<style>
/* CONTAINER */
.container{
    background:#f4f6f9;
    padding:25px;
    min-height:90vh;
}

/* TITLE */
h5{
    font-weight:600;
    color:#2c3e50;
}

/* SEARCH */
input.form-control{
    border-radius:6px;
    border:1px solid #ccc;
    padding:10px;
}

/* TABLE */
.table{
    background:white;
    border-radius:10px;
    overflow:hidden;
}

.table thead{
    background:#2c3e50 !important;
    color:white;
}

.table th{
    text-align:center;
    font-weight:500;
    padding:12px;
}

.table td{
    text-align:center;
    padding:12px;
    vertical-align:middle;
}

/* ROW HOVER */
.table tbody tr:hover{
    background:#f2f2f2;
}

/* BADGE STATUS */
.badge{
    padding:6px 10px;
    border-radius:6px;
    font-size:12px;
}

.bg-warning{
    background:#f39c12 !important;
    color:white !important;
}

.bg-success{
    background:#27ae60 !important;
}

.bg-danger{
    background:#c0392b !important;
}

/* BUTTON */
.btn{
    border-radius:6px;
    font-size:13px;
    padding:5px 10px;
}

.btn-success{
    background:#27ae60;
    border:none;
}

.btn-success:hover{
    background:#219150;
}

.btn-danger{
    background:#c0392b;
    border:none;
}

.btn-danger:hover{
    background:#a93226;
}

.btn-primary{
    background:#2980b9;
    border:none;
}

.btn-primary:hover{
    background:#2471a3;
}

/* NOTIF (FORMAL) */
.notif{
    position:fixed;
    top:20px;
    right:20px;
    background:#2c3e50;
    color:white;
    padding:12px 20px;
    border-radius:6px;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
    z-index:9999;
    font-size:14px;
    animation:fadeIn 0.4s ease;
}

/* ANIMASI */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(-10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>

@if(session('success'))
<div id="notif" class="notif">
    {{ session('success') }}
</div>
@endif
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
                    @if($item->status == 'menunggu')
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
                    @if($item->status == 'menunggu')

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
<script>
setTimeout(() => {
    let notif = document.getElementById('notif');
    if(notif){
        notif.style.transition = "0.5s";
        notif.style.opacity = "0";
        setTimeout(()=> notif.remove(), 500);
    }
}, 3000);
</script>
@endsection