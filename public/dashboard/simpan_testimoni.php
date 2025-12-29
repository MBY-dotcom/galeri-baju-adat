<?php
require_once 'auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Validasi input
|--------------------------------------------------------------------------
*/
$penyewaan_id = (int)($_POST['penyewaan_id'] ?? 0);
$rating       = (int)($_POST['rating'] ?? 0);
$komentar     = trim($_POST['komentar'] ?? '');

if (
    $penyewaan_id <= 0 ||
    $rating < 1 || $rating > 5 ||
    $komentar === ''
) {
    die('Input tidak valid.');
}

/*
|--------------------------------------------------------------------------
| Pastikan pesanan milik user & status sudah selesai / disetujui
|--------------------------------------------------------------------------
*/
$stmt = $koneksi->prepare("
    SELECT id 
    FROM penyewaan 
    WHERE id = ? 
      AND user_id = ? 
      AND status IN ('disetujui','selesai')
");
$stmt->bind_param("ii", $penyewaan_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Pesanan tidak valid.');
}

/*
|--------------------------------------------------------------------------
| Cek apakah sudah pernah review
|--------------------------------------------------------------------------
*/
$stmt = $koneksi->prepare("
    SELECT id 
    FROM testimoni 
    WHERE penyewaan_id = ?
");
$stmt->bind_param("i", $penyewaan_id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    die('Testimoni sudah pernah dikirim.');
}

/*
|--------------------------------------------------------------------------
| Simpan testimoni
|--------------------------------------------------------------------------
*/
$stmt = $koneksi->prepare("
    INSERT INTO testimoni 
    (penyewaan_id, user_id, rating, komentar, created_at)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->bind_param("iiis", $penyewaan_id, $user_id, $rating, $komentar);

if ($stmt->execute()) {
    header('Location: pesanan_saya.php?review=success');
    exit;
}

die('Gagal menyimpan testimoni.');
