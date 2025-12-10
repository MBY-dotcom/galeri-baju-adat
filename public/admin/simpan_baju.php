<?php
require_once __DIR__ . '/../../app/config/koneksi.php';

$nama       = $_POST['nama'];
$deskripsi  = $_POST['deskripsi'];
$kategori   = $_POST['kategori'];
$harga      = $_POST['harga'];
$ukuran     = $_POST['ukuran'];
$stok       = $_POST['stok'];

// ===== UPLOAD GAMBAR =====
$gambar = time() . '_' . $_FILES['gambar']['name'];
$target = "../gambar/" . $gambar;
move_uploaded_file($_FILES['gambar']['tmp_name'], $target);

try {
    $koneksi->begin_transaction();

    // 1️⃣ Insert ke koleksi_baju
    $stmt = $koneksi->prepare("
        INSERT INTO koleksi_baju (nama, deskripsi, kategori, harga, gambar)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssis", $nama, $deskripsi, $kategori, $harga, $gambar);
    $stmt->execute();

    $koleksi_id = $koneksi->insert_id;

    // 2️⃣ Insert stok per ukuran
    $stokStmt = $koneksi->prepare("
        INSERT INTO koleksi_stok (koleksi_id, ukuran, stok)
        VALUES (?, ?, ?)
    ");

    for ($i = 0; $i < count($ukuran); $i++) {
        if ($stok[$i] > 0) {
            $stokStmt->bind_param("isi", $koleksi_id, $ukuran[$i], $stok[$i]);
            $stokStmt->execute();
        }
    }

    $koneksi->commit();

    header("Location: admin_list.php?success=1");
    exit;

} catch (Exception $e) {
    $koneksi->rollback();
    echo "Gagal menyimpan data: " . $e->getMessage();
}
