<?php
session_start();
require_once 'app/config/koneksi.php';

if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_list.php');
    exit;
}

// CSRF
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('Invalid CSRF token');
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) die('ID tidak valid');

$nama = trim($_POST['nama'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$ukuran = trim($_POST['ukuran'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');

if ($nama === '' || $kategori === '' || $ukuran === '' || $deskripsi === '') {
    die('Input tidak lengkap');
}

// Fetch existing image filename
$stmt = $koneksi->prepare('SELECT gambar FROM koleksi_baju WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) die('Data tidak ditemukan');
$row = $res->fetch_assoc();
$oldImage = $row['gambar'];

$uploadDir = __DIR__ . '/gambar/';
$stored_filename = $oldImage; // default: keep old

// Handle new image upload if provided
if (!empty($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK && !empty($_FILES['gambar']['tmp_name'])) {
    $maxSize = 2 * 1024 * 1024; // 2MB
    if ($_FILES['gambar']['size'] > $maxSize) die('Ukuran file terlalu besar (maks 2MB)');

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['gambar']['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) die('Tipe file tidak diperbolehkan');
    $ext = $allowed[$mime];
    $stored_filename = bin2hex(random_bytes(12)) . '.' . $ext;

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $destination = $uploadDir . $stored_filename;
    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $destination)) die('Gagal memindahkan file');

    // remove old image safely
    $targetPath = realpath($uploadDir . $oldImage);
    $realUploadDir = realpath($uploadDir);
    if ($targetPath && $realUploadDir && strpos($targetPath, $realUploadDir) === 0 && file_exists($targetPath)) {
        @unlink($targetPath);
    }
}

// Prepare update
if ($stored_filename === $oldImage) {
    $upd = $koneksi->prepare('UPDATE koleksi_baju SET nama = ?, kategori = ?, ukuran = ?, deskripsi = ? WHERE id = ?');
    $upd->bind_param('ssssi', $nama, $kategori, $ukuran, $deskripsi, $id);
} else {
    $upd = $koneksi->prepare('UPDATE koleksi_baju SET nama = ?, kategori = ?, ukuran = ?, deskripsi = ?, gambar = ? WHERE id = ?');
    $upd->bind_param('sssssi', $nama, $kategori, $ukuran, $deskripsi, $stored_filename, $id);
}

if ($upd->execute()) {
    header('Location: admin_list.php');
    exit;
} else {
    error_log('Update failed: ' . $upd->error);
    die('Gagal mengupdate data!');
}
?>
