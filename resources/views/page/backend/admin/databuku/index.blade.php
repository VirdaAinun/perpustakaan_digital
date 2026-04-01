@extends('layouts.backend.admin.app')

@section('content')

<style>
.container-custom {
    padding: 25px;
}

/* CARD */
.card-custom {
    background: #ffffff;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #ddd;
}

/* TITLE */
.title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
}

/* BUTTON */
.btn-primary-custom {
    background: #34495e;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
}

.btn-primary-custom:hover {
    background: #2c3e50;
}

/* TABLE */
.table-custom {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table-custom thead {
    background: #f8f9fa;
}

.table-custom th {
    padding: 12px;
    text-align: center;
    border-bottom: 2px solid #ddd;
    color: #555;
}

.table-custom td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.table-custom tbody tr:hover {
    background: #f9f9f9;
}

/* IMAGE */
.img-buku {
    width: 55px;
    height: 75px;
    object-fit: cover;
    border-radius: 4px;
}

/* BADGE */
.badge-status {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
    color: white;
}

.tersedia {
    background: #27ae60;
}

.habis {
    background: #e74c3c;
}

/* ACTION BUTTON */
.btn-edit {
    background: #2980b9;
    color: #fff;
    border: none;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 13px;
    text-decoration: none;
}

.btn-edit:hover {
    background: #2471a3;
}

.btn-delete {
    background: #c0392b;
    color: #fff;
    border: none;
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 13px;
}

.btn-delete:hover {
    background: #a93226;
}
</style>

<div class="container container-custom">
    <div class="card-custom">

    <h2 class="title">Data Buku</h2>

    <a href="{{ route('databuku.create') }}" class="btn-primary-custom">
        + Tambah Buku
    </a>

    <br><br>

    <table class="table-custom">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
@forelse($bukus as $index => $item)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>
        @if($item->photo)
            <img src="{{ asset('storage/' . $item->photo) }}" width="50" height="70" alt="cover">
        @else
            <img src="https://via.placeholder.com/50x70?text=No+Image">
        @endif
    </td>
    <td>{{ $item->judul }}</td>
    <td>{{ $item->penulis }}</td>
    <td>{{ $item->penerbit }}</td>
    <td>{{ $item->kategori }}</td>
    <td>{{ $item->stok }}</td>
    <td>{{ $item->status }}</td>
    <td>
        <a href="{{ route('databuku.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('databuku.destroy', $item->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus buku ini?')">Hapus</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center">Data buku tidak tersedia</td>
</tr>
@endforelse
</tbody>

    </table>

</div>

</div>

@endsection
