<?php
require 'ceklogin_pelanggan.php'; 
require 'function.php';

$q = $_GET['q'] ?? '';
$hasil = [];

if ($q !== '') {
    $q = mysqli_real_escape_string($conn, $q);
    $hasil = mysqli_query($conn, "SELECT * FROM produk WHERE nama_produk LIKE '%$q%'");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cari Produk - <?= htmlspecialchars($q) ?></title>
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
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-dark" href="dbpelanggan.php">
      <i class="bi bi-arrow-left-circle"></i> Kembali
    </a>
    <form class="d-flex w-100 mx-3" action="cari_produk.php" method="get">
      <input class="form-control form-control-sm me-2" type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari produk..." required>
      <button class="btn btn-light btn-sm" type="submit"><i class="bi bi-search"></i></button>
    </form>
  </div>
</nav>

<!-- Hasil Pencarian -->
<div class="container py-4">
  <h5 class="mb-3 text-danger">Hasil pencarian untuk: "<strong><?= htmlspecialchars($q) ?></strong>"</h5>
  <div class="row">
    <?php if ($hasil && mysqli_num_rows($hasil) > 0): ?>
      <?php while ($p = mysqli_fetch_assoc($hasil)): ?>
        <div class="col-6 col-md-3 mb-4">
          <a href="detail_produk.php?id=<?= $p['id_produk'] ?>" class="text-decoration-none">
            <div class="card produk-card h-100">
              <img src="img/produk/<?= $p['gambar'] ?? 'default.jpg' ?>" class="card-img-top" alt="<?= $p['nama_produk'] ?>">
              <div class="card-body">
                <h6 class="card-title text-truncate"><?= htmlspecialchars($p['nama_produk']) ?></h6>
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
        <i class="bi bi-search fs-1 text-muted"></i>
        <p class="mt-3 text-muted">Tidak ditemukan produk dengan kata kunci "<strong><?= htmlspecialchars($q) ?></strong>".</p>
        <a href="dbpelanggan.php" class="btn btn-warning mt-2"><i class="bi bi-house-door"></i> Kembali ke Beranda</a>
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
  <a href="profil_pelanggan.php">
    <i class="bi bi-person-circle"></i>
    <div>Akun</div>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
