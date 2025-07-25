<?php
require 'ceklogin_pelanggan.php';
require 'function.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil data keranjang
$keranjang = mysqli_query($conn, "
  SELECT k.*, p.nama_produk, p.harga, p.stok
  FROM keranjang k
  JOIN produk p ON k.id_produk = p.id_produk
  WHERE k.id_pelanggan = '$id_pelanggan'
");

$total = 0;
$items = [];
while ($k = mysqli_fetch_assoc($keranjang)) {
  $subtotal = $k['qty'] * $k['harga'];
  $total += $subtotal;
  $items[] = $k;
}

// Proses Checkout
if (isset($_POST['checkout'])) {

  // Jika keranjang kosong
  if (count($items) === 0) {
    echo "<script>alert('Keranjang kamu kosong!'); location.href='keranjang.php';</script>";
    exit;
  }

  $metode = $_POST['metode'];
  $tanggal = date('Y-m-d');

  // Cek stok terlebih dahulu
  foreach ($items as $item) {
    $id_produk = $item['id_produk'];
    $qty = $item['qty'];
    $stok = $item['stok'];

    if ($stok < $qty) {
      echo "<div style='padding: 2rem; background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; margin: 1rem; border-radius: 8px;'>
        <strong>Gagal Checkout:</strong> Stok produk <b>" . htmlspecialchars($item['nama_produk']) . "</b> hanya tersedia <b>$stok</b>, sedangkan Anda memesan <b>$qty</b>.<br><br>
        <a href='keranjang.php'>← Kembali ke Keranjang</a>
      </div>";
      exit;
    }
  }

  // Simpan pesanan
  mysqli_query($conn, "INSERT INTO pesanan (idpelanggan, tanggal, total, metode, status) 
                       VALUES ('$id_pelanggan', '$tanggal', '$total', '$metode', 'Menunggu Konfirmasi')");
  $id_pesanan = mysqli_insert_id($conn);

  // Simpan detail pesanan dan kurangi stok produk
  foreach ($items as $item) {
    $id_produk = $item['id_produk'];
    $qty = $item['qty'];
    $harga = $item['harga'];

    mysqli_query($conn, "INSERT INTO detailpesanan (idpesanan, idproduk, qty, harga) 
                         VALUES ('$id_pesanan', '$id_produk', '$qty', '$harga')");

    // Kurangi stok produk
    mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");
  }

  // Upload bukti pembayaran
  $bukti = $_FILES['bukti']['name'];
  $tmp = $_FILES['bukti']['tmp_name'];

  $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
  $ext = strtolower(pathinfo($bukti, PATHINFO_EXTENSION));
  if (!in_array($ext, $allowed)) {
    echo "<script>alert('Format file tidak didukung!'); history.back();</script>";
    exit;
  }

  if (!file_exists('img/bukti')) {
    mkdir('img/bukti', 0777, true);
  }

  $nama_baru = uniqid('bukti_', true) . '.' . $ext;
  $folder = 'img/bukti/' . $nama_baru;
  move_uploaded_file($tmp, $folder);

  mysqli_query($conn, "INSERT INTO pembayaran (id_pesanan, bukti, tanggal) 
                       VALUES ('$id_pesanan', '$nama_baru', '$tanggal')");

  // Hapus keranjang
  mysqli_query($conn, "DELETE FROM keranjang WHERE id_pelanggan = '$id_pelanggan'");

  echo "<script>alert('Checkout berhasil! Menunggu konfirmasi kasir.'); location.href='dbpelanggan.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3 class="mb-4"><i class="bi bi-cart-check"></i> Checkout</h3>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Total Belanja</label>
      <input type="text" class="form-control" value="Rp <?= number_format($total) ?>" readonly>
    </div>
    <div class="mb-3">
      <label class="form-label">Metode Pembayaran</label>
      <select name="metode" class="form-select" required>
        <option value="Transfer Bank">Transfer Bank (5940671258)</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Upload Bukti Pembayaran</label>
      <input type="file" name="bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>
    <button type="submit" name="checkout" class="btn btn-success w-100">Kirim Pembayaran</button>
  </form>
</div>
</body>
</html>
