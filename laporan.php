<?php
require 'ceklogin.php';
require 'function.php';

$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

$q = mysqli_query($conn, "SELECT p.idorder, p.tanggal, pl.nama_pelanggan, 
    SUM(dp.qty * dp.harga) AS total 
    FROM pesanan p 
    JOIN pelanggan pl ON p.idpelanggan = pl.id_pelanggan 
    JOIN detailpesanan dp ON dp.idpesanan = p.idorder 
    WHERE p.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' 
    GROUP BY p.idorder 
    ORDER BY p.tanggal DESC");

$grand_total = 0;
$jumlah_transaksi = mysqli_num_rows($q);
$transaksi = [];
while ($row = mysqli_fetch_assoc($q)) {
    $grand_total += $row['total'];
    $transaksi[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Kedai Cemilan Riee</title>
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
        }

        .btn-merah:hover {
            background-color: #d32f2f;
        }

        .badge-custom { font-size: 0.85em; }
        .section-title {
            font-weight: 700;
            color: var(--biru);
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
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-graph-up-arrow"></i> Kedai Cemilan Riee
        </a>
        <div class="d-flex flex-wrap gap-2">
            <a href="produk.php" class="btn btn-kuning btn-sm"><i class="bi bi-box"></i> Produk</a>
            <a href="masuk.php" class="btn btn-kuning btn-sm"><i class="bi bi-download"></i> Masuk</a>
            <a href="pelanggan.php" class="btn btn-kuning btn-sm"><i class="bi bi-people"></i> Pelanggan</a>
            <a href="index.php" class="btn btn-kuning btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
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
    <h2 class="text-center section-title mb-4"><i class="bi bi-bar-chart-fill"></i> Laporan Penjualan</h2>

    <!-- Ringkasan -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="glass p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">Total Transaksi</div>
                        <div class="h4 fw-bold text-primary"><?= $jumlah_transaksi ?> transaksi</div>
                    </div>
                    <i class="bi bi-receipt-cutoff fs-1 text-secondary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">Total Pendapatan</div>
                        <div class="h4 fw-bold text-success">Rp<?= number_format($grand_total) ?></div>
                    </div>
                    <i class="bi bi-cash-coin fs-1 text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="glass mb-4 p-4">
        <h5 class="text-primary"><i class="bi bi-calendar-range"></i> Filter Tanggal</h5>
        <form method="get" class="row g-3 mt-2">
            <div class="col-md-4 col-12">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?>" required>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>" required>
            </div>
            <div class="col-md-4 col-12 d-flex align-items-end">
                <button type="submit" class="btn btn-kuning w-100 btn-custom"><i class="bi bi-search"></i> Tampilkan</button>
            </div>
        </form>
    </div>

    <!-- Tabel -->
    <div class="glass p-4">
        <h5 class="text-secondary"><i class="bi bi-table"></i> Daftar Transaksi</h5>
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transaksi)): $no = 1; foreach ($transaksi as $row): ?>
                    <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><span class="badge bg-info text-dark badge-custom"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></span></td>
                        <td><span class="badge bg-success text-white badge-custom"><?= htmlspecialchars($row['nama_pelanggan']) ?></span></td>
                        <td><span class="fw-bold text-primary">Rp<?= number_format($row['total']) ?></span></td>
                        <td>
                            <a href="pesanan_detail.php?idp=<?= $row['idorder'] ?>" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye-fill"></i> Lihat
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada transaksi ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script -->
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
