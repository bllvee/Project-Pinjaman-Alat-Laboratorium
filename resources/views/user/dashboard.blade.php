@extends('user.layouts.app')

@section('title', 'Beranda Mahasiswa')

@section('content')
<!-- Hero Welcome Mahasiswa -->
<div class="hero-welcome">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="mb-1"><i class="fa fa-graduation-cap me-2"></i> Selamat Datang, {{ $user->name }}!</h4>
            <p class="mb-0 opacity-75 small">
                NIM: <strong>{{ $user->nim ?? $user->username }}</strong> &bull;
                Prodi: <strong>{{ $user->prodi ?? 'Teknologi Informasi' }}</strong> &bull;
                No. HP: <strong>{{ $user->no_hp ?? '-' }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('user.barang.index') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="fa fa-boxes-stacked me-1 text-success"></i> Lihat Katalog
            </a>
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-warning btn-sm fw-semibold text-dark">
                <i class="fa fa-hand-holding-hand me-1"></i> Ajukan Pinjam
            </a>
        </div>
    </div>
</div>

<!-- Kartu Statistik Pribadi -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-stat">
            <p>Alat Sedang Dipinjam</p>
            <h2 class="text-warning">{{ $sedangDipinjam }}</h2>
            <div class="text-muted small mt-1">Harap perhatikan tanggal pengembalian</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat">
            <p>Peminjaman Selesai</p>
            <h2 class="text-success">{{ $peminjamanSelesai }}</h2>
            <div class="text-muted small mt-1">Total transaksi yang sudah dikembalikan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat">
            <p>Total Denda Tercatat</p>
            <h2 class="{{ $totalDenda > 0 ? 'text-danger' : 'text-secondary' }}">
                Rp {{ number_format($totalDenda, 0, ',', '.') }}
            </h2>
            <div class="text-muted small mt-1">Akumulasi denda keterlambatan/kerusakan</div>
        </div>
    </div>
</div>

<!-- Ketentuan Denda UTS -->
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-4">
    <i class="fa fa-triangle-exclamation fs-3 text-warning"></i>
    <div class="small">
        <strong>Ketentuan Pengembalian Alat Lab:</strong>
        Keterlambatan lebih dari 3 hari dari tanggal rencana kembali dikenakan denda keterlambatan <strong>Rp2.000 / hari</strong>.
        Apabila alat dikembalikan dalam kondisi rusak, dikenakan biaya perbaikan tetap sebesar <strong>Rp10.000</strong>.
    </div>
</div>

<!-- Tabel Pinjaman Aktif -->
<div class="table-wrap mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-dark"><i class="fa fa-clock text-warning me-2"></i> Alat Yang Sedang Anda Pinjam</h5>
        <span class="badge bg-warning text-dark">{{ $sedangDipinjam }} alat aktif</span>
    </div>

    @if ($peminjamanAktif->isEmpty())
        <div class="text-center py-4 text-muted">
            <i class="fa fa-circle-check fs-1 text-success mb-2 d-block"></i>
            Saat ini Anda tidak memiliki pinjaman alat yang sedang berjalan.
            <div class="mt-2">
                <a href="{{ route('user.peminjaman.create') }}" class="btn btn-sm btn-primary-lab text-white">
                    <i class="fa fa-plus me-1"></i> Pinjam Alat Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang Lab</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Rencana Kembali</th>
                        <th>Status Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $hariIni = \Carbon\Carbon::now()->startOfDay();
                    @endphp
                    @foreach ($peminjamanAktif as $index => $r)
                        @php
                            $rencana = \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->startOfDay();
                            $selisih = $hariIni->diffInDays($rencana, false);
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $r->barang->nama_barang }}</strong>
                                <div class="text-muted small">Kode: {{ $r->barang->kode_barang }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td><strong>{{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->format('d-m-Y') }}</strong></td>
                            <td>
                                @if ($selisih < 0)
                                    <span class="badge bg-danger">Terlambat {{ abs($selisih) }} hari</span>
                                @elseif ($selisih === 0)
                                    <span class="badge bg-warning text-dark">Batas hari ini!</span>
                                @else
                                    <span class="badge bg-success">Tersisa {{ $selisih }} hari lagi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Ringkasan Riwayat Terakhir -->
<div class="table-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-dark"><i class="fa fa-history text-secondary me-2"></i> Riwayat Transaksi Terakhir</h5>
        <a href="{{ route('user.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua Riwayat</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali Aktual</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Total Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatTerbaru as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->barang->nama_barang }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>
                            {{ $row->tanggal_kembali_aktual ? \Carbon\Carbon::parse($row->tanggal_kembali_aktual)->format('d-m-Y') : '-' }}
                        </td>
                        <td>
                            @if ($row->kondisi_saat_kembali)
                                <span class="badge {{ $row->kondisi_saat_kembali === 'rusak' ? 'badge-kondisi-rusak' : 'badge-kondisi-baik' }}">
                                    {{ ucfirst($row->kondisi_saat_kembali) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $row->status === 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan' }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($row->total_denda > 0)
                                <strong class="text-danger">Rp {{ number_format($row->total_denda, 0, ',', '.') }}</strong>
                            @else
                                <span class="text-muted">Rp 0</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">Belum ada riwayat transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
