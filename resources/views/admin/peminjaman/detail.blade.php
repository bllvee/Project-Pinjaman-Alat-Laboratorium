@extends('admin.layouts.app')

@section('title', 'Detail Transaksi Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="page-title mb-0"><i class="fa fa-file-invoice me-2"></i> Detail Transaksi Peminjaman #{{ $peminjaman->id }}</h3>
    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row g-4">
    <!-- Rincian Peminjaman & Mahasiswa -->
    <div class="col-md-6">
        <div class="table-wrap h-100">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Peminjam & Barang</h5>
            <table class="table table-borderless align-middle mb-0">
                <tr>
                    <th width="35%" class="text-muted">Nama Mahasiswa</th>
                    <td><strong>{{ $peminjaman->user->name }}</strong></td>
                </tr>
                <tr>
                    <th class="text-muted">NIM</th>
                    <td><code>{{ $peminjaman->user->nim ?? '-' }}</code></td>
                </tr>
                <tr>
                    <th class="text-muted">Program Studi</th>
                    <td>{{ $peminjaman->user->prodi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-muted">No. Telepon/WA</th>
                    <td>{{ $peminjaman->user->no_hp ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Nama Barang</th>
                    <td><strong>{{ $peminjaman->barang->nama_barang }}</strong></td>
                </tr>
                <tr>
                    <th class="text-muted">Kode Barang</th>
                    <td><code>{{ $peminjaman->barang->kode_barang }}</code></td>
                </tr>
                <tr>
                    <th class="text-muted">Kategori</th>
                    <td><span class="badge bg-light text-dark border">{{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}</span></td>
                </tr>
                <tr>
                    <th class="text-muted">Tanggal Pinjam</th>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Rencana Kembali</th>
                    <td><strong>{{ \Carbon\Carbon::parse($peminjaman->tanggal_rencana_kembali)->format('d F Y') }}</strong></td>
                </tr>
                <tr>
                    <th class="text-muted">Status Transaksi</th>
                    <td>
                        <span class="badge {{ $peminjaman->status === 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan' }} fs-6">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Kolom Aksi / Rincian Denda -->
    <div class="col-md-6">
        <div class="table-wrap h-100">
            @if ($peminjaman->status === 'dipinjam')
                <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">
                    <i class="fa fa-undo me-1"></i> Proses Pengembalian Alat
                </h5>

                <form method="POST" action="{{ route('admin.peminjaman.kembali', $peminjaman) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kembali Aktual <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali_aktual" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                        <small class="text-muted">Default diset hari ini.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kondisi Fisik Alat Saat Kembali <span class="text-danger">*</span></label>
                        <select name="kondisi_saat_kembali" class="form-select" required>
                            <option value="baik">Kondisi Baik (Tanpa Biaya Perbaikan)</option>
                            <option value="rusak">Kondisi Rusak (Dikenakan Biaya Perbaikan Rp10.000)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Pemeriksaan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan kondisi fisik alat atau keterangan lainnya...">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Kotak Aturan Bisnis UTS -->
                    <div class="alert alert-light border small text-muted mb-4">
                        <div class="fw-bold text-dark mb-1"><i class="fa fa-info-circle text-primary me-1"></i> Logika Denda Otomatis Sesuai Soal UTS:</div>
                        <ul class="mb-0 ps-3">
                            <li><strong>Aturan 1:</strong> Jika keterlambatan &gt; 3 hari dari rencana kembali, dikenakan denda <strong>Rp2.000 / hari</strong>.</li>
                            <li><strong>Aturan 2:</strong> Jika kondisi alat rusak, dikenakan biaya servis/perbaikan <strong>Rp10.000</strong>.</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-primary-lab text-white w-100 py-2 fw-semibold">
                        <i class="fa fa-check-circle me-1"></i> Simpan & Selesaikan Pengembalian
                    </button>
                </form>
            @else
                <h5 class="fw-bold mb-3 border-bottom pb-2 text-success">
                    <i class="fa fa-circle-check me-1"></i> Hasil Pengembalian Selesai
                </h5>

                <table class="table table-borderless align-middle">
                    <tr>
                        <th width="45%" class="text-muted">Tanggal Kembali Aktual</th>
                        <td><strong>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->format('d F Y') }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Kondisi Alat</th>
                        <td>
                            <span class="badge {{ $peminjaman->kondisi_saat_kembali === 'rusak' ? 'badge-kondisi-rusak' : 'badge-kondisi-baik' }}">
                                {{ ucfirst($peminjaman->kondisi_saat_kembali) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Jumlah Hari Keterlambatan</th>
                        <td>
                            @if ($peminjaman->hari_terlambat > 0)
                                <strong class="text-danger">{{ $peminjaman->hari_terlambat }} hari</strong>
                                @if ($peminjaman->hari_terlambat <= 3)
                                    <span class="small text-muted">(toleransi &le; 3 hari, denda Rp 0)</span>
                                @endif
                            @else
                                <span class="text-success">Tepat waktu (0 hari)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Denda Keterlambatan</th>
                        <td>Rp {{ number_format($peminjaman->denda_keterlambatan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Biaya Perbaikan Alat</th>
                        <td>Rp {{ number_format($peminjaman->biaya_perbaikan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top">
                        <th class="fs-5">Total Denda yang Ditagih</th>
                        <td>
                            <h4 class="{{ $peminjaman->total_denda > 0 ? 'text-danger' : 'text-success' }} mb-0 fw-bold">
                                Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}
                            </h4>
                        </td>
                    </tr>
                    @if ($peminjaman->catatan)
                        <tr>
                            <th class="text-muted">Catatan Petugas</th>
                            <td>{{ $peminjaman->catatan }}</td>
                        </tr>
                    @endif
                </table>

                <div class="alert alert-success mt-3 small">
                    <i class="fa fa-box me-1"></i> Stok unit barang <strong>{{ $peminjaman->barang->nama_barang }}</strong> telah otomatis dikembalikan ke sistem.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
