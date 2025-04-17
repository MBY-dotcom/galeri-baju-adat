<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

$id = $_GET['id'];

// Ambil nama file gambar yang mau dihapus
$data = $koneksi->query("SELECT gambar FROM koleksi_baju WHERE id = $id")->fetch_assoc();
$nama_gambar = $data['gambar'];

// Hapus gambar dari folder jika ada
if (file_exists("gambar/" . $nama_gambar)) {
    unlink("gambar/" . $nama_gambar);
}

// Hapus data dari database
$hapus = $koneksi->query("DELETE FROM koleksi_baju WHERE id = $id");

// Redirect balik ke admin_list.php
if ($hapus) {
    header("Location: admin_list.php");
} else {
    echo "Gagal menghapus data!";
}
?>
