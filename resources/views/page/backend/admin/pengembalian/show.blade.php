@extends('layouts.backend.admin.app')

@section('content')
<style>
    .detail-wrapper { padding: 25px; background: #f8f9fa; min-height: 100vh; }
    
    .detail-card {
        background: #fff;
        border-radius: 16px;
        padding: 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .detail-header {
        background: #1a5da4;
        color: #fff;
        padding: 25px 30px;
        position: relative;
    }
    
    .detail-header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        right: 0;
        height: 10px;
        background: #1a5da4;
        border-radius: 0 0 50% 50%;
    }
    
    .detail-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .detail-body { padding: 35px 30px 25px; }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
    }
    
    .info-item {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 18px;
        border-left: 4px solid #1a5da4;
    }
    
    .info-label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.4;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .status-menunggu { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    .status-terlambat { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .status-selesai { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    
    .btn-back {
        background: #6c757d;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }
    
    .btn-back:hover {
        background: #5a6268;
        color: #fff;
        transform: translateY(-1px);
    }
    
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        margin: 20px 0;
    }
</style>

<div class="detail-wrapper">
    <div class="detail-card">
        
        <div class="detail-header">
            <h4 class="detail-title">
                📤 Detail Pengembalian Buku
            </h4>
        </div>
        
        <div class="detail-body">
            
            <div class="info-grid">
                
                <div class="info-item">
                    <div class="info-label">👤 Nama Anggota</div>
                    <div class="info-value">{{ $item->nama_anggota }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">📧 Email</div>
                    <div class="info-value">{{ $item->user->email ?? 'Tidak tersedia' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">📚 Judul Buku</div>
                    <div class="info-value">{{ $item->buku->judul ?? 'Buku telah dihapus' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">✍️ Penulis</div>
                    <div class="info-value">{{ $item->buku->penulis ?? 'Tidak tersedia' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">📅 Tanggal Pinjam</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d F Y') }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">🔄 Tanggal Kembali</div>
                    <div class="info-value">
                        {{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d F Y') : 'Belum ditentukan' }}
                    </div>
                </div>
                
            </div>
            
            <div class="divider"></div>
            
            <div class="info-item" style="text-align: center; background: #fff; border: 2px dashed #dee2e6;">
                <div class="info-label">🏷️ Status Pengembalian</div>
                <div class="info-value">
                    @if($item->status == 'menunggu_verifikasi')
                        <span class="status-badge status-menunggu">
                            ⏳ Menunggu Verifikasi
                        </span>
                    @elseif($item->status == 'terlambat')
                        <span class="status-badge status-terlambat">
                            ⚠️ Terlambat
                        </span>
                    @elseif($item->status == 'selesai')
                        <span class="status-badge status-selesai">
                            ✅ Selesai
                        </span>
                    @else
                        <span class="status-badge status-menunggu">
                            📋 {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    @endif
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('pengembalian.index') }}" class="btn-back">
                    ← Kembali ke Daftar Pengembalian
                </a>
            </div>
            
        </div>
        
    </div>
</div>
@endsection