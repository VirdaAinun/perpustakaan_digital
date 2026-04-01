@extends('layouts.backend.admin.app')

@section('content')

<h2>Dashboard</h2>
<p class="welcome">Selamat Datang, Petugas</p>

<div class="cards">

<div class="card blue">
<h4>Total Buku</h4>
<h2>{{ $totalBuku }}</h2>
</div>

<div class="card yellow">
<h4>Buku Dipinjam</h4>
<h2>{{ $dipinjam }}</h2>
</div>

<div class="card green">
<h4>Anggota Aktif</h4>
<h2>{{ $anggota }}</h2>
</div>

<div class="card red">
<h4>Terlambat</h4>
<h2>{{ $terlambat }}</h2>
</div>

</div>

@endsection