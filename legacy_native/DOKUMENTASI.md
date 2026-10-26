# Dokumentasi UTS Praktikum Manajemen Sistem Informasi
## Sistem Peminjaman Alat Laboratorium (Soal 1)

**Kelompok 6**
| Nama | NIM |
|---|---|
| Lian Rosyid Alamzah | 24051130084 |
| Aditya Danu Marizta | 24051130094 |
| Muhammad Ghafur Amanu Putra Warnada | 24051130103 |

Teknologi: **PHP Native** (mysqli) + MySQL/MariaDB + Bootstrap 5

---

## 1. Deskripsi Sistem

Sistem ini digunakan untuk mengelola proses peminjaman dan pengembalian alat laboratorium oleh mahasiswa. Sistem mencatat data mahasiswa, data alat (beserta stok), serta transaksi peminjaman mulai dari alat dipinjam hingga dikembalikan.

Sistem menerapkan 3 aturan bisnis utama sesuai soal:
1. **Denda keterlambatan**: jika pengembalian alat terlambat lebih dari 3 hari dari tanggal rencana kembali, dikenakan denda **Rp2.000 per hari** keterlambatan.
2. **Biaya perbaikan**: jika alat dikembalikan dalam kondisi **rusak**, dikenakan biaya perbaikan tetap sebesar **Rp10.000**.
3. **Input transaksi**: sistem menyediakan form untuk mencatat transaksi peminjaman baru dan transaksi pengembalian, dengan perhitungan denda dilakukan otomatis oleh sistem (bukan input manual), sehingga menghindari kesalahan perhitungan.

Sebagai fitur tambahan (di luar ketentuan eksplisit soal), sistem juga melacak **stok_tersedia** pada tabel alat, sehingga mahasiswa tidak bisa meminjam alat yang stoknya habis — ini membuat sistem lebih realistis untuk penggunaan nyata.

---

## 2. ERD (Entity Relationship Diagram)

```
┌───────────────────┐        ┌─────────────────────────┐        ┌───────────────────┐
│     mahasiswa      │        │       peminjaman         │        │        alat         │
├───────────────────┤        ├─────────────────────────┤        ├───────────────────┤
│ PK id               │───┐    │ PK id                      │   ┌────│ PK id               │
│    nim (unique)     │   └───▶│ FK mahasiswa_id            │   │    │    kode_alat (unique)│
│    nama             │        │ FK alat_id                 │◀──┘    │    nama_alat         │
│    prodi            │        │    tanggal_pinjam          │        │    kategori          │
│    no_hp            │        │    tanggal_rencana_kembali │        │    stok_total        │
└───────────────────┘        │    tanggal_kembali_aktual  │        │    stok_tersedia     │
                                │    kondisi_saat_kembali    │        └───────────────────┘
                                │    hari_terlambat          │
                                │    denda_keterlambatan     │
                                │    biaya_perbaikan         │
                                │    total_denda              │
                                │    status                   │
                                │    created_at                │
                                └─────────────────────────┘
```

Relasi:
- Satu `mahasiswa` dapat memiliki banyak `peminjaman` (1 → N)
- Satu `alat` dapat dipinjam dalam banyak `peminjaman` (1 → N)
- Tabel `peminjaman` adalah tabel transaksi utama yang menghubungkan keduanya.

---

## 3. Struktur Tabel

### 3.1 Tabel `mahasiswa`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT, PK, AUTO_INCREMENT | |
| nim | VARCHAR, UNIQUE | Nomor Induk Mahasiswa |
| nama | VARCHAR | |
| prodi | VARCHAR | |
| no_hp | VARCHAR | |

### 3.2 Tabel `alat`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT, PK, AUTO_INCREMENT | |
| kode_alat | VARCHAR, UNIQUE | |
| nama_alat | VARCHAR | |
| kategori | VARCHAR | |
| stok_total | INT | Jumlah total unit alat |
| stok_tersedia | INT | Jumlah unit yang saat ini bisa dipinjam |

