<?php
include "db.php";
require_once "auth.php";
check_role('admin');

// ===== PROSES TAMBAH PEMINJAMAN BARU =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahasiswa_id = (int) $_POST['mahasiswa_id'];
    $alat_id      = (int) $_POST['alat_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_rencana_kembali = $_POST['tanggal_rencana_kembali'];

    // Validasi stok tersedia sebelum transaksi dicatat
    $alat = mysqli_fetch_assoc(mysqli_query($con, "SELECT stok_tersedia FROM alat WHERE id=$alat_id"));
    if ($alat && $alat['stok_tersedia'] > 0) {
        mysqli_query($con, "INSERT INTO peminjaman (mahasiswa_id, alat_id, tanggal_pinjam, tanggal_rencana_kembali, status)
                             VALUES ($mahasiswa_id, $alat_id, '$tanggal_pinjam', '$tanggal_rencana_kembali', 'dipinjam')");
        mysqli_query($con, "UPDATE alat SET stok_tersedia = stok_tersedia - 1 WHERE id=$alat_id");
        $pesan = "Peminjaman berhasil dicatat.";
    } else {
        $pesan = "Gagal: stok alat sedang tidak tersedia.";
    }
    header("Location: peminjaman.php?pesan=" . urlencode($pesan));
    exit;
}

include "header.php";
?>

<h3 class="page-title"><i class="fa fa-right-left"></i> Transaksi Peminjaman</h3>

<?php if (isset($_GET['pesan'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_GET['pesan']) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="table-wrap">
            <h5 class="mb-3">Form Peminjaman Baru</h5>
            <form method="post">
                <div class="mb-2">
                    <label class="form-label">Mahasiswa</label>
                    <select name="mahasiswa_id" class="form-select" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        <?php
                        $m = mysqli_query($con, "SELECT * FROM mahasiswa ORDER BY nama ASC");
                        while ($row = mysqli_fetch_assoc($m)) {
                            echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nama']) . " ({$row['nim']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Alat</label>
                    <select name="alat_id" class="form-select" required>
                        <option value="">-- Pilih Alat --</option>
                        <?php
                        $a = mysqli_query($con, "SELECT * FROM alat WHERE stok_tersedia > 0 ORDER BY nama_alat ASC");
                        while ($row = mysqli_fetch_assoc($a)) {
                            echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nama_alat']) . " (tersedia: {$row['stok_tersedia']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rencana Tanggal Kembali</label>
                    <input type="date" name="tanggal_rencana_kembali" class="form-control" required>
                </div>
                <button class="btn btn-primary-lab text-white w-100" type="submit">
                    <i class="fa fa-save"></i> Simpan Peminjaman
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-wrap">
            <h5 class="mb-3">Daftar Peminjaman</h5>
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>No</th><th>Mahasiswa</th><th>Alat</th><th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th><th>Status</th><th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $q = mysqli_query($con, "SELECT p.*, m.nama AS nama_mahasiswa, a.nama_alat
                                          FROM peminjaman p
                                          JOIN mahasiswa m ON m.id = p.mahasiswa_id
                                          JOIN alat a ON a.id = p.alat_id
                                          ORDER BY p.id DESC");
                $no = 1;
                while ($r = mysqli_fetch_assoc($q)) {
                    $badge = $r['status'] == 'dipinjam' ? 'badge-status-dipinjam' : 'badge-status-dikembalikan';
                    $aksi = $r['status'] == 'dipinjam'
                        ? "<a href='kembalikan.php?id={$r['id']}' class='btn btn-sm btn-primary-lab text-white'><i class='fa fa-undo'></i> Kembalikan</a>"
                        : "<span class='text-muted small'>Selesai</span>";
                    echo "<tr>
                            <td>{$no}</td>
                            <td>" . htmlspecialchars($r['nama_mahasiswa']) . "</td>
                            <td>" . htmlspecialchars($r['nama_alat']) . "</td>
                            <td>{$r['tanggal_pinjam']}</td>
                            <td>{$r['tanggal_rencana_kembali']}</td>
                            <td><span class='badge {$badge}'>{$r['status']}</span></td>
                            <td>{$aksi}</td>
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
