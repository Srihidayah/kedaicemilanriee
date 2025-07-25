<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Cek apakah keranjang milik pelanggan yang sedang login
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id = '$id' AND id_pelanggan = '{$_SESSION['id_pelanggan']}'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM keranjang WHERE id = '$id'");
    }
}

header("Location: keranjang.php");
exit;
