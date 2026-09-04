<?php
session_start();
require_once 'app/config/koneksi.php';

if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

// CSRF check
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('Invalid CSRF token');
}

$nama = trim($_POST['nama'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$ukuran = trim($_POST['ukuran'] ?? '');
deskripsi = trim($_POST['deskripsi'] ?? '');
$harga = isset($_POST['harga']) ? intval($_POST['harga']) : 0;

if ($nama === '' || $kategori === '' || $ukuran === '' || $deskripsi === '') {
    die('Input tidak lengkap');
}

// Handle file upload securely
if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    die('Gambar wajib diunggah');
}

$maxSize = 2 * 1024 * 1024; // 2 MB
if ($_FILES['gambar']['size'] > $maxSize) {
    die('Ukuran file terlalu besar (maks 2MB)');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['gambar']['tmp_name']);
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];
if (!isset($allowed[$mime])) {
    die('Tipe file tidak diperbolehkan');
}
$ext = $allowed[$mime];
$stored_filename = bin2hex(random_bytes(12)) . '.' . $ext;
$uploadDir = __DIR__ . '/gambar/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
$destination = $uploadDir . $stored_filename;

if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $destination)) {
    die('Gagal memindahkan file');
}

// Insert using prepared statement
$stmt = $koneksi->prepare("INSERT INTO koleksi_baju (nama, kategori, ukuran, deskripsi, harga, gambar, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
if (!$stmt) { error_log('Prepare failed: ' . $koneksi->error); die('Internal error'); }
$stmt->bind_param('ssssis', $nama, $kategori, $ukuran, $deskripsi, $harga, $stored_filename);
$exec = $stmt->execute();
if ($exec) {
    header('Location: admin_list.php');
    exit;
} else {
    error_log('Execute failed: ' . $stmt->error);
    die('Gagal menyimpan data');
}
?>
