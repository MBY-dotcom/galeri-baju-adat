<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

if (!isset($_GET['id'])) {
  echo "ID tidak ditemukan.";
  exit;
}

$id = intval($_GET['id']);
$query = $koneksi->query("SELECT * FROM koleksi_baju WHERE id = $id");

if ($query->num_rows == 0) {
  echo "Data tidak ditemukan.";
  exit;
}

$data = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $data['nama']; ?> - Detail Baju</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <div class="container mx-auto px-4 py-10">
    <a href="index.php" class="text-indigo-600 hover:underline mb-4 inline-block">&larr; Kembali ke galeri</a>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden md:flex">
      <img src="gambar/<?= $data['gambar']; ?>" class="w-full md:w-1/2 h-64 md:h-auto object-cover">
      
      <div class="p-6 md:w-1/2">
        <h1 class="text-3xl font-bold text-indigo-800 mb-2"><?= $data['nama']; ?></h1>
        <p class="text-gray-600 mb-1"><strong>Kategori:</strong> <?= $data['kategori']; ?></p>
        <p class="text-gray-600 mb-1"><strong>Ukuran:</strong> <?= $data['ukuran']; ?></p>
        <p class="text-indigo-700 font-semibold text-lg mb-4"><strong>Harga Sewa:</strong> Rp <?= number_format($data['harga'], 0, ',', '.'); ?></p>
        <p class="text-gray-700 leading-relaxed"><?= nl2br($data['deskripsi']); ?></p>
      </div>
    </div>
  </div>

</body>
</html>
