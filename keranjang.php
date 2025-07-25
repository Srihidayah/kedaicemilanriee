<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$nama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'"))['nama_pelanggan'];

$keranjang = mysqli_query($conn, "
    SELECT k.*, p.nama_produk, p.harga, p.gambar
    FROM keranjang k
    JOIN produk p ON k.id_produk = p.id_produk
    WHERE k.id_pelanggan = '$id_pelanggan'
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Saya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #fffde7);
      padding-bottom: 80px; /* ruang untuk bottom nav */
    }
    .navbar {
      background-color: #fbc02d !important;
    }
    .produk-img {
      width: 70px; height: 70px; object-fit: cover;
      border-radius: 10px;
    }
    .btn-merah { background-color: #e53935; color: #fff; }
    .btn-kuning { background-color: #fbc02d; color: #000; }
    .card-glass {
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .btn-qty { width: 36px; }
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
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="dbpelanggan.php">
      <img src="img/logo.png" alt="Logo" width="36" height="36" class="me-2 rounded-circle">
      Kedai Cemilan Riee
    </a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link text-dark fw-semibold" href="profil_pelanggan.php">
          <i class="bi bi-person-circle"></i> <?= htmlspecialchars($nama) ?>
        </a>
      </li>
    </ul>
  </div>
</nav>

<div class="container my-4">
  <div class="card card-glass p-4">
    <h4 class="mb-3 fw-semibold text-center"><i class="bi bi-cart"></i> Keranjang Belanja</h4>

    <?php if (mysqli_num_rows($keranjang) === 0): ?>
      <div class="text-center py-5">
        <i class="bi bi-cart-x fs-1 text-danger"></i>
        <p class="mt-3 fw-semibold">Keranjang Anda kosong.</p>
        <a href="dbpelanggan.php" class="btn btn-kuning mt-2">
          Belanja dulu yuk di sini <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    <?php else: ?>
      <form method="post" action="checkout.php" onsubmit="return validateCheckout()">
        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-warning">
              <tr>
                <th><input type="checkbox" id="pilihSemua" checked></th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $grand_total = 0;
            while ($k = mysqli_fetch_assoc($keranjang)):
              $subtotal = $k['harga'] * $k['qty'];
              $grand_total += $subtotal;
            ?>
              <tr data-id="<?= $k['id'] ?>">
                <td><input type="checkbox" class="form-check-input pilih-item" name="pilih[]" value="<?= $k['id'] ?>" checked></td>
                <td class="text-start">
                  <img src="img/produk/<?= $k['gambar'] ?>" class="produk-img me-2">
                  <?= htmlspecialchars($k['nama_produk']) ?>
                </td>
                <td>Rp<?= number_format($k['harga']) ?></td>
                <td>
                  <div class="input-group justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-min" data-id="<?= $k['id'] ?>">-</button>
                    <input type="number" name="qty[<?= $k['id'] ?>]" class="form-control text-center qty-input" min="1" value="<?= $k['qty'] ?>" data-harga="<?= $k['harga'] ?>">
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-plus" data-id="<?= $k['id'] ?>">+</button>
                  </div>
                </td>
                <td class="subtotal">Rp<?= number_format($subtotal) ?></td>
                <td><a href="hapus_keranjang.php?id=<?= $k['id'] ?>" class="btn btn-sm btn-merah">Hapus</a></td>
              </tr>
            <?php endwhile; ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td colspan="4" class="text-end">Total</td>
                <td colspan="2" id="grandTotal">Rp<?= number_format($grand_total) ?></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="mb-3 mt-3 text-start">
          <label class="form-label fw-semibold">Metode Pembayaran</label>
          <select name="metode_pembayaran" class="form-select" required>
            <option value="">-- Pilih Metode --</option>
            <option value="Transfer Bank">Transfer Bank</option>
          </select>
        </div>

        <div class="text-end mt-3">
          <button type="submit" class="btn btn-kuning btn-lg px-4">Checkout</button>
        </div>

        <div class="text-center mt-3 d-none" id="loading">
          <div class="spinner-border text-danger" role="status"></div>
          <p class="mt-2">Memproses pesanan...</p>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>

<!-- Bottom Nav -->
<div class="bottom-nav">
  <a href="dbpelanggan.php">
    <i class="bi bi-house-door-fill"></i>
    <span>Beranda</span>
  </a>
  <a href="keranjang.php" class="active">
    <i class="bi bi-cart-fill"></i>
    <span>Keranjang</span>
  </a>
  <a href="profil_pelanggan.php">
    <i class="bi bi-person-circle"></i>
    <span>Akun</span>
  </a>
</div>

<!-- Script -->
<script>
function validateCheckout() {
  const checked = Array.from(document.querySelectorAll('.pilih-item')).some(i => i.checked);
  if (!checked) {
    alert('Pilih minimal satu produk untuk checkout.');
    return false;
  }
  document.getElementById('loading').classList.remove('d-none');
  return true;
}

document.querySelectorAll('.btn-min').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.id;
    const input = document.querySelector(`input[name="qty[${id}]"]`);
    if (input.value > 1) input.value--;
    updateSubtotal(input);
  });
});

document.querySelectorAll('.btn-plus').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.id;
    const input = document.querySelector(`input[name="qty[${id}]"]`);
    input.value++;
    updateSubtotal(input);
  });
});

document.querySelectorAll('.qty-input').forEach(input => {
  input.addEventListener('input', () => updateSubtotal(input));
});

function updateSubtotal(input) {
  const harga = parseInt(input.dataset.harga);
  const qty = parseInt(input.value);
  const subtotal = harga * qty;
  const row = input.closest('tr');
  row.querySelector('.subtotal').innerText = "Rp" + subtotal.toLocaleString('id-ID');
  updateGrandTotal();
}

function updateGrandTotal() {
  let total = 0;
  document.querySelectorAll('tbody tr').forEach(row => {
    if (row.querySelector('.pilih-item').checked) {
      const subtotalText = row.querySelector('.subtotal').innerText.replace(/[^\d]/g, '');
      total += parseInt(subtotalText || 0);
    }
  });
  document.getElementById('grandTotal').innerText = "Rp" + total.toLocaleString('id-ID');
}

document.querySelectorAll('.pilih-item').forEach(checkbox => {
  checkbox.addEventListener('change', updateGrandTotal);
});

document.getElementById('pilihSemua')?.addEventListener('change', function () {
  document.querySelectorAll('.pilih-item').forEach(cb => cb.checked = this.checked);
  updateGrandTotal();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
