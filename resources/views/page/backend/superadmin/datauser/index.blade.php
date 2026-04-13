@extends('layouts.backend.superadmin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .page-wrapper { padding: 30px; min-height: 100vh; }

    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-header h5 { font-weight: 700; color: #2c3e50; font-size: 22px; margin: 0; border-left: 5px solid #1a5da4; padding-left: 15px; }

    .search-box {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    .search-box input {
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        width: 280px;
        outline: none;
        background: #fff;
    }
    .search-box input:focus { border-color: #1a5da4; }
    .search-box button {
        padding: 10px 20px;
        background: #1a5da4;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-tambah {
        background: #1a5da4;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-tambah:hover { background: #14467a; }

    /* TABLE */
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead { background: #1a5da4; }
    .table-custom th {
        color: white; font-size: 11px; text-transform: uppercase;
        letter-spacing: 0.8px; padding: 14px 15px; border: none; font-weight: 700;
    }
    .table-custom td {
        padding: 14px 15px; font-size: 13px; color: #444;
        border-bottom: 1px solid #f0f0f0; vertical-align: middle;
        background: white;
    }
    .table-custom tbody tr:hover { background: #f9fbff; }

    .btn-edit {
        background: #fff4e0; color: #f39c12; border: none;
        padding: 6px 12px; border-radius: 6px; font-size: 12px;
        cursor: pointer; margin-right: 4px;
    }
    .btn-hapus {
        background: #fde8e8; color: #c0392b; border: none;
        padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;
    }
    .btn-edit:hover { background: #ffe8b0; }
    .btn-hapus:hover { background: #fbc8c8; }

    /* NOTIF */
    .notif-success {
        background: #e1f7ea; color: #27ae60; padding: 12px 16px;
        border-radius: 8px; margin-bottom: 20px; font-size: 13px;
        display: flex; justify-content: space-between; align-items: center;
    }

    /* MODAL */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); z-index: 9999;
        justify-content: center; align-items: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff; border-radius: 12px; padding: 28px;
        width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .modal-box h5 { margin: 0 0 20px; font-weight: 700; font-size: 16px; color: #1a1a2e; }
    .modal-box label { font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px; color: #444; }
    .modal-box input {
        width: 100%; padding: 10px 12px; border: 1.5px solid #dde3ec;
        border-radius: 7px; font-size: 13px; margin-bottom: 15px; outline: none; box-sizing: border-box;
    }
    .modal-box input:focus { border-color: #1a5da4; }
    .modal-box .hint { font-size: 11px; color: #999; margin-top: -10px; margin-bottom: 15px; }
    .modal-footer-btn { display: flex; justify-content: flex-end; gap: 8px; margin-top: 5px; }
    .btn-cancel {
        background: #f1f3f5; color: #555; border: none;
        padding: 9px 20px; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer;
    }
    .btn-save {
        background: #1a5da4; color: #fff; border: none;
        padding: 9px 22px; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer;
    }
    .btn-cancel:hover { background: #e2e6ea; }
    .btn-save:hover { background: #14467a; }
</style>

<div class="page-wrapper">

    <div class="page-header">
        <h5>Manajemen Data User</h5>
        <button class="btn-tambah" onclick="openModal('modalTambah')">
            <i class="fas fa-user-plus"></i> Tambah User
        </button>
    </div>

    @if(session('success'))
        <div class="notif-success">
            <span>✅ {{ session('success') }}</span>
            <span onclick="this.parentElement.remove()" style="cursor:pointer; font-size:16px;">✕</span>
        </div>
    @endif

    <form action="{{ route('superadmin.datauser.index') }}" method="GET" class="search-box">
        <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}">
        <button type="submit"><i class="fas fa-search me-1"></i> Cari</button>
    </form>

    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="50" style="text-align:center;">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="150" style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="text-align:center;">{{ $loop->iteration }}</td>
                    <td style="font-weight:600;">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td style="text-align:center;">
                        <button class="btn-edit" onclick="openEdit({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('superadmin.datauser.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:40px; color:#999;">Belum ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box">
        <h5>➕ Tambah User Baru</h5>
        <form action="{{ route('superadmin.datauser.store') }}" method="POST">
            @csrf
            <label>Nama Lengkap</label>
            <input type="text" name="name" placeholder="Input nama" required>
            <label>Email</label>
            <input type="email" name="email" placeholder="Input email" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="Min. 6 karakter" required minlength="6">
            <div class="modal-footer-btn">
                <button type="button" class="btn-cancel" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">
        <h5>✏️ Edit User</h5>
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <label>Nama</label>
            <input type="text" id="editName" name="name" required>
            <label>Email</label>
            <input type="email" id="editEmail" name="email" required>
            <label>Password Baru</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diganti">
            <p class="hint">* Kosongkan jika tidak ingin mengganti password</p>
            <div class="modal-footer-btn">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    function openEdit(id, name, email) {
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('formEdit').action = '/superadmin/data-user/' + id;
        openModal('modalEdit');
    }
    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', e => { if (e.target === el) el.classList.remove('active'); });
    });
</script>
@endsection
