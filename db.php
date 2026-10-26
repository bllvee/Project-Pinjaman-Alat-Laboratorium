<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "lab_peminjaman";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
