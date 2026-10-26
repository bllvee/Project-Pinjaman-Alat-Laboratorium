@extends('user.layouts.app')

@section('title', 'Katalog Barang Lab')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-0"><i class="fa fa-boxes-stacked me-2"></i> Katalog Peralatan Laboratorium</h3>
        <p class="text-muted small mb-0">Cari peralatan yang dibutuhkan dan ajukan peminjaman secara mandiri.</p>
    </div>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary-lab text-white">
        <i class="fa fa-plus me-1"></i> Form Ajukan Pinjam
    </a>
</div>

<!-- Filter & Search Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
    <form method="GET" action="{{ route('user.barang.index') }}" class="row g-2 align-items-center">
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
                <a href="{{ route('user.barang.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Grid Kartu Barang -->
<div class="row g-3">
    @forelse ($barangs as $b)
        @php
            $bisaPinjam = $b->stok_tersedia > 0;
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="tool-card p-3 shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="tool-icon">
                        <i class="fa fa-flask"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <span class="badge bg-light text-dark border mb-1">{{ $b->kategori->nama_kategori ?? 'Umum' }}</span>
                        <h6 class="fw-bold mb-0 text-truncate" title="{{ $b->nama_barang }}">
                            {{ $b->nama_barang }}
                        </h6>
                        <small class="text-muted">Kode: {{ $b->kode_barang }}</small>
                    </div>
                </div>

                @if($b->deskripsi)
                    <p class="text-muted small mb-2 text-truncate" title="{{ $b->deskripsi }}">
                        {{ $b->deskripsi }}
                    </p>
                @endif

                <div class="d-flex justify-content-between align-items-center border-top border-bottom py-2 my-2 small">
                    <span class="text-muted">Status Ketersediaan:</span>
                    @if ($bisaPinjam)
                        <span class="badge bg-success-subtle text-success border border-success fw-semibold">
                            <i class="fa fa-check me-1"></i> Tersedia ({{ $b->stok_tersedia }} / {{ $b->stok_total }})
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger fw-semibold">
                            <i class="fa fa-times me-1"></i> Stok Habis (0 / {{ $b->stok_total }})
                        </span>
                    @endif
                </div>

                <div class="mt-auto pt-2">
                    @if ($bisaPinjam)
                        <a href="{{ route('user.peminjaman.create', ['barang_id' => $b->id]) }}" class="btn btn-sm btn-primary-lab text-white w-100">
                            <i class="fa fa-hand-holding-hand me-1"></i> Pinjam Alat Ini
                        </a>
                    @else
                        <button class="btn btn-sm btn-secondary w-100" disabled>
                            <i class="fa fa-ban me-1"></i> Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="table-wrap text-center py-5 text-muted">
                <i class="fa fa-box-open fs-1 mb-2 d-block text-secondary"></i>
                Tidak ada peralatan laboratorium yang cocok dengan kriteria pencarian Anda.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $barangs->links('pagination::bootstrap-5') }}
</div>
@endsection
