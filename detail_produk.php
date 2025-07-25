<?php
require 'function.php';
require 'ceklogin_pelanggan.php';

$id_produk = $_GET['id'] ?? 0;
$produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id_produk'"));

if (!$produk) {
  echo "Produk tidak ditemukan";
  exit;
}

// Simpan ke "terakhir_dilihat"
$id_pelanggan = $_SESSION['id_pelanggan'];
$id_produk = $_GET['id'] ?? 0;

mysqli_query($conn, "
  INSERT INTO terakhir_dilihat (id_pelanggan, id_produk)
  VALUES ('$id_pelanggan', '$id_produk')
");

// Cek jumlah item di keranjang
$id_pelanggan = $_SESSION['id_pelanggan'];
$qkeranjang = mysqli_query($conn, "SELECT SUM(qty) AS total_item FROM keranjang WHERE id_pelanggan = '$id_pelanggan'");
$jumlah_item_keranjang = mysqli_fetch_assoc($qkeranjang)['total_item'] ?? 0;

// Cek apakah produk ini sudah difavoritkan
$cek_favorit = mysqli_query($conn, "SELECT * FROM favorit WHERE id_pelanggan = '$id_pelanggan' AND id_produk = '$id_produk'");
$sudah_favorit = mysqli_num_rows($cek_favorit) > 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($produk['nama_produk']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff;
    }
    .price {
      font-size: 1.7rem;
      color: #e53935;
      font-weight: bold;
    }
    .old-price {
      text-decoration: line-through;
      color: #888;
    }
    .badge-diskon {
      background-color: #ff5252;
      color: #fff;
      font-size: 0.8rem;
      padding: 3px 6px;
      border-radius: 4px;
    }
    .product-info {
      border-top: 1px solid #eee;
      margin-top: 1rem;
      padding-top: 1rem;
      font-size: 0.9rem;
    }
    .buy-button {
      position: sticky;
      bottom: 0;
      width: 100%;
      z-index: 1000;
    }
    .navbar {
      background-color: #ffc107;
    }
    .favorit-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 1.5rem;
      color: red;
      background: white;
      border-radius: 50%;
      padding: 5px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container-fluid d-flex align-items-center justify-content-between px-3">
    <a class="navbar-brand fw-bold text-dark" href="dbpelanggan.php">RieeKasir</a>

    <ul class="navbar-nav flex-row gap-3 align-items-center">
      <li class="nav-item">
        <a class="nav-link text-dark position-relative" href="keranjang.php">
          <i class="bi bi-cart-fill fs-5"></i>
          <?php if ($jumlah_item_keranjang > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= $jumlah_item_keranjang ?>
          </span>
          <?php endif; ?>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link text-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle fs-5"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="profil_pelanggan.php"><i class="bi bi-person"></i> Profil</a></li>
          <li><a class="dropdown-item" href="riwayat_pembelian.php"><i class="bi bi-clock-history"></i> Riwayat</a></li>
          <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Konten -->
<div class="container px-3 pt-3">
  <!-- Gambar Produk -->
  <div class="position-relative">
    <div id="carouselProduk" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="img/produk/<?= htmlspecialchars($produk['gambar']) ?>" class="d-block w-100" alt="Gambar Produk">
        </div>
      </div>
    </div>

      <!-- favorit -->
    <form method="post" action="tambah_favorit.php" class="mb-2">
      <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
      <button type="submit" class="btn btn-outline-danger w-100 rounded-0 py-2"><i class="bi bi-heart"></i> Favorit</button>
    </form>

  <!-- Nama & Harga -->
  <div class="mt-3">
    <h5><?= htmlspecialchars($produk['nama_produk']) ?></h5>
    <?php if ($produk['diskon'] > 0): ?>
      <div class="d-flex align-items-center gap-2">
        <span class="price">Rp<?= number_format($produk['harga'] - $produk['diskon']) ?></span>
        <span class="old-price">Rp<?= number_format($produk['harga']) ?></span>
        <span class="badge-diskon">Diskon <?= round($produk['diskon'] / $produk['harga'] * 100) ?>%</span>
      </div>
    <?php else: ?>
      <div class="price">Rp<?= number_format($produk['harga']) ?></div>
    <?php endif; ?>
  </div>

  <!-- Info -->
  <div class="product-info">
    <div><strong>Garansi:</strong> Tiba sesuai estimasi atau uang kembali</div>
    <div><strong>Pengiriman:</strong> Cepat dan aman</div>
    <div><strong>Kondisi:</strong> Baru</div>
  </div>

  <!-- Deskripsi -->
  <div class="product-info">
    <h6>Deskripsi Produk</h6>
    <p><?= nl2br(htmlspecialchars($produk['deskripsi'] ?? 'Tidak ada deskripsi')) ?></p>
  </div>
</div>

<!-- Tombol Beli -->
<form method="post" action="tambah_keranjang.php">
  <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
  <input type="hidden" name="qty" value="1">
  <div class="buy-button">
    <button type="submit" class="btn btn-danger w-100 rounded-0 py-3">+ Tambah ke Keranjang</button>
  </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
