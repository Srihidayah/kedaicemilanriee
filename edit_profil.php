<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'"));

if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $kontak = htmlspecialchars($_POST['kontak']);

    $foto = $data['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nama = 'pelanggan_' . time() . '.' . $ext;
        $upload_path = 'img/pelanggan/' . $foto_nama;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
            $foto = $foto_nama;
        }
    }

    $update = mysqli_query($conn, "UPDATE pelanggan SET nama_pelanggan='$nama', kontak='$kontak', foto='$foto' WHERE id_pelanggan='$id_pelanggan'");
    if ($update) {
        header("Location: profil_pelanggan.php?update=1");
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan perubahan.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #fff8e1, #e3f2fd);
      padding-bottom: 80px;
    }
    .navbar {
      background-color: #fbc02d !important;
    }
    .card-custom {
      background-color: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    .form-label {
      font-weight: 500;
    }
    .bottom-nav {
      position: fixed;
      bottom: 0;
      width: 100%;
      background-color: #fff;
      border-top: 1px solid #ccc;
      display: flex;
      justify-content: space-around;
      z-index: 1000;
      padding: 6px 0;
    }
    .bottom-nav a {
      text-decoration: none;
      color: #555;
      font-size: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .bottom-nav a.active {
      color: #e53935;
      font-weight: bold;
    }
    .bottom-nav i {
      font-size: 20px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
  <div class="container-fluid">
    <a href="profil_pelanggan.php" class="navbar-brand text-white"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
    <span class="ms-auto fw-bold text-dark">Edit Profil</span>
  </div>
</nav>

<!-- Form -->
<div class="container mt-4">
  <div class="card card-custom mx-auto p-4" style="max-width: 500px;">
    <form method="post" enctype="multipart/form-data">
      <div class="text-center mb-3">
        <?php if ($data['foto']): ?>
          <img src="img/pelanggan/<?= $data['foto'] ?>" class="rounded-circle shadow" width="100" height="100" style="object-fit:cover;">
        <?php else: ?>
          <i class="bi bi-person-circle display-1 text-secondary"></i>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama_pelanggan']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Kontak</label>
        <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($data['kontak']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Foto Profil (Opsional)</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
      </div>
      <button type="submit" name="simpan" class="btn btn-danger w-100">Simpan Perubahan</button>
    </form>
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
