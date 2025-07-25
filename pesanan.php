<?php
require 'ceklogin.php'; 
require 'function.php';
// Tambah pesanan
if (isset($_POST['tambahpesanan'])) {
    $idpelanggan = $_POST['idpelanggan'];
    $tanggal = date('Y-m-d');

    $insert = mysqli_query($c, "INSERT INTO pesanan (idpelanggan, tanggal) VALUES ('$idpelanggan', '$tanggal')");

    if ($insert) {
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal membuat pesanan: " . mysqli_error($c) . "');</script>";
    }
}

$h1 = mysqli_query($c,"SELECT * FROM pesanan");
$h2 = mysqli_num_rows($h1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h1>Data Pesanan</h1>
    <p>Jumlah pesanan: <strong><?= $h2 ?></strong></p>

    <!-- Tombol buka modal -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#myModal">Tambah Pesanan</button>

    <!-- Tabel data -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>Jumlah Item</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $get = mysqli_query($c, "
                SELECT p.idpesanan AS idorder, p.tanggal, pl.namapelanggan, pl.alamat 
                FROM pesanan p 
                JOIN pelanggan pl ON p.idpelanggan = pl.idpelanggan
            ");
            if (mysqli_num_rows($get) == 0) {
                echo "<tr><td colspan='5'>Data kosong</td></tr>";
            }
            while ($P = mysqli_fetch_array($get)) {
                $idorder = $P['idorder'];
                $tanggal = $P['tanggal'];
                $namapelanggan = $P['namapelanggan'];
                $alamat = $P['alamat'];

                // Hitung jumlah produk di pesanan
                $hitungjumlah = mysqli_query($c, "SELECT * FROM detailpesanan WHERE idpesanan='$idorder'");
                $jumlah = mysqli_num_rows($hitungjumlah);
            ?>
            <tr>
                <td><?= $idorder ?></td>
                <td><?= $tanggal ?></td>
                <td><?= $namapelanggan ?> - <?= $alamat ?></td>
                <td><?= $jumlah ?></td>
                <td><a href="view.php?idp=<?= $idorder ?>" class="btn btn-sm btn-info">Lihat</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Modal tambah pesanan -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Pesanan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <label for="idpelanggan">Pilih Pelanggan:</label>
            <select name="idpelanggan" class="form-select" required>
                <?php
                $getpelanggan = mysqli_query($c, "SELECT * FROM pelanggan");
                while ($pl = mysqli_fetch_array($getpelanggan)) {
                    $idpel = $pl['idpelanggan'];
                    $nama = $pl['namapelanggan'];
                    $alamat = $pl['alamat'];
                    echo "<option value='$idpel'>$nama - $alamat</option>";
                }
                ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" name="tambahpesanan" class="btn btn-success">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
        </form>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
