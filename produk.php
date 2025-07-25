<?php
require 'ceklogin.php';
require 'function.php';

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama', '$harga', '$stok')");
    header("Location: produk.php");
    exit;
}

if (isset($_POST['edit'])) {
    $id = $_POST['id_produk'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok' WHERE id_produk='$id'");
    header("Location: produk.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id'");
    header("Location: produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Produk - Kedai Cemilan Riee</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#fbc02d">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --merah: #f44336;
      --biru: #0d6efd;
      --kuning: #fbc02d;
    }

    body {
      background: linear-gradient(to right, #fff3e0, #e3f2fd);
      font-family: 'Segoe UI', sans-serif;
    }

    [data-bs-theme="dark"] body {
      background: #1a1c2c;
      color: #fff;
    }

    .navbar-custom {
      background-color: var(--biru);
    }

    .glass {
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="dark"] .glass {
      background: rgba(30, 30, 30, 0.88);
    }

    .btn-biru {
      background-color: var(--biru);
      color: #fff;
      border: none;
    }

    .btn-biru:hover {
      background-color: #0b5ed7;
    }

    .btn-kuning {
      background-color: var(--kuning);
      color: #000;
      border: none;
    }

    .btn-kuning:hover {
      background-color: #f9a825;
    }

    .btn-merah {
      background-color: var(--merah);
      color: #fff;
      border: none;
    }

    .btn-merah:hover {
      background-color: #d32f2f;
    }

    .form-control, .form-select {
      border-radius: 10px;
    }

    .btn-custom {
      transition: 0.2s ease-in-out;
    }

    .btn-custom:hover {
      transform: scale(1.05);
    }

    .badge-merah {
      background-color: var(--merah);
    }

    .badge-kuning {
      background-color: var(--kuning);
      color: #000;
    }

    .badge-biru {
      background-color: var(--biru);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(13, 110, 253, 0.05);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#"><i class="bi bi-box-seam"></i> Kedai Cemilan Riee</a>
    <div class="d-flex flex-wrap gap-2">
      <a href="index.php" class="btn btn-kuning btn-sm"><i class="bi bi-house"></i> Dashboard</a>
      <a href="masuk.php" class="btn btn-kuning btn-sm"><i class="bi bi-download"></i> Masuk</a>
      <a href="pelanggan.php" class="btn btn-kuning btn-sm"><i class="bi bi-people"></i> Pelanggan</a>
      <a href="laporan.php" class="btn btn-kuning btn-sm"><i class="bi bi-bar-chart"></i> Laporan</a>
      <a href="konfirmasi_pesanan.php" class="btn btn-kuning btn-sm"><i class="bi bi-check-circle"></i> Konfirmasi</a>
      <a href="logout.php" class="btn btn-merah btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
      <button id="themeToggle" class="btn btn-outline-light btn-sm" title="Ganti Tema">
        <i id="themeIcon" class="bi bi-moon"></i>
      </button>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="container py-5">
  <h2 class="text-center fw-bold mb-4 text-primary"><i class="bi bi-box-seam-fill"></i> Manajemen Produk</h2>

  <?php if (isset($_GET['edit'])):
    $id = $_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
    $data = mysqli_fetch_assoc($q);
  ?>
  <!-- Form Edit -->
  <div class="glass p-4 mb-4">
    <h5 class="text-primary"><i class="bi bi-pencil-square"></i> Edit Produk</h5>
    <form method="post" class="row g-3 mt-2">
      <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">
      <div class="col-md-4 col-12">
        <input type="text" name="nama" class="form-control" value="<?= $data['nama_produk']; ?>" required>
      </div>
      <div class="col-md-3 col-12">
        <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" min="0" required>
      </div>
      <div class="col-md-3 col-12">
        <button type="submit" name="edit" class="btn btn-biru w-100 btn-custom">Update</button>
      </div>
    </form>
  </div>
  <?php endif; ?>

  <!-- Form Tambah -->
  <div class="glass p-4 mb-4">
    <h5 class="text-success"><i class="bi bi-plus-circle-fill"></i> Tambah Produk Baru</h5>
    <form method="post" class="row g-3 mt-2">
      <div class="col-md-4 col-12">
        <input type="text" name="nama" class="form-control" placeholder="Nama Produk" required>
      </div>
      <div class="col-md-3 col-12">
        <input type="number" name="harga" class="form-control" placeholder="Harga" min="0" required>
      </div>
      <div class="col-md-2 col-12">
        <input type="number" name="stok" class="form-control" placeholder="Stok" min="0" required>
      </div>
      <div class="col-md-3 col-12">
        <button type="submit" name="tambah" class="btn btn-kuning w-100 btn-custom"><i class="bi bi-plus-circle"></i> Tambah</button>
      </div>
    </form>
  </div>

  <!-- Tabel Produk -->
  <div class="glass">
    <div class="card-header bg-primary text-white"><i class="bi bi-card-list"></i> Daftar Produk</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light text-center">
          <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
          $no = 1;
          while ($p = mysqli_fetch_array($produk)) {
            echo "<tr class='text-center'>
              <td>{$no}</td>
              <td><span class='badge badge-kuning'>{$p['nama_produk']}</span></td>
              <td>Rp " . number_format($p['harga']) . "</td>
              <td>{$p['stok']}</td>
              <td>
                <a href='?edit={$p['id_produk']}' class='btn btn-biru btn-sm me-1 btn-custom'><i class='bi bi-pencil-square'></i></a>
                <a href='?hapus={$p['id_produk']}' onclick=\"return confirm('Yakin hapus?')\" class='btn btn-merah btn-sm btn-custom'><i class='bi bi-trash'></i></a>
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

<!-- Script Toggle Theme -->
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
