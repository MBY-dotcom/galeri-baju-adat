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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeri Baju Adat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
      background-repeat: no-repeat;
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

    /* FILTER KATEGORI */
    .filter-link {
      transition: all 0.3s ease;
      color: #5c2c06;
      font-weight: 600;
    }

    .filter-link:hover {
      color: #a0522d;
    }

    .filter-link .underline-hover {
      background-color: #a0522d;
    }

    /* KOLEKSI CARD */
    .koleksi-card h4 {
      font-family: 'Playfair Display', serif;
      color: #5c2c06;
      font-size: 1.1rem;
    }

    .koleksi-card p {
      font-size: 0.9rem;
      color: #5f5f5f;
    }

    .koleksi-card .harga {
      color: #8b4513;
      font-weight: 600;
    }

     /* TESTIMONIAL */
     .testimonial-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .testimonial-card .quote-icon {
      color: var(--accent);
      font-size: 2rem;
      opacity: 0.5;
    }

    /* ANIMATIONS */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
      animation: fadeIn 0.8s ease-out forwards;
    }

    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }

    /* SWIPER OVERRIDES */
    .swiper-pagination-bullet {
      background: var(--primary-light) !important;
      opacity: 0.5 !important;
    }

    .swiper-pagination-bullet-active {
      background: var(--primary) !important;
      opacity: 1 !important;
    }

   
  </style>
</head>

<body class="text-gray-800">
  
