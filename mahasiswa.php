<?php
include "db.php";
require_once "auth.php";
check_role('admin');

// ===== PROSES TAMBAH / EDIT =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim   = mysqli_real_escape_string($con, $_POST['nim']);
    $nama  = mysqli_real_escape_string($con, $_POST['nama']);
    $prodi = mysqli_real_escape_string($con, $_POST['prodi']);
    $no_hp = mysqli_real_escape_string($con, $_POST['no_hp']);

    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        mysqli_query($con, "UPDATE mahasiswa SET nim='$nim', nama='$nama', prodi='$prodi', no_hp='$no_hp' WHERE id=$id");
        mysqli_query($con, "UPDATE users SET username='$nim', nama='$nama' WHERE mahasiswa_id=$id");
    } else {
        mysqli_query($con, "INSERT INTO mahasiswa (nim, nama, prodi, no_hp) VALUES ('$nim', '$nama', '$prodi', '$no_hp')");
        $new_id = mysqli_insert_id($con);
        // Buat akun login mahasiswa otomatis (password default: 123456)
        $pass_hash = password_hash('123456', PASSWORD_DEFAULT);
        mysqli_query($con, "INSERT INTO users (username, password, role, nama, mahasiswa_id) 
                            VALUES ('$nim', '$pass_hash', 'mahasiswa', '$nama', $new_id)");
    }
    header("Location: mahasiswa.php");
    exit;
}

// ===== PROSES HAPUS =====
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($con, "DELETE FROM users WHERE mahasiswa_id=$id");
    mysqli_query($con, "DELETE FROM mahasiswa WHERE id=$id");
    header("Location: mahasiswa.php");
    exit;
}

// ===== DATA UNTUK FORM EDIT =====
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM mahasiswa WHERE id=$id"));
}

include "header.php";
?>

<h3 class="page-title"><i class="fa fa-user-graduate"></i> Data Mahasiswa</h3>

<div class="row g-4">
    <div class="col-md-4">
        <div class="table-wrap">
            <h5 class="mb-3"><?= $edit_data ? 'Edit' : 'Tambah' ?> Mahasiswa</h5>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
                <div class="mb-2">
                    <label class="form-label">NIM</label>
                    <input type="text" name="nim" class="form-control" required
                           value="<?= htmlspecialchars($edit_data['nim'] ?? '') ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required
                           value="<?= htmlspecialchars($edit_data['nama'] ?? '') ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Program Studi</label>
                    <input type="text" name="prodi" class="form-control" required
                           value="<?= htmlspecialchars($edit_data['prodi'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control"
                           value="<?= htmlspecialchars($edit_data['no_hp'] ?? '') ?>">
                </div>
                <button class="btn btn-primary-lab text-white w-100" type="submit">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <?php if ($edit_data): ?>
                    <a href="mahasiswa.php" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-wrap">
            <h5 class="mb-3">Daftar Mahasiswa</h5>
            <table class="table table-hover align-middle">
                <thead>
                <tr><th>No</th><th>NIM</th><th>Nama</th><th>Prodi</th><th>No. HP</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php
                $q = mysqli_query($con, "SELECT * FROM mahasiswa ORDER BY id ASC");
                $no = 1;
                while ($r = mysqli_fetch_assoc($q)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>" . htmlspecialchars($r['nim']) . "</td>
                            <td>" . htmlspecialchars($r['nama']) . "</td>
                            <td>" . htmlspecialchars($r['prodi']) . "</td>
                            <td>" . htmlspecialchars($r['no_hp']) . "</td>
                            <td>
                                <a href='mahasiswa.php?edit={$r['id']}' class='btn btn-sm btn-warning'><i class='fa fa-pen'></i></a>
                                <a href='mahasiswa.php?hapus={$r['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Hapus data ini?')\"><i class='fa fa-trash'></i></a>
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
