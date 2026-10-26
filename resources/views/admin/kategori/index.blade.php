@extends('admin.layouts.app')

@section('title', 'Kelola Kategori Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="page-title mb-0"><i class="fa fa-tags me-2"></i> Kategori Barang</h3>
</div>

<div class="row g-4">
    <!-- Form Tambah Kategori -->
    <div class="col-md-4">
        <div class="table-wrap">
            <h5 class="mb-3 fw-bold"><i class="fa fa-plus-circle text-success me-1"></i> Tambah Kategori</h5>
            <form method="POST" action="{{ route('admin.kategori.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" 
                           placeholder="Contoh: Elektronik, Optik, dsb." value="{{ old('nama_kategori') }}" required>
                    @error('nama_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat kategori...">{{ old('deskripsi') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary-lab text-white w-100 fw-semibold">
                    <i class="fa fa-save me-1"></i> Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-md-8">
        <div class="table-wrap">
            <h5 class="mb-3 fw-bold">Daftar Kategori</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Barang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $index => $k)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $k->nama_kategori }}</strong></td>
                                <td class="small text-muted">{{ $k->deskripsi ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $k->barangs_count }} barang</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $k->id }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.kategori.destroy', $k) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                    <!-- Modal Edit Kategori -->
                                    <div class="modal fade" id="editModal{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog text-start">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.kategori.update', $k) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nama Kategori</label>
                                                            <input type="text" name="nama_kategori" class="form-control" 
                                                                   value="{{ $k->nama_kategori }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control" rows="3">{{ $k->deskripsi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary-lab text-white">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada kategori terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
