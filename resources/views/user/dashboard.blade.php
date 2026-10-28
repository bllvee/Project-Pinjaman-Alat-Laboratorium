@extends('user.layouts.app')

@section('title', 'Beranda Mahasiswa')

@section('content')
<div class="user-dashboard-page">

    <!-- Hero Welcome Banner -->
    <div class="sipinjam-card mb-4 p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="user-avatar-circle user-avatar-lg">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h1 class="page-title mb-0 fs-4">Selamat Datang, {{ $user->name }}!</h1>
                        <span class="user-role-badge">Mahasiswa</span>
                    </div>
                    <p class="text-secondary small mb-0 mt-1">
                        NIM: <strong class="text-dark">{{ $user->nim ?? $user->username }}</strong> &bull; 
                        Prodi: <strong class="text-dark">{{ $user->prodi ?? 'Teknologi Informasi' }}</strong> &bull; 
                        No. HP: <strong class="text-dark">{{ $user->no_hp ?? '-' }}</strong>
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('user.barang.index') }}" class="btn-sipinjam-outline">
                    <i class="bi bi-box-seam"></i>
                    <span>Katalog Alat</span>
                </a>
                <a href="{{ route('user.peminjaman.create') }}" class="btn-sipinjam-primary">
                    <i class="bi bi-plus-lg"></i>
                    <span>Ajukan Pinjam</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3 Kartu Statistik Mahasiswa -->
    <div class="row g-3 mb-4">
        <!-- 1. Sedang Dipinjam -->
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-teal">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">SEDANG DIPINJAM</span>
                    <span class="stat-value">{{ $sedangDipinjam }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Peminjaman Selesai -->
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-green">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">SELESAI DIKEMBALIKAN</span>
                    <span class="stat-value">{{ $peminjamanSelesai }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Total Denda -->
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-red">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL DENDA ANDA</span>
                    <span class="stat-value stat-value-currency">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alat yang Sedang Dipinjam Aktif -->
    @if ($peminjamanAktif->count() > 0)
        <div class="sipinjam-card mb-4">
            <div class="card-title-header mb-3">
                <h3 class="fs-6 fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-clock-history text-teal fs-5"></i>
                    <span>Alat yang Sedang Anda Pinjam (Wajib Dikembalikan)</span>
                </h3>
            </div>

            <div class="row g-3">
                @foreach ($peminjamanAktif as $item)
                    @php
                        $tglRencana = \Carbon\Carbon::parse($item->tanggal_rencana_kembali)->startOfDay();
                        $hariIni = \Carbon\Carbon::now()->startOfDay();
                        $isTerlambat = $hariIni->gt($tglRencana);
                        $selisihHari = $hariIni->diffInDays($tglRencana);
                    @endphp
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon-box stat-icon-teal stat-icon-sm">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div>
                                    <h4 class="fs-6 fw-bold text-dark mb-0">{{ $item->barang->nama_barang ?? 'Barang Lab' }}</h4>
                                    <span class="text-secondary small">ID: {{ $item->barang->kode_barang ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-secondary small">Batas Tenggat:</span>
                                <strong class="{{ $isTerlambat ? 'text-danger' : 'text-teal' }}">
                                    {{ $tglRencana->translatedFormat('d M Y') }}
                                </strong>
                                @if ($isTerlambat)
                                    <span class="status-pill status-terlambat d-block mt-1">Terlambat {{ $selisihHari }} hari</span>
                                @else
                                    <span class="status-pill status-dipinjam d-block mt-1">Sisa {{ $selisihHari }} hari</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Tabel Riwayat Peminjaman Terbaru -->
    <div class="sipinjam-table-card">
        <div class="card-header-bar">
            <div>
                <h3 class="mb-0 fs-5 fw-bold text-dark">Riwayat Peminjaman Terakhir</h3>
                <span class="text-secondary small">Pantau status transaksi instrumen yang pernah Anda gunakan</span>
            </div>
            <a href="{{ route('user.peminjaman.index') }}" class="text-teal text-decoration-none fw-semibold small d-inline-flex align-items-center gap-1">
                <span>Lihat Semua Riwayat</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-sipinjam">
                <thead>
                    <tr>
                        <th style="width: 35%;">BARANG LAB</th>
                        <th style="width: 20%;">TGL PINJAM</th>
                        <th style="width: 20%;">ESTIMASI KEMBALI</th>
                        <th style="width: 15%;">STATUS</th>
                        <th class="text-end" style="width: 10%;">DENDA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayatTerbaru as $p)
                        <tr>
                            <!-- Barang -->
                            <td>
                                <div class="item-td-cell">
                                    <span class="item-name">{{ $p->barang->nama_barang ?? 'Barang Lab' }}</span>
                                    <span class="item-code">{{ $p->barang->kode_barang ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Tanggal Pinjam -->
                            <td>
                                <span class="text-dark fw-medium">
                                    {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d M Y') }}
                                </span>
                            </td>

                            <!-- Estimasi Kembali -->
                            <td>
                                <span class="text-dark">
                                    {{ \Carbon\Carbon::parse($p->tanggal_rencana_kembali)->translatedFormat('d M Y') }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                @if ($p->status === 'dipinjam')
                                    <span class="status-pill status-dipinjam">Dipinjam</span>
                                @elseif ($p->status === 'dikembalikan')
                                    <span class="status-pill status-dikembalikan">Dikembalikan</span>
                                @else
                                    <span class="status-pill status-terlambat">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>

                            <!-- Denda -->
                            <td class="text-end">
                                @if ($p->total_denda > 0)
                                    <strong class="text-danger">Rp {{ number_format($p->total_denda, 0, ',', '.') }}</strong>
                                @else
                                    <span class="text-muted small">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                <span class="fw-medium">Anda belum memiliki riwayat peminjaman.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
