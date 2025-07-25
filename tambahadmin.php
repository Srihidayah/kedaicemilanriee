<?php
require 'function.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
mysqli_query($conn, "INSERT INTO user (username, password) VALUES ('$username', '$password')");
echo "Admin berhasil ditambahkan.";
