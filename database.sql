-- =========================================================
-- UTS Praktikum Manajemen Sistem Informasi
-- Soal 1: Sistem Peminjaman Alat Laboratorium
-- Kelompok 6: Lian Rosyid Alamzah, Aditya Danu Marizta, Muhammad Ghafur Amanu Putra Warnada
-- =========================================================

CREATE DATABASE IF NOT EXISTS lab_peminjaman;
USE lab_peminjaman;

-- =========================================================
-- TABEL MAHASISWA
-- =========================================================
CREATE TABLE mahasiswa (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nim        VARCHAR(20)  NOT NULL UNIQUE,
    nama       VARCHAR(100) NOT NULL,
    prodi      VARCHAR(100) NOT NULL,
    no_hp      VARCHAR(20)  DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABEL ALAT
-- stok_tersedia dipakai untuk validasi sederhana
-- (tambahan logis dari kelompok, tidak eksplisit diminta soal,
--  tapi wajar untuk sistem peminjaman nyata)
-- =========================================================
CREATE TABLE alat (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    kode_alat      VARCHAR(20)  NOT NULL UNIQUE,
    nama_alat      VARCHAR(100) NOT NULL,
    kategori       VARCHAR(50)  DEFAULT NULL,
    stok_total     INT NOT NULL DEFAULT 1,
    stok_tersedia  INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABEL PEMINJAMAN (tabel transaksi utama)
-- =========================================================
CREATE TABLE peminjaman (
    id                       INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id             INT NOT NULL,
    alat_id                  INT NOT NULL,
    tanggal_pinjam           DATE NOT NULL,
    tanggal_rencana_kembali  DATE NOT NULL,
    tanggal_kembali_aktual   DATE DEFAULT NULL,
    kondisi_saat_kembali     ENUM('baik','rusak') DEFAULT NULL,
    hari_terlambat           INT NOT NULL DEFAULT 0,
    denda_keterlambatan      INT NOT NULL DEFAULT 0,
    biaya_perbaikan          INT NOT NULL DEFAULT 0,
    total_denda              INT NOT NULL DEFAULT 0,
    status                   ENUM('dipinjam','dikembalikan') NOT NULL DEFAULT 'dipinjam',
    created_at               TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_peminjaman_mahasiswa FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id),
    CONSTRAINT fk_peminjaman_alat FOREIGN KEY (alat_id) REFERENCES alat(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- DATA CONTOH (dummy data untuk uji coba sistem)
-- =========================================================
INSERT INTO mahasiswa (nim, nama, prodi, no_hp) VALUES
('24051130084', 'Lian Rosyid Alamzah', 'Teknologi Informasi', '081234560001'),
('24051130094', 'Aditya Danu Marizta', 'Teknologi Informasi', '081234560002'),
('24051130103', 'Muhammad Ghafur Amanu Putra Warnada', 'Teknologi Informasi', '081234560003');

INSERT INTO alat (kode_alat, nama_alat, kategori, stok_total, stok_tersedia) VALUES
('ALT-001', 'Multimeter Digital', 'Elektronik', 10, 10),
('ALT-002', 'Osiloskop', 'Elektronik', 3, 3),
('ALT-003', 'Soldering Iron', 'Elektronik', 8, 8),
('ALT-004', 'Mikroskop', 'Optik', 5, 5),
('ALT-005', 'Laptop Praktikum', 'Komputer', 6, 6);

-- Contoh transaksi: sudah dikembalikan, kondisi baik, tidak terlambat
INSERT INTO peminjaman
(mahasiswa_id, alat_id, tanggal_pinjam, tanggal_rencana_kembali, tanggal_kembali_aktual,
 kondisi_saat_kembali, hari_terlambat, denda_keterlambatan, biaya_perbaikan, total_denda, status)
VALUES
(1, 1, '2026-10-01', '2026-10-03', '2026-10-03', 'baik', 0, 0, 0, 0, 'dikembalikan');

-- Contoh transaksi: terlambat 5 hari (denda) dan alat rusak (biaya perbaikan)
INSERT INTO peminjaman
(mahasiswa_id, alat_id, tanggal_pinjam, tanggal_rencana_kembali, tanggal_kembali_aktual,
 kondisi_saat_kembali, hari_terlambat, denda_keterlambatan, biaya_perbaikan, total_denda, status)
VALUES
(2, 2, '2026-10-01', '2026-10-05', '2026-10-10', 'rusak', 5, 10000, 10000, 20000, 'dikembalikan');

-- Contoh transaksi: masih dipinjam (belum dikembalikan)
INSERT INTO peminjaman
(mahasiswa_id, alat_id, tanggal_pinjam, tanggal_rencana_kembali, status)
VALUES
(3, 4, '2026-10-15', '2026-10-20', 'dipinjam');

-- stok_tersedia alat id 4 (Mikroskop) dikurangi 1 karena sedang dipinjam di atas
UPDATE alat SET stok_tersedia = stok_tersedia - 1 WHERE id = 4;
