@extends('layouts.backend.superadmin.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .card-header { background: #fff; border-bottom: 1px solid #eee; padding: 1.5rem; border-radius: 12px 12px 0 0 !important; }
    .table thead th { background-color: #f8f9fa; color: #6c757d; font-weight: 600; font-size: 0.85rem; border-top: none; }
    .btn-custom { border-radius: 8px; padding: 8px 16px; font-weight: 500; }
</style>

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Manajemen Data User</h5>
            <button type="button" class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-user-plus me-2"></i>Tambah User
            </button>
        </div>
        <div class="card-body">
            
            <form action="{{ route('superadmin.datauser.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-secondary">Cari</button>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('superadmin.datauser.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('superadmin.datauser.update', $user->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">Edit User</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Kosongkan password jika tidak ingin diganti</label>
                                                <input type="password" name="password" class="form-control" placeholder="Password baru">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('superadmin.datauser.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah User Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Input nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Input email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection