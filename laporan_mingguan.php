<?php
require 'ceklogin.php';
require 'function.php';

// Mendapatkan tanggal 7 hari terakhir
$minggu_lalu = date('Y-m-d', strtotime('-7 days'));
$hari_ini = date('Y-m-d');

// Ambil data transaksi dalam 7 hari terakhir
$query = mysqli_query($conn, "
    SELECT p.tanggal, pr.nama_produk, dp.qty, dp.harga, (dp.qty * dp.harga) AS subtotal
    FROM detailpesanan dp
    JOIN produk pr ON dp.idproduk = pr.id_produk
    JOIN pesanan p ON dp.idpesanan = p.idorder
    WHERE p.tanggal BETWEEN '$minggu_lalu' AND '$hari_ini'
    ORDER BY p.tanggal DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mingguan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h3 class="mb-4">Laporan Penjualan Mingguan</h3>
    <p>Periode: <?= date('d-m-Y', strtotime($minggu_lalu)) ?> sampai <?= date('d-m-Y', strtotime($hari_ini)) ?></p>

    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            while ($row = mysqli_fetch_assoc($query)):
                $total += $row['subtotal'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['nama_produk'] ?></td>
                <td><?= $row['qty'] ?></td>
                <td>Rp<?= number_format($row['harga']) ?></td>
                <td>Rp<?= number_format($row['subtotal']) ?></td>
            </tr>
            <?php endwhile; ?>
            <tr class="table-secondary">
                <td colspan="5" class="text-end"><strong>Total</strong></td>
                <td><strong>Rp<?= number_format($total) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary mt-3">← Kembali ke Dashboard</a>
</div>
</body>
</html>
