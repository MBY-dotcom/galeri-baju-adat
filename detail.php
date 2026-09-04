<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

if (!isset($_GET['id'])) {
  echo "ID tidak ditemukan.";
  exit;
}

$id = intval($_GET['id']);
$query = $koneksi->prepare('SELECT * FROM koleksi_baju WHERE id = ?');
$query->bind_param('i', $id);
$query->execute();
$res = $query->get_result();

if ($res->num_rows == 0) {
  echo "Data tidak ditemukan.";
  exit;
}

$data = $res->fetch_assoc();
$wa_link = "https://wa.me/6282142544486?text=" . urlencode("Halo, saya ingin menanyakan ketersediaan baju *{$data['nama']}*. Apakah masih tersedia?");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?> - Detail Baju</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* styles omitted for brevity */
  </style>
</head>
<body class="text-gray-800">

  <div class="container mx-auto px-4 py-10">
    <a href="index.php" class="text-[#8b4513] hover:text-[#a0522d] mb-6 inline-block text-sm"><i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke galeri</a>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden md:flex border">
      <img src="gambar/<?= htmlspecialchars($data['gambar'], ENT_QUOTES, 'UTF-8'); ?>" class="w-full md:w-1/2 h-64 md:h-auto object-cover" alt="<?= htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?>">

      <div class="p-6 md:w-1/2 flex flex-col justify-between space-y-4">
        <div>
          <h1 class="text-3xl font-bold judul-detail mb-2"><?= htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?></h1>
          <p class="text-gray-600 mb-1"><strong>Kategori:</strong> <?= htmlspecialchars($data['kategori'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p class="text-gray-600 mb-1"><strong>Ukuran:</strong> <?= htmlspecialchars($data['ukuran'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p class="harga text-lg mb-4"><strong>Harga Sewa:</strong> Rp <?= number_format((int)$data['harga'], 0, ',', '.'); ?></p>
          <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($data['deskripsi'], ENT_QUOTES, 'UTF-8')); ?></p>
        </div>

        <div>
          <a href="<?= htmlspecialchars($wa_link, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn-tribal inline-block px-4 py-2 text-white rounded">Hubungi via WhatsApp</a>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
