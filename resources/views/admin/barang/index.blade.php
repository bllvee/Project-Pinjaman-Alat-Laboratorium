@extends('admin.layouts.app')

@section('title', 'Data Barang Lab')

@section('content')
<div class="barang-index-page">

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">Data Barang Laboratorium</h1>
            <p class="page-subtitle">Kelola inventaris, alokasi stok, dan ketersediaan peralatan lab.</p>
        </div>
        <a href="{{ route('admin.barang.create') }}" class="btn-sipinjam-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Barang</span>
        </a>
    </div>

    <!-- Filter & Pencarian -->
    <div class="sipinjam-card mb-4 p-3">
        <form method="GET" action="{{ route('admin.barang.index') }}" class="row g-2 align-items-center">
            <!-- Input Cari -->
            <div class="col-12 col-md-5">
                <div class="input-icon-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                           name="search" 
                           class="form-control-custom" 
                           placeholder="Cari nama atau kode barang..." 
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Dropdown Filter Kategori -->
            <div class="col-12 col-md-4">
                <select name="kategori_id" class="form-control-custom" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
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
                    <a href="{{ route('admin.barang.index') }}" class="btn-sipinjam-outline" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data Barang -->
    <div class="sipinjam-table-card">
        <div class="card-header-bar">
            <div>
                <h3 class="mb-0 fs-5 fw-bold text-dark">Daftar Inventaris Alat</h3>
                <span class="text-secondary small">Total {{ $barangs->total() }} barang terdata dalam sistem</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-sipinjam">
                <thead>
                    <tr>
                        <th style="width: 14%;">KODE</th>
                        <th style="width: 32%;">NAMA BARANG</th>
                        <th style="width: 18%;">KATEGORI</th>
                        <th class="text-center" style="width: 12%;">STOK TOTAL</th>
                        <th class="text-center" style="width: 12%;">TERSEDIA</th>
                        <th style="width: 12%;">STATUS</th>
                        <th class="text-end" style="width: 12%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barangs as $barang)
                        <tr>
                            <!-- Kode Barang -->
                            <td>
                                <span class="badge-pill" style="background-color: var(--bg-page); border: 1px solid var(--border-color); color: var(--text-main); font-weight: 600;">
                                    {{ $barang->kode_barang }}
                                </span>
                            </td>

                            <!-- Nama Barang & Deskripsi -->
                            <td>
                                <div class="item-td-cell">
                                    <span class="item-name">{{ $barang->nama_barang }}</span>
                                    @if ($barang->deskripsi)
                                        <span class="item-code text-truncate" style="max-width: 280px;" title="{{ $barang->deskripsi }}">
                                            {{ Str::limit($barang->deskripsi, 60) }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td>
                                <span class="text-secondary fw-medium">
                                    {{ $barang->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>

                            <!-- Stok Total -->
                            <td class="text-center">
                                <span class="fw-semibold text-dark">{{ $barang->stok_total }}</span>
                            </td>

                            <!-- Stok Tersedia -->
                            <td class="text-center">
                                <span class="fw-bold {{ $barang->stok_tersedia > 0 ? 'text-teal' : 'text-danger' }}">
                                    {{ $barang->stok_tersedia }}
                                </span>
                            </td>

                            <!-- Status Ketersediaan -->
                            <td>
                                @if ($barang->stok_tersedia > 0)
                                    <span class="status-pill status-tersedia">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="status-pill status-habis">
                                        Habis
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi Edit & Hapus -->
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.barang.edit', $barang->id) }}" 
                                       class="btn-sipinjam-icon" 
                                       title="Edit Barang">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.barang.destroy', $barang->id) }}" 
                                          method="POST" 
                                          class="d-inline m-0"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang {{ $barang->nama_barang }}? Data peminjaman terkait mungkin akan terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sipinjam-icon btn-danger-icon" title="Hapus Barang">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-box fs-1 d-block mb-2 text-muted"></i>
                                <span class="fw-medium">Tidak ada data barang yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($barangs->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $barangs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
