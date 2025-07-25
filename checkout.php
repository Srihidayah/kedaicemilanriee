<?php
require 'ceklogin_pelanggan.php';
require 'function.php';

$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil data keranjang
$keranjang = mysqli_query($conn, "
  SELECT k.*, p.nama_produk, p.harga
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

if (isset($_POST['checkout'])) {
  $metode = $_POST['metode'];
  $tanggal = date('Y-m-d');

  // Simpan pesanan
  mysqli_query($conn, "INSERT INTO pesanan (idpelanggan, tanggal, total, metode, status) VALUES ('$id_pelanggan', '$tanggal', '$total', '$metode', 'Menunggu Konfirmasi')");
  $id_pesanan = mysqli_insert_id($conn);

  // Simpan detail pesanan
  foreach ($items as $item) {
    $id_produk = $item['id_produk'];
    $qty = $item['qty'];
    $harga = $item['harga'];
    mysqli_query($conn, "INSERT INTO detailpesanan (idpesanan, idproduk, qty, harga) VALUES ('$id_pesanan', '$id_produk', '$qty', '$harga')");
  }

  // Upload bukti pembayaran
  $bukti = $_FILES['bukti']['name'];
  $tmp = $_FILES['bukti']['tmp_name'];
  $folder = 'img/bukti/' . $bukti;
  move_uploaded_file($tmp, $folder);

  mysqli_query($conn, "INSERT INTO pembayaran (id_pesanan, bukti, tanggal) VALUES ('$id_pesanan', '$bukti', '$tanggal')");

  // Kosongkan keranjang
  mysqli_query($conn, "DELETE FROM keranjang WHERE id_pelanggan = '$id_pelanggan'");

  echo "<script>alert('Checkout berhasil, menunggu konfirmasi admin'); location.href='dbpelanggan.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3>Checkout</h3>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Total Belanja</label>
      <input type="text" class="form-control" value="Rp <?= number_format($total) ?>" readonly>
    </div>
    <div class="mb-3">
      <label class="form-label">Metode Pembayaran</label>
      <select name="metode" class="form-select" required>
        <option value="Transfer Bank">Transfer Bank</option>
        <option value="QRIS">QRIS</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Upload Bukti Pembayaran</label>
      <input type="file" name="bukti" class="form-control" required>
    </div>
    <button type="submit" name="checkout" class="btn btn-success">Kirim Pembayaran</button>
  </form>
</div>
</body>
</html>
