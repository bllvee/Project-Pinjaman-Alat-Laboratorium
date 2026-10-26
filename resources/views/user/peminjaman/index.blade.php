@extends('user.layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-0"><i class="fa fa-clock-rotate-left me-2"></i> Riwayat Peminjaman Pribadi</h3>
        <p class="text-muted small mb-0">Rincian seluruh transaksi peminjaman alat lab beserta kalkulasi denda jika ada.</p>
    </div>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary-lab text-white">
        <i class="fa fa-plus me-1"></i> Pinjam Alat Baru
    </a>
</div>

<!-- Ringkasan Rekap Mahasiswa -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Total Transaksi</p>
            <h2>{{ $rekap['total'] }}</h2>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Sedang Dipinjam</p>
            <h2 class="text-warning">{{ $rekap['dipinjam'] }}</h2>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Selesai Kembali</p>
            <h2 class="text-success">{{ $rekap['dikembalikan'] }}</h2>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Total Denda Ditagih</p>
            <h2 class="{{ $rekap['total_denda'] > 0 ? 'text-danger' : 'text-secondary' }}">
                Rp {{ number_format($rekap['total_denda'], 0, ',', '.') }}
            </h2>
        </div>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="table-wrap">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h5 class="mb-0 fw-bold">Daftar Transaksi Saya</h5>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('user.peminjaman.index', ['status' => 'semua']) }}" 
               class="btn {{ request('status', 'semua') === 'semua' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' }}">Semua</a>
            <a href="{{ route('user.peminjaman.index', ['status' => 'dipinjam']) }}" 
               class="btn {{ request('status') === 'dipinjam' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' }}">Sedang Dipinjam</a>
            <a href="{{ route('user.peminjaman.index', ['status' => 'dikembalikan']) }}" 
               class="btn {{ request('status') === 'dikembalikan' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' }}">Sudah Dikembalikan</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang Lab</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Tgl Aktual</th>
                    <th>Kondisi</th>
                    <th>Terlambat</th>
                    <th>Denda Telat</th>
                    <th>Biaya Servis</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamans as $index => $r)
                    <tr>
                        <td>{{ $peminjamans->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $r->barang->nama_barang }}</strong>
                            <div class="text-muted small">Kode: {{ $r->barang->kode_barang }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->format('d-m-Y') }}</td>
                        <td>
                            {{ $r->tanggal_kembali_aktual ? \Carbon\Carbon::parse($r->tanggal_kembali_aktual)->format('d-m-Y') : '-' }}
                        </td>
                        <td>
                            @if ($r->kondisi_saat_kembali)
                                <span class="badge {{ $r->kondisi_saat_kembali === 'rusak' ? 'badge-kondisi-rusak' : 'badge-kondisi-baik' }}">
                                    {{ ucfirst($r->kondisi_saat_kembali) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $r->hari_terlambat > 0 ? "{$r->hari_terlambat} hari" : '-' }}
                        </td>
                        <td>
                            {{ $r->denda_keterlambatan > 0 ? 'Rp ' . number_format($r->denda_keterlambatan, 0, ',', '.') : 'Rp 0' }}
                        </td>
                        <td>
                            {{ $r->biaya_perbaikan > 0 ? 'Rp ' . number_format($r->biaya_perbaikan, 0, ',', '.') : 'Rp 0' }}
                        </td>
                        <td>
                            @if ($r->total_denda > 0)
                                <strong class="text-danger">Rp {{ number_format($r->total_denda, 0, ',', '.') }}</strong>
                            @else
                                <span class="text-muted">Rp 0</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $r->status === 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan' }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Belum ada riwayat transaksi peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $peminjamans->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
