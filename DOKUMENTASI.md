# Dokumentasi UTS Praktikum Manajemen Sistem Informasi
## Sistem Peminjaman Alat Laboratorium (Soal 1) &mdash; Laravel MVC Multi-Role

**Kelompok 6**
| Nama | NIM |
|---|---|
| Lian Rosyid Alamzah | 24051130084 |
| Aditya Danu Marizta | 24051130094 |
| Muhammad Ghafur Amanu Putra Warnada | 24051130103 |

Teknologi: **Laravel 12 (PHP MVC)** + MySQL + Blade Templating + Bootstrap 5

---

## 1. Deskripsi Sistem & Pemisahan 2 Tampilan (Role)

Sistem Informasi Manajemen Peminjaman Laboratorium ini dirancang dengan arsitektur **MVC (Model-View-Controller)** menggunakan framework **Laravel 12**. Sistem memisahkan hak akses dan antarmuka menjadi **2 Tampilan**:

1. **Tampilan Admin (Petugas Lab)**:
   - Dashboard analitik & ringkasan statistik (total barang, kategori, mahasiswa, transaksi aktif, serta rekap denda).
   - CRUD Data Barang Lab (kode barang, nama, kategori, stok total, dan stok tersedia).
   - CRUD Data Kategori Barang.
   - Manajemen Transaksi Peminjaman: Memeriksa detail transaksi, memverifikasi kondisi fisik saat pengembalian (baik/rusak), serta perhitungan otomatis denda.
2. **Tampilan User (Mahasiswa)**:
   - Beranda Mahasiswa dengan kartu status pinjaman aktif, ringkasan denda, dan notifikasi batas waktu.
   - Katalog Barang Laboratorium dengan pencarian nama/kode dan filter kategori serta ketersediaan stok.
   - Form Pengajuan Pinjam Alat mandiri (identitas mahasiswa otomatis terkunci ke akun login).
   - Riwayat Peminjaman Pribadi beserta rincian denda keterlambatan dan biaya perbaikan.

---

## 2. Aturan Bisnis UTS (Sesuai Ketentuan Soal)

1. **Denda Keterlambatan**: Jika pengembalian alat terlambat **lebih dari 3 hari** dari tanggal rencana kembali, dikenakan denda **Rp2.000 per hari** keterlambatan.
2. **Biaya Perbaikan**: Jika alat dikembalikan dalam kondisi **rusak**, dikenakan biaya perbaikan tetap sebesar **Rp10.000**.
3. **Kalkulasi Otomatis**: Perhitungan denda dan biaya perbaikan dilakukan secara otomatis oleh sistem di `PeminjamanAdminController.php` saat memproses pengembalian alat, sehingga menghilangkan potensi kesalahan manual.
4. **Validasi Stok**: Stok unit barang otomatis berkurang saat dipinjam dan bertambah kembali setelah alat berstatus dikembalikan.

---

## 3. Struktur File Project (Laravel MVC Sesuai Referensi)

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardAdminController.php
│   │   │   │   ├── BarangController.php
│   │   │   │   ├── KategoriController.php
│   │   │   │   └── PeminjamanAdminController.php
│   │   │   ├── User/
│   │   │   │   ├── DashboardUserController.php
│   │   │   │   └── PeminjamanUserController.php
│   │   │   └── Auth/
│   │   │       └── AuthController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Barang.php
│       ├── Kategori.php
│       └── Peminjaman.php
├── database/
│   ├── migrations/
│   │   ├── 2026_10_26_000001_create_kategoris_table.php
│   │   ├── 2026_10_26_000002_create_barangs_table.php
│   │   ├── 2026_10_26_000003_add_role_to_users_table.php
│   │   └── 2026_10_26_000004_create_peminjamans_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   ├── layouts/
│       │   │   └── app.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── barang/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── kategori/
│       │   │   └── index.blade.php
│       │   └── peminjaman/
│       │       ├── index.blade.php
│       │       └── detail.blade.php
│       └── user/
│           ├── layouts/
│           │   └── app.blade.php
│           ├── dashboard.blade.php
│           ├── barang/
│           │   └── index.blade.php
│           └── peminjaman/
│               ├── index.blade.php
│               └── create.blade.php
└── routes/
    └── web.php
```

---

## 4. Akun Demo Pengujian

| Peran | Username / NIM | Password | Keterangan |
|---|---|---|---|
| **Admin** | `admin` | `admin123` | Petugas Laboratorium (Akses Admin Panel) |
| **Mahasiswa 1** | `24051130084` | `123456` | Lian Rosyid Alamzah |
| **Mahasiswa 2** | `24051130094` | `123456` | Aditya Danu Marizta |
| **Mahasiswa 3** | `24051130103` | `123456` | Muhammad Ghafur Amanu Putra Warnada |

*(Pada halaman login sudah disediakan tombol pintas untuk mengisi akun uji coba secara langsung).*

---

## 5. Cara Menjalankan Project

### Opsi A (Melalui XAMPP Langsung):
1. Pastikan **Apache** dan **MySQL** aktif di XAMPP Control Panel.
2. Buka browser dan akses:
   👉 **`http://localhost/UTS%20MSI%20Kelompok%206/`**
   *(Sistem akan otomatis mengarahkan ke halaman login Laravel)*.

### Opsi B (Melalui Artisan Serve):
1. Buka Terminal / PowerShell di folder project:
   ```bash
   php artisan serve
   ```
2. Akses melalui browser:
   👉 **`http://127.0.0.1:8000`**

---

## 6. Pembagian Tugas Kelompok

| Anggota | Tanggung Jawab Modul |
|---|---|
| **Lian Rosyid Alamzah** (24051130084) | Perancangan Database Migrations, Seeder, dan Modul Admin: **Barang & Kategori** (`BarangController`, `KategoriController`, Views Barang & Kategori). |
| **Aditya Danu Marizta** (24051130094) | Modul Transaksi Inti & Business Rules: **Peminjaman & Pengembalian Admin** (`PeminjamanAdminController`, Views Peminjaman, Logika Denda & Servis). |
| **Muhammad Ghafur Amanu Putra Warnada** (24051130103) | Modul Autentikasi & Portal User: **Auth & Mahasiswa** (`AuthController`, `RoleMiddleware`, `DashboardUserController`, `PeminjamanUserController`, Views User & Styling CSS). |
