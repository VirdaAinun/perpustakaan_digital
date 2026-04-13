@extends('layouts.backend.admin.app')

@section('content')
<style>
    /* Layout Utama */
    .dashboard-page { background: #f4f7fe; padding: 25px; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; }
    .header-section { margin-bottom: 30px; }
     .page-title { font-weight: 900; color: #005fa8; font-size: 30px; margin: 0;}
    .header-section p { color: #000000; font-size: 14px; }

    /* Info Cards - Perbaikan bayangan & Radius */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .info-card { background: white; border-radius: 20px; padding: 20px; display: flex; align-items: center; border: none; box-shadow: 14px 17px 40px 4px rgba(112, 144, 176, 0.08); }
    .card-blue { background-color: #7dd3fc !important; }   /* Biru */
    .card-yellow { background-color: #fde68a !important; } /* Kuning/Orange Muda */
    .card-green { background-color: #bbf7d0 !important; }  /* Hijau */
    .card-red { background-color: #fecaca !important; }    /* Merah Muda */

    .icon-box { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 20px; }
    .icon-blue { background: #c4d9f0; color: #4318ff; }
    .icon-yellow { background: #fff9e6; color: #ffb800; }
    .icon-green { background: #e6faf5; color: #05cd99; }
    .icon-red { background: #eda3ae; color: #f58e84; }
    
    .stat-label { color: #000000; font-size: 14px; margin-bottom: 2px; }
    .stat-value { color: #2b3674; font-size: 24px; font-weight: 700; margin: 0; }

    /* Content Grid 2 Kolom */
    .content-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 25px; align-items: start; }
    .white-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 14px 17px 40px 4px rgba(112, 144, 176, 0.08); }
    
    /* Progress Bar */
    .progress-item { margin-bottom: 20px; }
    .progress-info { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .progress-bg { height: 12px; background: #eff4fb; border-radius: 10px; overflow: hidden; }
    .progress-fill { background: #4318ff; height: 100%; border-radius: 10px; transition: width 1s ease-in-out; }

    /* Recent Box (Biru Muda) */
    .recent-activity-card { background: #e9f3ff; border-radius: 20px; padding: 25px; margin-top: 25px; }
    .activity-item { background: white; border-radius: 15px; padding: 15px; margin-bottom: 12px; border-left: 5px solid #4318ff; }
</style>

<div class="dashboard-page">
    <div class="header-section">
        <h5 class="page-title">Dashboard</h5>
        <p>Selamat Datang, Petugas</p>
    </div>

    <div class="stat-grid">
        <div class="info-card card-blue">
            <div class="icon-box icon-blue">📚</div>
            <div>
                <p class="stat-label">Total Buku</p>
                <h4 class="stat-value">{{ $totalBuku }}</h4>
            </div>
        </div>
        <div class="info-card card-yellow">
            <div class="icon-box icon-yellow">📥</div>
            <div>
                <p class="stat-label">Buku Dipinjam</p>
                <h4 class="stat-value">{{ $dipinjam }}</h4>
            </div>
        </div>
        <div class="info-card card-green">
            <div class="icon-box icon-green">👥</div>
            <div>
                <p class="stat-label">Anggota Aktif</p>
                <h4 class="stat-value">{{ $anggota }}</h4>
            </div>
        </div>
        <div class="info-card card-red">
            <div class="icon-box icon-red">⚠️</div>
            <div>
                <p class="stat-label">Terlambat</p>
                <h4 class="stat-value">{{ $terlambat }}</h4>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <div class="white-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold text-dark">Statistik Peminjaman Buku</h5>
                    <p class="text-muted small">Tren peminjaman buku per bulan</p>
                </div>
                <select class="form-select form-select-sm w-auto border-0 bg-light rounded-pill">
                    <option>Tahun {{ date('Y') }}</option>
                </select>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="loanChart"></canvas>
            </div>
        </div>

        <div class="white-card">
            <h5 class="fw-bold text-dark mb-1">Daftar Buku paling sering dipinjam</h5>
            
            @foreach($bukuPopuler as $index => $buku)
            <div class="progress-item">
                <div class="progress-info">
                    <span class="fw-bold small text-dark">{{ $index + 1 }}. {{ $buku->judul }}</span>
                    <span class="small text-muted">{{ $buku->peminjaman_count }} Pinjam</span>
                </div>
                <div class="progress-bg">
                    @php 
                        $max = $bukuPopuler->max('peminjaman_count') ?: 1;
                        $width = ($buku->peminjaman_count / $max) * 100;
                    @endphp
                    <div class="progress-fill" style="width: {{ $width }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="recent-activity-card">
        <h5 class="fw-bold text-dark mb-4">Peminjaman Terbaru</h5>
        <div class="row">
            @foreach($peminjamanTerbaru ?? [] as $recent)
            <div class="col-md-4">
                <div class="activity-item shadow-sm">
                    <div class="fw-bold text-primary small">{{ $recent->nama_anggota }}</div>
                    <div class="text-dark small my-1">{{ $recent->buku->judul ?? 'Buku dihapus' }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="text-muted" style="font-size: 11px;">{{ $recent->created_at->format('d M Y') }}</span>
                        <span class="badge {{ $recent->status == 'selesai' ? 'bg-success' : 'bg-primary' }} rounded-pill" style="font-size: 10px;">
                            {{ strtoupper($recent->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('loanChart').getContext('2d');
        
        // Data dari Controller
        const chartData = {!! json_encode($dataGrafik ?? array_fill(0, 12, 0)) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Peminjaman',
                    data: chartData,
                    borderColor: '#4318ff',
                    borderWidth: 4,
                    backgroundColor: 'rgba(67, 24, 255, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#4318ff',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: '#f0f0f0', borderDash: [5, 5] },
                        ticks: { color: '#a3aed0', font: { size: 12 } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#a3aed0', font: { size: 12 } }
                    }
                }
            }
        });
    });
</script>
@endsection