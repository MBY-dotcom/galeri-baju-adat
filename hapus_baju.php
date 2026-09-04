<?php
session_start();
require_once 'app/config/koneksi.php';

if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die('ID tidak valid');
}

// Fetch filename safely using prepared statement
$stmt = $koneksi->prepare('SELECT gambar FROM koleksi_baju WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    die('Data tidak ditemukan');
}
$row = $res->fetch_assoc();
$nama_gambar = $row['gambar'];

$uploadDir = realpath(__DIR__ . '/gambar');
$targetPath = realpath(__DIR__ . '/gambar/' . $nama_gambar);
if ($targetPath && $uploadDir && strpos($targetPath, $uploadDir) === 0 && file_exists($targetPath)) {
    unlink($targetPath);
}

// Delete row
$del = $koneksi->prepare('DELETE FROM koleksi_baju WHERE id = ?');
$del->bind_param('i', $id);
$ok = $del->execute();

if ($ok) {
    header('Location: admin_list.php');
    exit;
} else {
    error_log('Delete failed: ' . $del->error);
    die('Gagal menghapus data!');
}
?>
