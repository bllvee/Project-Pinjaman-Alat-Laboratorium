@extends('user.layouts.app')

@section('title', 'Katalog Peralatan Laboratorium')

@section('content')
<div class="user-barang-page">

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">Katalog Alat Laboratorium</h1>
            <p class="page-subtitle">Eksplorasi instrumen praktikum yang tersedia dan ajukan peminjaman secara mandiri.</p>
        </div>
        <a href="{{ route('user.peminjaman.create') }}" class="btn-sipinjam-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Form Ajukan Pinjam</span>
        </a>
    </div>

    <!-- Filter & Pencarian -->
    <div class="sipinjam-card mb-4 p-3">
        <form method="GET" action="{{ route('user.barang.index') }}" class="row g-2 align-items-center">
            <!-- Search -->
            <div class="col-12 col-md-5">
                <div class="input-icon-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                           name="search" 
                           class="form-control-custom" 
                           placeholder="Cari nama alat, spesifikasi, atau kode..." 
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filter Kategori -->
            <div class="col-12 col-md-4">
                <select name="kategori_id" class="form-control-custom" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn-sipinjam-primary w-100">
                    <i class="bi bi-filter"></i>
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['search', 'kategori_id']))
                    <a href="{{ route('user.barang.index') }}" class="btn-sipinjam-outline" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grid Kartu Alat Laboratorium (3 Kolom) -->
    <div class="row g-3 mb-4">
        @forelse ($barangs as $barang)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="sipinjam-card h-100 d-flex flex-column justify-content-between p-4">
                    <div>
                        <!-- Header Kartu: Kategori & Status Stok -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-category">
                                {{ $barang->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                            @if ($barang->stok_tersedia > 0)
                                <span class="status-pill status-tersedia">
                                    Tersedia {{ $barang->stok_tersedia }}
                                </span>
                            @else
                                <span class="status-pill status-habis">
                                    Stok Habis
                                </span>
                            @endif
                        </div>

                        <!-- Info Alat -->
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="stat-icon-box stat-icon-teal stat-icon-sm shrink-0">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <h3 class="fs-6 fw-bold text-dark mb-1">{{ $barang->nama_barang }}</h3>
                                <span class="text-secondary small">Kode: {{ $barang->kode_barang }}</span>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <p class="text-secondary small mb-3" style="line-height: 1.5; min-height: 42px;">
                            {{ Str::limit($barang->deskripsi ?: 'Peralatan standar praktikum di laboratorium terpadu.', 90) }}
                        </p>
                    </div>

                    <!-- Footer Kartu: Tombol Aksi Pinjam -->
                    <div class="pt-3 border-top mt-2">
                        @if ($barang->stok_tersedia > 0)
                            <a href="{{ route('user.peminjaman.create', ['barang_id' => $barang->id]) }}" class="btn-sipinjam-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Pinjam Alat Ini</span>
                            </a>
                        @else
                            <button class="btn btn-secondary w-100 disabled" disabled>
                                <span>Tidak Dapat Dipinjam</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="sipinjam-card text-center py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                    <h4 class="fs-6 fw-bold text-dark">Alat Tidak Ditemukan</h4>
                    <p class="text-secondary small mb-0">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($barangs->hasPages())
        <div class="d-flex justify-content-center">
            {{ $barangs->links() }}
        </div>
    @endif

</div>
@endsection
