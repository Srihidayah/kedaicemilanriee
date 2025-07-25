<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

if (isset($_POST['tambahpesanan'])) {
    $idpelanggan = $_SESSION['id_pelanggan'];
    $tanggal = date('Y-m-d');

    // Insert pesanan baru
    $insert = mysqli_query($conn, "INSERT INTO pesanan (idpelanggan, tanggal) VALUES ('$idpelanggan', '$tanggal')");

    if ($insert) {
        $idorder = mysqli_insert_id($conn); // ambil id pesanan terakhir

        // Masukkan notifikasi
        $pesan = "Pesanan Anda berhasil diproses. No Pesanan: #$idorder";
        $notif = mysqli_query($conn, "INSERT INTO notifikasi (id_pelanggan, pesan, status) VALUES ('$idpelanggan', '$pesan', 'belum_dibaca')");

        // Jika notifikasi gagal, tampilkan error
        if (!$notif) {
            echo "Gagal menyimpan notifikasi: " . mysqli_error($conn);
            exit;
        }

        // Redirect ke halaman riwayat dengan indikator berhasil
        header("Location: riwayat_pembelian.php?berhasil=1");
        exit;
    } else {
        echo "Gagal menambahkan pesanan: " . mysqli_error($conn);
    }
}
?>
