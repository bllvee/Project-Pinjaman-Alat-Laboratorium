@extends('admin.layouts.app')

@section('title', 'Edit Barang: ' . $barang->nama_barang)

@section('content')
<div class="edit-barang-page">

    <!-- Breadcrumb Navigasi -->
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-1" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ route('admin.barang.index') }}" class="text-secondary text-decoration-none">Data Barang</a></li>
            <li class="breadcrumb-item active text-teal fw-semibold" aria-current="page">Edit Barang</li>
        </ol>
    </nav>

    <!-- Header Halaman -->
    <div class="mb-4">
        <h1 class="page-title">Edit Data Barang</h1>
        <p class="page-subtitle">Perbarui informasi detail atau penyesuaian alokasi stok alat laboratorium.</p>
    </div>

    <!-- Form Card Terpusat (Max Width 800px) -->
    <div class="form-card-centered">
        <form method="POST" action="{{ route('admin.barang.update', $barang->id) }}">
            @csrf
            @method('PUT')

            <!-- 1. Nama Barang (Full Width) -->
            <div class="mb-4">
                <label for="nama_barang" class="form-label-custom">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" 
                       id="nama_barang" 
                       name="nama_barang" 
                       class="form-control-custom @error('nama_barang') is-invalid @enderror" 
                       placeholder="Contoh: Mikroskop Binokuler XSZ-107BN" 
                       value="{{ old('nama_barang', $barang->nama_barang) }}" 
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
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $barang->kategori_id) == $kat->id ? 'selected' : '' }}>
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
                           value="{{ old('kode_barang', $barang->kode_barang) }}" 
                           required>
                    @error('kode_barang')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 3. Stok Total & Info Stok Tersedia (2 Kolom) -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <label for="stok_total" class="form-label-custom">Stok Total <span class="text-danger">*</span></label>
                    <input type="number" 
                           id="stok_total" 
                           name="stok_total" 
                           class="form-control-custom @error('stok_total') is-invalid @enderror" 
                           value="{{ old('stok_total', $barang->stok_total) }}" 
                           min="1" 
                           required>
                    @error('stok_total')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label-custom">Stok Tersedia Saat Ini</label>
                    <div class="form-control-custom d-flex align-items-center bg-light justify-content-between">
                        <span class="text-secondary">Tersedia untuk dipinjam:</span>
                        <strong class="text-teal fs-6">{{ $barang->stok_tersedia }} unit</strong>
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
                          placeholder="Tambahkan catatan detail tentang spesifikasi, kelengkapan, atau panduan alat...">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Aksi Batal & Simpan Perubahan -->
            <div class="d-flex justify-content-end align-items-center gap-3 pt-3 border-top">
                <a href="{{ route('admin.barang.index') }}" class="btn-sipinjam-outline">
                    Batal
                </a>
                <button type="submit" class="btn-sipinjam-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
