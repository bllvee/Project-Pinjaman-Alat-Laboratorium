<?php
include "db.php";
require_once "auth.php";
check_role('admin');

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: peminjaman.php");
    exit;
}

// ===== PROSES SIMPAN PENGEMBALIAN (BUSINESS RULE DI SINI) =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id'];
    $tanggal_kembali_aktual = $_POST['tanggal_kembali_aktual'];
    $kondisi = $_POST['kondisi_saat_kembali']; // 'baik' atau 'rusak'

    $data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM peminjaman WHERE id=$id"));

    // Hitung keterlambatan (dalam hari) dibanding rencana kembali
    $tgl_rencana = new DateTime($data['tanggal_rencana_kembali']);
    $tgl_aktual  = new DateTime($tanggal_kembali_aktual);
    $selisih     = $tgl_rencana->diff($tgl_aktual);
    $hari_terlambat = ($tgl_aktual > $tgl_rencana) ? (int) $selisih->days : 0;

    // ATURAN 1: Jika terlambat > 3 hari -> denda Rp2.000 / hari (dihitung dari seluruh hari terlambat)
    $denda_keterlambatan = ($hari_terlambat > 3) ? $hari_terlambat * 2000 : 0;

    // ATURAN 2: Jika alat dikembalikan rusak -> biaya perbaikan Rp10.000
    $biaya_perbaikan = ($kondisi == 'rusak') ? 10000 : 0;

    $total_denda = $denda_keterlambatan + $biaya_perbaikan;

    mysqli_query($con, "UPDATE peminjaman SET
                            tanggal_kembali_aktual = '$tanggal_kembali_aktual',
                            kondisi_saat_kembali = '$kondisi',
                            hari_terlambat = $hari_terlambat,
                            denda_keterlambatan = $denda_keterlambatan,
                            biaya_perbaikan = $biaya_perbaikan,
                            total_denda = $total_denda,
                            status = 'dikembalikan'
                         WHERE id = $id");

    // Stok alat bertambah kembali setelah dikembalikan
    mysqli_query($con, "UPDATE alat SET stok_tersedia = stok_tersedia + 1 WHERE id = {$data['alat_id']}");

    header("Location: peminjaman.php?pesan=" . urlencode("Pengembalian berhasil disimpan. Total denda: Rp" . number_format($total_denda, 0, ',', '.')));
    exit;
}

// ===== AMBIL DATA UNTUK DITAMPILKAN DI FORM =====
$id = (int) $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($con, "SELECT p.*, m.nama AS nama_mahasiswa, m.nim, a.nama_alat
                                                FROM peminjaman p
                                                JOIN mahasiswa m ON m.id = p.mahasiswa_id
                                                JOIN alat a ON a.id = p.alat_id
                                                WHERE p.id=$id"));

if (!$data || $data['status'] != 'dipinjam') {
    header("Location: peminjaman.php");
    exit;
}

include "header.php";
?>

<h3 class="page-title"><i class="fa fa-undo"></i> Proses Pengembalian Alat</h3>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-wrap">
            <table class="table table-borderless mb-4">
                <tr><th width="40%">Mahasiswa</th><td>: <?= htmlspecialchars($data['nama_mahasiswa']) ?> (<?= htmlspecialchars($data['nim']) ?>)</td></tr>
                <tr><th>Alat</th><td>: <?= htmlspecialchars($data['nama_alat']) ?></td></tr>
                <tr><th>Tanggal Pinjam</th><td>: <?= $data['tanggal_pinjam'] ?></td></tr>
                <tr><th>Rencana Kembali</th><td>: <?= $data['tanggal_rencana_kembali'] ?></td></tr>
            </table>

            <form method="post">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                <div class="mb-2">
                    <label class="form-label">Tanggal Kembali Aktual</label>
                    <input type="date" name="tanggal_kembali_aktual" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kondisi Alat Saat Kembali</label>
                    <select name="kondisi_saat_kembali" class="form-select" required>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>
                <div class="alert alert-light border small">
                    <i class="fa fa-circle-info"></i>
                    Aturan denda: keterlambatan &gt; 3 hari dikenakan Rp2.000/hari.
                    Jika alat kembali dalam kondisi rusak, dikenakan biaya perbaikan Rp10.000.
                    Perhitungan akan dilakukan otomatis oleh sistem setelah form disimpan.
                </div>
                <button class="btn btn-primary-lab text-white w-100" type="submit">
                    <i class="fa fa-save"></i> Simpan Pengembalian
                </button>
                <a href="peminjaman.php" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
