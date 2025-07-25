<?php 
session_start();
require 'function.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit;
        }
    }

    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Login - Kedai Cemilan Riee</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#ffc107">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --merah: #e53935;
      --biru: #1e88e5;
      --kuning: #fbc02d;
    }

    body {
      background: linear-gradient(to right, #fffde7, #e3f2fd);
      font-family: 'Segoe UI', sans-serif;
    }

    .card-glass {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(12px);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    .btn-kuning {
      background-color: var(--kuning);
      color: #000;
      border: none;
    }

    .btn-kuning:hover {
      background-color: #f9a825;
    }

    .logo-img {
      width: 90px;
      height: 90px;
      object-fit: cover;
      border-radius: 50%;
      background: #fff;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      margin-bottom: 1rem;
    }

    .nav-tabs .nav-link.active {
      background-color: var(--biru);
      color: #fff;
      border-radius: 0.375rem;
    }

    .nav-tabs .nav-link {
      color: var(--biru);
      font-weight: 500;
    }

    .nav-tabs .nav-link:hover {
      color: #0d47a1;
    }

    .form-control {
      border-radius: 10px;
    }

    .btn-primary {
      background-color: var(--merah);
      border: none;
    }

    @media (max-width: 768px) {
      .card-glass {
        margin-top: 20px;
      }
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

<div class="container px-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card-glass text-center">
        <img src="img/logo.png" alt="Logo" class="logo-img">
        
        <!-- Navigasi Tab -->
        <ul class="nav nav-tabs justify-content-center mb-4">
          <li class="nav-item">
            <a class="nav-link" href="pelanggan/login_pelanggan.php">pelanggan</a>
          </li>
        </ul>

        <h4 class="fw-bold mb-3 text-primary">Login Admin</h4>

        <?php if (isset($error)) : ?>
          <div class="alert alert-danger">Username atau Password salah!</div>
        <?php endif; ?>

        <form method="post">
          <div class="mb-3 text-start">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control rounded-3" required autofocus>
          </div>
          <div class="mb-4 text-start">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control rounded-3" required>
          </div>
          <button type="submit" name="login" class="btn btn-kuning w-100 fw-semibold">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
