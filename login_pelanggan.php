<?php
session_start();
require 'function.php';

// Login
if (isset($_POST['login'])) {
    $nama = $_POST['nama'];
    $kontak = $_POST['kontak'];
    $q = mysqli_query($conn, "SELECT * FROM pelanggan WHERE nama_pelanggan='$nama' AND kontak='$kontak'");
    if (mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        $_SESSION['id_pelanggan'] = $data['id_pelanggan'];
        header("Location: dbpelanggan.php");
        exit;
    } else {
        $error = "Nama atau kontak tidak cocok.";
    }
}

// Register
if (isset($_POST['register'])) {
    $nama = $_POST['reg_nama'];
    $kontak = $_POST['reg_kontak'];
    $cek = mysqli_query($conn, "SELECT * FROM pelanggan WHERE nama_pelanggan='$nama' AND kontak='$kontak'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Pelanggan sudah terdaftar, silakan login.";
    } else {
        mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, kontak) VALUES ('$nama', '$kontak')");
        $_SESSION['id_pelanggan'] = mysqli_insert_id($conn);
        header("Location: dbpelanggan.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login / Daftar Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --merah: #e53935;
      --kuning: #fdd835;
      --biru: #1e88e5;
    }

    body {
      background: linear-gradient(135deg, var(--biru), #fff8e1);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      overflow: hidden;
      display: flex;
      flex-direction: row;
      max-width: 900px;
      width: 100%;
    }

    .left-panel {
      background-color: var(--kuning);
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .left-panel img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      background: white;
      margin-bottom: 20px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    .right-panel {
      flex: 1;
      padding: 40px 30px;
    }

    .nav-tabs .nav-link.active {
      background-color: var(--biru);
      color: #fff;
      border-radius: 0.375rem;
    }

    .form-control {
      border-radius: 10px;
    }

    .btn-primary {
      background-color: var(--merah);
      border: none;
    }

    .btn-success {
      background-color: var(--biru);
      border: none;
    }

    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
        margin: 20px;
      }
      .left-panel {
        padding: 30px 20px;
      }
      .right-panel {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="login-container">
  <!-- Panel kiri -->
  <div class="left-panel">
    <img src="img/logo.png" alt="Logo Kedai Cemilan Riee">
    <h4 class="fw-bold mb-2">Kedai Cemilan Riee</h4>
    <p class="text-dark">Belanja camilan enak, murah, dan cepat!</p>
  </div>

  <!-- Panel kanan -->
  <div class="right-panel">
    <h4 class="mb-4 text-center fw-semibold"><i class="bi bi-person-circle"></i> Masuk / Daftar</h4>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Tab login/register -->
    <ul class="nav nav-tabs mb-3" id="formTab">
      <li class="nav-item">
        <a class="nav-link active" data-tab="login" href="#">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-tab="register" href="#">Daftar</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/kasir/login.php">Kasir</a>
      </li>
    </ul>

    <!-- Form Login -->
    <div id="login" class="tab-content d-block">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Kontak</label>
          <input type="text" name="kontak" class="form-control" placeholder="Masukkan nomor HP " required />
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
      </form>
    </div>

    <!-- Form Register -->
    <div id="register" class="tab-content d-none">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="reg_nama" class="form-control" placeholder="Nama lengkap" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Kontak</label>
          <input type="text" name="reg_kontak" class="form-control" placeholder="Nomor HP " required />
        </div>
        <button type="submit" name="register" class="btn btn-success w-100"><i class="bi bi-person-plus"></i> Daftar</button>
      </form>
    </div>
  </div>
</div>

<!-- Script Tab -->
<script>
  const tabs = document.querySelectorAll('[data-tab]');
  const contents = document.querySelectorAll('.tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', function(e) {
      e.preventDefault();
      tabs.forEach(t => t.classList.remove('active'));
      contents.forEach(c => {
        c.classList.remove('d-block');
        c.classList.add('d-none');
      });

      tab.classList.add('active');
      const target = document.getElementById(tab.getAttribute('data-tab'));
      target.classList.remove('d-none');
      target.classList.add('d-block');
    });
  });
</script>

</body>
</html>
