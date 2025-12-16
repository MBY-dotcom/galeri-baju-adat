<?php
// Debug: aktifkan sementara untuk melihat error (matikan di production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// load koneksi — pastikan path benar
require_once __DIR__ . '/../app/config/koneksi.php';

// pastikan file koneksi membuat $koneksi (mysqli)
if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Koneksi database tidak tersedia. Periksa file koneksi.php');
}

// ambil kategori aktif dari query string
$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';

if ($kategori_aktif !== 'Semua') {
    // gunakan prepared statement untuk mencegah SQL injection
    $stmt = $koneksi->prepare("SELECT * FROM koleksi_baju WHERE kategori = ? ORDER BY created_at DESC");
    if (!$stmt) {
        die("Prepare gagal: " . $koneksi->error);
    }
    $stmt->bind_param("s", $kategori_aktif);
    $stmt->execute();
    $baju = $stmt->get_result();
} else {
    $baju = $koneksi->query("SELECT * FROM koleksi_baju ORDER BY created_at DESC");
    if (!$baju) {
        die("Query gagal: " . $koneksi->error);
    }
}

// di bagian bawah file, setelah loop, jangan lupa:
// if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
// $koneksi->close();
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeri Baju Adat</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  
  <link href="assets/css/style.css" rel="stylesheet">


  <meta name="description" content="Toko persewaan baju adat Bu Nunuk Sahid. Menyediakan berbagai jenis pakaian adat untuk acara, pernikahan, dan kebutuhan budaya dengan harga terjangkau.">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://contoh-domain.com/"> <!-- GANTI BAGIAN INI: pakai domain asli -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Bu Nunuk Sahid - Persewaan Baju Adat",
    "image": "https://contoh-domain.com/logo.png", <!-- GANTI BAGIAN INI: pakai logo asli -->
    "@id": "https://contoh-domain.com/", <!-- GANTI BAGIAN INI: pakai domain asli -->
    "url": "https://contoh-domain.com/", <!-- GANTI BAGIAN INI: pakai domain asli -->
    "telephone": "+62-812-3456-7890",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Jl. Mawar No. 10",
      "addressLocality": "Surabaya",
      "addressRegion": "Jawa Timur",
      "postalCode": "60123",
      "addressCountry": "ID"
    }
  }
  </script>
</head>

<body class="text-gray-800">
  
