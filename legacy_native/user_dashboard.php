<?php
require_once "db.php";
require_once "auth.php";
check_role('mahasiswa');

$user = current_user();
$mhs_id = (int) $user['mahasiswa_id'];

// Ambil detail mahasiswa dari database
$mhs = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM mahasiswa WHERE id = $mhs_id"));

// Statistik pribadi mahasiswa
$sedang_dipinjam = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS n FROM peminjaman WHERE mahasiswa_id=$mhs_id AND status='dipinjam'"))['n'];
$selesai         = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS n FROM peminjaman WHERE mahasiswa_id=$mhs_id AND status='dikembalikan'"))['n'];
$total_denda     = mysqli_fetch_assoc(mysqli_query($con, "SELECT COALESCE(SUM(total_denda),0) AS n FROM peminjaman WHERE mahasiswa_id=$mhs_id"))['n'];

// Peminjaman aktif saat ini
$peminjaman_aktif = mysqli_query($con, "SELECT p.*, a.nama_alat, a.kode_alat, a.kategori
                                         FROM peminjaman p
                                         JOIN alat a ON a.id = p.alat_id
                                         WHERE p.mahasiswa_id = $mhs_id AND p.status = 'dipinjam'
                                         ORDER BY p.tanggal_rencana_kembali ASC");

include "header.php";
?>

<!-- Banner Sambutan Mahasiswa -->
<div class="hero-welcome">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="mb-1"><i class="fa fa-graduation-cap me-2"></i> Selamat Datang, <?= htmlspecialchars($mhs['nama'] ?? $user['nama']) ?>!</h4>
            <p class="mb-0 opacity-75 small">
                NIM: <strong><?= htmlspecialchars($mhs['nim'] ?? $user['nim']) ?></strong> &bull;
                Prodi: <strong><?= htmlspecialchars($mhs['prodi'] ?? '-') ?></strong> &bull;
                No. HP: <strong><?= htmlspecialchars($mhs['no_hp'] ?? '-') ?></strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="user_katalog.php" class="btn btn-light btn-sm fw-semibold">
                <i class="fa fa-boxes-stacked me-1 text-success"></i> Lihat Katalog
            </a>
            <a href="user_pinjam.php" class="btn btn-warning btn-sm fw-semibold text-dark">
                <i class="fa fa-hand-holding-hand me-1"></i> Ajukan Pinjam
            </a>
        </div>
    </div>
</div>

<!-- Kartu Statistik Pribadi -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-stat">
            <p>Alat Sedang Dipinjam</p>
            <h2 class="text-warning"><?= $sedang_dipinjam ?></h2>
            <div class="text-muted small mt-1">Harap perhatikan tanggal pengembalian</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat">
            <p>Peminjaman Selesai</p>
            <h2 class="text-success"><?= $selesai ?></h2>
            <div class="text-muted small mt-1">Total transaksi yang sudah dikembalikan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat">
            <p>Total Denda Tercatat</p>
            <h2 class="<?= $total_denda > 0 ? 'text-danger' : 'text-secondary' ?>">
                Rp <?= number_format($total_denda, 0, ',', '.') ?>
            </h2>
            <div class="text-muted small mt-1">Akumulasi denda keterlambatan/kerusakan</div>
        </div>
    </div>
</div>

<!-- Peringatan Aturan Peminjaman -->
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-4">
    <i class="fa fa-triangle-exclamation fs-3 text-warning"></i>
    <div class="small">
        <strong>Ketentuan Pengembalian Alat Lab:</strong>
        Keterlambatan lebih dari 3 hari dari tanggal rencana kembali dikenakan denda <strong>Rp2.000 / hari</strong>.
        Apabila alat dikembalikan dalam kondisi rusak, dikenakan biaya perbaikan tetap <strong>Rp10.000</strong>.
    </div>
</div>

<!-- Daftar Alat yang Sedang Dipinjam -->
<div class="table-wrap mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-dark"><i class="fa fa-clock text-warning me-2"></i> Alat Yang Sedang Anda Pinjam</h5>
        <span class="badge bg-warning text-dark"><?= $sedang_dipinjam ?> alat aktif</span>
    </div>

    <?php if (mysqli_num_rows($peminjaman_aktif) === 0): ?>
        <div class="text-center py-4 text-muted">
            <i class="fa fa-circle-check fs-1 text-success mb-2 d-block"></i>
            Saat ini Anda tidak memiliki pinjaman alat yang sedang berjalan.
            <div class="mt-2">
                <a href="user_pinjam.php" class="btn btn-sm btn-primary-lab text-white">
                    <i class="fa fa-plus me-1"></i> Pinjam Alat Sekarang
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode & Nama Alat</th>
                        <th>Kategori</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Rencana Kembali</th>
                        <th>Status Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $hari_ini = new DateTime(date('Y-m-d'));
                    while ($r = mysqli_fetch_assoc($peminjaman_aktif)):
                        $rencana = new DateTime($r['tanggal_rencana_kembali']);
                        $diff = $hari_ini->diff($rencana);
                        
                        if ($hari_ini > $rencana) {
                            $status_waktu = "<span class='badge bg-danger'>Terlambat {$diff->days} hari</span>";
                        } elseif ($diff->days === 0) {
                            $status_waktu = "<span class='badge bg-warning text-dark'>Batas hari ini!</span>";
                        } else {
                            $status_waktu = "<span class='badge bg-success'>Tersisa {$diff->days} hari lagi</span>";
                        }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($r['nama_alat']) ?></strong>
                            <div class="text-muted small">Kode: <?= htmlspecialchars($r['kode_alat']) ?></div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($r['kategori']) ?></span></td>
                        <td><?= date('d-m-Y', strtotime($r['tanggal_pinjam'])) ?></td>
                        <td><strong><?= date('d-m-Y', strtotime($r['tanggal_rencana_kembali'])) ?></strong></td>
                        <td><?= $status_waktu ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Ringkasan Riwayat 5 Transaksi Terakhir -->
<div class="table-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-dark"><i class="fa fa-history text-secondary me-2"></i> Riwayat Transaksi Terakhir</h5>
        <a href="user_riwayat.php" class="btn btn-sm btn-outline-secondary">Lihat Semua Riwayat</a>
    </div>

    <?php
    $riwayat = mysqli_query($con, "SELECT p.*, a.nama_alat, a.kode_alat
                                   FROM peminjaman p
                                   JOIN alat a ON a.id = p.alat_id
                                   WHERE p.mahasiswa_id = $mhs_id
                                   ORDER BY p.id DESC LIMIT 5");
    ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali Aktual</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Total Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($riwayat) === 0):
                    echo "<tr><td colspan='7' class='text-center text-muted py-3'>Belum ada riwayat transaksi.</td></tr>";
                else:
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($riwayat)):
                        $badge = $row['status'] == 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan';
                        $kondisi_badge = $row['kondisi_saat_kembali'] == 'rusak' ? 'badge-kondisi-rusak' : 'badge-kondisi-baik';
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_alat']) ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td><?= $row['tanggal_kembali_aktual'] ? date('d-m-Y', strtotime($row['tanggal_kembali_aktual'])) : '-' ?></td>
                    <td>
                        <?= $row['kondisi_saat_kembali'] 
                            ? "<span class='badge {$kondisi_badge}'>" . ucfirst($row['kondisi_saat_kembali']) . "</span>" 
                            : '-' ?>
                    </td>
                    <td><span class="badge <?= $badge ?>"><?= ucfirst($row['status']) ?></span></td>
                    <td>
                        <?= $row['total_denda'] > 0 
                            ? "<strong class='text-danger'>Rp " . number_format($row['total_denda'], 0, ',', '.') . "</strong>" 
                            : "<span class='text-muted'>Rp 0</span>" ?>
                    </td>
                </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>
