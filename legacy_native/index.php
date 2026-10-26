<?php
include "db.php";
require_once "auth.php";
check_role('admin');
include "header.php";

$total_mahasiswa = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS n FROM mahasiswa"))['n'];
$total_alat       = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS n FROM alat"))['n'];
$sedang_dipinjam  = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS n FROM peminjaman WHERE status='dipinjam'"))['n'];
$total_denda      = mysqli_fetch_assoc(mysqli_query($con, "SELECT COALESCE(SUM(total_denda),0) AS n FROM peminjaman"))['n'];
?>

<h3 class="page-title"><i class="fa fa-gauge"></i> Dashboard</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-stat">
            <p>Total Mahasiswa</p>
            <h2><?= $total_mahasiswa ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Jenis Alat</p>
            <h2><?= $total_alat ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Sedang Dipinjam</p>
            <h2><?= $sedang_dipinjam ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat">
            <p>Total Denda Terkumpul</p>
            <h2>Rp <?= number_format($total_denda, 0, ',', '.') ?></h2>
        </div>
    </div>
</div>

<div class="table-wrap">
    <h5 class="mb-3">Transaksi Terbaru</h5>
    <table class="table table-hover align-middle">
        <thead>
        <tr>
            <th>No</th><th>Mahasiswa</th><th>Alat</th><th>Tgl Pinjam</th><th>Tgl Rencana Kembali</th><th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $q = mysqli_query($con, "SELECT p.*, m.nama AS nama_mahasiswa, a.nama_alat
                                  FROM peminjaman p
                                  JOIN mahasiswa m ON m.id = p.mahasiswa_id
                                  JOIN alat a ON a.id = p.alat_id
                                  ORDER BY p.id DESC LIMIT 10");
        $no = 1;
        while ($r = mysqli_fetch_assoc($q)) {
            $badge = $r['status'] == 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan';
            echo "<tr>
                    <td>{$no}</td>
                    <td>" . htmlspecialchars($r['nama_mahasiswa']) . "</td>
                    <td>" . htmlspecialchars($r['nama_alat']) . "</td>
                    <td>{$r['tanggal_pinjam']}</td>
                    <td>{$r['tanggal_rencana_kembali']}</td>
                    <td><span class='badge {$badge}'>{$r['status']}</span></td>
                  </tr>";
            $no++;
        }
        ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>
