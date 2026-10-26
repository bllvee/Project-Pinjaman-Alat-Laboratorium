@extends('admin.layouts.app')

@section('title', 'Transaksi Peminjaman')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-0"><i class="fa fa-right-left me-2"></i> Transaksi Peminjaman & Pengembalian</h3>
        <p class="text-muted small mb-0">Kelola riwayat peminjaman, verifikasi pengembalian alat, dan hitung denda otomatis.</p>
    </div>
</div>

<!-- Kartu Ringkasan Rekap -->
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
            <p>Sudah Dikembalikan</p>
            <h2 class="text-success">{{ $rekap['dikembalikan'] }}</h2>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Total Denda Masuk</p>
            <h2 class="{{ $rekap['total_denda'] > 0 ? 'text-danger' : 'text-secondary' }}">
                Rp {{ number_format($rekap['total_denda'], 0, ',', '.') }}
            </h2>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
    <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="fa fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama mahasiswa, NIM, atau barang..." 
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="btn-group w-100">
                <a href="{{ route('admin.peminjaman.index', ['status' => 'semua', 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ request('status', 'semua') === 'semua' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' }}">Semua</a>
                <a href="{{ route('admin.peminjaman.index', ['status' => 'dipinjam', 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ request('status') === 'dipinjam' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' }}">Dipinjam</a>
                <a href="{{ route('admin.peminjaman.index', ['status' => 'dikembalikan', 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ request('status') === 'dikembalikan' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' }}">Dikembalikan</a>
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary-lab text-white w-100">
                <i class="fa fa-search me-1"></i> Cari
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Transaksi -->
<div class="table-wrap">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th>Barang</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Tgl Aktual</th>
                    <th>Kondisi</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamans as $index => $p)
                    <tr>
                        <td>{{ $peminjamans->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $p->user->name }}</strong>
                            <div class="text-muted small">NIM: {{ $p->user->nim ?? '-' }}</div>
                        </td>
                        <td>
                            <strong>{{ $p->barang->nama_barang }}</strong>
                            <div class="text-muted small">Kode: {{ $p->barang->kode_barang }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_rencana_kembali)->format('d-m-Y') }}</td>
                        <td>
                            {{ $p->tanggal_kembali_aktual ? \Carbon\Carbon::parse($p->tanggal_kembali_aktual)->format('d-m-Y') : '-' }}
                        </td>
                        <td>
                            @if ($p->kondisi_saat_kembali)
                                <span class="badge {{ $p->kondisi_saat_kembali === 'rusak' ? 'badge-kondisi-rusak' : 'badge-kondisi-baik' }}">
                                    {{ ucfirst($p->kondisi_saat_kembali) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($p->total_denda > 0)
                                <strong class="text-danger">Rp {{ number_format($p->total_denda, 0, ',', '.') }}</strong>
                            @else
                                <span class="text-muted">Rp 0</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $p->status === 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.peminjaman.detail', $p) }}" class="btn btn-sm {{ $p->status === 'dipinjam' ? 'btn-primary-lab text-white' : 'btn-outline-primary' }}">
                                <i class="fa {{ $p->status === 'dipinjam' ? 'fa-undo' : 'fa-eye' }}"></i> 
                                {{ $p->status === 'dipinjam' ? 'Proses Kembali' : 'Detail' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Tidak ada data transaksi peminjaman.</td>
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
