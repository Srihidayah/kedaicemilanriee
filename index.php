<?php
require 'ceklogin.php';
require 'function.php';

if (isset($_POST['buat_transaksi'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $tanggal = date('Y-m-d');
    mysqli_query($conn, "INSERT INTO pesanan (idpelanggan, tanggal) VALUES ('$id_pelanggan', '$tanggal')");
    $idorder = mysqli_insert_id($conn);
    header("Location: pesanan_detail.php?idp=$idorder");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Kasir - Kedai Cemilan Riee</title>
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

    .form-select, .form-control {
      border-radius: 10px;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(30, 136, 229, 0.07);
    }

    .btn-sm {
      transition: all 0.2s ease-in-out;
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
    <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shop-window"></i> Kedai Cemilan Riee</a>
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
  <h2 class="text-center fw-bold mb-4 text-primary"><i class="bi bi-cash-register"></i> Dashboard Kasir</h2>

  <!-- Buat Transaksi -->
  <div class="glass p-4 mb-4">
    <h5 class="mb-3 text-warning"><i class="bi bi-plus-circle-fill"></i> Buat Transaksi Baru</h5>
    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
        <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php
          $pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
          while ($p = mysqli_fetch_assoc($pelanggan)) {
              echo "<option value='{$p['id_pelanggan']}'>{$p['nama_pelanggan']} - {$p['kontak']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" name="buat_transaksi" class="btn btn-kuning w-100">
          <i class="bi bi-check-circle-fill"></i> Buat Transaksi
        </button>
      </div>
    </form>
  </div>

  <!-- Riwayat Transaksi -->
  <div class="glass">
    <div class="card-header bg-primary text-white"><i class="bi bi-clock-history"></i> Riwayat Transaksi Terakhir</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light text-center">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Pelanggan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $transaksi = mysqli_query($conn, "SELECT p.idorder, p.tanggal, pl.nama_pelanggan 
                                            FROM pesanan p 
                                            JOIN pelanggan pl ON p.idpelanggan = pl.id_pelanggan 
                                            ORDER BY p.idorder DESC LIMIT 10");
          while ($row = mysqli_fetch_assoc($transaksi)) {
              echo "<tr class='text-center'>
                  <td>{$no}</td>
                  <td><span class='badge badge-kuning'>{$row['tanggal']}</span></td>
                  <td><span class='badge badge-merah'>{$row['nama_pelanggan']}</span></td>
                  <td>
                      <a href='pesanan_detail.php?idp={$row['idorder']}' class='btn btn-biru btn-sm'>Lihat</a>
                      <a href='invoice.php?idp={$row['idorder']}' class='btn btn-outline-dark btn-sm'>Invoice</a>
                  </td>
              </tr>";
              $no++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Script Theme -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const currentTheme = localStorage.getItem('theme') || 'light';
  document.documentElement.setAttribute('data-bs-theme', currentTheme);
  updateIcon(currentTheme);

  themeToggle.addEventListener('click', () => {
    let newTheme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
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
