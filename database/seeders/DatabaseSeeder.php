<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Peminjaman;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin & Mahasiswa
        $admin = User::create([
            'name'     => 'Petugas Laboratorium',
            'username' => 'admin',
            'email'    => 'admin@lab.ac.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'nim'      => null,
            'prodi'    => 'Laboratorium Terpadu',
            'no_hp'    => '081122334455',
        ]);

        $mhs1 = User::create([
            'name'     => 'Lian Rosyid Alamzah',
            'username' => '24051130084',
            'email'    => 'lian@student.ac.id',
            'password' => Hash::make('123456'),
            'role'     => 'user',
            'nim'      => '24051130084',
            'prodi'    => 'Teknologi Informasi',
            'no_hp'    => '081234560001',
        ]);

        $mhs2 = User::create([
            'name'     => 'Aditya Danu Marizta',
            'username' => '24051130094',
            'email'    => 'aditya@student.ac.id',
            'password' => Hash::make('123456'),
            'role'     => 'user',
            'nim'      => '24051130094',
            'prodi'    => 'Teknologi Informasi',
            'no_hp'    => '081234560002',
        ]);

        $mhs3 = User::create([
            'name'     => 'Muhammad Ghafur Amanu Putra Warnada',
            'username' => '24051130103',
            'email'    => 'ghafur@student.ac.id',
            'password' => Hash::make('123456'),
            'role'     => 'user',
            'nim'      => '24051130103',
            'prodi'    => 'Teknologi Informasi',
            'no_hp'    => '081234560003',
        ]);

        // 2. Kategori Barang
        $katElektronik = Kategori::create([
            'nama_kategori' => 'Elektronik',
            'deskripsi'     => 'Perangkat dan instrumen elektronik laboratorium',
        ]);

        $katOptik = Kategori::create([
            'nama_kategori' => 'Optik',
            'deskripsi'     => 'Peralatan optik, lensa, dan mikroskop',
        ]);

        $katKomputer = Kategori::create([
            'nama_kategori' => 'Komputer',
            'deskripsi'     => 'Perangkat keras komputasi dan praktikum',
        ]);

        // 3. Data Barang Lab
        $brg1 = Barang::create([
            'kode_barang'   => 'ALT-001',
            'nama_barang'   => 'Multimeter Digital',
            'kategori_id'   => $katElektronik->id,
            'stok_total'    => 10,
            'stok_tersedia' => 10,
            'deskripsi'     => 'Multimeter digital presisi tinggi untuk pengukuran arus dan tegangan.',
        ]);

        $brg2 = Barang::create([
            'kode_barang'   => 'ALT-002',
            'nama_barang'   => 'Osiloskop Digital 100MHz',
            'kategori_id'   => $katElektronik->id,
            'stok_total'    => 3,
            'stok_tersedia' => 3,
            'deskripsi'     => 'Osiloskop dual-channel untuk analisa sinyal gelombang.',
        ]);

        $brg3 = Barang::create([
            'kode_barang'   => 'ALT-003',
            'nama_barang'   => 'Soldering Station Kit',
            'kategori_id'   => $katElektronik->id,
            'stok_total'    => 8,
            'stok_tersedia' => 8,
            'deskripsi'     => 'Perangkat solder dengan kontrol temperatur konstan.',
        ]);

        $brg4 = Barang::create([
            'kode_barang'   => 'ALT-004',
            'nama_barang'   => 'Mikroskop Binokuler',
            'kategori_id'   => $katOptik->id,
            'stok_total'    => 5,
            'stok_tersedia' => 4, // 1 sedang dipinjam
            'deskripsi'     => 'Mikroskop pembesaran optik hingga 1000x.',
        ]);

        $brg5 = Barang::create([
            'kode_barang'   => 'ALT-005',
            'nama_barang'   => 'Laptop Praktikum Core i7',
            'kategori_id'   => $katKomputer->id,
            'stok_total'    => 6,
            'stok_tersedia' => 6,
            'deskripsi'     => 'Laptop workstation untuk praktikum simulasi jaringan.',
        ]);

        // 4. Data Sampel Pengujian Kasus UTS
        // Kasus 1: Pengembalian tepat waktu, denda 0, kondisi baik
        Peminjaman::create([
            'user_id'                 => $mhs1->id,
            'barang_id'               => $brg1->id,
            'tanggal_pinjam'          => '2026-10-01',
            'tanggal_rencana_kembali' => '2026-10-03',
            'tanggal_kembali_aktual'  => '2026-10-03',
            'kondisi_saat_kembali'    => 'baik',
            'hari_terlambat'          => 0,
            'denda_keterlambatan'     => 0,
            'biaya_perbaikan'         => 0,
            'total_denda'             => 0,
            'status'                  => 'dikembalikan',
            'catatan'                 => 'Pengembalian tepat waktu, kondisi prima.',
        ]);

        // Kasus 2: Terlambat 5 hari (> 3 hari) dan rusak -> Denda 5 x 2000 = 10.000 + Biaya servis 10.000 = 20.000
        Peminjaman::create([
            'user_id'                 => $mhs2->id,
            'barang_id'               => $brg2->id,
            'tanggal_pinjam'          => '2026-10-01',
            'tanggal_rencana_kembali' => '2026-10-05',
            'tanggal_kembali_aktual'  => '2026-10-10',
            'kondisi_saat_kembali'    => 'rusak',
            'hari_terlambat'          => 5,
            'denda_keterlambatan'     => 10000,
            'biaya_perbaikan'         => 10000,
            'total_denda'             => 20000,
            'status'                  => 'dikembalikan',
            'catatan'                 => 'Kabel probe rusak dan terlambat 5 hari.',
        ]);

        // Kasus 3: Sedang dipinjam
        Peminjaman::create([
            'user_id'                 => $mhs3->id,
            'barang_id'               => $brg4->id,
            'tanggal_pinjam'          => '2026-10-15',
            'tanggal_rencana_kembali' => '2026-10-20',
            'status'                  => 'dipinjam',
            'catatan'                 => 'Praktikum biologi optik komputasi.',
        ]);
    }
}
