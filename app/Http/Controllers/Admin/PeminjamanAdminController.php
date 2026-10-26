<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            })->orWhereHas('barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        $peminjamans = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $rekap = [
            'total'          => Peminjaman::count(),
            'dipinjam'       => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan'   => Peminjaman::where('status', 'dikembalikan')->count(),
            'total_denda'    => Peminjaman::sum('total_denda'),
            'denda_telat'    => Peminjaman::sum('denda_keterlambatan'),
            'biaya_perbaikan'=> Peminjaman::sum('biaya_perbaikan'),
        ];

        return view('admin.peminjaman.index', compact('peminjamans', 'rekap'));
    }

    public function detail(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'barang']);
        return view('admin.peminjaman.detail', compact('peminjaman'));
    }

    public function prosesKembali(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Transaksi ini sudah selesai dikembalikan.');
        }

        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_saat_kembali'   => 'required|in:baik,rusak',
            'catatan'                => 'nullable|string',
        ]);

        $tglRencana = Carbon::parse($peminjaman->tanggal_rencana_kembali)->startOfDay();
        $tglAktual  = Carbon::parse($request->tanggal_kembali_aktual)->startOfDay();

        // Hitung keterlambatan (hari)
        $hariTerlambat = 0;
        if ($tglAktual->gt($tglRencana)) {
            $hariTerlambat = $tglRencana->diffInDays($tglAktual);
        }

        // ATURAN 1: Keterlambatan > 3 hari dikenakan denda Rp2.000 / hari
        $dendaKeterlambatan = ($hariTerlambat > 3) ? ($hariTerlambat * 2000) : 0;

        // ATURAN 2: Kondisi rusak dikenakan biaya perbaikan Rp10.000
        $biayaPerbaikan = ($request->kondisi_saat_kembali === 'rusak') ? 10000 : 0;

        $totalDenda = $dendaKeterlambatan + $biayaPerbaikan;

        $peminjaman->update([
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
            'kondisi_saat_kembali'   => $request->kondisi_saat_kembali,
            'hari_terlambat'         => $hariTerlambat,
            'denda_keterlambatan'    => $dendaKeterlambatan,
            'biaya_perbaikan'        => $biayaPerbaikan,
            'total_denda'            => $totalDenda,
            'status'                 => 'dikembalikan',
            'catatan'                => $request->catatan,
        ]);

        // Kembalikan stok tersedia barang
        $peminjaman->barang()->increment('stok_tersedia');

        return redirect()->route('admin.peminjaman.detail', $peminjaman)
            ->with('success', 'Pengembalian berhasil diproses! Total denda: Rp ' . number_format($totalDenda, 0, ',', '.'));
    }
}
