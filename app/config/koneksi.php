<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'db_bajuadat';

$koneksi = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($koneksi->connect_error) {
    error_log('Koneksi database gagal: ' . $koneksi->connect_error);
    // In production do not reveal internal errors to users
    die('Koneksi database gagal. Silakan hubungi administrator.');
}
?>
