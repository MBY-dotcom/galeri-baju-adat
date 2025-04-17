<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

$id = $_POST['id'];
$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$ukuran = $_POST['ukuran'];
$deskripsi = $_POST['deskripsi'];

// Ambil data lama untuk gambar lama
$lama = $koneksi->query("SELECT gambar FROM koleksi_baju WHERE id = $id")->fetch_assoc();
$gambar_lama = $lama['gambar'];

if ($_FILES['gambar']['name'] != "") {
    // Jika user upload gambar baru
    $nama_file = $_FILES['gambar']['name'];
    $lokasi_tmp = $_FILES['gambar']['tmp_name'];
    $folder_upload = "gambar/";

    move_uploaded_file($lokasi_tmp, $folder_upload . $nama_file);

    // Hapus gambar lama jika file baru diupload
    if (file_exists("gambar/" . $gambar_lama)) {
        unlink("gambar/" . $gambar_lama);
    }

    // Update data dengan gambar baru
    $query = "UPDATE koleksi_baju SET 
        nama='$nama', 
        kategori='$kategori', 
        ukuran='$ukuran', 
        deskripsi='$deskripsi',
        gambar='$nama_file'
        WHERE id=$id";
} else {
    // Update data tanpa ganti gambar
    $query = "UPDATE koleksi_baju SET 
        nama='$nama', 
        kategori='$kategori', 
        ukuran='$ukuran', 
        deskripsi='$deskripsi'
        WHERE id=$id";
}

$simpan = $koneksi->query($query);

// Redirect ke list
if ($simpan) {
    header("Location: admin_list.php");
} else {
    echo "Gagal mengupdate data!";
}
?>