<!-- NAVBAR -->
<header class="navbar-header shadow sticky top-0 z-50" style="background-color: #dce0e7;">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-[#0f2858] dark:text-[#fbe9d0]">GALERI BU NUNUK SAHID</h1>

    <!-- Desktop Nav -->
    <nav class="hidden md:flex items-center space-x-4">
      <a href="#" class="text-[#0f2858] dark:text-[#fbe9d0] hover:text-[#0f2858] font-medium">Beranda</a>
      <a href="#tentang-kami" class="text-[#0f2858] dark:text-[#fbe9d0] hover:text-[#0f2858] font-medium">Tentang Kami</a>
      <a href="#galeri" class="text-[#0f2858] dark:text-[#fbe9d0] hover:text-[#0f2858] font-medium">Koleksi Baju</a>
      <a href="#testimonial" class="text-[#0f2858] dark:text-[#fbe9d0] hover:text-[#0f2858] font-medium">Testimoni</a>
      <a href="#faq" class="text-[#0f2858] dark:text-[#fbe9d0] hover:text-[#0f2858] font-medium">Pertanyaan</a>
      <a href="admin/dashboard.php" id="admin-link" class="text-gray-600 hover:text-[#0f2858]" style="display:none;">Admin</a>
    
    <button id="darkModeToggle" class="ml-4 px-3 py-1 border rounded text-sm">🌙</button>
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
    <a href="#" class="flex items-center gap-3 py-3 px-4 text-[#0f2858] dark:text-[#fbe9d0] hover:bg-gray-100/70">
      <i class="fas fa-home"></i> Beranda
    </a>
    <a href="#tentang-kami" class="flex items-center gap-3 py-3 px-4 text-[#0f2858] dark:text-[#fbe9d0] hover:bg-gray-100/70">
      <i class="fas fa-address-card"></i> Tentang Kami
    </a>
    <a href="#galeri" class="flex items-center gap-3 py-3 px-4 text-[#0f2858] dark:text-[#fbe9d0] hover:bg-gray-100/70">
      <i class="fas fa-tshirt"></i> Koleksi Baju
    </a>
    <a href="#testimonial" class="flex items-center gap-3 py-3 px-4 text-[#0f2858] dark:text-[#fbe9d0] hover:bg-gray-100/70">
      <i class="fas fa-comment"></i> Testimoni
    </a>
    <a href="#faq" class="flex items-center gap-3 py-3 px-4 text-[#0f2858] dark:text-[#fbe9d0] hover:bg-gray-100/70">
      <i class="fas fa-question-circle"></i> Pertanyaan
    </a>
    <a href="admin_list.php" id="mobile-admin-link" class="flex items-center gap-3 py-3 px-4 text-gray-600 hover:bg-gray-100/70" style="display:none;">
      <i class="fas fa-user-cog"></i> Admin
    </a>
  </div>
</header>

<!-- HERO SLIDER -->
<section class="relative bg-tribal text-center text-[#0f2858] py-8 md:py-14 overflow-hidden">
  <div class="container mx-auto px-4 relative min-h-[400px]">  
    <!-- Slides 1 -->
    <div class="hero-slide active hero-overlay max-w-4xl mx-auto">
      <p class="text-[17px] md:text-lg mb-2 font-medium">Selamat Datang di</p>
      <h1 class="text-[30px] md:text-4xl font-extrabold mb-3 tracking-wide leading-snug">GALERI BU NUNUK SAHID</h2>
      <p class="text-[16px] md:text-lg mb-6 text-gray-700 leading-relaxed">Temukan koleksi baju adat terlengkap dari seluruh Indonesia untuk berbagai acara dan kebutuhan Anda.</p>
      <a href="dashboard/index.php" class="btn-tribal text-[#0f2858] dark:text-[#fbe9d0] px-6 py-3 rounded-full text-base inline-block min-w-[200px] text-center">Jelajahi Koleksi</a>
    </div>
  </div>
</section>
<!-- Bagian Hitam + Stats -->
<section class="stats-section relative bg-[#00070f] pt-2 pb-6">
  <div class="container mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
  </div>
</section>
<!-- Gradient Transisi -->
<div class="gradient-bar h-16 bg-gradient-to-b from-[#00070f] to-white"></div>


<!-- About Section -->
    <section id="tentang-kami" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8 hidden md:block" data-aos="fade-right">
                    <img src="gambar/Cuplikan layar.png" alt="Bu Nunuk Sahid" class="rounded-lg shadow-lg w-full">
                </div>
                <div class="md:w-1/2" data-aos="fade-left">
                    <h2 class="text-3xl text-[#0f2858] dark:text-[#fbe9d0] font-serif font-bold text-primary mb-4">Tentang Galeri Bu Nunuk Sahid</h2>
                    <p class="text-gray-600 text-justify leading-relaxed mb-6 ">
                      Galeri Bu Nunuk Sahid adalah penyedia <strong>persewaan baju adat Tuban, Lamongan, dan Bojonegoro</strong> 
                      yang sudah dipercaya masyarakat selama lebih dari <span class="font-semibold">15 tahun</span>. 
                      Kami menghadirkan koleksi busana adat dari berbagai daerah di Indonesia dengan kualitas terbaik, 
                      cocok untuk <strong>acara wisuda, pesta, pernikahan, hingga karnaval budaya</strong>. 
                      Dengan layanan cepat, harga bersahabat, serta pilihan model yang lengkap, 
                      kami berkomitmen mendukung setiap momen penting Anda menjadi lebih berkesan. 
                      Banyak pelanggan dari wilayah Tuban, Lamongan, dan Bojonegoro memilih kami karena selain koleksi yang lengkap, 
                      pelayanan kami juga ramah dan terpercaya.
                    </p>
                    <div class="flex space-x-4">
                        <div class="bg-gray-100 p-4 rounded-lg text-center flex-1">
                            <i data-feather="award" class="text-primary w-8 h-8 mx-auto mb-2"></i>
                            <p class="font-bold">15+ Tahun</p>
                            <p class="text-sm">Pengalaman</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg text-center flex-1">
                            <i data-feather="layers" class="text-primary w-8 h-8 mx-auto mb-2"></i>
                            <p class="font-bold">200+</p>
                            <p class="text-sm">Koleksi</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg text-center flex-1">
                            <i data-feather="users" class="text-primary w-8 h-8 mx-auto mb-2"></i>
                            <p class="font-bold">1000+</p>
                            <p class="text-sm">Pelanggan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- KOLEKSI BAJU -->
<section class="mx-auto px-4 py-8 scroll-mt-12" id="galeri">
  <h3 class="text-2xl md:text-3xl font-bold mb-3 text-center text-[#001247] dark:text-[#fbe9d0]">
    Koleksi Pilihan
  </h3>

  <p class="text-center text-gray-600 dark:text-gray-300 mb-8">
    Beberapa koleksi pilihan yang sering disewa dan terbaru
  </p>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
    <?php
    $index = 0;
    while ($row = $baju->fetch_assoc()):
      $index++;

      // fake ribbon logic
      if ($index <= 2) {
          $ribbon_text = "Best Seller";
          $ribbon_class = "bg-red-600";
      } else {
          $ribbon_text = "New Arrival";
          $ribbon_class = "bg-indigo-600";
      }
    ?>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border hover:shadow-lg transition">

        <!-- RIBBON -->
        <span class="absolute top-3 left-3 text-xs text-white px-3 py-1 rounded-full <?= $ribbon_class ?>">
          <?= $ribbon_text ?>
        </span>

        <a href="detail.php?id=<?= $row['id'] ?>">
          <img src="gambar/<?= htmlspecialchars($row['gambar']) ?>"
               alt="<?= htmlspecialchars($row['nama']) ?>"
               class="w-full h-48 object-cover"
               loading="lazy">
        </a>

        <div class="p-4">
          <h4 class="font-semibold text-lg mb-1">
            <?= htmlspecialchars($row['nama']) ?>
          </h4>

          <p class="text-sm text-gray-500 mb-2">
            <?= htmlspecialchars($row['kategori']) ?>
          </p>

          <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
            <?= htmlspecialchars(substr($row['deskripsi'], 0, 80)) ?>...
          </p>

          <p class="font-bold text-indigo-600 mb-3">
            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
          </p>

          <a href="detail.php?id=<?= $row['id'] ?>"
             class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md text-sm transition">
            Lihat Detail
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>


<!-- How It Works -->
 <section class="py-16 bg-gray-100" id="cara-sewa">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl  text-[#0f2858] dark:text-[#fbe9d0] font-bold text-center mb-12 text-primary">Cara Mudah Sewa di Tempat Kami</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                    <div class="flex flex-col items-center animate__animated animate__fadeInUp">
                        <div class="w-20 h-20 bg-primary/20 text-primary rounded-full flex items-center justify-center mb-4 transition-transform duration-500 hover:scale-110">
                            <i class="fas fa-search text-3xl"></i>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">1. Pilih Busana</h3>
                        <p class="text-gray-600 text-sm">Jelajahi koleksi kami setelah login dan temukan busana favorit Anda.</p>
                    </div>
                    <div class="flex flex-col items-center animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="w-20 h-20 bg-primary/20 text-primary rounded-full flex items-center justify-center mb-4 transition-transform duration-500 hover:scale-110">
                            <i class="fas fa-calendar-check text-3xl"></i>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">2. Reservasi</h3>
                        <p class="text-gray-600 text-sm">Hubungi kami untuk cek ketersediaan dan booking tanggal.</p>
                    </div>
                    <div class="flex flex-col items-center animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="w-20 h-20 bg-primary/20 text-primary rounded-full flex items-center justify-center mb-4 transition-transform duration-500 hover:scale-110">
                            <i class="fas fa-hand-holding-heart text-3xl"></i>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">3. Ambil & Pakai</h3>
                        <p class="text-gray-600 text-sm">Ambil busana di galeri kami dan nikmati momen spesial Anda.</p>
                    </div>
                    <div class="flex flex-col items-center animate__animated animate__fadeInUp animate__delay-3s">
                        <div class="w-20 h-20 bg-primary/20 text-primary rounded-full flex items-center justify-center mb-4 transition-transform duration-500 hover:scale-110">
                            <i class="fas fa-box-open text-3xl"></i>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">4. Kembalikan</h3>
                        <p class="text-gray-600 text-sm">Kembalikan busana sesuai dengan tanggal yang disepakati.</p>
                    </div>
                </div>
            </div>
        </section>


  <!-- FAQ SECTION -->
  <section id="faq" class="py-8 bg-secondary scroll-mt-12">
    <div class="container mx-auto px-2">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
          <h2 class="text-2xl md:text-3xl font-bold mb-3 text-center text-[#001247] dark:text-[#fbe9d0]">Pertanyaan yang Sering Diajukan</h2>
          <p class="text-gray-600">
            Temukan jawaban atas pertanyaan umum seputar penyewaan baju adat
          </p>
        </div>

        <div class="space-y-4">
          <!-- FAQ Item 1 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Kapan baju mulai bisa diambil ?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Baju baru boleh diambil antara H-1 sebelum acara.
              </p>
            </div>
          </div>

           <!-- FAQ Item 2 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Kapan sebiknya baju dikembalikan?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Baju sebaiknya dikembalikan hari H setelah selesai acara hingga paling lambat H+1 acara. Keterlambatan pengembalian baju akan dikenai denda.
              </p>
            </div>
          </div>

          <!-- FAQ Item 3 -->
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

          <!-- FAQ Item 4 -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-question w-full text-left p-5 flex justify-between items-center focus:outline-none">
              <span class="font-medium">Bagaimana jika ukuran baju tidak pas?</span>
              <i class="fas fa-chevron-down text-primary-light transition-transform duration-300"></i>
            </button>
            <div class="faq-answer px-5 pb-5 hidden">
              <p class="text-gray-700">
                Kami menyediakan layanan fitting sebelum penyewaan untuk memastikan ukuran pas. Fitting boleh dilakukan kapanpun bahkan jauh hari sebelum hari H.
              </p>
            </div>
          </div>

          <!-- FAQ Item 5 -->
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

          <!-- FAQ Item 6 -->
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

    <!-- TESTIMONIAL SECTION -->
  <section id="testimonial" class="py-8 bg-white scroll-mt-12">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-2xl md:text-3xl font-bold mb-3 text-center text-[#001247] dark:text-[#fbe9d0]">Apa Kata Pelanggan Kami</h2>
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

<!-- Contact -->
 <section class="py-16 bg-white" id="kontak">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-10 text-primary">Hubungi & Kunjungi Kami</h2>
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="md:w-1/2 animate__animated animate__fadeInLeft">
                        <p class="text-gray-600 mb-4 text-center md:text-left">Ingin tahu lebih lanjut atau melakukan reservasi? Jangan ragu untuk menghubungi kami melalui kontak di bawah ini.</p>
                        <div class="flex flex-col items-center md:items-start space-y-4">
                            <a href="https://wa.me/6282142544486" target="_blank" class="flex items-center space-x-2 text-gray-700 hover:text-green-500 transition-colors duration-300">
                                <i class="fab fa-whatsapp text-2xl"></i>
                                <span>WhatsApp: +62 821-4254-4486</span>
                            </a>
                            <a href="https://instagram.com/galeribununuksahid" target="_blank" class="flex items-center space-x-2 text-gray-700 hover:text-pink-500 transition-colors duration-300">
                                <i class="fab fa-instagram text-2xl"></i>
                                <span>Instagram: @galeribununuksahid</span>
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 w-full h-80 rounded-lg overflow-hidden shadow-lg animate__animated animate__fadeInRight">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.247190011406!2d112.74838407455822!3d-7.299596071881768!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb7f43b5938d%3A0x6a0f44e268a2d1d4!2sToko%20Baju%20Adat%20Nusantara%20Bu%20Nunuk%20Sahid!5e0!3m2!1sid!2sid!4v1699971987545!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>

<!-- Footer -->
<footer class="text-white pt-12 pb-6" style="background-color:#a8b1c3;">
  <div class="container mx-auto px-6"> 
      <div class="border-t-2 border-black mt-4 pt-6 text-center">
        <p class="text-sm" style="color:black;">
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


<script> // Dropdown Menu
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

<script>
  document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.key === 'l') {
      const adminLink = document.getElementById('admin-link');
      const mobileAdminLink = document.getElementById('mobile-admin-link');
      if (adminLink) {
        adminLink.style.display = adminLink.style.display === 'none' ? 'block' : 'none';
      }
      if (mobileAdminLink) {
        mobileAdminLink.style.display = mobileAdminLink.style.display === 'none' ? 'block' : 'none';
      }
    }
  });
</script>
<script>
  const darkModeToggle = document.getElementById('darkModeToggle');
  const body = document.body;

  // Cek preferensi mode gelap di localStorage
  if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark');
    darkModeToggle.textContent = '☀️';
  }

  darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark');
    if (body.classList.contains('dark')) {
      darkModeToggle.textContent = '☀️';
      localStorage.setItem('darkMode', 'enabled');
    } else {
      darkModeToggle.textContent = '🌙';
      localStorage.setItem('darkMode', 'disabled');
    }
  });
</script>
</body>
</html>
<?php
// Tutup koneksi database