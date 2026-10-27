@extends('admin.layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="create-barang-page">

    <!-- Breadcrumb Navigasi -->
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-1" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ route('admin.barang.index') }}" class="text-secondary text-decoration-none">Data Barang</a></li>
            <li class="breadcrumb-item active text-teal fw-semibold" aria-current="page">Tambah Barang Baru</li>
        </ol>
    </nav>

    <!-- Header Halaman -->
    <div class="mb-4">
        <h1 class="page-title">Tambah Barang Baru</h1>
        <p class="page-subtitle">Silakan isi formulir berikut untuk menambahkan inventaris alat laboratorium baru.</p>
    </div>

    <!-- Form Card Terpusat (Max Width 800px) -->
    <div class="form-card-centered">
        <form method="POST" action="{{ route('admin.barang.store') }}">
            @csrf

            <!-- 1. Nama Barang (Full Width) -->
            <div class="mb-4">
                <label for="nama_barang" class="form-label-custom">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" 
                       id="nama_barang" 
                       name="nama_barang" 
                       class="form-control-custom @error('nama_barang') is-invalid @enderror" 
                       placeholder="Contoh: Mikroskop Binokuler XSZ-107BN" 
                       value="{{ old('nama_barang') }}" 
                       required>
                @error('nama_barang')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2. Kategori & Kode Barang (2 Kolom) -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <label for="kategori_id" class="form-label-custom">Kategori <span class="text-danger">*</span></label>
                    <select id="kategori_id" name="kategori_id" class="form-control-custom @error('kategori_id') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="kode_barang" class="form-label-custom">Kode Barang <span class="text-danger">*</span></label>
                    <input type="text" 
                           id="kode_barang" 
                           name="kode_barang" 
                           class="form-control-custom @error('kode_barang') is-invalid @enderror" 
                           placeholder="Contoh: ALT-001" 
                           value="{{ old('kode_barang') }}" 
                           required>
                    @error('kode_barang')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 3. Stok Total & Info Stok (2 Kolom) -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <label for="stok_total" class="form-label-custom">Stok Total <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" 
                               id="stok_total" 
                               name="stok_total" 
                               class="form-control-custom @error('stok_total') is-invalid @enderror" 
                               placeholder="0" 
                               value="{{ old('stok_total', 1) }}" 
                               min="1" 
                               required>
                    </div>
                    @error('stok_total')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label-custom">Alokasi Stok Awal</label>
                    <div class="form-control-custom d-flex align-items-center bg-light text-secondary">
                        <i class="bi bi-info-circle me-2 text-teal"></i>
                        <span>Stok tersedia otomatis disamakan dengan stok total</span>
                    </div>
                </div>
            </div>

            <!-- 4. Deskripsi Tambahan (Textarea) -->
            <div class="mb-4">
                <label for="deskripsi" class="form-label-custom">Deskripsi Tambahan</label>
                <textarea id="deskripsi" 
                          name="deskripsi" 
                          rows="4" 
                          class="form-control-custom @error('deskripsi') is-invalid @enderror" 
                          placeholder="Tambahkan catatan detail tentang spesifikasi, kelengkapan, atau panduan alat...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Aksi Batal & Simpan -->
            <div class="d-flex justify-content-end align-items-center gap-3 pt-3 border-top">
                <a href="{{ route('admin.barang.index') }}" class="btn-sipinjam-outline">
                    Batal
                </a>
                <button type="submit" class="btn-sipinjam-primary">
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
