<?php
require 'ceklogin.php';
require 'function.php';

// Proses tambah produk
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];

    $insert = mysqli_query($conn, "INSERT INTO produk (nama_produk, stok) VALUES ('$nama', '$stok')");
    if ($insert) {
        header("Location: stock.php");
    }
}

// Proses hapus produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id'");
    header("Location: stock.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Data Produk</h2>

        <!-- Form Tambah Produk -->
        <form method="post" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="nama" class="form-control" placeholder="Nama Produk" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="stok" class="form-control" placeholder="Stok Awal" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="tambah" class="btn btn-success w-100">Tambah</button>
            </div>
        </form>

        <!-- Tabel Produk -->
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $get = mysqli_query($conn, "SELECT * FROM produk");
                while ($p = mysqli_fetch_array($get)) {
                    $id = $p['id_produk'];
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($p['nama_produk']); ?></td>
                        <td><?= $p['stok']; ?></td>
                        <td>
                            <a href="stock.php?hapus=<?= $id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary mt-3">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
