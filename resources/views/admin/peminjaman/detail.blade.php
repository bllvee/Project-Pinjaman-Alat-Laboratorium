@extends('admin.layouts.app')

@section('title', 'Detail Peminjaman #' . $peminjaman->id)

@section('content')
<div class="peminjaman-detail-page">

    <!-- Breadcrumb Navigasi -->
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-1" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ route('admin.peminjaman.index') }}" class="text-secondary text-decoration-none">Transaksi</a></li>
            <li class="breadcrumb-item active text-teal fw-semibold" aria-current="page">Detail Peminjaman</li>
        </ol>
    </nav>

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <h1 class="page-title mb-0">Detail Peminjaman</h1>
            @if ($peminjaman->status === 'dipinjam')
                <span class="status-pill status-dipinjam fs-6">
                    DIPINJAM
                </span>
            @else
                <span class="status-pill status-dikembalikan fs-6">
                    DIKEMBALIKAN
                </span>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.peminjaman.index') }}" class="btn-sipinjam-outline">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <button onclick="window.print()" class="btn-sipinjam-outline" title="Cetak Transaksi">
                <i class="bi bi-printer"></i>
            </button>
        </div>
    </div>

    <!-- 2 Kolom Layout Utama -->
    <div class="row g-4">
        <!-- Kolom Kiri: Informasi Peminjam & Barang -->
        <div class="col-12 col-lg-7">
            <!-- Kartu 1: Informasi Peminjam -->
            <div class="sipinjam-card mb-4">
                <div class="card-title-header mb-4">
                    <h3 class="fs-6 fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                        <i class="bi bi-person-badge text-teal fs-5"></i>
                        <span>Informasi Peminjam</span>
                    </h3>
                </div>

                <div class="row g-4">
                    <div class="col-6">
                        <span class="d-block text-secondary small fw-semibold text-uppercase mb-1">NAMA MAHASISWA</span>
                        <span class="fs-6 fw-bold text-dark">{{ $peminjaman->user->name ?? 'Mahasiswa' }}</span>
                    </div>

                    <div class="col-6">
                        <span class="d-block text-secondary small fw-semibold text-uppercase mb-1">NIM</span>
                        <span class="fs-6 fw-bold text-dark">{{ $peminjaman->user->nim ?? ($peminjaman->user->username ?? '-') }}</span>
                    </div>

                    <div class="col-6">
                        <span class="d-block text-secondary small fw-semibold text-uppercase mb-1">TANGGAL PINJAM</span>
                        <span class="fs-6 fw-bold text-dark">
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <div class="col-6">
                        <span class="d-block text-secondary small fw-semibold text-uppercase mb-1">ESTIMASI KEMBALI</span>
                        <span class="fs-6 fw-bold {{ $peminjaman->status === 'dipinjam' && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($peminjaman->tanggal_rencana_kembali)) ? 'text-danger' : 'text-dark' }}">
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_rencana_kembali)->translatedFormat('d M Y') }}
                        </span>
                    </div>
                </div>

                @if ($peminjaman->catatan && $peminjaman->status === 'dipinjam')
                    <div class="mt-4 pt-3 border-top">
                        <span class="d-block text-secondary small fw-semibold mb-1">Catatan Peminjaman:</span>
                        <p class="text-secondary small mb-0">{{ $peminjaman->catatan }}</p>
                    </div>
                @endif
            </div>

            <!-- Kartu 2: Barang yang Dipinjam -->
            <div class="sipinjam-card">
                <div class="card-title-header mb-3">
                    <h3 class="fs-6 fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                        <i class="bi bi-box-seam text-teal fs-5"></i>
                        <span>Barang yang Dipinjam</span>
                    </h3>
                </div>

                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                    <div class="brand-logo-box shrink-0" style="width: 52px; height: 52px; font-size: 24px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 2v7.31L4.1 19.34A2 2 0 0 0 5.8 22h12.4a2 2 0 0 0 1.7-2.66L14 9.31V2"/>
                            <path d="M8.5 2h7"/>
                            <path d="M7 16h10"/>
                        </svg>
                    </div>
                    <div class="d-flex flex-column">
                        <h4 class="fs-6 fw-bold text-dark mb-1">{{ $peminjaman->barang->nama_barang ?? 'Barang Lab' }}</h4>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="text-secondary small">ID: {{ $peminjaman->barang->kode_barang ?? '-' }}</span>
                            @if(isset($peminjaman->barang->kategori))
                                <span class="badge-pill" style="background-color: var(--teal-soft); color: var(--teal-primary); border: 1px solid var(--teal-border); font-size: 11px;">
                                    Kategori: {{ $peminjaman->barang->kategori->nama_kategori }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Proses Pengembalian & Aturan Denda -->
        <div class="col-12 col-lg-5">
            @if ($peminjaman->status === 'dipinjam')
                <!-- Formulir Proses Pengembalian -->
                <div class="sipinjam-card mb-4">
                    <div class="card-title-header mb-3">
                        <h3 class="fs-6 fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-clock-history text-teal fs-5"></i>
                            <span>Proses Pengembalian</span>
                        </h3>
                    </div>

                    <form method="POST" action="{{ route('admin.peminjaman.kembali', $peminjaman->id) }}">
                        @csrf

                        <!-- Tanggal Kembali Aktual -->
                        <div class="mb-3">
                            <label for="tanggal_kembali_aktual" class="form-label-custom">Tanggal Kembali Aktual <span class="text-danger">*</span></label>
                            <input type="date" 
                                   id="tanggal_kembali_aktual" 
                                   name="tanggal_kembali_aktual" 
                                   class="form-control-custom @error('tanggal_kembali_aktual') is-invalid @enderror" 
                                   value="{{ old('tanggal_kembali_aktual', date('Y-m-d')) }}" 
                                   required>
                            @error('tanggal_kembali_aktual')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kondisi Saat Kembali -->
                        <div class="mb-3">
                            <label for="kondisi_saat_kembali" class="form-label-custom">Kondisi Saat Kembali <span class="text-danger">*</span></label>
                            <select id="kondisi_saat_kembali" name="kondisi_saat_kembali" class="form-control-custom @error('kondisi_saat_kembali') is-invalid @enderror" required>
                                <option value="baik" {{ old('kondisi_saat_kembali') == 'baik' ? 'selected' : '' }}>
                                    Bagus (Siap Pakai)
                                </option>
                                <option value="rusak" {{ old('kondisi_saat_kembali') == 'rusak' ? 'selected' : '' }}>
                                    Rusak (Dikenakan Biaya Perbaikan Rp 10.000)
                                </option>
                            </select>
                            @error('kondisi_saat_kembali')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan Kerusakan / Keterangan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label-custom">Catatan Kerusakan (Opsional)</label>
                            <textarea id="catatan" 
                                      name="catatan" 
                                      rows="3" 
                                      class="form-control-custom @error('catatan') is-invalid @enderror" 
                                      placeholder="Tulis catatan jika ada kelengkapan kurang atau komponen rusak...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn-sipinjam-primary w-100" style="height: 48px; font-size: 15px;">
                            <i class="bi bi-plus-lg"></i>
                            <span>Simpan Pengembalian</span>
                        </button>
                    </form>
                </div>
            @else
                <!-- Hasil Rincian Pengembalian & Denda -->
                <div class="sipinjam-card mb-4">
                    <div class="card-title-header mb-3">
                        <h3 class="fs-6 fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-check2-circle text-success fs-5"></i>
                            <span>Rincian Pengembalian Selesai</span>
                        </h3>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-secondary small">Tgl Kembali Aktual</span>
                            <strong class="text-dark">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->translatedFormat('d M Y') }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-secondary small">Kondisi Alat</span>
                            <span class="badge-pill {{ $peminjaman->kondisi_saat_kembali === 'rusak' ? 'status-pill-danger' : 'status-pill-success' }}">
                                {{ ucfirst($peminjaman->kondisi_saat_kembali) }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-secondary small">Keterlambatan</span>
                            <strong class="{{ $peminjaman->hari_terlambat > 0 ? 'text-danger' : 'text-dark' }}">
                                {{ $peminjaman->hari_terlambat }} hari
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-secondary small">Denda Keterlambatan</span>
                            <strong class="text-dark">Rp {{ number_format($peminjaman->denda_keterlambatan, 0, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-secondary small">Biaya Perbaikan Alat</span>
                            <strong class="text-dark">Rp {{ number_format($peminjaman->biaya_perbaikan, 0, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between py-2 mt-1 bg-light px-3 rounded-2">
                            <span class="fw-bold text-dark">TOTAL DENDA</span>
                            <span class="fs-5 fw-bold {{ $peminjaman->total_denda > 0 ? 'text-danger' : 'text-teal' }}">
                                Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if ($peminjaman->catatan)
                        <div class="p-3 bg-light rounded-2 border">
                            <span class="d-block text-secondary small fw-bold mb-1">Catatan Petugas:</span>
                            <p class="text-secondary small mb-0">{{ $peminjaman->catatan }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Kotak Penjelasan Aturan Denda Sesuai Soal UTS -->
            <div class="rule-box-uts">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div class="rule-box-content">
                    <div class="rule-title">Aturan Denda UTS</div>
                    <p class="rule-text">
                        Keterlambatan pengembalian <strong>&gt; 3 hari</strong> dikenakan denda <strong>Rp 2.000 / hari</strong>. Kerusakan alat dikenakan biaya perbaikan <strong>Rp 10.000 / unit</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
