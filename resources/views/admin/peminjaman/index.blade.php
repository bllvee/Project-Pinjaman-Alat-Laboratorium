@extends('admin.layouts.app')

@section('title', 'Transaksi Peminjaman & Pengembalian')

@section('content')
<div class="transaksi-index-page">

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">Transaksi Peminjaman & Pengembalian</h1>
            <p class="page-subtitle">Kelola sirkulasi alat, proses verifikasi pengembalian, dan hitung denda otomatis.</p>
        </div>
    </div>

    <!-- 4 Kartu Rekap Telemetri -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Transaksi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-blue">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL TRANSAKSI</span>
                    <span class="stat-value">{{ $rekap['total'] }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Sedang Dipinjam -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-teal">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">SEDANG DIPINJAM</span>
                    <span class="stat-value">{{ $rekap['dipinjam'] }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Sudah Dikembalikan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box" style="background-color: var(--status-success-bg); color: var(--status-success-text);">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">DIKEMBALIKAN</span>
                    <span class="stat-value">{{ $rekap['dikembalikan'] }}</span>
                </div>
            </div>
        </div>

        <!-- 4. Total Denda Masuk -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-red">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL DENDA</span>
                    <span class="stat-value" style="font-size: 22px;">Rp {{ number_format($rekap['total_denda'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="sipinjam-card mb-4 p-3">
        <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="row g-2 align-items-center">
            <!-- Search -->
            <div class="col-12 col-md-5">
                <div class="input-icon-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                           name="search" 
                           class="form-control-custom" 
                           placeholder="Cari nama mahasiswa, NIM, atau kode alat..." 
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filter Status -->
            <div class="col-12 col-md-4">
                <select name="status" class="form-control-custom" onchange="this.form.submit()">
                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn-sipinjam-primary w-100">
                    <i class="bi bi-filter"></i>
                    <span>Terapkan</span>
                </button>
                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn-sipinjam-outline" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Transaksi Peminjaman -->
    <div class="sipinjam-table-card">
        <div class="card-header-bar">
            <div>
                <h3 class="mb-0 fs-5 fw-bold text-dark">Data Sirkulasi Transaksi</h3>
                <span class="text-secondary small">Total {{ $peminjamans->total() }} entri log peminjaman</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-sipinjam">
                <thead>
                    <tr>
                        <th style="width: 26%;">MAHASISWA</th>
                        <th style="width: 24%;">BARANG</th>
                        <th style="width: 14%;">TGL PINJAM</th>
                        <th style="width: 14%;">TGL KEMBALI</th>
                        <th style="width: 12%;">STATUS</th>
                        <th style="width: 12%;">TOTAL DENDA</th>
                        <th class="text-end" style="width: 8%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamans as $p)
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
                                @else
                                    <span class="status-pill status-terlambat">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Total Denda -->
                            <td>
                                @if ($p->total_denda > 0)
                                    <span class="fw-bold text-danger">
                                        Rp {{ number_format($p->total_denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-secondary small">Rp 0</span>
                                @endif
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="text-end">
                                <a href="{{ route('admin.peminjaman.detail', $p->id) }}" 
                                   class="btn-sipinjam-icon" 
                                   title="Lihat Detail & Proses Pengembalian">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                <span class="fw-medium">Belum ada transaksi peminjaman yang sesuai filter.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($peminjamans->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