### 3.3 Tabel `peminjaman` (tabel transaksi)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT, PK, AUTO_INCREMENT | |
| mahasiswa_id | INT, FK → mahasiswa.id | |
| alat_id | INT, FK → alat.id | |
| tanggal_pinjam | DATE | |
| tanggal_rencana_kembali | DATE | |
| tanggal_kembali_aktual | DATE, NULLABLE | Diisi saat pengembalian |
| kondisi_saat_kembali | ENUM('baik','rusak'), NULLABLE | |
| hari_terlambat | INT, DEFAULT 0 | Dihitung otomatis |
| denda_keterlambatan | INT, DEFAULT 0 | Dihitung otomatis (Aturan 1) |
| biaya_perbaikan | INT, DEFAULT 0 | Dihitung otomatis (Aturan 2) |
| total_denda | INT, DEFAULT 0 | denda_keterlambatan + biaya_perbaikan |
| status | ENUM('dipinjam','dikembalikan') | |
| created_at | TIMESTAMP | |

---

## 4. Alur Penggunaan Sistem

1. **Dashboard** (`index.php`) — menampilkan ringkasan jumlah mahasiswa, alat, peminjaman aktif, dan total denda terkumpul, serta 10 transaksi terbaru.
2. **Data Mahasiswa** (`mahasiswa.php`) — CRUD data mahasiswa.
3. **Data Alat** (`alat.php`) — CRUD data alat, termasuk stok total.
4. **Peminjaman** (`peminjaman.php`) — form input transaksi peminjaman baru (validasi stok tersedia), dan daftar seluruh transaksi.
5. **Pengembalian** (`kembalikan.php`) — form input tanggal kembali aktual dan kondisi alat. Sistem otomatis menghitung hari terlambat, denda keterlambatan, dan biaya perbaikan berdasarkan 2 aturan bisnis, lalu memperbarui status transaksi dan stok alat.
6. **Laporan** (`laporan.php`) — menampilkan rekap seluruh transaksi beserta total denda, dengan filter berdasarkan status.

---

## 5. Contoh Hasil Uji (Sample Data)

Database sudah diisi 3 contoh transaksi pada `database.sql`:

| Skenario | Tgl Pinjam | Rencana Kembali | Tgl Kembali | Kondisi | Hari Terlambat | Denda | Biaya Perbaikan | Total |
|---|---|---|---|---|---|---|---|---|
| Pengembalian tepat waktu | 01-10-2026 | 05-10-2026 | 05-10-2026 | Baik | 0 | Rp0 | Rp0 | Rp0 |
| Terlambat 5 hari + alat rusak | 01-10-2026 | 05-10-2026 | 10-10-2026 | Rusak | 5 | Rp10.000 (5×Rp2.000) | Rp10.000 | **Rp20.000** |
| Masih dipinjam (belum dikembalikan) | 15-10-2026 | 20-10-2026 | - | - | - | - | - | - |

Pengujian aturan bisnis:
- **Uji Aturan 1 (denda keterlambatan)**: terlambat 5 hari (>3 hari) → 5 × Rp2.000 = **Rp10.000** ✔
- **Uji Aturan 2 (biaya perbaikan)**: kondisi = rusak → **Rp10.000** ✔
- **Uji terlambat ≤ 3 hari**: jika terlambat 1–3 hari, denda = **Rp0** (sesuai aturan, hanya dikenakan jika > 3 hari) ✔
- **Uji input transaksi**: form peminjaman dan pengembalian berhasil menyimpan data ke tabel `peminjaman` dan memperbarui `stok_tersedia` pada tabel `alat` ✔

---

## 6. Pembagian Tugas Kelompok

| Anggota | Tugas |
|---|---|
| **Lian Rosyid Alamzah** (24051130084) | Merancang database (ERD, skema tabel, `database.sql`), serta membangun modul CRUD **Data Mahasiswa** dan **Data Alat** (`mahasiswa.php`, `alat.php`) |
| **Aditya Danu Marizta** (24051130094) | Membangun modul transaksi inti: **Peminjaman** dan **Pengembalian** (`peminjaman.php`, `kembalikan.php`), termasuk implementasi logika aturan bisnis (denda keterlambatan & biaya perbaikan) |
| **Muhammad Ghafur Amanu Putra Warnada** (24051130103) | Membangun **Dashboard** dan **Laporan** (`index.php`, `laporan.php`), styling UI (Bootstrap, `style.css`), serta menyusun dokumentasi ini |

Setiap anggota melakukan pengujian pada modul masing-masing dan saling cross-check sebelum presentasi.

---

## 7. Cara Menjalankan

1. Salin folder `uts-lab` ke `C:\xampp\htdocs\`
2. Jalankan Apache dan MySQL di XAMPP Control Panel
3. Buka `http://localhost/phpmyadmin`, import file `database.sql`
4. Buka `http://localhost/uts-lab/` di browser
