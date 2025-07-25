<?php
require 'function.php';
require 'ceklogin_pelanggan.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$id_produk = $_POST['id_produk'] ?? 0;

// Cek apakah sudah ada di favorit
$cek = mysqli_query($conn, "SELECT * FROM favorit WHERE id_pelanggan='$id_pelanggan' AND id_produk='$id_produk'");
if (mysqli_num_rows($cek) == 0) {
    mysqli_query($conn, "INSERT INTO favorit (id_pelanggan, id_produk) VALUES ('$id_pelanggan', '$id_produk')");
}

header("Location: detail_produk.php?id=$id_produk");
exit;
?>
