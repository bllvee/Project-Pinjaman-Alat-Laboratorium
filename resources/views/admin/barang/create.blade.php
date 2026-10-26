@extends('admin.layouts.app')

@section('title', 'Tambah Barang Lab')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="table-wrap">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa fa-plus-circle fs-3 text-success"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Tambah Barang Laboratorium</h5>
                    <small class="text-muted">Masukkan data peralatan baru ke sistem.</small>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.barang.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                    <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" 
                           placeholder="Contoh: BRG-001" value="{{ old('kode_barang') }}" required>
                    @error('kode_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                           placeholder="Contoh: Osiloskop Digital 100MHz" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Stok Total <span class="text-danger">*</span></label>
                    <input type="number" name="stok_total" class="form-control @error('stok_total') is-invalid @enderror" 
                           placeholder="Jumlah unit" min="1" value="{{ old('stok_total', 1) }}" required>
                    <small class="text-muted">Stok tersedia otomatis diset sama dengan stok total saat awal dibuat.</small>
                    @error('stok_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi / Spesifikasi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat spesifikasi barang...">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lab text-white flex-grow-1 fw-semibold">
                        <i class="fa fa-save me-1"></i> Simpan Barang
                    </button>
                    <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
