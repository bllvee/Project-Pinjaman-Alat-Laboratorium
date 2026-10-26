<?php
require_once "auth.php";
check_login();

$user = current_user();
$current = basename($_SERVER['PHP_SELF']);
$role = $user['role'] ?? 'mahasiswa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Peminjaman Alat Laboratorium</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $role === 'admin' ? 'index.php' : 'user_dashboard.php' ?>">
            <i class="fa fa-flask fs-4 text-warning"></i> 
            <span>SiPinjam Lab</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <?php if ($role === 'admin'): ?>
                    <!-- Menu Khusus Admin -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='index.php'?'active':'' ?>" href="index.php">
                            <i class="fa fa-gauge me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='mahasiswa.php'?'active':'' ?>" href="mahasiswa.php">
                            <i class="fa fa-user-graduate me-1"></i> Data Mahasiswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='alat.php'?'active':'' ?>" href="alat.php">
                            <i class="fa fa-flask-vial me-1"></i> Data Alat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($current, ['peminjaman.php', 'kembalikan.php']) ?'active':'' ?>" href="peminjaman.php">
                            <i class="fa fa-right-left me-1"></i> Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='laporan.php'?'active':'' ?>" href="laporan.php">
                            <i class="fa fa-file-lines me-1"></i> Laporan
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Menu Khusus Mahasiswa -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='user_dashboard.php'?'active':'' ?>" href="user_dashboard.php">
                            <i class="fa fa-house me-1"></i> Beranda Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='user_katalog.php'?'active':'' ?>" href="user_katalog.php">
                            <i class="fa fa-boxes-stacked me-1"></i> Katalog Alat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='user_pinjam.php'?'active':'' ?>" href="user_pinjam.php">
                            <i class="fa fa-hand-holding-hand me-1"></i> Ajukan Pinjam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current=='user_riwayat.php'?'active':'' ?>" href="user_riwayat.php">
                            <i class="fa fa-clock-rotate-left me-1"></i> Riwayat Pinjam
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Info User & Logout -->
            <div class="d-flex align-items-center gap-2 text-white">
                <div class="text-end d-none d-sm-block lh-sm">
                    <div class="fw-semibold small"><?= htmlspecialchars($user['nama']) ?></div>
                    <span class="badge <?= $role === 'admin' ? 'bg-warning text-dark' : 'bg-info text-dark' ?>" style="font-size: 10px;">
                        <i class="fa <?= $role === 'admin' ? 'fa-shield-halved' : 'fa-graduation-cap' ?> me-1"></i>
                        <?= strtoupper($role) ?>
                    </span>
                </div>
                <a href="logout.php" class="btn btn-sm btn-outline-light ms-2" title="Keluar">
                    <i class="fa fa-arrow-right-from-bracket"></i> <span class="d-none d-md-inline ms-1">Keluar</span>
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="container my-4">
