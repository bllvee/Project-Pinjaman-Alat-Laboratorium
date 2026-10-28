@extends('user.layouts.app')

@section('title', 'Ajukan Peminjaman Alat')

@section('content')
<div class="create-peminjaman-page">

    <!-- Breadcrumb Navigasi -->
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb breadcrumb-sipinjam">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-secondary text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item active text-teal fw-semibold" aria-current="page">Form Pengajuan Peminjaman</li>
        </ol>
    </nav>

    <!-- Header Halaman -->
    <div class="mb-4">
        <h1 class="page-title">Form Pengajuan Peminjaman Alat</h1>
        <p class="page-subtitle">Laboratorium Manajemen Sistem Informasi — isi formulir dengan lengkap dan benar.</p>
    </div>

    <!-- Form Card Terpusat (Max Width 800px) -->
    <div class="form-card-centered">

        <!-- Identitas Peminjam Terkunci (Read-only) -->
        <div class="user-identity-box mb-4">
            <i class="bi bi-person-badge user-identity-icon"></i>
            <div class="w-100">
                <div class="user-identity-title">Identitas Peminjam (Tervalidasi Otomatis)</div>
                <div class="row g-2 mt-1">
                    <div class="col-6 col-sm-3">
                        <span class="d-block text-secondary small">Nama:</span>
                        <strong class="user-identity-item">{{ auth()->user()->name }}</strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <span class="d-block text-secondary small">NIM:</span>
                        <strong class="user-identity-item text-teal">{{ auth()->user()->nim ?? auth()->user()->username }}</strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <span class="d-block text-secondary small">Prodi:</span>
                        <span class="user-identity-item">{{ auth()->user()->prodi ?? 'Teknologi Informasi' }}</span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <span class="d-block text-secondary small">No. HP:</span>
                        <span class="user-identity-item">{{ auth()->user()->no_hp ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('user.peminjaman.store') }}">
            @csrf

            <!-- 1. Pilih Peralatan Laboratorium -->
            <div class="mb-4">
                <label for="barang_id" class="form-label-custom">
                    Pilih Peralatan Laboratorium <span class="text-danger">*</span>
                </label>
                <select id="barang_id"
                        name="barang_id"
                        class="form-control-custom @error('barang_id') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih Alat yang Ingin Dipinjam --</option>
                    @foreach ($barangs as $b)
                        <option value="{{ $b->id }}" {{ (old('barang_id') == $b->id || $selectedBarangId == $b->id) ? 'selected' : '' }}>
                            {{ $b->nama_barang }} [{{ $b->kode_barang }}] — Tersedia: {{ $b->stok_tersedia }} unit
                        </option>
                    @endforeach
                </select>
                @error('barang_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2. Tanggal Pinjam & Tanggal Rencana Kembali (2 Kolom) -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <label for="tanggal_pinjam" class="form-label-custom">
                        Tanggal Pinjam <span class="text-danger">*</span>
                    </label>
                    <input type="date"
                           id="tanggal_pinjam"
                           name="tanggal_pinjam"
                           class="form-control-custom @error('tanggal_pinjam') is-invalid @enderror"
                           value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                           required>
                    @error('tanggal_pinjam')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="tanggal_rencana_kembali" class="form-label-custom">
                        Rencana Tanggal Kembali <span class="text-danger">*</span>
                    </label>
                    <input type="date"
                           id="tanggal_rencana_kembali"
                           name="tanggal_rencana_kembali"
                           class="form-control-custom @error('tanggal_rencana_kembali') is-invalid @enderror"
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('tanggal_rencana_kembali') }}"
                           required>
                    @error('tanggal_rencana_kembali')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 3. Catatan / Keperluan Praktikum -->
            <div class="mb-4">
                <label for="catatan" class="form-label-custom">Catatan / Keperluan Praktikum</label>
                <textarea id="catatan"
                          name="catatan"
                          rows="3"
                          class="form-control-custom @error('catatan') is-invalid @enderror"
                          placeholder="Contoh: Praktikum Jaringan / Simulasi Rangkaian Tertanam...">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kotak Aturan Denda -->
            <div class="rule-box mb-4">
                <i class="bi bi-exclamation-triangle-fill rule-box-icon"></i>
                <div>
                    <div class="rule-box-title">Aturan Pengembalian Alat (Sesuai Soal UTS MSI)</div>
                    <p class="rule-box-text">
                        Keterlambatan pengembalian <strong>lebih dari 3 hari</strong> dari rencana kembali dikenakan denda <strong>Rp 2.000 / hari</strong>. Peralatan yang dikembalikan dalam kondisi <strong>rusak</strong> dikenakan biaya perbaikan tetap <strong>Rp 10.000 / unit</strong>.
                    </p>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end align-items-center gap-3 pt-3 border-top">
                <a href="{{ route('user.dashboard') }}" class="btn-sipinjam-outline">
                    Batal
                </a>
                <button type="submit" class="btn-sipinjam-primary">
                    <i class="bi bi-check-circle"></i>
                    <span>Ajukan Peminjaman</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
