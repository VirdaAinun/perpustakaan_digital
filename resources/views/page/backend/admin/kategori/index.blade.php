@extends('layouts.backend.admin.app')

@section('content')
<style>
    .kategori-wrapper { padding: 30px; background: #f8f9fa; min-height: 100vh; }

    .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .title { color: #1a1a2e; font-weight: 700; font-size: 22px; margin: 0; }

    .btn-add {
        background: #005fa8; color: #fff; border: none;
        padding: 9px 20px; border-radius: 6px; font-size: 13px;
        font-weight: 600; cursor: pointer; display: inline-flex;
        align-items: center; gap: 6px;
    }

    .alert-success { background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }

    /* TABLE */
    .table-card { background: #fff; border-radius: 0px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead { background: #005fa8; }
    .table-custom th { padding: 13px 16px; color: #fff; text-transform: uppercase; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; border: none; }
    .table-custom td { padding: 13px 16px; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #333; vertical-align: middle; }
    .table-custom tbody tr:last-child td { border-bottom: none; }
    .table-custom tbody tr:hover { background: #f9fbff; }

    /* AKSI */
    .aksi-group { display: flex; gap: 6px; justify-content: center; }
    .btn-edit {
        background: #f39c12; color: #fff; border: none;
        padding: 5px 12px; border-radius: 5px; font-size: 12px;
        font-weight: 600; cursor: pointer; display: inline-flex;
        align-items: center; gap: 4px;
    }
    .btn-hapus {
        background: #e74c3c; color: #fff; border: none;
        padding: 5px 12px; border-radius: 5px; font-size: 12px;
        font-weight: 600; cursor: pointer; display: inline-flex;
        align-items: center; gap: 4px;
    }
    .btn-edit:hover { background: #e08e0b; }
    .btn-hapus:hover { background: #c0392b; }

    /* MODAL */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 9999; justify-content: center; align-items: center; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #fff; border-radius: 12px; padding: 28px; width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
    .modal-box h5 { margin: 0 0 20px; font-weight: 700; font-size: 16px; color: #1a1a2e; }
    .modal-box label { font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px; color: #444; }
    .modal-box input { width: 100%; padding: 10px 12px; border: 1.5px solid #dde3ec; border-radius: 7px; font-size: 13px; margin-bottom: 20px; outline: none; }
    .modal-box input:focus { border-color: #005fa8; }
    .modal-footer-btn { display: flex; justify-content: flex-end; gap: 8px; }
    .btn-cancel { background: #f1f3f5; color: #555; border: none; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; }
    .btn-save { background: #005fa8; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; }
    .btn-cancel:hover { background: #e2e6ea; }
    .btn-save:hover { background: #004a82; }
</style>

<div class="kategori-wrapper">

    <div class="header-flex">
        <h2 class="title">Data Kategori</h2>
        <button class="btn-add" onclick="openModal('modalTambah')">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    <div class="table-card">
        <table class="table-custom text-center">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th style="text-align:left;">Nama Kategori</th>
                    <th width="130">Jumlah Buku</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $index => $item)
                <tr>
                    <td>{{ $kategoris->firstItem() + $index }}</td>
                    <td style="text-align:left; font-weight:500;">{{ $item->nama_kategori }}</td>
                    <td>
                        <span style="color:#005fa8; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                            {{ $item->bukus->count() }} Buku
                        </span>
                    </td>
                    <td>
                        <div class="aksi-group">
                            <button class="btn-edit" onclick="openEdit({{ $item->id }}, '{{ addslashes($item->nama_kategori) }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-hapus" onclick="return confirm('Hapus kategori ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:40px; color:#999;">Belum ada data kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $kategoris->links('pagination::bootstrap-5') }}
    </div>

</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box">
        <h5>➕ Tambah Kategori</h5>
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori..." required autofocus>
            <div class="modal-footer-btn">
                <button type="button" class="btn-cancel" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">
        <h5>✏️ Edit Kategori</h5>
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <label>Nama Kategori</label>
            <input type="text" id="editNama" name="nama_kategori" required>
            <div class="modal-footer-btn">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    function openEdit(id, nama) {
        document.getElementById('editNama').value = nama;
        document.getElementById('formEdit').action = '/admin/kategori/' + id;
        openModal('modalEdit');
    }
    // Tutup modal klik di luar
    document.querySelectorAll('.modal-overlay').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (e.target === el) el.classList.remove('active');
        });
    });
</script>
@endsection
