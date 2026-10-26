@extends('admin.layouts.app')

@section('title', 'Data Barang Lab')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-0"><i class="fa fa-flask-vial me-2"></i> Data Barang Laboratorium</h3>
        <p class="text-muted small mb-0">Kelola master data peralatan laboratorium beserta stok ketersediaan.</p>
    </div>
    <a href="{{ route('admin.barang.create') }}" class="btn btn-primary-lab text-white">
        <i class="fa fa-plus me-1"></i> Tambah Barang Baru
    </a>
</div>

<!-- Filter & Search Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
    <form method="GET" action="{{ route('admin.barang.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="fa fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode barang..." 
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="kategori_id" class="form-select">
                <option value="">-- Semua Kategori --</option>
                @foreach ($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary-lab text-white w-100">
                <i class="fa fa-filter me-1"></i> Filter
            </button>
            @if(request('search') || request('kategori_id'))
                <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Barang -->
<div class="table-wrap">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Total</th>
                    <th>Stok Tersedia</th>
                    <th>Status Stok</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $index => $b)
                    <tr>
                        <td>{{ $barangs->firstItem() + $index }}</td>
                        <td><code>{{ $b->kode_barang }}</code></td>
                        <td>
                            <strong>{{ $b->nama_barang }}</strong>
                            @if($b->deskripsi)
                                <div class="text-muted small">{{ Str::limit($b->deskripsi, 40) }}</div>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                        <td><strong>{{ $b->stok_total }}</strong> unit</td>
                        <td>
                            <strong class="{{ $b->stok_tersedia > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $b->stok_tersedia }}
                            </strong> unit
                        </td>
                        <td>
                            @if ($b->stok_tersedia > 0)
                                <span class="badge bg-success-subtle text-success border border-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger">Habis</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.barang.edit', $b) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.barang.destroy', $b) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Tidak ada data barang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $barangs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
