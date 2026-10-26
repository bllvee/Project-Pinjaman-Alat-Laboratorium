<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Kategori;

class PeminjamanUserController extends Controller
{
    /**
     * Menampilkan katalog barang untuk user/mahasiswa.
     */
    public function barangIndex(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $barangs   = $query->orderBy('nama_barang', 'asc')->paginate(9)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('user.barang.index', compact('barangs', 'kategoris'));
    }

    /**
     * Menampilkan seluruh riwayat transaksi peminjaman milik user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Peminjaman::with('barang')->where('user_id', $user->id);

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $rekap = [
            'total'        => Peminjaman::where('user_id', $user->id)->count(),
            'dipinjam'     => Peminjaman::where('user_id', $user->id)->where('status', 'dipinjam')->count(),
            'dikembalikan' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
            'total_denda'  => Peminjaman::where('user_id', $user->id)->sum('total_denda'),
        ];

        return view('user.peminjaman.index', compact('peminjamans', 'rekap'));
    }

    /**
     * Form pengajuan peminjaman barang oleh user.
     */
    public function create(Request $request)
    {
        $selectedBarangId = (int) $request->get('barang_id', 0);
        $barangs = Barang::where('stok_tersedia', '>', 0)->orderBy('nama_barang', 'asc')->get();

        return view('user.peminjaman.create', compact('barangs', 'selectedBarangId'));
    }

    /**
     * Simpan pengajuan peminjaman barang.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'barang_id'               => 'required|exists:barangs,id',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan'                 => 'nullable|string',
        ], [
            'tanggal_rencana_kembali.after_or_equal' => 'Tanggal rencana kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stok_tersedia <= 0) {
            return back()->with('error', 'Maaf, stok barang sedang habis.')->withInput();
        }

        Peminjaman::create([
            'user_id'                 => $user->id,
            'barang_id'               => $barang->id,
            'tanggal_pinjam'          => $request->tanggal_pinjam,
            'tanggal_rencana_kembali' => $request->tanggal_rencana_kembali,
            'status'                  => 'dipinjam',
            'catatan'                 => $request->catatan,
        ]);

        // Kurangi stok tersedia
        $barang->decrement('stok_tersedia');

        return redirect()->route('user.dashboard')
            ->with('success', 'Peminjaman alat ' . $barang->nama_barang . ' berhasil diajukan!');
    }
}
