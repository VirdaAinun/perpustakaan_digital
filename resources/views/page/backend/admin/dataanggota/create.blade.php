@extends('layouts.backend.admin.app')

@section('content')
<style>
    .container-form { padding: 40px; background: #f4f7f6; min-height: 100vh; }
    .form-box { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: bold; color: #1f5f99; }
    input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
    .btn-simpan { background: #1f5f99; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; }
    .btn-batal { display: block; text-align: center; margin-top: 15px; color: #777; text-decoration: none; }
</style>

<div class="container-form">
    <div class="form-box">
        <h3 style="text-align: center; color: #1f5f99; margin-bottom: 25px;">Tambah Anggota Baru</h3>
        
        <form action="{{ route('admin.dataanggota.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>NIS (Akan menjadi Password Default)</label>
                <input type="text" name="nis" placeholder="Masukkan NIS..." required>
                @error('nis') <small style="color:red">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap..." required>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" placeholder="Contoh: XII RPL 1" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="aktif">Aktif</option>
                    <option value="tidak_aktif">Non-Aktif</option>
                </select>
            </div>

            <button type="submit" class="btn-simpan">Simpan Data</button>
            <a href="{{ route('admin.dataanggota.index') }}" class="btn-batal">Batal</a>
        </form>
    </div>
</div>
@endsection