<?php
require_once "db.php";
require_once "auth.php";
check_role('mahasiswa');

$user = current_user();
$mhs_id = (int) $user['mahasiswa_id'];

// Ambil data profil mahasiswa yang sedang login
$mhs = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM mahasiswa WHERE id=$mhs_id"));

$error = "";
$success = "";

// PROSES SUBMIT PEMINJAMAN OLEH MAHASISWA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alat_id                 = (int) ($_POST['alat_id'] ?? 0);
    $tanggal_pinjam          = $_POST['tanggal_pinjam'] ?? '';
    $tanggal_rencana_kembali = $_POST['tanggal_rencana_kembali'] ?? '';

    if ($alat_id <= 0 || empty($tanggal_pinjam) || empty($tanggal_rencana_kembali)) {
        $error = "Harap lengkapi seluruh isian form.";
    } elseif ($tanggal_rencana_kembali < $tanggal_pinjam) {
        $error = "Tanggal rencana kembali tidak boleh lebih awal dari tanggal pinjam.";
    } else {
        // Cek ketersediaan stok alat
        $alat = mysqli_fetch_assoc(mysqli_query($con, "SELECT nama_alat, stok_tersedia FROM alat WHERE id=$alat_id"));
        if ($alat && $alat['stok_tersedia'] > 0) {
            $tgl_p  = mysqli_real_escape_string($con, $tanggal_pinjam);
            $tgl_rk = mysqli_real_escape_string($con, $tanggal_rencana_kembali);

            mysqli_query($con, "INSERT INTO peminjaman (mahasiswa_id, alat_id, tanggal_pinjam, tanggal_rencana_kembali, status)
                                 VALUES ($mhs_id, $alat_id, '$tgl_p', '$tgl_rk', 'dipinjam')");
            
            // Kurangi stok tersedia alat
            mysqli_query($con, "UPDATE alat SET stok_tersedia = stok_tersedia - 1 WHERE id=$alat_id");

            header("Location: user_dashboard.php?pesan=" . urlencode("Peminjaman alat " . $alat['nama_alat'] . " berhasil dicatat!"));
            exit;
        } else {
            $error = "Maaf, stok alat yang Anda pilih baru saja habis atau tidak tersedia.";
        }
    }
}

$selected_alat_id = (int) ($_GET['alat_id'] ?? 0);

include "header.php";
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="table-wrap">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa fa-hand-holding-hand fs-3 text-success"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Form Pengajuan Peminjaman Alat</h5>
                    <small class="text-muted">Laboratorium Manajemen Sistem Informasi</small>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 small mb-3">
                    <i class="fa fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="user_pinjam.php">
                <!-- Identitas Mahasiswa (Terkunci ke Akun Sendiri) -->
                <div class="mb-3 p-3 bg-light rounded-3 border">
                    <div class="row g-2 small">
                        <div class="col-sm-6">
                            <span class="text-muted d-block">Peminjam:</span>
                            <strong><?= htmlspecialchars($mhs['nama'] ?? $user['nama']) ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block">NIM:</span>
                            <code><?= htmlspecialchars($mhs['nim'] ?? $user['nim']) ?></code>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block">Program Studi:</span>
                            <span><?= htmlspecialchars($mhs['prodi'] ?? '-') ?></span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block">No. Telepon / WA:</span>
                            <span><?= htmlspecialchars($mhs['no_hp'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Alat Laboratorium <span class="text-danger">*</span></label>
                    <select name="alat_id" class="form-select" required>
                        <option value="">-- Pilih Alat yang Ingin Dipinjam --</option>
                        <?php
                        $alats = mysqli_query($con, "SELECT * FROM alat WHERE stok_tersedia > 0 ORDER BY nama_alat ASC");
                        while ($a = mysqli_fetch_assoc($alats)) {
                            $sel = ($a['id'] == $selected_alat_id) ? 'selected' : '';
                            echo "<option value='{$a['id']}' {$sel}>" 
                                . htmlspecialchars($a['nama_alat']) . " [{$a['kode_alat']}] - Tersedia: {$a['stok_tersedia']} unit"
                                . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">Rencana Tanggal Kembali <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_rencana_kembali" class="form-control" 
                               min="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <!-- Info Aturan Bisnis Sesuai Soal UTS -->
                <div class="alert alert-light border small text-muted mb-4">
                    <div class="fw-bold text-dark mb-1"><i class="fa fa-info-circle text-primary me-1"></i> Peraturan Peminjaman:</div>
                    <ul class="mb-0 ps-3">
                        <li>Pengembalian terlambat <strong>lebih dari 3 hari</strong> dari rencana kembali akan dikenakan denda keterlambatan <strong>Rp2.000 / hari</strong>.</li>
                        <li>Alat yang dikembalikan dalam kondisi <strong>rusak</strong> dikenakan biaya perbaikan tetap sebesar <strong>Rp10.000</strong>.</li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lab text-white flex-grow-1 py-2 fw-semibold">
                        <i class="fa fa-check me-1"></i> Ajukan Peminjaman
                    </button>
                    <a href="user_dashboard.php" class="btn btn-outline-secondary py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
