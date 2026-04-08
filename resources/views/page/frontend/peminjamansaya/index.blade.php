@extends('layouts.frontend.app')

@section('content')
<style>
/* CONTENT */
.container-custom {
    padding: 50px;
    min-height: 90vh;
    background-color:white;
}

/* SEARCH */
.search-box {
    margin: 20px 0;
    display: flex;
    gap: 15px;
}

.search-box input {
    width: 350px;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #ddd; /* Tambahan sedikit border agar terlihat jika bg putih */
}

.search-box button {
    background: #4a8fcf;
    border: none;
    color: white;
    padding: 10px 25px;
    border-radius: 8px;
    cursor: pointer;
}

/* Perbaikan Pagination agar tidak muncul gambar besar */
.table-box svg {
    width: 20px; /* Membatasi ukuran icon panah */
    height: 20px;
}

.table-box nav div:first-child {
    display: none; /* Menyembunyikan teks "Showing X to Y" versi mobile yang berantakan */
}

.table-box nav div:last-child {
    display: flex;
    justify-content: center;
    gap: 5px;
}

/* Opsional: Mempercantik tampilan link angka pagination */
.table-box nav a, .table-box nav span {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #1f5f99;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
    color: black;
}

.table-custom thead {
    background: #1f5f99;
    color: white;
}

.table-custom th, .table-custom td {
    padding: 14px;
    text-align: left;
}

.table-custom tbody tr {
    border-bottom: 1px solid #ddd;
}

.penulis {
    color: #777;
    font-size: 14px;
}

/* BADGE STATUS */
.badge-custom {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: bold;
    display: inline-block;
}

.dipinjam {
    background: #a6c9e2;
    color: #114e7d;
}

.menunggu_verifikasi {
    background: #fcf3cf;
    color: #856404;
}

.terlambat {
    background: #f3a2a2;
    color: #a00000;
}

.selesai {
    background: #9dd89d;
    color: #0c6b0c;
}

.ditolak {
    background: #f5b7b1;
    color: #943126;
}

/* BUTTON */
.btn-kembali {
    background: #1f5f99;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn-detail-custom {
    background: #ecf0f1;
    color: #2c3e50;
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    display: inline-block;
    margin-right: 5px;
}
</style>

<div class="overlay">
    <div class="container-custom">
        <h2>Peminjaman Saya</h2>

        <form action="{{ route('peminjamansaya.index') }}" method="GET" class="search-box">
            <input type="text" name="search" placeholder="Cari judul buku...." value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </form>

        <div class="table-box">
            @if($peminjamans->count())
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamans as $index => $p)
                    <tr>
                        <td>{{ $peminjamans->firstItem() + $index }}</td>
                        <td>
                            <b>{{ $p->buku->judul ?? '-' }}</b>
                            <br>
                            <span class="penulis">Peminjam: {{ $p->nama_anggota }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}</td>
                        <td>
                            <span class="badge-custom {{ $p->status }}">
                                {{ ucfirst(str_replace('_',' ',$p->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('peminjamansaya.show',$p->id) }}" class="btn-detail-custom">
                                Detail
                            </a>

                            @if($p->status == 'dipinjam')
                            <form id="form-{{ $p->id }}" action="{{ route('peminjamansaya.ajukan', $p->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="button" class="btn-kembali" 
                                    onclick="konfirmasi(event, {{ $p->id }}, '{{ $p->tgl_kembali }}', '{{ $p->buku->judul }}', '{{ $p->tgl_pinjam }}')">
                                    Ajukan Pengembalian
                                </button>
                            </form>
                            @elseif($p->status == 'selesai')
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $peminjamans->links() }}
            </div>
            @else
            <div style="text-align: center; padding: 40px; color: #95a5a6;">
                <p>Tidak ada riwayat peminjaman.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasi(event, id, tglKembali, judul, tglPinjam) {
        // 1. Logika Perhitungan Keterlambatan
        const hariIni = new Date();
        hariIni.setHours(0, 0, 0, 0); // Reset waktu ke jam 00:00
        
        const deadline = new Date(tglKembali);
        deadline.setHours(0, 0, 0, 0);

        let statusText = '<span style="color: #2ecc71;">Tepat Waktu</span>';
        let dendaHtml = '';
        
        // Cek jika terlambat
        if (hariIni > deadline) {
            const selisihWaktu = hariIni.getTime() - deadline.getTime();
            const selisihHari = Math.ceil(selisihWaktu / (1000 * 3600 * 24));
            const dendaPerHari = 1000; // Sesuaikan nominal denda Anda
            const totalDenda = selisihHari * dendaPerHari;

            statusText = `<span style="color: #ff4d4d;">Terlambat ${selisihHari} hari</span>`;
            dendaHtml = `<p style="margin: 5px 0;">Estimasi Denda : Rp ${totalDenda.toLocaleString('id-ID')}</p>`;
        }

        // 2. Tampilkan SweetAlert2 dengan Custom UI
        Swal.fire({
            title: 'Ajukan Pengembalian',
            html: `
                <div style="text-align: left; font-size: 14px; line-height: 1.6;">
                    <p style="margin: 5px 0;">Judul Buku : ${judul}</p>
                    <p style="margin: 5px 0;">Tanggal Pinjam : ${formatDate(tglPinjam)}</p>
                    <p style="margin: 5px 0;">Tanggal Kembali : ${formatDate(tglKembali)}</p>
                    <p style="margin: 5px 0;">Status : ${statusText}</p>
                    ${dendaHtml}
                </div>
            `,
            background: '#1f5f99', // Warna biru sesuai gambar
            color: '#ffffff',      // Warna teks putih
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Ajukan',
            cancelButtonText: 'Batal',
            reverseButtons: false,
            width: '400px',
            customClass: {
                title: 'text-white border-0',
                popup: 'rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-' + id).submit();
            }
        });
    }

    // Fungsi pembantu untuk memformat tanggal (YYYY-MM-DD ke DD-MM-YYYY)
    function formatDate(dateString) {
        const d = new Date(dateString);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        return `${day} - ${month} - ${year}`;
    }
</script>
@endsection