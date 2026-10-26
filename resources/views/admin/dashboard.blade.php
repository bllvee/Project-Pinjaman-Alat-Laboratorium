@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="page-title mb-0"><i class="fa fa-gauge me-2"></i> Dashboard Admin</h3>
    <div>
        <a href="{{ route('admin.barang.create') }}" class="btn btn-sm btn-primary-lab text-white">
            <i class="fa fa-plus me-1"></i> Tambah Barang
        </a>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-tags me-1"></i> Kelola Kategori
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-stat">
            <p>Total Mahasiswa</p>
            <h2>{{ $totalMahasiswa }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Jenis Barang Lab</p>
            <h2>{{ $totalBarang }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Sedang Dipinjam</p>
            <h2 class="text-warning">{{ $sedangDipinjam }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Total Denda Masuk</p>
            <h2 class="text-success">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small text-uppercase fw-semibold d-block">Denda Keterlambatan</span>
                <span class="fs-5 fw-bold text-dark">Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }}</span>
                <div class="text-muted small">Aturan: > 3 hari keterlambatan (Rp2.000/hari)</div>
            </div>
            <i class="fa fa-clock-rotate-left fs-2 text-warning opacity-75"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small text-uppercase fw-semibold d-block">Biaya Perbaikan Alat</span>
                <span class="fs-5 fw-bold text-dark">Rp {{ number_format($biayaPerbaikan, 0, ',', '.') }}</span>
                <div class="text-muted small">Aturan: Alat kondisi rusak (Rp10.000/unit)</div>
            </div>
            <i class="fa fa-screwdriver-wrench fs-2 text-danger opacity-75"></i>
        </div>
    </div>
</div>

<div class="table-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-bold"><i class="fa fa-list me-2"></i> Transaksi Peminjaman Terbaru</h5>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua Transaksi</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th>Barang</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksiTerbaru as $index => $r)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $r->user->name }}</strong>
                            <div class="text-muted small">NIM: {{ $r->user->nim ?? '-' }}</div>
                        </td>
                        <td>
                            <strong>{{ $r->barang->nama_barang }}</strong>
                            <div class="text-muted small">Kode: {{ $r->barang->kode_barang }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge {{ $r->status === 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan' }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.peminjaman.detail', $r) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada transaksi peminjaman tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