<!-- NAVBAR -->
<header class="bg-white shadow sticky top-0 z-50">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-[#8b4513]">GALERI BU NUNUK SAHID</h1>

    <!-- Desktop Nav -->
    <nav class="hidden md:flex items-center space-x-4">
      <a href="#" class="text-[#8b4513] hover:text-[#a0522d] font-medium">Beranda</a>
      <a href="#galeri" class="text-[#8b4513] hover:text-[#a0522d] font-medium">Koleksi Baju</a>
      <a href="#testimonial" class="text-[#8b4513] hover:text-[#a0522d] font-medium">Testimoni</a>
      <a href="#faq" class="text-[#8b4513] hover:text-[#a0522d] font-medium">Pertanyaan</a>
      <a href="admin_list.php" class="text-gray-600 hover:text-[#a0522d]">Admin</a>
    </nav>

    <!-- Mobile Menu Button -->
    <div class="md:hidden">
      <button id="menu-btn" class="text-gray-800 focus:outline-none">
        <i class="fas fa-bars text-2xl"></i>
      </button>
    </div>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div id="mobile-menu" class="hidden md:hidden">
  <a href="#" class="flex items-center gap-3 py-3 px-4 text-[#8b4513] hover:bg-gray-100/70">
    <i class="fas fa-home"></i> Beranda
  </a>
  <a href="#galeri" class="flex items-center gap-3 py-3 px-4 text-[#8b4513] hover:bg-gray-100/70">
    <i class="fas fa-tshirt"></i> Koleksi Baju
  </a>
  <a href="#testimonial" class="flex items-center gap-3 py-3 px-4 text-[#8b4513] hover:bg-gray-100/70">
    <i class="fas fa-comment"></i> Testimoni
  </a>
  <a href="#faq" class="flex items-center gap-3 py-3 px-4 text-[#8b4513] hover:bg-gray-100/70">
    <i class="fas fa-question-circle"></i> Pertanyaan
  </a>
  <a href="admin_list.php" class="flex items-center gap-3 py-3 px-4 text-gray-600 hover:bg-gray-100/70">
    <i class="fas fa-user-cog"></i> Admin
  </a>
</div>


</header>


<!-- HERO SLIDER -->
<section class="relative bg-tribal text-center text-[#5c2c06] py-8 md:py-16 overflow-hidden">
  <div class="container mx-auto px-4 relative min-h-[280px]">
    
    <!-- Slides 1 -->
    <div class="hero-slide active hero-overlay max-w-4xl mx-auto">
      <p class="text-[17px] md:text-lg mb-2 font-medium">Selamat Datang di</p>
      <h2 class="text-[30px] md:text-4xl font-extrabold mb-3 tracking-wide leading-snug">GALERI BU NUNUK SAHID</h2>
      <p class="text-[16px] md:text-lg mb-6 text-gray-700 leading-relaxed">Temukan koleksi baju adat terlengkap dari seluruh Indonesia untuk berbagai acara dan kebutuhan Anda.</p>
      <a href="#galeri" class="btn-tribal text-white px-6 py-3 rounded-full text-base inline-block min-w-[200px] text-center">Jelajahi Koleksi</a>
    </div>
    <!-- Slides 2-->
    <div class="hero-slide hero-overlay max-w-4xl mx-auto">
      <p class="text-[17px] md:text-lg mb-2 font-medium">Ingin tampil memukau di acara spesial?</p>
      <h2 class="text-[30px] md:text-4xl font-extrabold mb-3 tracking-wide leading-snug">Curi Perhatian dengan Sentuhan Tradisi</h2>
      <p class="text-[16px] md:text-lg mb-6 text-gray-700 leading-relaxed">Koleksi kami siap membuat penampilan Anda tak terlupakan dalam balutan budaya yang anggun.</p>
      <a href="#galeri" class="btn-tribal text-white px-6 py-3 rounded-full text-base inline-block min-w-[200px] text-center">Lihat Katalog</a>
    </div>
    <!-- Slides 3-->
    <div class="hero-slide hero-overlay max-w-4xl mx-auto">
      <p class="text-[17px] md:text-lg mb-2 font-medium">Capek keliling cari baju adat yang cocok dan terjangkau?</p>
      <h2 class="text-[30px] md:text-4xl font-extrabold mb-3 tracking-wide leading-snug">Nggak Perlu Ribet, Cukup Sewa di Sini</h2>
      <p class="text-[16px] md:text-lg mb-6 text-gray-700 leading-relaxed">Proses mudah, pilihan lengkap, harga ramah di kantong. Semuanya ada di Galeri Bu Nunuk Sahid!</p>
      <a href="#galeri" class="btn-tribal text-white px-6 py-3 rounded-full text-base inline-block min-w-[200px] text-center">Temukan Gayamu</a>
    </div>
    <!-- Slides dot-->
    <div class="flex justify-center mt-6 space-x-2 relative z-20">
      <button class="w-3 h-3 rounded-full bg-[#8b4513] opacity-50 slider-dot active"></button>
      <button class="w-3 h-3 rounded-full bg-[#8b4513] opacity-50 slider-dot"></button>
      <button class="w-3 h-3 rounded-full bg-[#8b4513] opacity-50 slider-dot"></button>
    </div>
  </div>
</section>

 <!-- STATS SECTION -->
 <section class="py-8 bg-white">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        
        <div class="bg-[#fdf7f0] rounded-xl p-4 text-center shadow-sm">
          <div class="text-2xl md:text-3xl font-bold text-[#8b4513] mb-1">50+</div>
          <div class="text-gray-600 text-sm">Jenis Baju</div>
        </div>
        
        <div class="bg-[#fdf7f0] rounded-xl p-4 text-center shadow-sm">
          <div class="text-2xl md:text-3xl font-bold text-[#8b4513] mb-1">1000+</div>
          <div class="text-gray-600 text-sm">Pelanggan</div>
        </div>
        
        <div class="bg-[#fdf7f0] rounded-xl p-4 text-center shadow-sm">
          <div class="text-2xl md:text-3xl font-bold text-[#8b4513] mb-1">30+</div>
          <div class="text-gray-600 text-sm">Daerah</div>
        </div>
        
        <div class="bg-[#fdf7f0] rounded-xl p-4 text-center shadow-sm">
          <div class="text-2xl md:text-3xl font-bold text-[#8b4513] mb-1">15</div>
          <div class="text-gray-600 text-sm">Tahun Pengalaman</div>
        </div>
      </div>
    </div>
  </section>

  <!-- KOLEKSI + FILTER -->
  <section class="container mx-auto px-4 py-8 scroll-mt-16" id="galeri">
    <h3 class="text-2xl font-semibold mb-2 text-center text-[#5c2c06]">Koleksi Baju Adat</h3>
    <!-- FILTER KATEGORI -->
    <div class="text-center mb-12">
      <p class="text-gray-600 text-sm md:text-base mx-auto mb-8">
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
          <a href="index.php?kategori=<?= urlencode($kategori); ?>#galeri" class="relative group filter-link flex items-center gap-2 <?= $is_active ? 'text-[#a0522d]' : '' ?>">
            <i class="fa-solid <?= $ikon ?>"></i>
            <?= str_replace('Baju Adat ', '', $kategori); ?>
            <span class="underline-hover absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-[3px] rounded-full transition-all duration-300 <?= $is_active ? 'opacity-100 scale-100' : 'opacity-0 scale-0 group-hover:opacity-50 group-hover:scale-100' ?>"></span>
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
          <div class="p-4 flex flex-col h-full koleksi-card">
            <h4><?= $row['nama']; ?></h4>
            <p><?= $row['kategori']; ?> - <?= $row['ukuran']; ?></p>
            <p class="mt-2"><?= substr($row['deskripsi'], 0, 80); ?>...</p>
            <p class="mt-2 harga">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
            <a href="<?= $wa_link; ?>" target="_blank"
              class="mt-4 inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition text-sm">
              <i class="fa-brands fa-whatsapp mr-2"></i> Sewa via WhatsApp
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- TESTIMONIAL SECTION -->
  <section id="testimonial" class="py-8 bg-white scroll-mt-16">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">Apa Kata Pelanggan Kami</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
          Testimoni jujur dari pelanggan yang telah menggunakan jasa kami
        </p>
      </div>

      <div class="swiper testimonial-swiper">
        <div class="swiper-wrapper pb-12">
          <!-- Testimonial 1 -->
          <div class="swiper-slide">
            <div class="testimonial-card p-8 h-full">
              <div class="quote-icon mb-4">
                <i class="fas fa-quote-left"></i>
              </div>
              <p class="text-gray-700 mb-6">
                "Sangat puas dengan pelayanan dan kualitas bajunya. Baju adat Jawa yang saya sewa untuk pernikahan adik sangat bagus dan nyaman dipakai."
              </p>
              <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Testimoni" class="w-12 h-12 rounded-full mr-4">
                <div>
                  <h4 class="font-bold">Dewi Lestari</h4>
                  <p class="text-sm text-gray-600">Pernikahan, Jakarta</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 2 -->
          <div class="swiper-slide">
            <div class="testimonial-card p-8 h-full">
              <div class="quote-icon mb-4">
                <i class="fas fa-quote-left"></i>
              </div>
              <p class="text-gray-700 mb-6">
                "Proses sewa mudah dan cepat. Baju adat Bali untuk acara wisuda sangat cantik dan mendapat banyak pujian. Harganya juga terjangkau."
              </p>
              <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Testimoni" class="w-12 h-12 rounded-full mr-4">
                <div>
                  <h4 class="font-bold">Budi Santoso</h4>
                  <p class="text-sm text-gray-600">Wisuda, Bandung</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 3 -->
          <div class="swiper-slide">
            <div class="testimonial-card p-8 h-full">
              <div class="quote-icon mb-4">
                <i class="fas fa-quote-left"></i>
              </div>
              <p class="text-gray-700 mb-6">
                "Koleksinya lengkap banget! Saya bisa menemukan baju adat Dayak yang autentik untuk festival budaya. Pelayanannya ramah dan profesional."
              </p>
              <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/women/63.jpg" alt="Testimoni" class="w-12 h-12 rounded-full mr-4">
                <div>
                  <h4 class="font-bold">Siti Rahayu</h4>
                  <p class="text-sm text-gray-600">Festival Budaya, Kalimantan</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 4 -->
          <div class="swiper-slide">
            <div class="testimonial-card p-8 h-full">
              <div class="quote-icon mb-4">
                <i class="fas fa-quote-left"></i>
              </div>
              <p class="text-gray-700 mb-6">
                "Sudah beberapa kali sewa di sini untuk acara sekolah anak. Baju selalu bersih dan rapi. Recommended banget untuk yang butuh baju adat berkualitas."
              </p>
              <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Testimoni" class="w-12 h-12 rounded-full mr-4">
                <div>
                  <h4 class="font-bold">Agus Setiawan</h4>
                  <p class="text-sm text-gray-600">Acara Sekolah, Surabaya</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Add pagination -->
        <div class="swiper-pagination mt-6"></div>
      </div>
    </div>
  </section>

  <!-- FAQ SECTION -->
  <section id="faq" class="py-8 bg-secondary scroll-mt-16">
    <div class="container mx-auto px-2">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
          <h2 class="text-2xl md:text-3xl font-bold mb-3">Pertanyaan yang Sering Diajukan</h2>
          <p class="text-gray-600">
            Temukan jawaban atas pertanyaan umum seputar penyewaan baju adat
          </p>
        </div>

        <div class="space-y-4">
          <!-- FAQ Item 1 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Berapa lama proses penyewaan baju adat?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Proses penyewaan biasanya memakan waktu 1-2 hari kerja setelah konfirmasi pembayaran. Untuk kebutuhan mendesak, kami menyediakan layanan express dengan tambahan biaya.
              </p>
            </div>
          </div>

          <!-- FAQ Item 2 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Apakah tersedia layanan pengantaran?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Ya, kami menyediakan layanan pengantaran dengan biaya tambahan yang disesuaikan dengan jarak lokasi pengantaran. Kami juga bekerja sama dengan jasa pengiriman untuk area yang lebih jauh.
              </p>
            </div>
          </div>

          <!-- FAQ Item 3 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Bagaimana jika ukuran baju tidak pas?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Kami menyediakan layanan fitting sebelum penyewaan untuk memastikan ukuran pas. Jika setelah penyewaan ternyata ukuran tidak sesuai, kami akan berusaha mencari alternatif yang sesuai jika masih tersedia stok.
              </p>
            </div>
          </div>

          <!-- FAQ Item 4 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Apakah ada diskon untuk penyewaan dalam jumlah banyak?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Tentu saja! Kami memberikan diskon khusus untuk penyewaan lebih dari 10 baju. Silakan hubungi kami via WhatsApp untuk negosiasi harga lebih lanjut.
              </p>
            </div>
          </div>

          <!-- FAQ Item 5 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Bagaimana kebersihan baju yang disewa?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Kami memiliki standar kebersihan yang ketat. Setiap baju yang kembali dari penyewaan akan melalui proses dry clean profesional sebelum disewakan kembali kepada pelanggan berikutnya.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-16 bg-amber-800 text-white">
      <div class="container mx-auto px-6 text-center">
        <h2 class="font-display text-3xl md:text-4xl font-bold mb-6">Siap Memesan Baju Adat Impian Anda?</h2>
        <p class="text-amber-100 max-w-2xl mx-auto mb-8">
          Hubungi kami sekarang untuk informasi lebih lanjut atau langsung kunjungi galeri kami.
        </p>
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
          <a href="https://wa.me/6282142544486" target="_blank" class="bg-white hover:bg-gray-100 text-amber-800 px-8 py-3 rounded-full font-medium transition duration-300 flex items-center justify-center">
            <i class="fab fa-whatsapp mr-2 text-xl"></i> Hubungi via WhatsApp
          </a>
          <a href="#" class="border-2 border-white hover:bg-white hover:text-amber-800 px-8 py-3 rounded-full font-medium transition duration-300">
            Lihat Lokasi
          </a>
        </div>
      </div>
    </section>

<!-- Footer -->
<footer class="bg-amber-900 text-white pt-12 pb-6">
  <div class="container mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
      <div>
        <h3 class="font-display text-xl font-bold mb-4">Galeri Bu Nunuk Sahid</h3>
        <p class="text-amber-100 mb-4">
            Penyedia jasa sewa baju adat terlengkap dan terpercaya sejak 2008.
         </p>
        <div class="flex space-x-4">
          <a href="#" class="text-amber-100 hover:text-white text-xl"><i class="fab fa-facebook"></i></a>
          <a href="#" class="text-amber-100 hover:text-white text-xl"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-amber-100 hover:text-white text-xl"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
        
      <div>
        <h4 class="font-display text-lg font-semibold mb-4">Tautan Cepat</h4>
        <ul class="space-y-2">
          <li><a href="#" class="text-amber-100 hover:text-white">Beranda</a></li>
          <li><a href="#galeri" class="text-amber-100 hover:text-white">Koleksi</a></li>
          <li><a href="#about" class="text-amber-100 hover:text-white">Tentang Kami</a></li>
          <li><a href="#testimonials" class="text-amber-100 hover:text-white">Testimoni</a></li>
          <li><a href="admin_list.php" class="text-amber-100 hover:text-white">Admin</a></li>
        </ul>
      </div>
        
      <div>
        <h4 class="font-display text-lg font-semibold mb-4">Kontak Kami</h4>
        <ul class="space-y-2">
          <li class="flex items-center">
            <i class="fas fa-map-marker-alt mr-3 text-amber-200"></i>
            <span class="text-amber-100">Jl. Kebudayaan No. 123, Jakarta</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-phone-alt mr-3 text-amber-200"></i>
            <span class="text-amber-100">(021) 1234567</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-envelope mr-3 text-amber-200"></i>
            <span class="text-amber-100">info@bununuksahid.com</span>
          </li>
        </ul>
      </div>
        
      
    <div class="border-t border-amber-800 pt-6 text-center">
      <p class="text-amber-100 text-sm">
        &copy; Galeri Baju Adat. Dibuat dengan ❤️ oleh Brian.
      </p>
    </div>
  </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script> 
  // Wait for DOM to load
  document.addEventListener('DOMContentLoaded', function() {
    // Hide loading overlay
    setTimeout(function() {
      document.querySelector('.loading-overlay').style.opacity = '0';
      setTimeout(function() {
        document.querySelector('.loading-overlay').style.display = 'none';
      }, 500);
    }, 1000);

    // Hero slider
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

    const slideInterval = setInterval(nextSlide, 8500);

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        clearInterval(slideInterval);
        current = index;
        showSlide(current);
      });
    });

    showSlide(0);

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
    });
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      // === Testimonial Swiper ===
      new Swiper('.testimonial-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        breakpoints: {
          640:  { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
        },
      });

      // === FAQ Accordion ===
      document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => {
          const answer = btn.nextElementSibling;
          const icon   = btn.querySelector('i');
          answer.classList.toggle('hidden');
          icon.style.transform = answer.classList.contains('hidden')
            ? 'rotate(0deg)' : 'rotate(180deg)';
        });
      });

    });
</script>

<script>
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });

  // Tutup dropdown saat menu di klik
  const menuLinks = mobileMenu.querySelectorAll('a');
  menuLinks.forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.add('hidden');
    });
  });
</script>

</body>
</html>
