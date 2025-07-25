<?php
require 'ceklogin_pelanggan.php';
require 'function.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bantuan Pelanggan</title>
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
    .card-glass {
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      padding: 20px;
    }
    h5, h6 {
      color: #e53935;
    }
    ul li {
      margin-bottom: 10px;
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
    <a class="navbar-brand fw-bold" href="profil_pelanggan.php"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
    <span class="ms-auto text-dark fw-bold">Bantuan</span>
  </div>
</nav>

<!-- Konten -->
<div class="container my-4">
  <div class="card-glass">
    <h5><i class="bi bi-question-circle"></i> Pusat Bantuan</h5>
    <p>Jika Anda memiliki pertanyaan, kendala, atau butuh bantuan dalam menggunakan aplikasi, silakan hubungi kami melalui:</p>
    <ul>
      <li><strong>WhatsApp:</strong> <a href="https://wa.me/6281234567890" target="_blank">+62 895-4006-30131</a></li>
      <li><strong>Email:</strong> <a href="mailto:support@rieekasir.com">srihidayah1505@gmail.com</a></li>
      <li><strong>Jam Operasional:</strong> Setiap hari 08:00 - 21:00 WIB</li>
    </ul>
    <hr>
    <h6><i class="bi bi-info-circle"></i> FAQ (Pertanyaan yang Sering Ditanyakan)</h6>
    <ul>
      <li><strong>Bagaimana cara melihat pesanan saya?</strong> <br>Anda bisa melihatnya di menu <i>Riwayat Pembelian</i>.</li>
      <li><strong>Bagaimana cara mengubah data profil?</strong> <br>Masuk ke <i>Edit Profil</i> lalu simpan perubahan.</li>
    </ul>
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
