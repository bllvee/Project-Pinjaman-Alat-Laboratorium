@extends('user.layouts.app')

@section('title', 'Riwayat Peminjaman Pribadi')

@section('content')
<div class="user-peminjaman-page">

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">Riwayat Peminjaman Saya</h1>
            <p class="page-subtitle">Rincian seluruh transaksi peminjaman alat beserta kalkulasi denda jika ada.</p>
        </div>
        <a href="{{ route('user.peminjaman.create') }}" class="btn-sipinjam-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Pinjam Alat Baru</span>
        </a>
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

        <!-- 3. Selesai Dikembalikan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-green">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">SELESAI DIKEMBALIKAN</span>
                    <span class="stat-value">{{ $rekap['dikembalikan'] }}</span>
                </div>
            </div>
        </div>

        <!-- 4. Total Denda -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-red">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL DENDA DITAGIH</span>
                    <span class="stat-value stat-value-currency">Rp {{ number_format($rekap['total_denda'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Status Tab -->
    <div class="sipinjam-card mb-4 p-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span class="fw-semibold text-dark small">Filter Riwayat Peminjaman:</span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('user.peminjaman.index', ['status' => 'semua']) }}"
                   class="btn-sipinjam-{{ request('status', 'semua') === 'semua' ? 'primary' : 'outline' }}" style="height: 34px; padding: 0 14px; font-size: 13px;">
                    Semua
                </a>
                <a href="{{ route('user.peminjaman.index', ['status' => 'dipinjam']) }}"
                   class="btn-sipinjam-{{ request('status') === 'dipinjam' ? 'primary' : 'outline' }}" style="height: 34px; padding: 0 14px; font-size: 13px;">
                    Sedang Dipinjam
                </a>
                <a href="{{ route('user.peminjaman.index', ['status' => 'dikembalikan']) }}"
                   class="btn-sipinjam-{{ request('status') === 'dikembalikan' ? 'primary' : 'outline' }}" style="height: 34px; padding: 0 14px; font-size: 13px;">
                    Sudah Dikembalikan
                </a>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="sipinjam-table-card">
        <div class="card-header-bar">
            <div>
                <h3 class="mb-0 fs-5 fw-bold text-dark">Daftar Riwayat Peminjaman</h3>
                <span class="text-secondary small">Total {{ $peminjamans->total() }} entri transaksi</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-sipinjam">
                <thead>
                    <tr>
                        <th style="width: 5%;">NO</th>
                        <th style="width: 28%;">BARANG & IDENTITAS</th>
                        <th style="width: 16%;">TGL PINJAM</th>
                        <th style="width: 16%;">EST. KEMBALI</th>
                        <th style="width: 15%;">KEMBALI AKTUAL</th>
                        <th style="width: 10%;">STATUS</th>
                        <th class="text-end" style="width: 10%;">DENDA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamans as $index => $r)
                        <tr>
                            <!-- No -->
                            <td>
                                <span class="text-secondary fw-semibold small">
                                    #{{ sprintf('%02d', $peminjamans->firstItem() + $index) }}
                                </span>
                            </td>

                            <!-- Barang & Identitas -->
                            <td>
                                <div class="item-td-cell">
                                    <span class="item-name">{{ $r->barang->nama_barang ?? 'Barang Lab' }}</span>
                                    <span class="item-code">
                                        {{ $r->barang->kode_barang ?? '-' }}
                                        @if(isset($r->barang->kategori))
                                            &bull; {{ $r->barang->kategori->nama_kategori }}
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Tanggal Pinjam -->
                            <td>
                                <span class="text-dark fw-medium">
                                    {{ \Carbon\Carbon::parse($r->tanggal_pinjam)->translatedFormat('d M Y') }}
                                </span>
                            </td>

                            <!-- Estimasi Kembali -->
                            <td>
                                <span class="text-dark">
                                    {{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->translatedFormat('d M Y') }}
                                </span>
                            </td>

                            <!-- Kembali Aktual & Kondisi -->
                            <td>
                                @if ($r->tanggal_kembali_aktual)
                                    <span class="d-block text-dark fw-medium">
                                        {{ \Carbon\Carbon::parse($r->tanggal_kembali_aktual)->translatedFormat('d M Y') }}
                                    </span>
                                    @if ($r->kondisi_saat_kembali === 'rusak')
                                        <span class="status-pill status-rusak d-inline-flex mt-1">Rusak</span>
                                    @elseif ($r->kondisi_saat_kembali)
                                        <span class="status-pill status-baik d-inline-flex mt-1">Baik</span>
                                    @endif
                                @else
                                    <span class="text-secondary small">Belum dikembalikan</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                @if ($r->status === 'dipinjam')
                                    <span class="status-pill status-dipinjam">Dipinjam</span>
                                @elseif ($r->status === 'dikembalikan')
                                    <span class="status-pill status-dikembalikan">Dikembalikan</span>
                                @else
                                    <span class="status-pill status-terlambat">{{ ucfirst($r->status) }}</span>
                                @endif
                            </td>

                            <!-- Denda -->
                            <td class="text-end">
                                @if ($r->total_denda > 0)
                                    <span class="fw-bold text-danger">Rp {{ number_format($r->total_denda, 0, ',', '.') }}</span>
                                    <span class="d-block text-muted small">
                                        Telat: Rp {{ number_format($r->denda_keterlambatan, 0, ',', '.') }}<br>
                                        Rusak: Rp {{ number_format($r->biaya_perbaikan, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-secondary small">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                <span class="fw-medium">Belum ada riwayat peminjaman pada filter ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($peminjamans->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-secondary small">
                    Menampilkan <strong class="text-dark">{{ $peminjamans->firstItem() }}</strong> - <strong class="text-dark">{{ $peminjamans->lastItem() }}</strong> dari <strong class="text-dark">{{ $peminjamans->total() }}</strong> total data
                </span>
                <div class="d-flex justify-content-center">
                    {{ $peminjamans->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
