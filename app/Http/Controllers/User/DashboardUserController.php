<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Barang;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $sedangDipinjam    = Peminjaman::where('user_id', $user->id)->where('status', 'dipinjam')->count();
        $peminjamanSelesai = Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count();
        $totalDenda        = Peminjaman::where('user_id', $user->id)->sum('total_denda');

        $peminjamanAktif = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_rencana_kembali', 'asc')
            ->get();

        $riwayatTerbaru = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'sedangDipinjam',
            'peminjamanSelesai',
            'totalDenda',
            'peminjamanAktif',
            'riwayatTerbaru'
        ));
    }
}
