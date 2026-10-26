<?php
require_once "db.php";
require_once "auth.php";
check_role('mahasiswa');

$user = current_user();
$mhs_id = (int) $user['mahasiswa_id'];

// Filter status
$filter = $_GET['status'] ?? 'semua';
$where_status = "";
if ($filter === 'dipinjam') {
    $where_status = "AND p.status = 'dipinjam'";
} elseif ($filter === 'dikembalikan') {
    $where_status = "AND p.status = 'dikembalikan'";
}

$query = mysqli_query($con, "SELECT p.*, a.nama_alat, a.kode_alat, a.kategori
                             FROM peminjaman p
                             JOIN alat a ON a.id = p.alat_id
                             WHERE p.mahasiswa_id = $mhs_id $where_status
                             ORDER BY p.id DESC");

// Rekap pribadi
$rekap = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
            COUNT(*) AS total_transaksi,
            COALESCE(SUM(CASE WHEN status='dipinjam' THEN 1 ELSE 0 END),0) AS total_aktif,
            COALESCE(SUM(denda_keterlambatan),0) AS total_denda_terlambat,
            COALESCE(SUM(biaya_perbaikan),0) AS total_biaya_perbaikan,
            COALESCE(SUM(total_denda),0) AS grand_total_denda
        FROM peminjaman 
        WHERE mahasiswa_id = $mhs_id"));

include "header.php";
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-0"><i class="fa fa-clock-rotate-left me-2"></i> Riwayat Peminjaman Pribadi</h3>
        <p class="text-muted small mb-0">Daftar seluruh transaksi peminjaman alat lab yang Anda lakukan.</p>
    </div>
    <a href="user_pinjam.php" class="btn btn-primary-lab text-white">
        <i class="fa fa-plus me-1"></i> Pinjam Alat Baru
    </a>
</div>

<!-- Ringkasan Rekap Mahasiswa -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Total Transaksi</p>
            <h2><?= $rekap['total_transaksi'] ?></h2>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Sedang Dipinjam</p>
            <h2 class="text-warning"><?= $rekap['total_aktif'] ?></h2>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Denda Keterlambatan</p>
            <h2>Rp <?= number_format($rekap['total_denda_terlambat'], 0, ',', '.') ?></h2>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card-stat">
            <p>Biaya Perbaikan Alat</p>
            <h2 class="<?= $rekap['total_biaya_perbaikan'] > 0 ? 'text-danger' : '' ?>">
                Rp <?= number_format($rekap['total_biaya_perbaikan'], 0, ',', '.') ?>
            </h2>
        </div>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="table-wrap">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h5 class="mb-0">Rincian Transaksi</h5>
        <div class="btn-group btn-group-sm">
            <a href="user_riwayat.php?status=semua" class="btn <?= $filter==='semua' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' ?>">Semua</a>
            <a href="user_riwayat.php?status=dipinjam" class="btn <?= $filter==='dipinjam' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' ?>">Sedang Dipinjam</a>
            <a href="user_riwayat.php?status=dikembalikan" class="btn <?= $filter==='dikembalikan' ? 'btn-primary-lab text-white' : 'btn-outline-secondary' ?>">Sudah Dikembalikan</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Alat</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Tgl Kembali Aktual</th>
                    <th>Kondisi</th>
                    <th>Terlambat</th>
                    <th>Denda Telat</th>
                    <th>Biaya Servis</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($query) === 0):
                    echo "<tr><td colspan='11' class='text-center text-muted py-4'>Tidak ada data transaksi yang ditemukan.</td></tr>";
                else:
                    $no = 1;
                    while ($r = mysqli_fetch_assoc($query)):
                        $badge = $r['status'] == 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan';
                        $kondisi_badge = $r['kondisi_saat_kembali'] == 'rusak' ? 'badge-kondisi-rusak' : 'badge-kondisi-baik';
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <strong><?= htmlspecialchars($r['nama_alat']) ?></strong>
                        <div class="text-muted small">Kode: <?= htmlspecialchars($r['kode_alat']) ?></div>
                    </td>
                    <td><?= date('d-m-Y', strtotime($r['tanggal_pinjam'])) ?></td>
                    <td><?= date('d-m-Y', strtotime($r['tanggal_rencana_kembali'])) ?></td>
                    <td><?= $r['tanggal_kembali_aktual'] ? date('d-m-Y', strtotime($r['tanggal_kembali_aktual'])) : '<span class="text-muted">-</span>' ?></td>
                    <td>
                        <?= $r['kondisi_saat_kembali'] 
                            ? "<span class='badge {$kondisi_badge}'>" . ucfirst($r['kondisi_saat_kembali']) . "</span>" 
                            : '<span class="text-muted">-</span>' ?>
                    </td>
                    <td><?= $r['hari_terlambat'] > 0 ? "{$r['hari_terlambat']} hari" : '<span class="text-muted">0</span>' ?></td>
                    <td><?= $r['denda_keterlambatan'] > 0 ? "Rp " . number_format($r['denda_keterlambatan'], 0, ',', '.') : '<span class="text-muted">Rp 0</span>' ?></td>
                    <td><?= $r['biaya_perbaikan'] > 0 ? "Rp " . number_format($r['biaya_perbaikan'], 0, ',', '.') : '<span class="text-muted">Rp 0</span>' ?></td>
                    <td>
                        <?= $r['total_denda'] > 0 
                            ? "<strong class='text-danger'>Rp " . number_format($r['total_denda'], 0, ',', '.') . "</strong>" 
                            : "<span class='text-muted'>Rp 0</span>" ?>
                    </td>
                    <td><span class="badge <?= $badge ?>"><?= ucfirst($r['status']) ?></span></td>
                </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>
