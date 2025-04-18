<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

$kategori_aktif = $_GET['kategori'] ?? 'Semua';
$query = "SELECT * FROM koleksi_baju";
if ($kategori_aktif !== 'Semua') {
  $query .= " WHERE kategori = '$kategori_aktif'";
}
$query .= " ORDER BY created_at DESC";
$baju = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Galeri Baju Adat</title>
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

    .bg-tribal {
      background-image: url('gambar/11781.jpg');
      background-size: cover;
      background-position: center;
    }

    .hero-overlay {
      background: rgba(255, 255, 255);
      padding: 3rem 1rem;
      border-radius: 0.5rem;
    }

    .btn-tribal {
      background-color: #8b4513;
    }

    .btn-tribal:hover {
      background-color: #a0522d;
    }

    .hero-slide {
      opacity: 0;
      transition: opacity 1s ease-in-out;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
    }

    .hero-slide.active {
      opacity: 1;
      position: relative;
      z-index: 10;
    }

    .slider-dot {
      transition: opacity 0.3s ease;
    }

    .slider-dot.active {
      opacity: 1 !important;
    }
  </style>
</head>
<body class="text-gray-800">

  <!-- NAVBAR -->
  <header class="bg-white shadow sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-[#8b4513]">BajuAdat</h1>
      <nav class="space-x-4">
        <a href="#" class="text-[#8b4513] hover:text-[#a0522d] font-medium">Beranda</a>
        <a href="admin_list.php" class="text-gray-600 hover:text-[#a0522d]">Admin</a>
      </nav>
    </div>
  </header>

  <!-- HERO SLIDER -->
  <section class="relative bg-tribal text-center text-[#5c2c06] py-16 md:py-24 overflow-hidden">
    <div class="container mx-auto px-4 relative min-h-[250px]">
      <!-- Slide 1 -->
      <div class="hero-slide active hero-overlay max-w-4xl mx-auto">
        <p class="text-lg mb-6">Selamat Datang di</p>
        <h2 class="text-3xl md:text-4xl font-bold mb-4">GALERI BU NUNUK SAHID</h2>
        <a href="#galeri" class="btn-tribal text-white px-6 py-3 rounded-full text-lg inline-block">Jelajahi Koleksi</a>
      </div>

      <!-- Slide 2 -->
      <div class="hero-slide hero-overlay max-w-4xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Sewa Baju Adat Berkualitas</h2>
        <p class="text-lg mb-6">Dengan harga terjangkau dan kualitas terjamin</p>
        <a href="#galeri" class="btn-tribal text-white px-6 py-3 rounded-full text-lg inline-block">Jelajahi Koleksi</a>
      </div>

      <!-- Slide 3 -->
      <div class="hero-slide hero-overlay max-w-4xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Pengalaman Berbusana Tradisional</h2>
        <p class="text-lg mb-6">Rasakan kemewahan dan keunikan budaya Indonesia</p>
        <a href="#galeri" class="btn-tribal text-white px-6 py-3 rounded-full text-lg inline-block">Jelajahi Koleksi</a>
      </div>

      <div class="flex justify-center mt-6 space-x-2 relative z-20">
        <button class="w-3 h-3 rounded-full bg-[#8b4513] opacity-50 slider-dot active"></button>
        <button class="w-3 h-3 rounded-full bg-[#8b4513] opacity-50 slider-dot"></button>
        <button class="w-3 h-3 rounded-full bg-[#8b4513] opacity-50 slider-dot"></button>
      </div>
    </div>
  </section>

  <!-- STATS SECTION -->
  <section class="py-12 bg-[#f8f1e9]">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-[#8b4513] mb-2">50+</div>
          <div class="text-gray-600">Jenis Baju</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-[#8b4513] mb-2">1000+</div>
          <div class="text-gray-600">Pelanggan</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-[#8b4513] mb-2">30+</div>
          <div class="text-gray-600">Daerah</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-[#8b4513] mb-2">15</div>
          <div class="text-gray-600">Tahun Pengalaman</div>
        </div>
      </div>
    </div>
  </section>

  <!-- KOLEKSI + FILTER -->
  <main class="container mx-auto px-4 py-12" id="galeri">
    <h3 class="text-2xl font-semibold mb-6 text-center text-[#5c2c06]">Koleksi Baju Adat</h3>

    <!-- FILTER KATEGORI -->
    <div class="text-center mb-12">
      <p class="text-gray-600 text-sm md:text-base max-w-xl mx-auto mb-8">
        Temukan berbagai pilihan baju adat dari seluruh Indonesia untuk berbagai acara dan kebutuhan
      </p>

      <div class="flex flex-wrap justify-center gap-8">
        <?php
        $kategori_list = [
          "Semua" => "fa-table-cells",
          "Pernikahan" => "fa-heart",
          "Wisuda" => "fa-graduation-cap",
          "Baju Adat Carnaval" => "fa-mask"
        ];
        foreach ($kategori_list as $kategori => $ikon) :
          $is_active = ($kategori_aktif == $kategori);
        ?>
          <a href="index.php?kategori=<?= urlencode($kategori); ?>" class="relative group text-sm md:text-base font-semibold flex items-center gap-2 text-gray-800 <?= $is_active ? 'text-yellow-500' : 'hover:text-yellow-500' ?>">
            <i class="fa-solid <?= $ikon ?>"></i>
            <?= str_replace('Baju Adat ', '', $kategori); ?>
            <span class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-[3px] rounded-full transition-all duration-300 bg-yellow-500 <?= $is_active ? 'opacity-100 scale-100' : 'opacity-0 scale-0 group-hover:opacity-50 group-hover:scale-100' ?>"></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- GALERI BAJU -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php while ($row = $baju->fetch_assoc()) :
        $wa_link = "https://wa.me/6282142544486?text=" . urlencode("Halo, saya ingin menanyakan ketersediaan baju *{$row['nama']}*. Apakah masih tersedia?");
      ?>
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition border">
          <a href="detail.php?id=<?= $row['id']; ?>">
            <img src="gambar/<?= $row['gambar']; ?>" alt="<?= $row['nama']; ?>" class="w-full h-48 object-cover">
          </a>
          <div class="p-4 flex flex-col h-full">
            <h4 class="text-lg font-bold text-[#8b4513]"><?= $row['nama']; ?></h4>
            <p class="text-sm text-gray-500"><?= $row['kategori']; ?> - <?= $row['ukuran']; ?></p>
            <p class="mt-2 text-sm text-gray-600"><?= substr($row['deskripsi'], 0, 80); ?>...</p>
            <p class="mt-2 text-[#8b4513] font-semibold">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
            <a href="<?= $wa_link; ?>" target="_blank"
              class="mt-4 inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition text-sm">
              <i class="fa-brands fa-whatsapp mr-2"></i> Sewa via WhatsApp
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-[#8b4513] text-white text-center py-6 mt-10">
    <p class="text-sm">© <?= date('Y'); ?> Baju Adat Nusantara. Dibuat dengan ❤️ oleh Brian</p>
  </footer>

  <!-- SLIDER SCRIPT -->
  <script>
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    let current = 0;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('active');
        dots[i].classList.remove('active');
      });
      slides[index].classList.add('active');
      dots[index].classList.add('active');
    }

    function nextSlide() {
      current = (current + 1) % slides.length;
      showSlide(current);
    }

    setInterval(nextSlide, 5000); // setiap 5 detik

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        current = index;
        showSlide(current);
      });
    });

    showSlide(0);
  </script>

</body>
</html>
