<?php
require 'ceklogin.php';
require 'function.php';

if (!isset($_GET['idp'])) {
    header("Location: index.php");
    exit;
}

$idp = intval($_GET['idp']);

$q = mysqli_query($conn, "SELECT p.idorder, pl.nama_pelanggan FROM pesanan p JOIN pelanggan pl ON p.idpelanggan = pl.id_pelanggan WHERE p.idorder = '$idp'");
if (!$q || mysqli_num_rows($q) == 0) {
    echo "Data pesanan tidak ditemukan.";
    exit;
}
$data = mysqli_fetch_assoc($q);
$namapel = $data['nama_pelanggan'];

if (isset($_POST['addproduk'])) {
    $id_produk = $_POST['idproduk'];
    $qty = $_POST['qty'];

    $qproduk = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk = '$id_produk'");
    if ($qproduk && mysqli_num_rows($qproduk) > 0) {
        $data_produk = mysqli_fetch_assoc($qproduk);
        $harga = $data_produk['harga'];

        mysqli_query($conn, "INSERT INTO detailpesanan (idpesanan, idproduk, qty, harga) VALUES ('$idp', '$id_produk', '$qty', '$harga')");
        mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");
    }

    header("Location: pesanan_detail.php?idp=$idp");
    exit;
}

if (isset($_POST['hapusprodukpesanan'])) {
    $iddetail = $_POST['idp'];
    $idproduk = $_POST['idpr'];
    $idorder  = $_POST['idorder'];

    $q = mysqli_query($conn, "SELECT qty FROM detailpesanan WHERE iddetailpesanan = '$iddetail'");
    if ($q && mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        $qty = $data['qty'];

        mysqli_query($conn, "UPDATE produk SET stok = stok + $qty WHERE id_produk = '$idproduk'");
        mysqli_query($conn, "DELETE FROM detailpesanan WHERE iddetailpesanan = '$iddetail'");
    }

    header("Location: pesanan_detail.php?idp=$idorder");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan - Kedai Cemilan Riee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d6efd">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --merah: #e53935;
            --biru: #1e88e5;
            --kuning: #fbc02d;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e3f2fd, #fff8e1);
            transition: background 0.3s ease;
        }

        [data-bs-theme="dark"] body {
            background: #121212;
        }

        .navbar-custom {
            background-color: var(--biru);
        }

        .glass {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        [data-bs-theme="dark"] .glass {
            background: rgba(30, 30, 30, 0.88);
            color: #f1f1f1;
        }

        .btn-biru {
            background-color: var(--biru);
            color: #fff;
            border: none;
        }

        .btn-biru:hover {
            background-color: #1565c0;
            color: #fff;
        }

        .btn-merah {
            background-color: var(--merah);
            color: #fff;
            border: none;
        }

        .btn-merah:hover {
            background-color: #c62828;
        }

        .btn-kuning {
            background-color: var(--kuning);
            color: #000;
            border: none;
        }

        .btn-kuning:hover {
            background-color: #f9a825;
            color: #000;
        }

        .badge-kuning {
            background-color: var(--kuning);
            color: #000;
        }

        .badge-merah {
            background-color: var(--merah);
            color: #fff;
        }

        .badge-biru {
            background-color: var(--biru);
            color: #fff;
        }

        .btn-sm:hover {
            transform: scale(1.03);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-shop-window"></i> Kedai Cemilan Riee
        </a>
        <div class="d-flex flex-wrap gap-2">
            <a href="produk.php" class="btn btn-kuning btn-sm"><i class="bi bi-box"></i> Produk</a>
            <a href="masuk.php" class="btn btn-kuning btn-sm"><i class="bi bi-download"></i> Masuk</a>
            <a href="pelanggan.php" class="btn btn-kuning btn-sm"><i class="bi bi-people"></i> Pelanggan</a>
            <a href="laporan.php" class="btn btn-kuning btn-sm"><i class="bi bi-bar-chart"></i> Laporan</a>
            <a href="konfirmasi_pesanan.php" class="btn btn-kuning btn-sm"><i class="bi bi-check-circle"></i> Konfirmasi</a>
            <a href="logout.php" class="btn btn-merah btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
            <button id="themeToggle" class="btn btn-outline-light btn-sm" title="Mode Gelap/Terang">
                <i id="themeIcon" class="bi bi-moon"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container py-5">
    <div class="glass p-4">
        <h4 class="mb-3 fw-bold"><i class="bi bi-receipt"></i> Detail Pesanan <span class="text-muted">#<?= $idp ?></span></h4>
        <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($namapel) ?></p>

        <button class="btn btn-biru btn-sm mb-4" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Produk</button>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $get = mysqli_query($conn, "SELECT dp.*, pr.nama_produk, pr.harga 
                    FROM detailpesanan dp 
                    JOIN produk pr ON dp.idproduk = pr.id_produk 
                    WHERE dp.idpesanan = '$idp'");
                $no = 1;
                $total = 0;
                while ($row = mysqli_fetch_assoc($get)):
                    $subtotal = $row['qty'] * $row['harga'];
                    $total += $subtotal;
                ?>
                    <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td class="text-end">Rp<?= number_format($row['harga']) ?></td>
                        <td><?= $row['qty'] ?></td>
                        <td class="text-end">Rp<?= number_format($subtotal) ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Hapus produk ini?')" class="d-inline">
                                <input type="hidden" name="idp" value="<?= $row['iddetailpesanan'] ?>">
                                <input type="hidden" name="idpr" value="<?= $row['idproduk'] ?>">
                                <input type="hidden" name="idorder" value="<?= $idp ?>">
                                <button type="submit" name="hapusprodukpesanan" class="btn btn-outline-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <tr class="table-success fw-bold text-end">
                    <td colspan="4">Total</td>
                    <td>Rp<?= number_format($total) ?></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="invoice.php?idp=<?= $idp ?>" class="btn btn-success"><i class="bi bi-printer"></i> Cetak Invoice</a>
            <a href="index.php" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="idproduk" class="form-label">Pilih Produk</label>
                <select name="idproduk" class="form-select mb-3" required>
                    <?php
                    $produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");
                    while ($p = mysqli_fetch_assoc($produk)):
                    ?>
                    <option value="<?= $p['id_produk'] ?>"><?= $p['nama_produk'] ?> (Stok: <?= $p['stok'] ?>)</option>
                    <?php endwhile; ?>
                </select>
                <label class="form-label">Jumlah</label>
                <input type="number" name="qty" class="form-control" placeholder="Jumlah" min="1" required>
            </div>
            <div class="modal-footer">
                <button type="submit" name="addproduk" class="btn btn-success">+ Tambah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', currentTheme);
    updateIcon(currentTheme);

    themeToggle.addEventListener('click', () => {
        const newTheme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcon(newTheme);
    });

    function updateIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    }
</script>
</body>
</html>
