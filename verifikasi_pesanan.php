<?php
require 'ceklogin.php';
require 'function.php';

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Validasi parameter
if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    echo "<script>alert('Permintaan tidak valid'); location.href='konfirmasi_pesanan.php';</script>";
    exit();
}

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi == 'konfirmasi') {
    $update = mysqli_query($conn, "UPDATE pesanan SET status='Dikonfirmasi' WHERE idorder='$id'");
    if ($update) {
        echo "<script>alert('Pesanan berhasil dikonfirmasi!'); location.href='konfirmasi_pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal mengkonfirmasi pesanan.'); location.href='konfirmasi_pesanan.php';</script>";
    }
} elseif ($aksi == 'tolak') {
    $update = mysqli_query($conn, "UPDATE pesanan SET status='Ditolak' WHERE idorder='$id'");
    if ($update) {
        echo "<script>alert('Pesanan berhasil ditolak.'); location.href='konfirmasi_pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal menolak pesanan.'); location.href='konfirmasi_pesanan.php';</script>";
    }
} else {
    echo "<script>alert('Aksi tidak dikenali'); location.href='konfirmasi_pesanan.php';</script>";
}
?>
