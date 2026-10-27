@extends('admin.layouts.app')

@section('title', 'Dashboard Ringkasan')

@section('content')
<div class="dashboard-page">

    <!-- Page Header & Quick Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">Dashboard Ringkasan</h1>
            <p class="page-subtitle">Pantau statistik operasional laboratorium hari ini.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.kategori.index') }}" class="btn-sipinjam-outline">
                <i class="bi bi-tags"></i>
                <span>Kelola Kategori</span>
            </a>
            <a href="{{ route('admin.barang.create') }}" class="btn-sipinjam-primary">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Barang</span>
            </a>
        </div>
    </div>

    <!-- 4 Kartu Statistik Telemetri -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Barang -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-blue">
                    <i class="bi bi-inbox-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL BARANG</span>
                    <span class="stat-value">{{ number_format($totalBarang, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Peminjaman Aktif -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-teal">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">PEMINJAMAN AKTIF</span>
                    <span class="stat-value">{{ number_format($sedangDipinjam, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Terlambat Kembali -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-red">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TERLAMBAT KEMBALI</span>
                    <span class="stat-value">
                        {{ str_pad($transaksiTerbaru->where('hari_terlambat', '>', 0)->count(), 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 4. Total Mahasiswa -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-yellow">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL MAHASISWA</span>
                    <span class="stat-value">{{ number_format($totalMahasiswa, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Kartu Akumulasi Kas & Aturan Denda UTS -->
    <div class="row g-3 mb-4">
        <!-- Kartu 1: Total Akumulasi Denda -->
        <div class="col-12 col-lg-6">
            <div class="accumulation-card h-100">
                <div>
                    <div class="accumulation-header">
                        <div class="accumulation-title">
                            <i class="bi bi-cash-stack"></i>
                            <span>Total Akumulasi Denda</span>
                        </div>
                        <span class="accumulation-pill">
                            {{ strtoupper(\Carbon\Carbon::now()->translatedFormat('F Y')) }}
                        </span>
                    </div>

                    <div class="accumulation-value">
                        <span class="accumulation-rp">Rp</span>
                        <span class="accumulation-number">{{ number_format($totalDenda, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <div class="accumulation-tag accumulation-tag-green mb-2">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Sesuai Aturan UTS-1: Keterlambatan > 3 hari (Rp 2.000/hari)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-secondary small pt-1 border-top">
                        <span>Total Denda Keterlambatan:</span>
                        <strong class="text-dark">Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Biaya Perbaikan Alat -->
        <div class="col-12 col-lg-6">
            <div class="accumulation-card h-100">
                <div>
                    <div class="accumulation-header">
                        <div class="accumulation-title">
                            <i class="bi bi-tools"></i>
                            <span>Biaya Perbaikan Alat</span>
                        </div>
                        <span class="accumulation-pill">
                            {{ strtoupper(\Carbon\Carbon::now()->translatedFormat('F Y')) }}
                        </span>
                    </div>

                    <div class="accumulation-value">
                        <span class="accumulation-rp">Rp</span>
                        <span class="accumulation-number">{{ number_format($biayaPerbaikan, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <div class="accumulation-tag accumulation-tag-warning mb-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>Sesuai Aturan UTS-2: Alat kondisi rusak (Rp 10.000/unit)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-secondary small pt-1 border-top">
                        <span>Total Klaim Biaya Kerusakan:</span>
                        <strong class="text-dark">Rp {{ number_format($biayaPerbaikan, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Peminjaman Terbaru -->
    <div class="sipinjam-table-card">
        <div class="card-header-bar">
            <div>
                <h3 class="mb-0 fs-5 fw-bold text-dark">Peminjaman Terbaru</h3>
                <span class="text-secondary small">Log transaksi sirkulasi perangkat laboratorium terkini</span>
            </div>
            <a href="{{ route('admin.peminjaman.index') }}" class="text-teal text-decoration-none fw-semibold small d-inline-flex align-items-center gap-1">
                <span>Lihat Semua</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-sipinjam">
                <thead>
                    <tr>
                        <th style="width: 28%;">MAHASISWA</th>
                        <th style="width: 26%;">BARANG</th>
                        <th style="width: 16%;">TGL PINJAM</th>
                        <th style="width: 16%;">TGL KEMBALI</th>
                        <th style="width: 14%;">STATUS</th>
                        <th class="text-end" style="width: 10%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiTerbaru as $p)
                        <tr>
                            <!-- Kolom Mahasiswa -->
                            <td>
                                <div class="user-td-cell">
                                    <div class="avatar">
                                        {{ strtoupper(substr($p->user->name ?? 'M', 0, 2)) }}
                                    </div>
                                    <div class="details">
                                        <span class="name">{{ $p->user->name ?? 'Mahasiswa' }}</span>
                                        <span class="nim">{{ $p->user->nim ?? ($p->user->username ?? '-') }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Barang -->
                            <td>
                                <div class="item-td-cell">
                                    <span class="item-name">{{ $p->barang->nama_barang ?? 'Barang Lab' }}</span>
                                    <span class="item-code">
                                        {{ $p->barang->kode_barang ?? '-' }}
                                        @if(isset($p->barang->kategori))
                                            &bull; {{ $p->barang->kategori->nama_kategori }}
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Kolom Tanggal Pinjam -->
                            <td>
                                <span class="text-dark fw-medium">
                                    {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d M Y') }}
                                </span>
                            </td>

                            <!-- Kolom Tanggal Kembali -->
                            <td>
                                <div>
                                    <span class="{{ $p->hari_terlambat > 0 ? 'text-danger fw-semibold' : 'text-dark' }}">
                                        {{ \Carbon\Carbon::parse($p->tanggal_rencana_kembali)->translatedFormat('d M Y') }}
                                    </span>
                                    @if ($p->hari_terlambat > 0)
                                        <span class="d-block text-danger small fw-medium">
                                            +{{ $p->hari_terlambat }} hari
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kolom Status -->
                            <td>
                                @if ($p->status === 'dipinjam')
                                    <span class="status-pill status-dipinjam">
                                        Dipinjam
                                    </span>
                                @elseif ($p->status === 'dikembalikan')
                                    <span class="status-pill status-dikembalikan">
                                        Kembali
                                    </span>
                                @elseif ($p->status === 'terlambat')
                                    <span class="status-pill status-terlambat">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="status-pill status-menunggu">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="text-end">
                                <a href="{{ route('admin.peminjaman.detail', $p->id) }}" 
                                   class="btn-sipinjam-icon" 
                                   title="Lihat Detail Transaksi">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                <span class="fw-medium">Belum ada transaksi peminjaman terbaru.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
