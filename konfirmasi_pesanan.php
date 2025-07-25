<?php
require 'ceklogin.php';
require 'function.php';

$pesanan = mysqli_query($conn, "
  SELECT p.*, pl.nama_pelanggan, pl.kontak, pm.bukti,
    (SELECT SUM(dp.qty * dp.harga) FROM detailpesanan dp WHERE dp.idpesanan = p.idorder) as total
  FROM pesanan p
  JOIN pelanggan pl ON p.idpelanggan = pl.id_pelanggan
  LEFT JOIN pembayaran pm ON p.idorder = pm.id_pesanan
  WHERE p.status = 'Menunggu Konfirmasi'
");
$totalAwal = mysqli_num_rows($pesanan);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pesanan - Kedai Cemilan Riee</title>
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
    .navbar-custom { background-color: var(--biru); }
    .glass {
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .btn-kuning { background-color: var(--kuning); color: #000; }
    .btn-kuning:hover { background-color: #f9a825; }
    .btn-merah { background-color: var(--merah); color: #fff; }
    .btn-merah:hover { background-color: #d32f2f; }
    .btn-biru { background-color: var(--biru); color: #fff; }
    .btn-biru:hover { background-color: #0b5ed7; }
    .btn-custom { transition: 0.2s ease-in-out; }
    .btn-custom:hover { transform: scale(1.05); }
    .section-title { font-weight: 700; color: var(--biru); }
  </style>
</head>
<body onclick="aktifkanSuara()"> <!-- Untuk mengaktifkan audio -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#"><i class="bi bi-check-circle"></i> Kedai Cemilan Riee</a>
    <div class="d-flex flex-wrap gap-2">
      <a href="produk.php" class="btn btn-kuning btn-sm"><i class="bi bi-box"></i> Produk</a>
      <a href="masuk.php" class="btn btn-kuning btn-sm"><i class="bi bi-download"></i> Masuk</a>
      <a href="pelanggan.php" class="btn btn-kuning btn-sm"><i class="bi bi-people"></i> Pelanggan</a>
      <a href="laporan.php" class="btn btn-kuning btn-sm"><i class="bi bi-bar-chart"></i> Laporan</a>
      <a href="index.php" class="btn btn-kuning btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
      <a href="logout.php" class="btn btn-merah btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
      <button id="themeToggle" class="btn btn-outline-light btn-sm"><i id="themeIcon" class="bi bi-moon"></i></button>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h2 class="text-center section-title mb-4"><i class="bi bi-person-check"></i> Konfirmasi Pesanan</h2>
  <div class="glass p-4">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Kontak</th>
            <th>Total</th>
            <th>Bukti</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while($p = mysqli_fetch_assoc($pesanan)) : ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><span class="badge bg-primary"><?= htmlspecialchars($p['nama_pelanggan']) ?></span></td>
            <td><span class="badge bg-info text-dark"><?= $p['tanggal'] ?></span></td>
            <td><?= htmlspecialchars($p['kontak']) ?></td>
            <td><span class="text-success fw-bold">Rp <?= number_format($p['total'] ?? 0) ?></span></td>
            <td>
              <?php if ($p['bukti']) : ?>
                <a href="../pelanggan/img/bukti/<?= $p['bukti'] ?>" target="_blank" class="btn btn-sm btn-outline-info">
                  <i class="bi bi-eye"></i> Lihat
                </a>
              <?php else : ?>
                <span class="text-danger">Belum ada</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="verifikasi_pesanan.php?id=<?= $p['idorder'] ?>&aksi=konfirmasi" class="btn btn-sm btn-biru btn-custom mb-1">
                <i class="bi bi-check-circle"></i> Konfirmasi
              </a>
              <a href="verifikasi_pesanan.php?id=<?= $p['idorder'] ?>&aksi=tolak" class="btn btn-sm btn-merah btn-custom">
                <i class="bi bi-x-circle"></i> Tolak
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php if ($no === 1): ?>
            <tr><td colspan="7" class="text-muted text-center">Belum ada pesanan menunggu konfirmasi.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Notifikasi Suara -->
<audio id="notifSound" src="sound/ting.mp3" preload="auto"></audio>

<script>
let lastTotal = <?= $totalAwal ?>;

function cekPesananBaru() {
  fetch('cek_pesanan_baru.php')
    .then(res => res.json())
    .then(data => {
      if (data.total > lastTotal) {
        lastTotal = data.total;

        // Notifikasi visual
        const alert = document.createElement('div');
        alert.className = 'alert alert-warning position-fixed top-0 end-0 m-3 shadow';
        alert.style.zIndex = 9999;
        alert.innerHTML = '<strong>🔔 Pesanan Baru!</strong> Silakan konfirmasi.';
        document.body.appendChild(alert);

        // Suara
        const audio = document.getElementById("notifSound");
        audio.play().catch(e => console.warn("Autoplay diblokir:", e));

        // Hapus alert dan reload
        setTimeout(() => {
          alert.remove();
          location.reload();
        }, 4000);
      }
    });
}

// Trigger otomatis
setInterval(cekPesananBaru, 5000);

// Aktifkan suara setelah klik pertama
function aktifkanSuara() {
  document.getElementById("notifSound").play().catch(() => {});
  document.body.removeAttribute("onclick");
}
</script>

<!-- Tema Gelap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const currentTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-bs-theme', currentTheme);
themeIcon.className = currentTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';

themeToggle.addEventListener('click', () => {
  const newTheme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
  document.documentElement.setAttribute('data-bs-theme', newTheme);
  localStorage.setItem('theme', newTheme);
  themeIcon.className = newTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
});
</script>
</body>
</html>
