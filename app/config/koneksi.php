<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

// Cek koneksi
if ($koneksi->connect_error) {
  die("Koneksi database gagal: " . $koneksi->connect_error);
}
?>
