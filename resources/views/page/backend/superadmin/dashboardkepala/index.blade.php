@extends('layouts.backend.superadmin.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .super-wrapper {
        padding: 30px;
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .dashboard-header { margin-bottom: 28px; }
    .dashboard-header h2 { color: #2b3674; font-weight: 800; font-size: 24px; margin-bottom: 4px; }
    .dashboard-header p { color: #a3aed0; font-size: 14px; margin: 0; }

    /* STAT CARDS */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .card-stat {
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .card-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: rgba(255,255,255,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .bg-soft-blue  { background: #a3d9ff; }
    .bg-soft-cyan  { background: #c0e5f0; }
    .bg-soft-green { background: #b2f5b5; }
    .bg-soft-red   { background: #ff9b9b; }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #2b3674;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        opacity: 0.8;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #2b3674;
        margin: 0;
        line-height: 1;
    }

    /* BOTTOM GRID */
    .content-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
    }

    .card-white {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .card-title-main { font-size: 15px; font-weight: 800; color: #2b3674; margin-bottom: 2px; }
    .card-subtitle-main { font-size: 12px; color: #a3aed0; font-weight: 500; margin-bottom: 20px; }

    .select-custom {
        background: #f4f7fe; border: none; font-size: 12px;
        font-weight: 600; color: #2b3674; padding: 6px 12px;
        border-radius: 10px; cursor: pointer;
    }

    /* BUKU POPULER */
    .book-list-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f4f9;
    }
    .book-list-item:last-child { border-bottom: none; }

    .rank-indicator {
        width: 30px; height: 30px;
        background: #2b3674; color: #fff;
        border-radius: 8px; display: flex;
        align-items: center; justify-content: center;
        font-weight: 800; font-size: 13px;
        margin-right: 14px; flex-shrink: 0;
    }

    .book-details { flex-grow: 1; }
    .book-title-text { font-weight: 700; color: #2b3674; font-size: 13px; margin: 0; display: block; }
    .book-author-text { color: #a3aed0; font-size: 11px; }

    .progress-box { width: 90px; margin: 0 16px; }
    .progress-bar-bg { height: 6px; border-radius: 10px; background: #eeeeee; overflow: hidden; }
    .progress-bar-fill { background: #4318ff; height: 100%; border-radius: 10px; }

    .loan-total-text { color: #a3aed0; font-size: 11px; font-weight: 600; text-align: right; min-width: 80px; }
</style>

<div class="super-wrapper">

    <div class="dashboard-header">
        <h2>Dashboard</h2>
        <p>Selamat Datang, Kepala Perpustakaan</p>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-grid">
        <div class="card-stat bg-soft-blue">
            <div class="card-stat-icon">👥</div>
            <div>
                <div class="stat-label">Total Anggota</div>
                <h3 class="stat-value">{{ $stats['totalAnggota'] }}</h3>
            </div>
        </div>
        <div class="card-stat bg-soft-cyan">
            <div class="card-stat-icon">📚</div>
            <div>
                <div class="stat-label">Total Buku</div>
                <h3 class="stat-value">{{ number_format($stats['totalBuku']) }}</h3>
            </div>
        </div>
        <div class="card-stat bg-soft-green">
            <div class="card-stat-icon">📥</div>
            <div>
                <div class="stat-label">Total Peminjaman</div>
                <h3 class="stat-value">{{ $stats['totalPinjam'] }}</h3>
            </div>
        </div>
        <div class="card-stat bg-soft-red">
            <div class="card-stat-icon">💰</div>
            <div>
                <div class="stat-label">Total Denda</div>
                <h3 class="stat-value" style="font-size:18px;">Rp {{ number_format(abs($stats['totalDenda']), 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- GRAFIK & BUKU POPULER --}}
    <div class="content-grid">
        <div class="card-white">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <h5 class="card-title-main">Statistik Peminjaman Buku</h5>
                    <p class="card-subtitle-main">Tren peminjaman buku per bulan</p>
                </div>
                <select class="select-custom">
                    <option>Tahun {{ date('Y') }}</option>
                </select>
            </div>
            <div style="height: 280px;">
                <canvas id="loanChart"></canvas>
            </div>
        </div>

        <div class="card-white">
            <h5 class="card-title-main">Buku Paling Sering Dipinjam</h5>
            <p class="card-subtitle-main">Daftar buku dengan peminjaman tertinggi</p>

            @forelse($bukuPopuler->take(3) as $index => $buku)
            <div class="book-list-item">
                <div class="rank-indicator">{{ $index + 1 }}</div>
                <div class="book-details">
                    <span class="book-title-text">{{ Str::limit($buku->judul, 22) }}</span>
                    <span class="book-author-text">{{ $buku->penulis }}</span>
                </div>
                <div class="progress-box">
                    <div class="progress-bar-bg">
                        @php
                            $maxTotal = $bukuPopuler->first()->total ?? 1;
                            $percent  = round(($buku->total / $maxTotal) * 100);
                        @endphp
                        <div class="progress-bar-fill" style="width: {{ $percent }}%;"></div>
                    </div>
                </div>
                <div class="loan-total-text">{{ $buku->total }}x</div>
            </div>
            @empty
            <p style="color:#a3aed0; font-size:13px; text-align:center; padding:20px 0;">Belum ada data peminjaman.</p>
            @endforelse
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('loanChart').getContext('2d');
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
                    tension: 0.4,
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
                        grid: { color: '#f1f4f9' },
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
