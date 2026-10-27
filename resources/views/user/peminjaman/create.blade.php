@extends('user.layouts.app')

@section('title', 'Ajukan Peminjaman Alat')

@section('content')
<div class="max-w-3xl mx-auto flex flex-col gap-space-lg">
    <!-- Header -->
    <div class="flex items-center justify-between pb-space-xs border-b border-outline-variant/20">
        <div class="flex items-center gap-space-sm">
            <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[24px]">assignment_add</span>
            </div>
            <div>
                <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight">Form Pengajuan Peminjaman Alat</h1>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Laboratorium Manajemen Sistem Informasi</p>
            </div>
        </div>
        <a href="{{ route('user.dashboard') }}" 
           class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md flex items-center gap-space-xs transition-colors border border-outline-variant/30">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Identitas Mahasiswa Terkunci -->
    <div class="p-space-md rounded-xl bg-surface-container-low border border-outline-variant/30 shadow-sm flex flex-col gap-space-xs">
        <div class="flex items-center gap-space-xs text-primary font-label-sm text-label-sm uppercase tracking-wider mb-1">
            <span class="material-symbols-outlined text-[18px]">badge</span>
            <span>Identitas Peminjam (Tervalidasi Otomatis)</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm font-body-sm text-body-sm">
            <div class="p-2 rounded bg-surface-container border border-outline-variant/10">
                <span class="text-on-surface-variant block text-xs">Nama Mahasiswa:</span>
                <strong class="text-on-surface">{{ auth()->user()->name }}</strong>
            </div>
            <div class="p-2 rounded bg-surface-container border border-outline-variant/10">
                <span class="text-on-surface-variant block text-xs">NIM:</span>
                <code class="text-tertiary font-bold">{{ auth()->user()->nim ?? auth()->user()->username }}</code>
            </div>
            <div class="p-2 rounded bg-surface-container border border-outline-variant/10">
                <span class="text-on-surface-variant block text-xs">Program Studi:</span>
                <span class="text-on-surface">{{ auth()->user()->prodi ?? 'Teknologi Informasi' }}</span>
            </div>
            <div class="p-2 rounded bg-surface-container border border-outline-variant/10">
                <span class="text-on-surface-variant block text-xs">No. Telepon / WA:</span>
                <span class="text-on-surface">{{ auth()->user()->no_hp ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Form Pengajuan Pinjam -->
    <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl p-space-lg shadow-md">
        <form method="POST" action="{{ route('user.peminjaman.store') }}" class="space-y-space-md">
            @csrf

            <!-- Pilih Alat -->
            <div class="flex flex-col gap-1">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider" for="barang_id">
                    Pilih Peralatan Laboratorium <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <select class="w-full appearance-none bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg py-space-sm pl-space-md pr-10 border border-outline-variant/30 focus:outline-none focus:border-primary focus:bg-surface-container cursor-pointer transition-all" 
                            id="barang_id" 
                            name="barang_id" 
                            required>
                        <option value="">-- Pilih Alat yang Ingin Dipinjam --</option>
                        @foreach ($barangs as $b)
                            <option value="{{ $b->id }}" {{ (old('barang_id') == $b->id || $selectedBarangId == $b->id) ? 'selected' : '' }}>
                                {{ $b->nama_barang }} [{{ $b->kode_barang }}] — Tersedia: {{ $b->stok_tersedia }} unit
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">expand_more</span>
                </div>
                @error('barang_id')
                    <span class="font-body-sm text-body-sm text-error mt-0.5">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Pinjam & Tanggal Rencana Kembali -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider" for="tanggal_pinjam">
                        Tanggal Pinjam <span class="text-error">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg py-space-sm px-space-md border border-outline-variant/30 focus:outline-none focus:border-primary focus:bg-surface-container transition-all" 
                               id="tanggal_pinjam" 
                               name="tanggal_pinjam" 
                               required 
                               type="date" 
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"/>
                    </div>
                    @error('tanggal_pinjam')
                        <span class="font-body-sm text-body-sm text-error mt-0.5">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider" for="tanggal_rencana_kembali">
                        Rencana Tanggal Kembali <span class="text-error">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg py-space-sm px-space-md border border-outline-variant/30 focus:outline-none focus:border-primary focus:bg-surface-container transition-all" 
                               id="tanggal_rencana_kembali" 
                               min="{{ date('Y-m-d') }}" 
                               name="tanggal_rencana_kembali" 
                               required 
                               type="date" 
                               value="{{ old('tanggal_rencana_kembali') }}"/>
                    </div>
                    @error('tanggal_rencana_kembali')
                        <span class="font-body-sm text-body-sm text-error mt-0.5">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Catatan Keperluan -->
            <div class="flex flex-col gap-1">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider" for="catatan">
                    Catatan / Keperluan Praktikum
                </label>
                <textarea class="w-full bg-surface-container-lowest text-on-surface placeholder:text-outline-variant font-body-md text-body-md rounded-lg p-space-md border border-outline-variant/30 focus:outline-none focus:border-primary focus:bg-surface-container transition-all" 
                          id="catatan" 
                          name="catatan" 
                          placeholder="Contoh: Praktikum Jaringan / Simulasi Rangkaian Tertanam..." 
                          rows="3">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <span class="font-body-sm text-body-sm text-error mt-0.5">{{ $message }}</span>
                @enderror
            </div>

            <!-- Info Ketentuan UTS -->
            <div class="rounded-xl bg-surface-container p-space-md flex flex-col gap-space-xs border border-outline-variant/20">
                <div class="flex items-center gap-space-xs text-primary font-label-md text-label-md font-semibold">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <span>Peraturan Pengembalian Alat (Aturan UTS MSI):</span>
                </div>
                <ul class="list-disc list-inside text-body-sm text-on-surface-variant space-y-1">
                    <li>Keterlambatan pengembalian <strong>lebih dari 3 hari</strong> dari rencana kembali dikenakan denda <strong>Rp2.000 / hari</strong>.</li>
                    <li>Peralatan yang dikembalikan dalam kondisi <strong>rusak</strong> dikenakan biaya perbaikan tetap <strong>Rp10.000</strong>.</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-space-sm pt-space-md border-t border-outline-variant/20">
                <button type="submit" 
                        class="flex-1 bg-inverse-surface hover:bg-white text-inverse-on-surface font-label-lg text-label-lg py-space-sm px-space-lg rounded-xl flex items-center justify-center gap-space-xs shadow-md transition-all active:scale-[0.99]">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>Ajukan Peminjaman Sekarang</span>
                </button>
                <a href="{{ route('user.dashboard') }}" 
                   class="px-space-lg py-space-sm rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors border border-outline-variant/30">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
