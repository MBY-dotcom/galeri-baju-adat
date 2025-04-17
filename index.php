<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");
$baju = $koneksi->query("SELECT * FROM koleksi_baju ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Galeri Baju Adat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- 🔵 Navbar -->
  <header class="bg-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-indigo-700">Baju Adat Gallery</h1>
      <nav>
        <a href="#galeri" class="text-gray-700 hover:text-indigo-500 px-3">Galeri</a>
        <a href="admin.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Admin</a>
      </nav>
    </div>
  </header>

  <!-- 🔵 Hero Section -->
  <section class="bg-gradient-to-br from-indigo-100 to-purple-100 py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
      <h2 class="text-4xl font-extrabold text-indigo-800 mb-4">Selamat Datang di Galeri Baju Adat</h2>
      <p class="text-lg text-gray-700 mb-6">Temukan koleksi baju adat terbaik untuk kebutuhan acara budaya, pernikahan, dan lainnya.</p>
      <a href="#galeri" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">Lihat Koleksi</a>
    </div>
  </section>

  <!-- 🔵 Galeri Dinamis -->
  <section id="galeri" class="max-w-6xl mx-auto px-4 py-12">
    <h3 class="text-3xl font-bold mb-8 text-center text-indigo-700">Koleksi Baju Adat</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
      <?php while($row = $baju->fetch_assoc()): ?>
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
          <img src="gambar/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>" class="h-52 w-full object-cover">
          <div class="p-4">
            <h4 class="font-semibold text-lg text-indigo-800"><?php echo $row['nama']; ?></h4>
            <p class="text-sm text-gray-500"><?php echo $row['kategori']; ?> • <?php echo $row['ukuran']; ?></p>
            <p class="mt-2 text-sm text-gray-700"><?php echo $row['deskripsi']; ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- 🔵 Footer -->
  <footer class="bg-white border-t mt-10 py-4 text-center text-sm text-gray-500">
    &copy; <?php echo date('Y'); ?> Baju Adat Gallery. All rights reserved.
  </footer>

</body>
</html>
