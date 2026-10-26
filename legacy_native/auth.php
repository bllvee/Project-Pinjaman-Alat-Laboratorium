<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Cek apakah user sudah login.
 * Jika belum, redirect ke login.php.
 */
function check_login() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Cek apakah user memiliki role tertentu ('admin' atau 'mahasiswa').
 * Jika role tidak sesuai, redirect ke dashboard masing-masing.
 */
function check_role($required_role) {
    check_login();
    if ($_SESSION['user']['role'] !== $required_role) {
        if ($_SESSION['user']['role'] === 'mahasiswa') {
            header("Location: user_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }
}

/**
 * Ambil data user yang sedang login saat ini.
 */
function current_user() {
    return $_SESSION['user'] ?? null;
}
?>
