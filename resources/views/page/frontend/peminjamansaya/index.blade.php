@extends('layouts.frontend.app')

@section('content')

<style>
.container {
    max-width: 1100px;
    margin: 20px auto;
    padding: 15px;
}

h1 {
    font-size: 1.6rem;
    margin-bottom: 20px;
    color: #2c3e50;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.table th, .table td {
    padding: 10px;
    border: 1px solid #ddd;
}

.table th {
    background: #2c3e50;
    color: white;
}

/* BUTTON */
.btn {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 13px;
    border: none;
    cursor: pointer;
}

.btn-detail {
    background: #27ae60;
    color: white;
}

.btn-pengembalian {
    background: #c0392b;
    color: white;
}

/* STATUS */
.status {
    padding: 4px 8px;
    border-radius: 4px;
    color: white;
    font-size: 12px;
}

.status-dipinjam { background:#2980b9; }
.status-mengajukan_pengembalian { background:#f39c12; }
.status-selesai { background:#27ae60; }
.status-ditolak { background:#c0392b; }
.status-menunggu { background:#8e44ad; }

/* MODAL */
.modal {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
}

.modal-content {
    background:white;
    padding:20px;
    border-radius:8px;
    text-align:center;
}

/* NOTIF */
.notif{
position:fixed;
top:20px;
right:20px;
background:#2c3e50;
color:white;
padding:10px 20px;
border-radius:6px;
}
</style>

{{-- NOTIF --}}
@if(session('success'))
<div id="notif" class="notif">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="container">

<h1>Peminjaman Saya</h1>

@if($peminjamans->count())
<table class="table">
<thead>
<tr>
    <th>Buku</th>
    <th>Tgl Pinjam</th>
    <th>Tgl Kembali</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@foreach($peminjamans as $p)
<tr>
    <td>{{ $p->buku->judul ?? '-' }}</td>
    <td>{{ $p->tgl_pinjam }}</td>
    <td>{{ $p->tgl_kembali }}</td>

    <td>
        <span class="status status-{{ $p->status }}">
            {{ ucfirst(str_replace('_',' ',$p->status)) }}
        </span>
    </td>

    <td>
        <a href="{{ route('peminjamansaya.show',$p->id) }}" class="btn btn-detail">Detail</a>

        @if($p->status == 'dipinjam')
        <button class="btn btn-pengembalian"
            onclick="openModal('{{ $p->id }}','{{ $p->buku->judul }}')">
            Ajukan Pengembalian
        </button>
        @endif
    </td>
</tr>
@endforeach
</tbody>

</table>

{{ $peminjamans->links() }}

@else
<p>Tidak ada peminjaman</p>
@endif

</div>

{{-- MODAL --}}
<div id="modalPinjam" class="modal">
<div class="modal-content">

<p id="modalText"></p>

<form id="formPengembalian" method="POST">
@csrf

<button type="submit" class="btn btn-pengembalian">Ya</button>
<button type="button" class="btn btn-detail" onclick="closeModal()">Batal</button>

</form>

</div>
</div>

<script>
function openModal(id,judul){
document.getElementById("modalPinjam").style.display="flex";
document.getElementById("modalText").innerText =
"Yakin ingin mengajukan pengembalian buku '"+judul+"' ?";
document.getElementById("formPengembalian").action =
"/anggota/peminjamansaya/ajukan/"+id;
}

function closeModal(){
document.getElementById("modalPinjam").style.display="none";
}

// auto hilang notif
setTimeout(()=>{
let notif=document.getElementById('notif');
if(notif){
notif.style.opacity="0";
setTimeout(()=>notif.remove(),500);
}
},3000);
</script>

@endsection