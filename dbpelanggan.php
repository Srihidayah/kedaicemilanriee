<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$nama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'"))['nama_pelanggan'];

$produk_semua = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 12") ?: [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Pelanggan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #fffde7);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 80px;
    }

    .navbar {
      background-color: #fbc02d !important;
    }

    .produk-card {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.2s ease;
    }

    .produk-card:hover {
      transform: scale(1.03);
    }

    .produk-card img {
      height: 160px;
      object-fit: cover;
    }

    .produk-card .card-body {
      background: #1e88e5;
      color: white;
    }

    .produk-card .card-title {
      font-size: 14px;
      margin-bottom: 0.25rem;
    }

    .produk-card p {
      font-size: 13px;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: #fff;
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 8px 0;
      z-index: 999;
    }

    .bottom-nav a {
      text-decoration: none;
      color: #555;
      font-size: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 33.33%;
      transition: 0.2s ease-in-out;
    }

    .bottom-nav a i {
      font-size: 20px;
      margin-bottom: 2px;
    }

    .bottom-nav a.active {
      color: #e53935;
      font-weight: bold;
    }

    .kategori-scroll {
      background: #e53935;
      color: white;
      font-weight: bold;
    }

    .kategori-scroll a {
      color: white;
      padding: 10px 14px;
      text-decoration: none;
      display: inline-block;
      white-space: nowrap;
    }

    .kategori-scroll a:hover {
      background-color: #1e88e5;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="dbpelanggan.php">
      <img src="img/logo.png" alt="Logo" width="36" height="36" class="me-2 rounded-circle">
      Kedai Cemilan Riee
    </a>
    <form class="d-flex me-3" action="cari_produk.php" method="get">
      <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Cari produk..." aria-label="Search">
      <button class="btn btn-light btn-sm" type="submit"><i class="bi bi-search"></i></button>
    </form>
  </div>
</nav>

<!-- Kategori -->
<div class="kategori-scroll d-flex overflow-auto border-bottom">
  <a href="semua_produk.php"><i class="bi bi-box-seam"></i> Semua Produk</a>
</div>

<!-- Produk -->
<div class="container py-4 mb-5">
  <h5 class="mb-3 text-danger"><i class="bi bi-star-fill"></i> Produk Tersedia</h5>
  <div class="row">
    <?php while($p = mysqli_fetch_assoc($produk_semua)): ?>
    <div class="col-6 col-md-3 mb-4">
      <a href="detail_produk.php?id=<?= $p['id_produk'] ?>" class="text-decoration-none">
        <div class="card produk-card">
          <img src="img/produk/<?= $p['gambar'] ?? 'default.jpg' ?>" class="card-img-top" alt="Produk">
          <div class="card-body p-2">
            <h6 class="card-title text-truncate"><?= $p['nama_produk'] ?></h6>
            <p class="text-light fw-bold mb-0">Rp<?= number_format($p['harga']) ?></p>
          </div>
        </div>
      </a>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- Bottom Nav -->
<div class="bottom-nav">
  <a href="dbpelanggan.php" class="active">
    <i class="bi bi-house-door-fill"></i>
    <div>Beranda</div>
  </a>
  <a href="keranjang.php">
    <i class="bi bi-cart-fill"></i>
    <div>Keranjang</div>
  </a>
  <a href="profil_pelanggan.php">
    <i class="bi bi-person-circle"></i>
    <div>Akun</div>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
