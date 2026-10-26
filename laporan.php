<?php
include "db.php";
require_once "auth.php";
check_role('admin');

// ===== FILTER STATUS (opsional) =====
$filter = $_GET['status'] ?? 'semua';
$where = "";
if ($filter == 'dipinjam') $where = "WHERE p.status='dipinjam'";
if ($filter == 'dikembalikan') $where = "WHERE p.status='dikembalikan'";

include "header.php";

$q = mysqli_query($con, "SELECT p.*, m.nama AS nama_mahasiswa, m.nim, a.nama_alat
                          FROM peminjaman p
                          JOIN mahasiswa m ON m.id = p.mahasiswa_id
                          JOIN alat a ON a.id = p.alat_id
                          $where
                          ORDER BY p.id DESC");

$rekap = mysqli_fetch_assoc(mysqli_query($con, "SELECT
                                COUNT(*) AS total_transaksi,
                                COALESCE(SUM(denda_keterlambatan),0) AS total_denda_terlambat,
                                COALESCE(SUM(biaya_perbaikan),0) AS total_biaya_perbaikan,
                                COALESCE(SUM(total_denda),0) AS total_keseluruhan
                              FROM peminjaman"));
?>

<h3 class="page-title"><i class="fa fa-file-lines"></i> Laporan Transaksi Peminjaman</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-stat">
            <p>Total Transaksi</p>
            <h2><?= $rekap['total_transaksi'] ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Denda Keterlambatan</p>
            <h2>Rp <?= number_format($rekap['total_denda_terlambat'], 0, ',', '.') ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Biaya Perbaikan</p>
            <h2>Rp <?= number_format($rekap['total_biaya_perbaikan'], 0, ',', '.') ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Total Keseluruhan</p>
            <h2>Rp <?= number_format($rekap['total_keseluruhan'], 0, ',', '.') ?></h2>
        </div>
    </div>
</div>

<div class="table-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Detail Transaksi</h5>
        <div class="btn-group">
            <a href="laporan.php?status=semua" class="btn btn-sm <?= $filter=='semua'?'btn-primary-lab text-white':'btn-outline-secondary' ?>">Semua</a>
            <a href="laporan.php?status=dipinjam" class="btn btn-sm <?= $filter=='dipinjam'?'btn-primary-lab text-white':'btn-outline-secondary' ?>">Dipinjam</a>
            <a href="laporan.php?status=dikembalikan" class="btn btn-sm <?= $filter=='dikembalikan'?'btn-primary-lab text-white':'btn-outline-secondary' ?>">Dikembalikan</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
            <tr>
                <th>No</th><th>Mahasiswa</th><th>Alat</th><th>Tgl Pinjam</th><th>Rencana Kembali</th>
                <th>Tgl Kembali</th><th>Terlambat</th><th>Kondisi</th><th>Denda</th><th>Perbaikan</th><th>Total</th><th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            while ($r = mysqli_fetch_assoc($q)) {
                $badge = $r['status'] == 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan';
                $kondisi_label = $r['kondisi_saat_kembali']
                    ? "<span class='badge badge-kondisi-{$r['kondisi_saat_kembali']}'>{$r['kondisi_saat_kembali']}</span>"
                    : '-';
                echo "<tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($r['nama_mahasiswa']) . " <br><small class='text-muted'>" . htmlspecialchars($r['nim']) . "</small></td>
                        <td>" . htmlspecialchars($r['nama_alat']) . "</td>
                        <td>{$r['tanggal_pinjam']}</td>
                        <td>{$r['tanggal_rencana_kembali']}</td>
                        <td>" . ($r['tanggal_kembali_aktual'] ?? '-') . "</td>
                        <td>{$r['hari_terlambat']} hari</td>
                        <td>{$kondisi_label}</td>
                        <td>Rp " . number_format($r['denda_keterlambatan'],0,',','.') . "</td>
                        <td>Rp " . number_format($r['biaya_perbaikan'],0,',','.') . "</td>
                        <td><strong>Rp " . number_format($r['total_denda'],0,',','.') . "</strong></td>
                        <td><span class='badge {$badge}'>{$r['status']}</span></td>
                      </tr>";
                $no++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>
