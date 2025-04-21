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
$wa_link = "https://wa.me/6282142544486?text=" . urlencode("Halo, saya ingin menanyakan ketersediaan baju *{$data['nama']}*. Apakah masih tersedia?");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $data['nama']; ?> - Detail Baju</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto&display=swap');

    body {
      font-family: 'Roboto', sans-serif;
      background-color: #fdf7f0;
    }

    h1, h2, h3, h4 {
      font-family: 'Playfair Display', serif;
    }

    .btn-tribal {
      background-color: #8b4513;
    }

    .btn-tribal:hover {
      background-color: #a0522d;
    }

    .harga {
      color: #8b4513;
      font-weight: 600;
    }

    .judul-detail {
      color: #5c2c06;
    }
  </style>
</head>
<body class="text-gray-800">

  <div class="container mx-auto px-4 py-10">
    <a href="index.php" class="text-[#8b4513] hover:text-[#a0522d] mb-6 inline-block text-sm"><i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke galeri</a>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden md:flex border">
      <img src="gambar/<?= $data['gambar']; ?>" class="w-full md:w-1/2 h-64 md:h-auto object-cover">

      <div class="p-6 md:w-1/2 flex flex-col justify-between space-y-4">
        <div>
          <h1 class="text-3xl font-bold judul-detail mb-2"><?= $data['nama']; ?></h1>
          <p class="text-gray-600 mb-1"><strong>Kategori:</strong> <?= $data['kategori']; ?></p>
          <p class="text-gray-600 mb-1"><strong>Ukuran:</strong> <?= $data['ukuran']; ?></p>
          <p class="harga text-lg mb-4"><strong>Harga Sewa:</strong> Rp <?= number_format($data['harga'], 0, ',', '.'); ?></p>
          <p class="text-gray-700 leading-relaxed"><?= nl2br($data['deskripsi']); ?></p>
        </div>

        <div>
          <a href="<?= $wa_link; ?>" target="_blank"
            class="mt-6 inline-block bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-md text-sm text-center w-full md:w-auto">
            <i class="fa-brands fa-whatsapp mr-2"></i> Sewa via WhatsApp
          </a>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-[#8b4513] text-white text-center py-6 mt-16">
    <p class="text-sm">© <?= date('Y'); ?> Baju Adat Nusantara. Dibuat dengan ❤️ oleh Brian</p>
  </footer>

</body>
</html>
