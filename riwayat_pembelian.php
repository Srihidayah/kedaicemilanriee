<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$nama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'"))['nama_pelanggan'];

$status = $_GET['status'] ?? '';

$sql = "
  SELECT p.tanggal, pr.nama_produk, dp.qty, dp.harga, p.status
  FROM pesanan p
  JOIN detailpesanan dp ON p.idorder = dp.idpesanan
  JOIN produk pr ON dp.idproduk = pr.id_produk
  WHERE p.idpelanggan = '$id_pelanggan'
";

if ($status !== '') {
  $sql .= " AND p.status = '$status'";
}

$sql .= " ORDER BY p.tanggal DESC";
$riwayat = mysqli_query($conn, $sql) ?: [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pembelian</title>
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

    .table th {
      background-color: #ffeb3b;
    }

    .btn-filter {
      margin-right: 8px;
      margin-bottom: 8px;
    }

    .btn-active {
      font-weight: bold;
      border: 2px solid #f44336;
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

    @media (max-width: 576px) {
      .table th, .table td {
        font-size: 0.85rem;
      }
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
    <span class="fw-semibold text-dark">Riwayat Pembelian</span>
  </div>
</nav>

<!-- Konten -->
<div class="container mt-4">
  <?php if (isset($_GET['berhasil']) && $_GET['berhasil'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      ✅ <strong>Pesanan berhasil dibuat!</strong> Terima kasih telah berbelanja.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
  <?php endif; ?>

  <h4 class="mb-3 text-danger"><i class="bi bi-clock-history"></i> Riwayat Pembelian</h4>

  <!-- Filter Status -->
  <div class="mb-3">
    <a href="riwayat_pembelian.php" class="btn btn-outline-dark btn-sm btn-filter <?= $status === '' ? 'btn-active' : '' ?>">Semua</a>
    <a href="?status=belum_bayar" class="btn btn-outline-dark btn-sm btn-filter <?= $status === 'belum_bayar' ? 'btn-active' : '' ?>">Belum Bayar</a>
    <a href="?status=Menunggu Konfirmasi" class="btn btn-outline-dark btn-sm btn-filter <?= $status === 'Menunggu Konfirmasi' ? 'btn-active' : '' ?>">Menunggu</a>
    <a href="?status=Dikonfirmasi" class="btn btn-outline-dark btn-sm btn-filter <?= $status === 'Dikonfirmasi' ? 'btn-active' : '' ?>">Selesai</a>
  </div>

  <!-- Tabel Riwayat -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-sm align-middle">
      <thead class="text-center">
        <tr>
          <th>Tanggal</th>
          <th>Produk</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Status</th> <!-- Tambahan -->
        </tr>
      </thead>
      <tbody>
      <?php if (mysqli_num_rows($riwayat) > 0): ?>
        <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
          <tr>
            <td><?= $r['tanggal'] ?></td>
            <td><?= htmlspecialchars($r['nama_produk']) ?></td>
            <td class="text-center"><?= $r['qty'] ?></td>
            <td class="text-end">Rp<?= number_format($r['harga'] * $r['qty']) ?></td>
            <td class="text-center">
              <?php
              $label = match($r['status']) {
                'belum_bayar' => '<span class="badge bg-secondary">Belum Bayar</span>',
                'Menunggu Konfirmasi' => '<span class="badge bg-warning text-dark">Menunggu</span>',
                'Dikonfirmasi' => '<span class="badge bg-success">Selesai</span>',
                default => '<span class="badge bg-light text-dark">-</span>'
              };
              echo $label;
              ?>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat pembelian.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Bottom Navigation -->
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
