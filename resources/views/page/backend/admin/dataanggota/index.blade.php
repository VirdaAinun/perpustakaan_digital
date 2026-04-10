@extends('layouts.backend.admin.app')

@section('content')
<style>
    .container-admin { padding: 30px; background: #f4f7f6; min-height: 100vh; }
    .header-admin { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .btn-tambah { background: #1f5f99; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; }
    .table-box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(11, 11, 11, 0.1); }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #1f5f99; color: white; }
    th{ padding: 15px; text-align: left; border-bottom: 1px solid #eee; color: #ffffff; }
    td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; color: #000000; }
    
    .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .aktif { background: #d4edda; color: #155724; }
    .nonaktif { background: #f8d7da; color: #721c24; }

    .btn-aksi { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; color: white; margin-right: 5px; border: none; cursor: pointer; }
    .btn-edit { background: #f39c12; }
    .btn-hapus { background: #e74c3c; }
</style>

<div class="container-admin">
    <div class="header-admin">
        <h2>Data Anggota Perpustakaan</h2>
        <a href="{{ route('admin.dataanggota.create') }}" class="btn-tambah">+ Tambah Anggota</a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggota as $index => $row)
                <tr>
                    <td>{{ $anggota->firstItem() + $index }}</td>
                    <td><b>{{ $row->nis }}</b></td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->kelas }}</td>
                    <td>
                        <span class="status-badge {{ $row->status == 'aktif' ? 'aktif' : 'nonaktif' }}">
                            {{ ucfirst($row->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex;">
                            <a href="{{ route('admin.dataanggota.edit', $row->id) }}" class="btn-aksi btn-edit">Edit</a>
                            
                            <form action="{{ route('admin.dataanggota.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-aksi btn-hapus">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #999;">Belum ada data anggota.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $anggota->links() }}
        </div>
    </div>
</div>
@endsection