@extends('layouts.frontend.app')

@section('content')
<style>
/* CONTAINER */
.katalog-container {
    padding: 20px;
}

/* GRID */
.katalog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

/* CARD */
.katalog-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: 0.3s;
    display: flex;
    flex-direction: column;
}

.katalog-card:hover {
    transform: translateY(-5px);
}

/* COVER */
.katalog-cover {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

/* BODY */
.katalog-body {
    padding: 12px;
    flex: 1;
}

.katalog-judul {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

.katalog-penulis {
    font-size: 13px;
    color: #777;
}

/* STATUS */
.katalog-status {
    font-size: 12px;
    margin-top: 5px;
}

.status-tersedia {
    color: green;
}

.status-dipinjam {
    color: red;
}

/* BUTTON */
.btn-pinjam {
    margin-top: 10px;
    width: 100%;
    background: #3490dc;
    color: #fff;
    border: none;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-pinjam:hover {
    background: #2779bd;
}

/* RESPONSIVE */
@media(max-width: 576px) {
    .katalog-cover {
        height: 180px;
    }
}
</style>
<div class="katalog-container">
    <h3>Katalog Buku</h3>

    <div class="katalog-grid">
        @foreach($bukus as $buku)
        <div class="katalog-card">

            <!-- GAMBAR COVER -->
            <img 
                src="{{ asset('storage/' . $buku->cover) }}" 
                class="katalog-cover"
                onerror="this.src='https://via.placeholder.com/200x220?text=No+Image'"
            >

            <!-- BODY -->
            <div class="katalog-body">
                <div class="katalog-judul">{{ $buku->judul }}</div>
                <div class="katalog-penulis">{{ $buku->penulis }}</div>

                <!-- STATUS -->
                <div class="katalog-status">
                    @if($buku->status == 'Tersedia')
                        <span class="status-tersedia">Tersedia</span>
                    @else
                        <span class="status-dipinjam">Dipinjam</span>
                    @endif
                </div>

                <!-- BUTTON -->
                <button 
                    class="btn-pinjam"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalPinjam"
                    data-id="{{ $buku->id }}"
                    data-judul="{{ $buku->judul }}"
                    {{ $buku->status != 'Tersedia' ? 'disabled' : '' }}
                >
                    Pinjam Buku
                </button>
            </div>

        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modalPinjam');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const judul = button.getAttribute('data-judul');

        // isi form
        document.getElementById('buku_id').value = id;
        document.getElementById('judul_buku').value = judul;

        // set action form dinamis
        document.getElementById('formPinjam').action = `/katalog/pinjam/${id}`;
    });
</script>

<!-- 🔥 SWEET ALERT -->
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif
@endpush