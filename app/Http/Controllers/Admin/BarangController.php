<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
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

        $barangs = $query->orderBy('nama_barang', 'asc')->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('admin.barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('admin.barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok_total'  => 'required|integer|min:1',
            'deskripsi'   => 'nullable|string',
        ]);

        Barang::create([
            'kode_barang'   => $request->kode_barang,
            'nama_barang'   => $request->nama_barang,
            'kategori_id'   => $request->kategori_id,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('admin.barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok_total'  => 'required|integer|min:1',
            'deskripsi'   => 'nullable|string',
        ]);

        $selisih = $request->stok_total - $barang->stok_total;
        $stokTersediaBaru = max(0, $barang->stok_tersedia + $selisih);

        $barang->update([
            'kode_barang'   => $request->kode_barang,
            'nama_barang'   => $request->nama_barang,
            'kategori_id'   => $request->kategori_id,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $stokTersediaBaru,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil dihapus.');
    }
}
