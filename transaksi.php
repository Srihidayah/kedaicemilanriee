<?php
require 'ceklogin.php';
require 'function.php';

// Jika form disubmit
if (isset($_POST['tambah'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $tanggal = date('Y-m-d');

    // Simpan ke tabel pesanan
    mysqli_query($conn, "INSERT INTO pesanan (idpelanggan, tanggal) VALUES ('$id_pelanggan', '$tanggal')");
    $idorder = mysqli_insert_id($conn);

    header("Location: pesanan_detail.php?idp=$idorder");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2>Transaksi Baru</h2>
        <form method="post" class="row g-3">
            <div class="col-md-6">
                <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
                    while ($p = mysqli_fetch_assoc($pelanggan)):
                    ?>
                        <option value="<?= $p['id_pelanggan'] ?>"><?= $p['nama_pelanggan'] ?> - <?= $p['kontak'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" name="tambah" class="btn btn-primary w-100">Buat Transaksi</button>
            </div>
        </form>

        <hr class="my-4">

        <h4>Riwayat Transaksi Terakhir</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($conn, "SELECT p.idorder, p.tanggal, pl.nama_pelanggan FROM pesanan p JOIN pelanggan pl ON p.idpelanggan = pl.id_pelanggan ORDER BY p.idorder DESC LIMIT 10");
                while ($d = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $d['tanggal'] ?></td>
                    <td><?= $d['nama_pelanggan'] ?></td>
                    <td>
                        <a href="pesanan_detail.php?idp=<?= $d['idorder'] ?>" class="btn btn-info btn-sm">Lihat</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary mt-3">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
