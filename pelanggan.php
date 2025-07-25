<?php
require 'ceklogin.php';
require 'function.php';

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $kontak = $_POST['kontak'];
    mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, kontak) VALUES ('$nama', '$kontak')");
    header("Location: pelanggan.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = '$id'");
    header("Location: pelanggan.php");
    exit;
}

$total_pelanggan = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pelanggan"))['total'];
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Pelanggan - Kedai Cemilan Riee</title>
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

        .badge-merah {
            background-color: var(--merah);
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

        .section-title {
            font-weight: 700;
            color: var(--biru);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-people-fill"></i> Kedai Cemilan Riee
        </a>
        <div class="d-flex flex-wrap gap-2">
            <a href="produk.php" class="btn btn-kuning btn-sm"><i class="bi bi-box"></i> Produk</a>
            <a href="masuk.php" class="btn btn-kuning btn-sm"><i class="bi bi-download"></i> Masuk</a>
            <a href="index.php" class="btn btn-kuning btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
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
    <h2 class="text-center section-title mb-4">
        <i class="bi bi-person-lines-fill"></i> Data Pelanggan
        <span class="badge bg-warning text-dark">Total: <?= $total_pelanggan ?></span>
    </h2>

    <!-- Form Tambah -->
    <div class="glass p-4 mb-4">
        <h5 class="text-primary mb-3"><i class="bi bi-person-plus-fill"></i> Tambah Pelanggan Baru</h5>
        <form method="post" class="row g-3">
            <div class="col-md-5 col-12">
                <input type="text" name="nama" class="form-control" placeholder="Nama Pelanggan" required>
            </div>
            <div class="col-md-4 col-12">
                <input type="text" name="kontak" class="form-control" placeholder="No. Telepon / WA" required>
            </div>
            <div class="col-md-3 col-12">
                <button type="submit" name="tambah" class="btn btn-kuning w-100 btn-custom">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="glass">
        <div class="card-header bg-primary text-white"><i class="bi bi-people-fill"></i> Daftar Pelanggan</div>
        <div class="card-body table-responsive">
            <table class="table table-sm table-bordered table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $data = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
                    while ($row = mysqli_fetch_array($data)) {
                        echo "<tr class='text-center'>
                            <td>{$no}</td>
                            <td class='text-start'><span class='badge bg-success text-white'>{$row['nama_pelanggan']}</span></td>
                            <td class='text-start'><span class='badge bg-info text-dark'>{$row['kontak']}</span></td>
                            <td>
                                <a href='?hapus={$row['id_pelanggan']}' onclick=\"return confirm('Hapus pelanggan ini?')\" class='btn btn-sm btn-merah'>
                                    <i class='bi bi-trash'></i> Hapus
                                </a>
                            </td>
                        </tr>";
                        $no++;
                    }
                    if ($no === 1) {
                        echo "<tr><td colspan='4' class='text-center text-muted'>Belum ada data pelanggan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script Toggle Tema -->
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
