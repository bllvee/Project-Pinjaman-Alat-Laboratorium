<?php
require_once "db.php";
require_once "auth.php";
check_role('mahasiswa');

// Filter pencarian dan kategori
$search   = trim($_GET['search'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');

$where_clauses = [];
if ($search !== '') {
    $search_clean = mysqli_real_escape_string($con, $search);
    $where_clauses[] = "(nama_alat LIKE '%$search_clean%' OR kode_alat LIKE '%$search_clean%')";
}
if ($kategori !== '' && $kategori !== 'semua') {
    $kat_clean = mysqli_real_escape_string($con, $kategori);
    $where_clauses[] = "kategori = '$kat_clean'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$alat_list = mysqli_query($con, "SELECT * FROM alat $where_sql ORDER BY nama_alat ASC");

// Ambil semua kategori unik untuk filter
$kategori_list = mysqli_query($con, "SELECT DISTINCT kategori FROM alat WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori ASC");

include "header.php";
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-0"><i class="fa fa-boxes-stacked me-2"></i> Katalog Alat Laboratorium</h3>
        <p class="text-muted small mb-0">Lihat ketersediaan alat lab dan lakukan peminjaman mandiri.</p>
    </div>
    <a href="user_pinjam.php" class="btn btn-primary-lab text-white">
        <i class="fa fa-plus me-1"></i> Form Ajukan Pinjam
    </a>
</div>

<!-- Filter & Search Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="fa fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode alat..." 
                       value="<?= htmlspecialchars($search) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="kategori" class="form-select">
                <option value="semua">-- Semua Kategori --</option>
                <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
                    <option value="<?= htmlspecialchars($k['kategori']) ?>" <?= $kategori == $k['kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary-lab text-white w-100">
                <i class="fa fa-filter me-1"></i> Filter
            </button>
            <?php if ($search !== '' || ($kategori !== '' && $kategori !== 'semua')): ?>
                <a href="user_katalog.php" class="btn btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Grid Kartu Alat -->
<div class="row g-3">
    <?php if (mysqli_num_rows($alat_list) === 0): ?>
        <div class="col-12">
            <div class="table-wrap text-center py-5 text-muted">
                <i class="fa fa-box-open fs-1 mb-2 d-block text-secondary"></i>
                Tidak ada alat laboratorium yang cocok dengan kriteria pencarian Anda.
            </div>
        </div>
    <?php else: ?>
        <?php while ($alat = mysqli_fetch_assoc($alat_list)): ?>
            <?php
            $tersedia = (int) $alat['stok_tersedia'];
            $total    = (int) $alat['stok_total'];
            $bisa_pinjam = $tersedia > 0;
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="tool-card p-3 shadow-sm">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="tool-icon">
                            <i class="fa fa-flask"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <span class="badge bg-light text-dark border mb-1"><?= htmlspecialchars($alat['kategori'] ?? 'Umum') ?></span>
                            <h6 class="fw-bold mb-0 text-truncate" title="<?= htmlspecialchars($alat['nama_alat']) ?>">
                                <?= htmlspecialchars($alat['nama_alat']) ?>
                            </h6>
                            <small class="text-muted">Kode: <?= htmlspecialchars($alat['kode_alat']) ?></small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top border-bottom py-2 my-2 small">
                        <span class="text-muted">Status Ketersediaan:</span>
                        <?php if ($bisa_pinjam): ?>
                            <span class="badge bg-success-subtle text-success border border-success fw-semibold">
                                <i class="fa fa-check me-1"></i> Tersedia (<?= $tersedia ?> / <?= $total ?>)
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger fw-semibold">
                                <i class="fa fa-times me-1"></i> Stok Habis (0 / <?= $total ?>)
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-auto pt-2">
                        <?php if ($bisa_pinjam): ?>
                            <a href="user_pinjam.php?alat_id=<?= $alat['id'] ?>" class="btn btn-sm btn-primary-lab text-white w-100">
                                <i class="fa fa-hand-holding-hand me-1"></i> Pinjam Alat Ini
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-secondary w-100" disabled>
                                <i class="fa fa-ban me-1"></i> Tidak Tersedia
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php include "footer.php"; ?>
