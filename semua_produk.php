<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Semua Produk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .produk-card img {
      height: 160px;
      object-fit: cover;
    }
    .produk-card .card-body {
      padding: 0.75rem;
    }
    .produk-card .harga {
      font-weight: bold;
      color: #e53935;
    }
    .produk-card .harga-asli {
      font-size: 0.85rem;
      color: #999;
      text-decoration: line-through;
    }
  </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-warning shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white" href="dbpelanggan.php"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
    <span class="fw-semibold text-white">Semua Produk</span>
  </div>
</nav>

<!-- Daftar Produk -->
<div class="container mt-4">
  <div class="row">
    <?php while($p = mysqli_fetch_assoc($produk)): ?>
      <div class="col-6 col-md-3 mb-4">
        <a href="detail_produk.php?id=<?= $p['id_produk'] ?>" class="text-decoration-none text-dark">
          <div class="card produk-card shadow-sm h-100">
            <img src="img/produk/<?= $p['gambar'] ?? 'default.jpg' ?>" class="card-img-top">
            <div class="card-body d-flex flex-column justify-content-between">
              <h6 class="text-truncate"><?= htmlspecialchars($p['nama_produk']) ?></h6>
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
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
