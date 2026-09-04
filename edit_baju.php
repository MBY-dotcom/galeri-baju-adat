<?php
session_start();
require_once 'app/config/koneksi.php';

if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
  header('Location: login.php');
  exit;
}

if (!isset($_GET['id'])) {
  echo 'ID tidak ditemukan.';
  exit;
}

$id = intval($_GET['id']);
$stmt = $koneksi->prepare('SELECT * FROM koleksi_baju WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
  echo 'Data tidak ditemukan.';
  exit;
}
$data = $res->fetch_assoc();

// ensure CSRF token
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Baju</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="max-w-xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6 text-indigo-700">✏️ Edit Data Baju</h1>

    <form action="update_baju.php" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
      <input type="hidden" name="id" value="<?= intval($data['id']); ?>">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">

      <div>
        <label class="block mb-1 font-medium">Nama Baju</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?>" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block mb-1 font-medium">Kategori</label>
        <input type="text" name="kategori" value="<?= htmlspecialchars($data['kategori'], ENT_QUOTES, 'UTF-8'); ?>" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block mb-1 font-medium">Ukuran</label>
        <input type="text" name="ukuran" value="<?= htmlspecialchars($data['ukuran'], ENT_QUOTES, 'UTF-8'); ?>" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block mb-1 font-medium">Deskripsi</label>
        <textarea name="deskripsi" required class="w-full border rounded px-3 py-2"><?= htmlspecialchars($data['deskripsi'], ENT_QUOTES, 'UTF-8'); ?></textarea>
      </div>

      <div>
        <label class="block mb-1 font-medium">Gambar Sekarang</label>
        <img src="gambar/<?= htmlspecialchars($data['gambar'], ENT_QUOTES, 'UTF-8'); ?>" alt="" class="w-32 h-32 object-cover rounded mb-2">
        <input type="file" name="gambar" class="block">
        <small class="text-gray-500 text-sm">Kosongkan jika tidak ingin mengganti gambar.</small>
      </div>

      <div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Simpan Perubahan</button>
        <a href="admin_list.php" class="ml-2 text-gray-600 hover:underline">Kembali</a>
      </div>
    </form>
  </div>

</body>
</html>
