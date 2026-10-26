<?php
include "db.php";
require_once "auth.php";
check_role('admin');

// ===== PROSES TAMBAH / EDIT =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_alat   = mysqli_real_escape_string($con, $_POST['kode_alat']);
    $nama_alat   = mysqli_real_escape_string($con, $_POST['nama_alat']);
    $kategori    = mysqli_real_escape_string($con, $_POST['kategori']);
    $stok_total  = (int) $_POST['stok_total'];

    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        // Jaga stok_tersedia tetap konsisten saat stok_total diubah
        $old = mysqli_fetch_assoc(mysqli_query($con, "SELECT stok_total, stok_tersedia FROM alat WHERE id=$id"));
        $selisih = $stok_total - $old['stok_total'];
        $stok_tersedia_baru = max(0, $old['stok_tersedia'] + $selisih);
        mysqli_query($con, "UPDATE alat SET kode_alat='$kode_alat', nama_alat='$nama_alat', kategori='$kategori',
                             stok_total=$stok_total, stok_tersedia=$stok_tersedia_baru WHERE id=$id");
    } else {
        mysqli_query($con, "INSERT INTO alat (kode_alat, nama_alat, kategori, stok_total, stok_tersedia)
                             VALUES ('$kode_alat', '$nama_alat', '$kategori', $stok_total, $stok_total)");
    }
    header("Location: alat.php");
    exit;
}

// ===== PROSES HAPUS =====
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($con, "DELETE FROM alat WHERE id=$id");
    header("Location: alat.php");
    exit;
}

// ===== DATA UNTUK FORM EDIT =====
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM alat WHERE id=$id"));
}

include "header.php";
?>

<h3 class="page-title"><i class="fa fa-flask-vial"></i> Data Alat Laboratorium</h3>

<div class="row g-4">
    <div class="col-md-4">
        <div class="table-wrap">
            <h5 class="mb-3"><?= $edit_data ? 'Edit' : 'Tambah' ?> Alat</h5>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
                <div class="mb-2">
                    <label class="form-label">Kode Alat</label>
                    <input type="text" name="kode_alat" class="form-control" required
                           value="<?= htmlspecialchars($edit_data['kode_alat'] ?? '') ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="nama_alat" class="form-control" required
                           value="<?= htmlspecialchars($edit_data['nama_alat'] ?? '') ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control"
                           value="<?= htmlspecialchars($edit_data['kategori'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok Total</label>
                    <input type="number" name="stok_total" min="0" class="form-control" required
                           value="<?= htmlspecialchars($edit_data['stok_total'] ?? '0') ?>">
                    <?php if ($edit_data): ?>
                        <small class="text-muted">Stok tersedia saat ini: <?= $edit_data['stok_tersedia'] ?></small>
                    <?php endif; ?>
                </div>
                <button class="btn btn-primary-lab text-white w-100" type="submit">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <?php if ($edit_data): ?>
                    <a href="alat.php" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-wrap">
            <h5 class="mb-3">Daftar Alat</h5>
            <table class="table table-hover align-middle">
                <thead>
                <tr><th>No</th><th>Kode</th><th>Nama Alat</th><th>Kategori</th><th>Stok Total</th><th>Stok Tersedia</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php
                $q = mysqli_query($con, "SELECT * FROM alat ORDER BY id ASC");
                $no = 1;
                while ($r = mysqli_fetch_assoc($q)) {
                    $warn = $r['stok_tersedia'] == 0 ? 'text-danger fw-bold' : '';
                    echo "<tr>
                            <td>{$no}</td>
                            <td>" . htmlspecialchars($r['kode_alat']) . "</td>
                            <td>" . htmlspecialchars($r['nama_alat']) . "</td>
                            <td>" . htmlspecialchars($r['kategori']) . "</td>
                            <td>{$r['stok_total']}</td>
                            <td class='{$warn}'>{$r['stok_tersedia']}</td>
                            <td>
                                <a href='alat.php?edit={$r['id']}' class='btn btn-sm btn-warning'><i class='fa fa-pen'></i></a>
                                <a href='alat.php?hapus={$r['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Hapus data ini?')\"><i class='fa fa-trash'></i></a>
                            </td>
                          </tr>";
                    $no++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
