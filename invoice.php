<?php
require 'ceklogin.php';
require 'function.php';
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (!isset($_GET['idp'])) {
    die("ID pesanan tidak ditemukan.");
}

$idp = intval($_GET['idp']);

// Ambil data pesanan dan pelanggan
$order = mysqli_query($conn, "SELECT p.tanggal, pl.nama_pelanggan, pl.kontak 
    FROM pesanan p 
    JOIN pelanggan pl ON p.idpelanggan = pl.id_pelanggan 
    WHERE p.idorder = '$idp'");
$data_order = mysqli_fetch_assoc($order);

// Ambil detail produk
$detail = mysqli_query($conn, "SELECT dp.*, pr.nama_produk 
    FROM detailpesanan dp 
    JOIN produk pr ON dp.idproduk = pr.id_produk 
    WHERE dp.idpesanan = '$idp'");

// Awal HTML
$html = '
<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background-color: #f2f2f2; }
    .center { text-align: center; }
    .right { text-align: right; }
</style>

<h2 class="center">INVOICE PESANAN</h2>
<hr>
<p><strong>Nomor Pesanan:</strong> #' . $idp . '<br>
<strong>Tanggal:</strong> ' . $data_order['tanggal'] . '<br>
<strong>Nama Pelanggan:</strong> ' . htmlspecialchars($data_order['nama_pelanggan']) . '<br>
<strong>Kontak:</strong> ' . htmlspecialchars($data_order['kontak']) . '</p>
<hr>

<table>
<thead>
    <tr>
        <th>No</th>
        <th>Produk</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>
</thead>
<tbody>';

$no = 1;
$grand_total = 0;
while ($row = mysqli_fetch_assoc($detail)) {
    $subtotal = $row['qty'] * $row['harga'];
    $grand_total += $subtotal;

    $html .= '
    <tr>
        <td class="center">' . $no++ . '</td>
        <td>' . htmlspecialchars($row['nama_produk']) . '</td>
        <td class="right">Rp' . number_format($row['harga'], 0, ',', '.') . '</td>
        <td class="center">' . $row['qty'] . '</td>
        <td class="right">Rp' . number_format($subtotal, 0, ',', '.') . '</td>
    </tr>';
}

$html .= '
<tr>
    <td colspan="4" class="right"><strong>Total</strong></td>
    <td class="right"><strong>Rp' . number_format($grand_total, 0, ',', '.') . '</strong></td>
</tr>
</tbody>
</table>

<p style="margin-top: 20px;"><strong>Pembayaran:</strong> 5940671258 / BCA a.n <strong>Sri Hidayah</strong></p>

<p class="center" style="margin-top: 30px;">Terima kasih telah berbelanja di <strong style="color:blue;">Kedai Cemilan Riee</strong>.</p>
<p style="text-align:right; margin-top: 60px;">
    Hormat Kami,<br><br><br>
    <strong>Kedai Cemilan Riee</strong>
</p>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Invoice_#{$idp}.pdf", array("Attachment" => false));
exit;
?>
