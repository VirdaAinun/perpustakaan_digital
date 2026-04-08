@extends('layouts.backend.superadmin.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .super-wrapper { 
        background: #f8f9fd; 
        padding: 30px; 
        min-height: 100vh; 
        font-family: 'Plus Jakarta Sans', sans-serif; 
    }

    /* Judul Header */
    .dashboard-header h2 { color: #2b3674; font-weight: 800; font-size: 24px; margin-bottom: 2px; }
    .dashboard-header p { color: #a3aed0; font-size: 14px; margin-bottom: 30px; }

    /* --- STAT CARDS (Dibuat Pendek & Ramping) --- */
    .card-stat {
        border: none;
        border-radius: 16px;
        padding: 18px 22px;
        height: 100px; /* Tinggi dikunci agar proporsional */
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.02);
    }
    
    /* Warna Pastel Sesuai Gambar */
    .bg-soft-blue { background-color: #a3d9ff; }
    .bg-soft-cyan { background-color: #c0e5f0; }
    .bg-soft-green { background-color: #b2f5b5; }
    .bg-soft-red { background-color: #ff9b9b; }

    .stat-label { 
        font-size: 11px; 
        font-weight: 800; 
        text-transform: uppercase; 
        color: #2b3674; 
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }
    .stat-value { 
        font-size: 28px; 
        font-weight: 800; 
        color: #2b3674; 
        margin: 0; 
        line-height: 1;
    }

    /* --- MAIN CONTENT CARDS --- */
    .card-white {
        background: #ffffff;
        border-radius: 20px;
        border: none;
        padding: 24px;
        height: 100%;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.03);
    }

    .card-title-main { font-size: 16px; font-weight: 800; color: #2b3674; margin-bottom: 2px; }
    .card-subtitle-main { font-size: 12px; color: #a3aed0; font-weight: 500; margin-bottom: 20px; }

    /* Dropdown Tahun Style */
    .select-custom {
        background: #f4f7fe;
        border: none;
        font-size: 12px;
        font-weight: 600;
        color: #2b3674;
        padding: 6px 12px;
        border-radius: 10px;
        cursor: pointer;
    }

    /* --- LIST BUKU TERPOPULER --- */
    .book-list-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f4f9;
    }
    .book-list-item:last-child { border-bottom: none; }

    .rank-indicator {
        width: 28px;
        height: 28px;
        background: #111111;
        color: #ffffff;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .book-details { flex-grow: 1; }
    .book-title-text { font-weight: 700; color: #2b3674; font-size: 13px; margin: 0; display: block; }
    .book-author-text { color: #a3aed0; font-size: 11px; }

    .progress-box { width: 100px; margin: 0 20px; }
    .progress-bar-bg { height: 7px; border-radius: 10px; background: #eeeeee; overflow: hidden; }
    .progress-bar-fill { background: #4318ff; height: 100%; border-radius: 10px; }

    .loan-total-text { color: #a3aed0; font-size: 11px; font-weight: 600; text-align: right; min-width: 90px; }
    .card-stat {
        border: none;
        border-radius: 16px;
        padding: 15px 20px;
        height: 85px; /* Tinggi dikunci agar pendek */
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.02);
        min-width: 0; /* Mencegah overflow */
    }

    .stat-label { 
        font-size: 10px; /* Ukuran font label diperkecil */
        font-weight: 800; 
        text-transform: uppercase; 
        color: #2b3674; 
        margin-bottom: 2px;
        opacity: 0.9;
    }

    .stat-value { 
        font-size: 22px; /* Ukuran angka disesuaikan agar pas */
        font-weight: 800; 
        color: #2b3674; 
        margin: 0; 
    }
</style>

<div class="super-wrapper">
    <div class="dashboard-header">
        <h2>Dashboard</h2>
        <p>Selamat Datang, Kepala Perpustakaan</p>
    </div>

    <div class="row mb-4" style="display: flex; flex-wrap: nowrap; gap: 15px; margin: 0;">
        <div style="flex: 1;">
            <div class="card-stat bg-soft-blue">
                <span class="stat-label">Total Anggota</span>
                <h3 class="stat-value">{{ $stats['totalAnggota'] }}</h3>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card-stat bg-soft-cyan">
                <span class="stat-label">Total Buku</span>
                <h3 class="stat-value">{{ number_format($stats['totalBuku']) }}</h3>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card-stat bg-soft-green">
                <span class="stat-label">Total Peminjaman</span>
                <h3 class="stat-value">{{ $stats['totalPinjam'] }}</h3>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card-stat bg-soft-red">
                <span class="stat-label">Total Denda</span>
                <h3 class="stat-value">RP 120.000</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title-main">Statistik Peminjaman Buku</h5>
                        <p class="card-subtitle-main">Tren peminjaman buku per bulan</p>
                    </div>
                    <select class="select-custom">
                        <option>Pilih Tahun: 2024</option>
                    </select>
                </div>
                <div style="height: 280px;">
                    <canvas id="loanChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card-white">
                <h5 class="card-title-main">Buku paling sering dipinjam</h5>
                <p class="card-subtitle-main">Daftar buku dengan jumlah peminjaman tertinggi</p>
                
                @foreach($anggotaAktif->take(3) as $index => $agt)
                <div class="book-list-item">
                    <div class="rank-indicator">{{ $index + 1 }}</div>
                    <div class="book-details">
                        <span class="book-title-text">{{ Str::limit($agt->nama_anggota, 25) }}</span>
                        <span class="book-author-text">Petugas Sistem</span>
                    </div>
                    <div class="progress-box">
                        <div class="progress-bar-bg">
                            @php 
                                // Kalkulasi persentase sederhana untuk visual bar
                                $percent = 100 - ($index * 20); 
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $percent }}%;"></div>
                        </div>
                    </div>
                    <div class="loan-total-text">{{ $agt->total }} Peminjaman</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('loanChart').getContext('2d');
        
        // Efek Gradient persis gambar
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(67, 24, 255, 0.2)');
        gradient.addColorStop(1, 'rgba(67, 24, 255, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [{
                    data: @json($dataGrafik),
                    borderColor: '#4318ff',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4, // Membuat garis melengkung lembut
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4318ff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f4f9', drawBorder: false },
                        ticks: { color: '#a3aed0', font: { size: 11 } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#a3aed0', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endsection