@extends('admin.layouts.app')

@section('title', 'Edit Barang Lab')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="table-wrap">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa fa-pencil fs-3 text-warning"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Edit Data Barang</h5>
                    <small class="text-muted">Perbarui informasi barang laboratorium.</small>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.barang.update', $barang) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                    <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" 
                           value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                    @error('kode_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                           value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>
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
                           min="1" value="{{ old('stok_total', $barang->stok_total) }}" required>
                    <small class="text-muted">Stok saat ini: total {{ $barang->stok_total }} unit, tersedia {{ $barang->stok_tersedia }} unit.</small>
                    @error('stok_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi / Spesifikasi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lab text-white flex-grow-1 fw-semibold">
                        <i class="fa fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
