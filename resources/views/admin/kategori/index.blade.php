@extends('admin.layouts.app')

@section('title', 'Kategori Alat Lab')

@section('content')
<div class="kategori-index-page">

    <!-- Header Halaman & Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">Kategori Alat</h1>
            <p class="page-subtitle">Kelola klasifikasi alat laboratorium untuk pengorganisasian yang lebih baik.</p>
        </div>
        <button type="button" class="btn-sipinjam-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Tabel Kategori Alat (Full Width Card) -->
    <div class="sipinjam-table-card">
        <div class="table-responsive">
            <table class="table-sipinjam">
                <thead>
                    <tr>
                        <th style="width: 45%;">NAMA KATEGORI</th>
                        <th style="width: 20%;">JUMLAH BARANG</th>
                        <th style="width: 20%;">TERAKHIR UPDATE</th>
                        <th class="text-end" style="width: 15%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $iconClasses = ['stat-icon-blue', 'stat-icon-teal', 'stat-icon-yellow', 'stat-icon-red'];
                        $iconSymbols = ['bi-binoculars-fill', 'bi-boxes', 'bi-hourglass-top', 'bi-cpu-fill', 'bi-droplet-fill'];
                    @endphp

                    @forelse ($kategoris as $index => $kat)
                        @php
                            $colorClass = $iconClasses[$index % count($iconClasses)];
                            $iconSymbol = $iconSymbols[$index % count($iconSymbols)];
                        @endphp
                        <tr>
                            <!-- Kolom Nama & Deskripsi Kategori -->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon-box {{ $colorClass }} stat-icon-sm shrink-0">
                                        <i class="bi {{ $iconSymbol }}"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark fs-6 lh-sm mb-1">{{ $kat->nama_kategori }}</span>
                                        <span class="text-secondary small">
                                            {{ $kat->deskripsi ?: 'Klasifikasi instrumen laboratorium' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Jumlah Barang -->
                            <td>
                                <span class="badge-count">
                                    {{ $kat->barangs_count }} Barang
                                </span>
                            </td>

                            <!-- Kolom Terakhir Update -->
                            <td>
                                <span class="text-secondary fw-medium">
                                    {{ $kat->updated_at ? $kat->updated_at->translatedFormat('d M Y') : '-' }}
                                </span>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- Tombol Edit (Modal Trigger) -->
                                    <button type="button" 
                                            class="btn-sipinjam-icon" 
                                            title="Edit Kategori"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditKategori{{ $kat->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.kategori.destroy', $kat->id) }}" 
                                          method="POST" 
                                          class="d-inline m-0"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $kat->nama_kategori }}? Seluruh alat dalam kategori ini mungkin terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sipinjam-icon btn-danger-icon" title="Hapus Kategori">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Edit Kategori -->
                                <div class="modal fade text-start" id="modalEditKategori{{ $kat->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $kat->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: var(--radius-card); border: 1px solid var(--border-color);">
                                            <form method="POST" action="{{ route('admin.kategori.update', $kat->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header border-bottom px-4 py-3">
                                                    <h5 class="modal-title fw-bold text-dark fs-5" id="modalEditLabel{{ $kat->id }}">
                                                        Edit Kategori Alat
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label for="nama_kategori_{{ $kat->id }}" class="form-label-custom">Nama Kategori <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               id="nama_kategori_{{ $kat->id }}" 
                                                               name="nama_kategori" 
                                                               class="form-control-custom" 
                                                               value="{{ old('nama_kategori', $kat->nama_kategori) }}" 
                                                               required>
                                                    </div>
                                                    <div>
                                                        <label for="deskripsi_{{ $kat->id }}" class="form-label-custom">Deskripsi</label>
                                                        <textarea id="deskripsi_{{ $kat->id }}" 
                                                                  name="deskripsi" 
                                                                  rows="3" 
                                                                  class="form-control-custom" 
                                                                  placeholder="Keterangan singkat tentang instrumen pada kategori ini...">{{ old('deskripsi', $kat->deskripsi) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top px-4 py-3">
                                                    <button type="button" class="btn-sipinjam-outline" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn-sipinjam-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary">
                                <i class="bi bi-tags fs-1 d-block mb-2 text-muted"></i>
                                <span class="fw-medium">Belum ada kategori alat yang ditambahkan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: var(--radius-card); border: 1px solid var(--border-color);">
                <form method="POST" action="{{ route('admin.kategori.store') }}">
                    @csrf
                    <div class="modal-header border-bottom px-4 py-3">
                        <h5 class="modal-title fw-bold text-dark fs-5" id="modalTambahLabel">
                            Tambah Kategori Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="modal_nama_kategori" class="form-label-custom">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="modal_nama_kategori" 
                                   name="nama_kategori" 
                                   class="form-control-custom @error('nama_kategori') is-invalid @enderror" 
                                   placeholder="Contoh: Spektrometri, Mikroskopis, Biosains" 
                                   value="{{ old('nama_kategori') }}" 
                                   required>
                            @error('nama_kategori')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="modal_deskripsi" class="form-label-custom">Deskripsi</label>
                            <textarea id="modal_deskripsi" 
                                      name="deskripsi" 
                                      rows="3" 
                                      class="form-control-custom @error('deskripsi') is-invalid @enderror" 
                                      placeholder="Keterangan singkat tentang kelompok instrumen ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-top px-4 py-3">
                        <button type="button" class="btn-sipinjam-outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-sipinjam-primary">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
