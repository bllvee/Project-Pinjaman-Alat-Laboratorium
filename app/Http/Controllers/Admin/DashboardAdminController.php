<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalMahasiswa     = User::where('role', 'user')->count();
        $totalBarang        = Barang::count();
        $totalKategori      = Kategori::count();
        $sedangDipinjam     = Peminjaman::where('status', 'dipinjam')->count();
        $totalDenda         = Peminjaman::sum('total_denda');
        $dendaKeterlambatan = Peminjaman::sum('denda_keterlambatan');
        $biayaPerbaikan     = Peminjaman::sum('biaya_perbaikan');

        $transaksiTerbaru = Peminjaman::with(['user', 'barang'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalBarang',
            'totalKategori',
            'sedangDipinjam',
            'totalDenda',
            'dendaKeterlambatan',
            'biayaPerbaikan',
            'transaksiTerbaru'
        ));
    }
}
