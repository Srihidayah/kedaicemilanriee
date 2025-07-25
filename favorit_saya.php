<?php
require 'function.php';
require 'ceklogin_pelanggan.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$produk = mysqli_query($conn, "
  SELECT pr.* FROM favorit f 
  JOIN produk pr ON f.id_produk = pr.id_produk 
  WHERE f.id_pelanggan = '$id_pelanggan'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Favorit Saya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #fffde7);
      padding-bottom: 80px;
      font-family: 'Segoe UI', sans-serif;
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
      padding: 0.75rem;
    }

    .produk-card .card-title {
      font-size: 14px;
      margin-bottom: 0.25rem;
    }

    .produk-card .harga {
      font-weight: bold;
      font-size: 14px;
    }

    .produk-card .harga-asli {
      font-size: 12px;
      color: #eee;
      text-decoration: line-through;
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
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark sticky-top shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-dark" href="dbpelanggan.php"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
    <span class="fw-semibold text-dark">Favorit Saya</span>
  </div>
</nav>

<div class="container py-4">
  <h5 class="mb-3 text-danger">❤️ Produk yang Anda Sukai</h5>
  <div class="row">
    <?php if ($produk && mysqli_num_rows($produk) > 0): ?>
      <?php while($p = mysqli_fetch_assoc($produk)): ?>
      <div class="col-6 col-md-3 mb-4">
        <a href="detail_produk.php?id=<?= $p['id_produk'] ?>" class="text-decoration-none">
          <div class="card produk-card h-100">
            <img src="img/produk/<?= $p['gambar'] ?>" class="card-img-top" alt="<?= $p['nama_produk'] ?>">
            <div class="card-body">
              <h6 class="card-title text-truncate"><?= $p['nama_produk'] ?></h6>
              <?php if ($p['diskon'] > 0): ?>
                <div class="harga">Rp<?= number_format($p['harga'] - $p['diskon']) ?></div>
                <div class="harga-asli">Rp<?= number_format($p['harga']) ?></div>
              <?php else: ?>
                <div class="harga">Rp<?= number_format($p['harga']) ?></div>
              <?php endif; ?>
            </div>
          </div>
        </a>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center mt-5">
        <i class="bi bi-heart fs-1 text-muted"></i>
        <p class="text-muted mt-3">Belum ada produk favorit Anda.</p>
        <a href="dbpelanggan.php" class="btn btn-warning mt-2"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Bottom Nav -->
<div class="bottom-nav">
  <a href="dbpelanggan.php">
    <i class="bi bi-house-door-fill"></i>
    <div>Beranda</div>
  </a>
  <a href="keranjang.php">
    <i class="bi bi-cart-fill"></i>
    <div>Keranjang</div>
  </a>
  <a href="profil_pelanggan.php" class="active">
    <i class="bi bi-person-circle"></i>
    <div>Akun</div>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
