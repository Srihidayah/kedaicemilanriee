<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$data = mysqli_query($conn, "
  SELECT td.*, p.nama_produk, p.harga, p.gambar 
  FROM terakhir_dilihat td 
  JOIN produk p ON td.id_produk = p.id_produk
  WHERE td.id_pelanggan = '$id_pelanggan'
  ORDER BY td.dilihat_pada DESC
  LIMIT 20
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Terakhir Dilihat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #fffde7);
      padding-bottom: 80px;
    }

    .navbar {
      background-color: #fbc02d !important;
    }

    .produk-card {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
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
      background: #ffffff;
      color: #000;
      padding: 0.75rem;
    }

    .produk-card .harga {
      font-weight: bold;
      color: #e53935;
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
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-dark" href="profil_pelanggan.php">
      <i class="bi bi-arrow-left-circle"></i> Kembali
    </a>
    <span class="fw-semibold text-dark">Terakhir Dilihat</span>
  </div>
</nav>

<div class="container py-4 mb-5">
  <h5 class="mb-3 text-danger"><i class="bi bi-eye-fill"></i> Terakhir Dilihat</h5>
  <div class="row">
    <?php if (mysqli_num_rows($data) > 0): ?>
      <?php while($d = mysqli_fetch_assoc($data)): ?>
        <div class="col-6 col-md-3 mb-4">
          <a href="detail_produk.php?id=<?= $d['id_produk'] ?>" class="text-decoration-none">
            <div class="card produk-card">
              <img src="img/produk/<?= $d['gambar'] ?>" class="card-img-top" alt="<?= $d['nama_produk'] ?>">
              <div class="card-body">
                <h6 class="card-title text-truncate"><?= htmlspecialchars($d['nama_produk']) ?></h6>
                <div class="harga">Rp<?= number_format($d['harga']) ?></div>
              </div>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center text-muted mt-5">
        <i class="bi bi-eye-slash fs-1"></i>
        <p class="mt-2">Belum ada produk yang dilihat.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Bottom Nav -->
<div class="bottom-nav">
  <a href="dbpelanggan.php">
    <i class="bi bi-house-door-fill"></i>
    <span>Beranda</span>
  </a>
  <a href="keranjang.php">
    <i class="bi bi-cart-fill"></i>
    <span>Keranjang</span>
  </a>
  <a href="profil_pelanggan.php" class="active">
    <i class="bi bi-person-circle"></i>
    <span>Akun</span>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
