@extends('layouts.frontend.app')

@section('content')
<style>
.container {
    max-width: 1200px;
    margin: 20px auto 0 auto;
    padding: 0 15px;
}
h1 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    text-align: left;
    color: #2c3e50;
}
.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.table th, .table td {
    padding: 10px 12px;
    border: 1px solid #dcdde1;
    text-align: left;
}
.table th {
    background-color: #f1f2f6;
    color: #2c3e50;
}
.btn {
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.85rem;
    color: white;
    margin-right: 5px;
    cursor: pointer;
    border: none;
}
.btn-detail { background-color: #27ae60; }
.btn-detail:hover { background-color: #1e8449; }
.btn-pengembalian { background-color: #c0392b; }
.btn-pengembalian:hover { background-color: #922b21; }
.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
}
.status-dipinjam { background-color: #2980b9; }
.status-mengajukan_pengembalian { background-color: #f39c12; }
.status-selesai { background-color: #27ae60; }
.status-ditolak { background-color: #c0392b; }
.status-menunggu_verifikasi { background-color: #8e44ad; }

/* ---------- MODAL ---------- */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0; top: 0; width: 100%; height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.5);
}
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    max-width: 400px;
    text-align: center;
}
.modal-content p { margin-bottom: 20px; font-size: 1rem; }
.modal-content button {
    margin: 0 5px;
}
</style>

<div class="container">
    <h1>Peminjaman Saya</h1>

    @if($peminjamans->count())
    <table class="table">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $p)
            <tr>
                <td>{{ $p->buku->judul }}</td>
                <td>{{ $p->tgl_pinjam }}</td>
                <td>{{ $p->tgl_kembali }}</td>
                <td>
                    <span class="status status-{{ str_replace('_','-',$p->status) }}">
                        {{ ucfirst(str_replace('_',' ',$p->status)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('peminjamansaya.show', $p->id) }}" class="btn btn-detail">Detail</a>
                    @if($p->status == 'dipinjam')
                        <button class="btn btn-pengembalian" onclick="openModal({{ $p->id }}, '{{ $p->buku->judul }}')">Ajukan Pengembalian</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $peminjamans->links() }}
    @else
        <p>Tidak ada peminjaman.</p>
    @endif
</div>

<!-- MODAL POPUP -->
<div id="pengembalianModal" class="modal">
    <div class="modal-content">
        <p id="modalText">Apakah Anda yakin ingin mengajukan pengembalian buku ini?</p>
        <form id="modalForm" method="POST" action="">
            @csrf
            <button type="submit" class="btn btn-pengembalian">Ya, Ajukan</button>
            <button type="button" class="btn btn-detail" onclick="closeModal()">Batal</button>
        </form>
    </div>
</div>

<script>
function openModal(id, judul){
    document.getElementById('pengembalianModal').style.display = 'block';
    document.getElementById('modalText').innerText = 'Apakah Anda yakin ingin mengajukan pengembalian buku "' + judul + '"?';
    document.getElementById('modalForm').action = '/anggota/peminjamansaya/ajukan/' + id;
}

function closeModal(){
    document.getElementById('pengembalianModal').style.display = 'none';
}

// klik di luar modal juga menutup
window.onclick = function(event){
    var modal = document.getElementById('pengembalianModal');
    if(event.target == modal){
        modal.style.display = "none";
    }
}
</script>
@endsection