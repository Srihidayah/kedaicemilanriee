<?php
// File: kasir/cek_pesanan_baru.php
require 'function.php';

// Query untuk menghitung jumlah pesanan yang statusnya "Menunggu Konfirmasi"
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan WHERE status = 'Menunggu Konfirmasi'");

if ($query && $data = mysqli_fetch_assoc($query)) {
    header('Content-Type: application/json');
    echo json_encode(['total' => (int)$data['total']]);
} else {
    // Jika terjadi error query
    http_response_code(500);
    echo json_encode(['total' => 0, 'error' => 'Gagal mengambil data']);
}
