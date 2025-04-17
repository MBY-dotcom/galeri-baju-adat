<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$ukuran = $_POST['ukuran'];
$deskripsi = $_POST['deskripsi'];

// Upload gambar
$nama_file = $_FILES['gambar']['name'];
$lokasi_tmp = $_FILES['gambar']['tmp_name'];
$folder_upload = "gambar/";

// Pindahkan file ke folder gambar/
move_uploaded_file($lokasi_tmp, $folder_upload . $nama_file);

// Simpan data ke database
$simpan = $koneksi->query("INSERT INTO koleksi_baju (nama, kategori, ukuran, deskripsi, gambar, created_at) 
VALUES ('$nama', '$kategori', '$ukuran', '$deskripsi', '$nama_file', NOW())");

// Redirect ke admin_list
if ($simpan) {
    header("Location: admin_list.php");
} else {
    echo "Gagal menyimpan data!";
}
?>
