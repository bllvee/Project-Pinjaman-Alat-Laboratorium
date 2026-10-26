@extends('user.layouts.app')

@section('title', 'Ajukan Peminjaman Alat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="table-wrap">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa fa-hand-holding-hand fs-3 text-success"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Form Pengajuan Peminjaman Alat</h5>
                    <small class="text-muted">Laboratorium Manajemen Sistem Informasi</small>
                </div>
            </div>

            <!-- Identitas Mahasiswa Terkunci -->
            <div class="mb-3 p-3 bg-light rounded-3 border">
                <div class="row g-2 small">
                    <div class="col-sm-6">
                        <span class="text-muted d-block">Peminjam:</span>
                        <strong>{{ auth()->user()->name }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted d-block">NIM:</span>
                        <code>{{ auth()->user()->nim ?? auth()->user()->username }}</code>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted d-block">Program Studi:</span>
                        <span>{{ auth()->user()->prodi ?? 'Teknologi Informasi' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted d-block">No. Telepon / WA:</span>
                        <span>{{ auth()->user()->no_hp ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('user.peminjaman.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Peralatan Laboratorium <span class="text-danger">*</span></label>
                    <select name="barang_id" class="form-select @error('barang_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Alat yang Ingin Dipinjam --</option>
                        @foreach ($barangs as $b)
                            <option value="{{ $b->id }}" {{ (old('barang_id') == $b->id || $selectedBarangId == $b->id) ? 'selected' : '' }}>
                                {{ $b->nama_barang }} [{{ $b->kode_barang }}] &mdash; Tersedia: {{ $b->stok_tersedia }} unit
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        @error('tanggal_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">Rencana Tanggal Kembali <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_rencana_kembali" class="form-control @error('tanggal_rencana_kembali') is-invalid @enderror" 
                               min="{{ date('Y-m-d') }}" value="{{ old('tanggal_rencana_kembali') }}" required>
                        @error('tanggal_rencana_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan / Keperluan Praktikum</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Praktikum Jaringan / Uji Rangkaian">{{ old('catatan') }}</textarea>
                </div>

                <!-- Info Ketentuan UTS -->
                <div class="alert alert-light border small text-muted mb-4">
                    <div class="fw-bold text-dark mb-1"><i class="fa fa-info-circle text-primary me-1"></i> Peraturan Peminjaman:</div>
                    <ul class="mb-0 ps-3">
                        <li>Pengembalian terlambat <strong>lebih dari 3 hari</strong> dari rencana kembali dikenakan denda <strong>Rp2.000 / hari</strong>.</li>
                        <li>Alat yang dikembalikan dalam kondisi <strong>rusak</strong> dikenakan biaya perbaikan tetap sebesar <strong>Rp10.000</strong>.</li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lab text-white flex-grow-1 py-2 fw-semibold">
                        <i class="fa fa-check me-1"></i> Ajukan Peminjaman
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
