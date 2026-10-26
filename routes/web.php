<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PeminjamanAdminController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\PeminjamanUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke halaman login atau dashboard sesuai status login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// RUTE KHUSUS ADMIN (ROLE: ADMIN)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // Manajemen Barang & Kategori
    Route::resource('barang', BarangController::class);
    Route::resource('kategori', KategoriController::class)->except(['create', 'show', 'edit']);

    // Manajemen Transaksi Peminjaman & Pengembalian
    Route::get('/peminjaman', [PeminjamanAdminController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanAdminController::class, 'detail'])->name('peminjaman.detail');
    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanAdminController::class, 'prosesKembali'])->name('peminjaman.kembali');
});

// ==========================================
// RUTE KHUSUS USER / MAHASISWA (ROLE: USER)
// ==========================================
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard Mahasiswa
    Route::get('/dashboard', [DashboardUserController::class, 'index'])->name('dashboard');

    // Katalog Barang
    Route::get('/barang', [PeminjamanUserController::class, 'barangIndex'])->name('barang.index');

    // Transaksi & Riwayat Peminjaman Pribadi
    Route::get('/peminjaman', [PeminjamanUserController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanUserController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanUserController::class, 'store'])->name('peminjaman.store');
});
