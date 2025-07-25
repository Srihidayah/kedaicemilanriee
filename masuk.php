<?php
require 'ceklogin.php';
require 'function.php';

if (isset($_POST['simpan'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d');

    mysqli_query($conn, "INSERT INTO barang_masuk (id_produk, tanggal, jumlah) VALUES ('$id_produk', '$tanggal', '$jumlah')");
    mysqli_query($conn, "UPDATE produk SET stok = stok + $jumlah WHERE id_produk = '$id_produk'");

    header("Location: masuk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Barang Masuk - Kedai Cemilan Riee</title>
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
            <i class="bi bi-box-arrow-in-down"></i> Kedai Cemilan Riee
        </a>
        <div class="d-flex flex-wrap gap-2">
            <a href="produk.php" class="btn btn-kuning btn-sm"><i class="bi bi-box"></i> Produk</a>
            <a href="index.php" class="btn btn-kuning btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
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
    <h2 class="text-center section-title mb-4">
        <i class="bi bi-box-arrow-in-down"></i> Barang Masuk
    </h2>

    <!-- Form Tambah Barang Masuk -->
    <div class="glass p-4 mb-4">
        <h5 class="text-primary"><i class="bi bi-plus-circle-fill"></i> Tambah Barang Masuk</h5>
        <form method="post" class="row g-3 mt-2">
            <div class="col-md-5 col-12">
                <label class="form-label">Pilih Produk</label>
                <select name="id_produk" class="form-select" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php
                    $produk = mysqli_query($conn, "SELECT * FROM produk");
                    while ($p = mysqli_fetch_array($produk)) {
                        echo '<option value="'.$p['id_produk'].'">'.$p['nama_produk'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Jumlah Masuk</label>
                <input type="number" name="jumlah" class="form-control" min="1" required>
            </div>
            <div class="col-md-3 col-12 d-flex align-items-end">
                <button type="submit" name="simpan" class="btn btn-kuning w-100 btn-custom">
                    <i class="bi bi-save2"></i> Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat -->
    <div class="glass">
        <div class="card-header bg-primary text-white"><i class="bi bi-clock-history"></i> Riwayat Barang Masuk</div>
        <div class="card-body table-responsive">
            <table class="table table-sm table-bordered table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $data = mysqli_query($conn, "SELECT bm.*, p.nama_produk 
                        FROM barang_masuk bm 
                        JOIN produk p ON bm.id_produk = p.id_produk 
                        ORDER BY bm.tanggal DESC");
                    while ($row = mysqli_fetch_array($data)) {
                        echo "<tr>
                            <td class='text-center'>{$no}</td>
                            <td>{$row['tanggal']}</td>
                            <td>".htmlspecialchars($row['nama_produk'])."</td>
                            <td class='text-center'><span class='badge bg-danger'>{$row['jumlah']}</span></td>
                        </tr>";
                        $no++;
                    }
                    if ($no === 1) {
                        echo "<tr><td colspan='4' class='text-center text-muted'>Belum ada data barang masuk.</td></tr>";
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
