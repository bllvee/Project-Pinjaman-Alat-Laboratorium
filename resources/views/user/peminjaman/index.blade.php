@extends('user.layouts.app')

@section('title', 'Riwayat Peminjaman Pribadi')

@section('content')
<div class="flex flex-col w-full gap-space-lg">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md pb-space-xs border-b border-outline-variant/20">
        <div class="flex items-center gap-space-sm">
            <span class="p-2 rounded-xl bg-surface-container-high text-primary flex items-center justify-center border border-outline-variant/30">
                <span class="material-symbols-outlined text-[24px]">history</span>
            </span>
            <div>
                <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight">Riwayat Peminjaman Pribadi</h1>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Rincian seluruh transaksi peminjaman alat lab beserta kalkulasi denda jika ada</p>
            </div>
        </div>
        <a href="{{ route('user.peminjaman.create') }}" 
           class="flex items-center gap-space-xs px-4 py-2 rounded-xl bg-inverse-surface hover:bg-white text-inverse-on-surface font-label-md text-label-md shadow-sm transition-all transform active:scale-95">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            <span>Pinjam Alat Baru</span>
        </a>
    </div>

    <!-- 4 Telemetry Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
        <div class="p-space-md rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-center justify-between shadow-sm">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">TOTAL TRANSAKSI</span>
                <span class="font-headline-xl text-headline-xl text-on-surface tracking-tight font-bold mt-1">
                    {{ $rekap['total'] }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface border border-outline-variant/30">
                <span class="material-symbols-outlined text-[22px]">receipt_long</span>
            </div>
        </div>

        <div class="p-space-md rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-center justify-between shadow-sm">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-tertiary uppercase tracking-wider">SEDANG DIPINJAM</span>
                <span class="font-headline-xl text-headline-xl text-tertiary tracking-tight font-bold mt-1">
                    {{ $rekap['dipinjam'] }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-tertiary-container/20 flex items-center justify-center text-tertiary border border-tertiary/20">
                <span class="material-symbols-outlined text-[22px]">timelapse</span>
            </div>
        </div>

        <div class="p-space-md rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-center justify-between shadow-sm">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider">SELESAI KEMBALI</span>
                <span class="font-headline-xl text-headline-xl text-primary tracking-tight font-bold mt-1">
                    {{ $rekap['dikembalikan'] }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-primary-container/20 flex items-center justify-center text-primary border border-primary/20">
                <span class="material-symbols-outlined text-[22px]">task_alt</span>
            </div>
        </div>

        <div class="p-space-md rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-center justify-between shadow-sm">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">TOTAL DENDA DITAGIH</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="font-label-md text-label-md {{ $rekap['total_denda'] > 0 ? 'text-tertiary' : 'text-primary' }}">Rp</span>
                    <span class="font-headline-xl text-headline-xl {{ $rekap['total_denda'] > 0 ? 'text-tertiary' : 'text-primary' }} tracking-tight font-bold">
                        {{ number_format($rekap['total_denda'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary border border-primary/20">
                <span class="material-symbols-outlined text-[22px]">payments</span>
            </div>
        </div>
    </div>

    <!-- Tab Filter -->
    <div class="p-space-md rounded-xl bg-surface-container-low border border-outline-variant/30 shadow-sm flex items-center justify-between gap-space-md">
        <span class="font-label-lg text-label-lg text-on-surface">Filter Riwayat Peminjaman:</span>
        <div class="flex items-center gap-1 bg-surface-container p-1 rounded-lg border border-outline-variant/20">
            <a href="{{ route('user.peminjaman.index', ['status' => 'semua']) }}" 
               class="px-3 py-1.5 rounded-md font-label-sm text-label-sm transition-all {{ request('status', 'semua') === 'semua' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                Semua
            </a>
            <a href="{{ route('user.peminjaman.index', ['status' => 'dipinjam']) }}" 
               class="px-3 py-1.5 rounded-md font-label-sm text-label-sm transition-all {{ request('status') === 'dipinjam' ? 'bg-tertiary-container/30 text-tertiary font-semibold shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                Sedang Dipinjam
            </a>
            <a href="{{ route('user.peminjaman.index', ['status' => 'dikembalikan']) }}" 
               class="px-3 py-1.5 rounded-md font-label-sm text-label-sm transition-all {{ request('status') === 'dikembalikan' ? 'bg-primary/20 text-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                Sudah Dikembalikan
            </a>
        </div>
    </div>

    <!-- Rows of Personal Loans -->
    <div class="flex flex-col gap-space-xs">
        <!-- Columns Header -->
        <div class="hidden md:grid grid-cols-12 gap-space-md px-space-md py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
            <div class="col-span-1">No</div>
            <div class="col-span-3">Barang & Identitas</div>
            <div class="col-span-2">Jadwal Pinjam</div>
            <div class="col-span-2">Kembali & Kondisi</div>
            <div class="col-span-2">Rincian Denda</div>
            <div class="col-span-2 text-right">Status</div>
        </div>

        <div class="flex flex-col gap-space-sm">
            @forelse ($peminjamans as $index => $r)
                <div class="p-space-md rounded-xl bg-surface-container-low hover:bg-surface-container transition-all flex flex-col md:grid md:grid-cols-12 gap-space-md items-start md:items-center shadow-sm border border-outline-variant/20">
                    <!-- Column 1: No -->
                    <div class="col-span-1 font-code-sm text-code-sm text-on-surface-variant font-semibold">
                        #{{ sprintf('%02d', $peminjamans->firstItem() + $index) }}
                    </div>

                    <!-- Column 2: Barang & Identitas -->
                    <div class="col-span-3 flex flex-col min-w-0 w-full">
                        <span class="font-label-lg text-label-lg text-on-surface truncate">{{ $r->barang->nama_barang }}</span>
                        <div class="flex items-center gap-space-xs mt-0.5">
                            <span class="font-code-sm text-code-sm text-tertiary bg-tertiary-container/10 px-1.5 py-0.5 rounded border border-tertiary/20">{{ $r->barang->kode_barang }}</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant truncate">{{ $r->barang->kategori->nama_kategori ?? 'Umum' }}</span>
                        </div>
                    </div>

                    <!-- Column 3: Jadwal Pinjam -->
                    <div class="col-span-2 flex flex-col font-body-sm text-body-sm text-on-surface-variant w-full">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-tertiary">arrow_outward</span>
                            <span>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d-m-Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-on-surface">
                            <span class="material-symbols-outlined text-[14px] text-on-surface-variant">event_busy</span>
                            <span>{{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->format('d-m-Y') }}</span>
                        </div>
                    </div>

                    <!-- Column 4: Kembali Aktual & Kondisi -->
                    <div class="col-span-2 flex flex-col font-body-sm text-body-sm text-on-surface-variant w-full">
                        <span>Aktual: {{ $r->tanggal_kembali_aktual ? \Carbon\Carbon::parse($r->tanggal_kembali_aktual)->format('d-m-Y') : '-' }}</span>
                        @if ($r->kondisi_saat_kembali)
                            <div class="flex items-center gap-1 mt-0.5">
                                <span class="font-code-sm text-code-sm px-1.5 py-0.5 rounded {{ $r->kondisi_saat_kembali === 'rusak' ? 'bg-error-container/20 text-error border border-error/20' : 'bg-surface-container text-on-surface-variant' }}">
                                    Kondisi: {{ ucfirst($r->kondisi_saat_kembali) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Column 5: Rincian Denda -->
                    <div class="col-span-2 flex flex-col font-code-sm text-code-sm w-full">
                        @if ($r->total_denda > 0)
                            <span class="text-tertiary font-bold">Total: Rp {{ number_format($r->total_denda, 0, ',', '.') }}</span>
                            <span class="text-outline text-xs">
                                Telat: Rp {{ number_format($r->denda_keterlambatan, 0, ',', '.') }} • Servis: Rp {{ number_format($r->biaya_perbaikan, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-outline">Denda: Rp 0</span>
                        @endif
                    </div>

                    <!-- Column 6: Status -->
                    <div class="col-span-2 flex md:justify-end w-full md:w-auto">
                        @if ($r->status === 'dipinjam')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                                Dipinjam
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm border border-primary/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                Selesai
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-space-xl rounded-xl bg-surface-container-low text-center text-on-surface-variant border border-outline-variant/20">
                    <span class="material-symbols-outlined text-5xl mb-2 text-outline">inbox</span>
                    <p class="font-headline-sm text-headline-sm text-on-surface mb-1">Belum Ada Riwayat</p>
                    <p class="font-body-sm text-body-sm">Anda belum memiliki riwayat peminjaman pada filter status ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($peminjamans->hasPages())
            <div class="p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center justify-between mt-space-sm text-on-surface">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan <strong class="text-on-surface">{{ $peminjamans->firstItem() }}</strong> - <strong class="text-on-surface">{{ $peminjamans->lastItem() }}</strong> dari <strong class="text-on-surface">{{ $peminjamans->total() }}</strong> total data
                </span>
                <div class="flex items-center gap-space-xs">
                    @if ($peminjamans->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg bg-surface-container text-outline opacity-50 cursor-not-allowed font-label-md text-label-md flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span> Sebelumnya
                        </span>
                    @else
                        <a href="{{ $peminjamans->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md flex items-center gap-1 transition-colors border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span> Sebelumnya
                        </a>
                    @endif

                    @if ($peminjamans->hasMorePages())
                        <a href="{{ $peminjamans->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md flex items-center gap-1 transition-colors border border-outline-variant/30">
                            Berikutnya <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg bg-surface-container text-outline opacity-50 cursor-not-allowed font-label-md text-label-md flex items-center gap-1">
                            Berikutnya <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
