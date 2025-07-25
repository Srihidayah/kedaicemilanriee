<?php
require 'function.php';
require 'ceklogin_pelanggan.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --merah: #e53935;
      --kuning: #fbc02d;
    }

    body {
      background: linear-gradient(to right, #e3f2fd, #fffde7);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 80px;
    }

    .navbar {
      background-color: var(--kuning) !important;
    }

    .profile-img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #ddd;
    }

    .card-glass {
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .section-title {
      padding: 0.75rem 1rem;
      font-weight: 600;
      background: #fff3cd;
      border-radius: 12px;
      margin-bottom: 1rem;
    }

    .list-group-item {
      background: #fff;
      border: none;
      border-bottom: 1px solid #eee;
      font-size: 0.95rem;
    }

    .list-group-item:hover {
      background-color: #f8f8f8;
    }

    .btn-outline-primary {
      border-color: #1e88e5;
      color: #1e88e5;
    }

    .btn-outline-primary:hover {
      background-color: #1e88e5;
      color: #fff;
    }

    .btn-danger {
      background-color: var(--merah);
      border: none;
    }

    .btn-danger:hover {
      background-color: #c62828;
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
      color: var(--merah);
      font-weight: bold;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold">Profil Saya</span>
  </div>
</nav>

<!-- Konten Profil -->
<div class="container my-4">
  <div class="card card-glass p-3 d-flex align-items-center">
    <?php if (!empty($data['foto']) && file_exists('img/pelanggan/' . $data['foto'])): ?>
      <img src="img/pelanggan/<?= htmlspecialchars($data['foto']) ?>" class="profile-img mb-2" alt="Foto Profil">
    <?php else: ?>
      <i class="bi bi-person-circle fs-1 text-secondary mb-2"></i>
    <?php endif; ?>
    <h6 class="mb-0"><?= htmlspecialchars($data['nama_pelanggan']) ?></h6>
    <small class="text-muted"><?= htmlspecialchars($data['kontak']) ?></small>
    <small class="text-muted">ID: <?= $data['id_pelanggan'] ?></small>
  </div>

  <div class="mt-4">
    <div class="section-title">Aktivitas Saya</div>
    <div class="list-group list-group-flush">
      <a href="favorit_saya.php" class="list-group-item list-group-item-action">
        <i class="bi bi-heart me-2"></i> Favorit Saya
      </a>
      <a href="riwayat_pembelian.php" class="list-group-item list-group-item-action">
        <i class="bi bi-receipt me-2"></i> Riwayat Pembelian
      </a>
      <a href="terakhir_dilihat.php" class="list-group-item list-group-item-action">
        <i class="bi bi-clock-history me-2"></i> Terakhir Dilihat
      </a>
      <a href="bantuan.php" class="list-group-item list-group-item-action">
        <i class="bi bi-chat-dots me-2"></i> Bantuan
      </a>
    </div>
  </div>

  <!-- Tombol -->
  <div class="mt-4">
    <a href="edit_profil.php" class="btn btn-outline-primary w-100 mb-2">
      <i class="bi bi-pencil-square"></i> Edit Profil
    </a>
    <a href="logout.php" class="btn btn-danger w-100">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
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
    <i class="bi bi-person-fill"></i>
    <span>Akun</span>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
