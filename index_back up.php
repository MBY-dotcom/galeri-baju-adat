<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Bu Nunuk Sahid - Sewa Baju Adat Tuban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1534452203293-494d7ddbc7b4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1472&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .costume-card {
            transition: all 0.3s ease;
        }
        
        .costume-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .testimonial-card {
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover {
            transform: scale(1.03);
        }
        
        .nav-link {
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #f59e0b;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }

        .map-container {
            position: relative;
            overflow: hidden;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .responsive-iframe {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Filter Styles */
        .filter-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .filter-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #f59e0b;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .filter-btn.active {
            color: #f59e0b;
            font-weight: 600;
        }

        .filter-btn.active::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .filter-btn:hover {
            color: #f59e0b;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="#" class="flex items-center">
                        <img src="https://via.placeholder.com/50" alt="Galeri Bu Nunuk Sahid Logo" class="h-10 w-10 rounded-full">
                        <span class="ml-3 text-xl font-bold text-gray-800">Galeri Bu Nunuk Sahid</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="nav-link text-gray-800 hover:text-yellow-600">Beranda</a>
                    <a href="#products" class="nav-link text-gray-800 hover:text-yellow-600">Koleksi</a>
                    <a href="#about" class="nav-link text-gray-800 hover:text-yellow-600">Tentang Kami</a>
                    <a href="#testimonials" class="nav-link text-gray-800 hover:text-yellow-600">Testimoni</a>
                    <a href="#contact" class="nav-link text-gray-800 hover:text-yellow-600">Kontak</a>
                </div>
                
                <div class="md:hidden">
                    <button class="text-gray-800 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero py-32 text-white">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Sewa Baju Adat </h1>
            <p class="text-xl md:text-2xl mb-8">Temukan koleksi baju adat tradisional dari seluruh Nusantara untuk acara spesial Anda</p>
            <div class="flex justify-center space-x-4">
                <a href="#products" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-full transition duration-300">Lihat Koleksi</a>
                <a href="#contact" class="bg-transparent hover:bg-white hover:text-gray-800 text-white font-bold py-3 px-6 border-2 border-white rounded-full transition duration-300">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg bg-white shadow-md">
                    <div class="text-yellow-600 text-4xl mb-4">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Pengantaran Gratis</h3>
                    <p class="text-gray-600">Gratis pengantaran dengan harga murah untuk wilayah Jakarta dan sekitarnya</p>
                </div>
                
                <div class="text-center p-6 rounded-lg bg-white shadow-md">
                    <div class="text-yellow-600 text-4xl mb-4">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Kualitas Premium</h3>
                    <p class="text-gray-600">Bahan berkualitas tinggi dengan jahitan rapi dan detail autentik</p>
                </div>
                
                <div class="text-center p-6 rounded-lg bg-white shadow-md">
                    <div class="text-yellow-600 text-4xl mb-4">
                        <i class="fas fa-spa"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Perawatan Hygienis</h3>
                    <p class="text-gray-600">Setiap baju dicuci dan disterilkan profesional setelah pemakaian</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-16">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Koleksi Baju Adat Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan berbagai pilihan baju adat dari seluruh Indonesia untuk berbagai acara dan kebutuhan</p>
                
                <!-- Filter Buttons -->
                <div class="flex flex-wrap justify-center mt-8 gap-2">
                    <button class="filter-btn active px-4 py-2 rounded-lg" data-filter="all">
                        <i class="fas fa-th-large mr-2"></i>Semua
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg" data-filter="wedding">
                        <i class="fas fa-heart mr-2"></i>Pernikahan
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg" data-filter="graduation">
                        <i class="fas fa-graduation-cap mr-2"></i>Wisuda
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg" data-filter="carnaval">
                        <i class="fas fa-mask mr-2"></i>Carnaval
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="costume-container">
                <!-- Product Card 1 - Wedding -->
                <div class="costume-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300" data-category="wedding">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1600180758890-6b56219c75f9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Baju Adat Jawa" class="w-full h-64 object-cover">
                        <div class="absolute top-2 right-2 bg-yellow-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-heart mr-1"></i> Pernikahan
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Kebaya Jawa</h3>
                        <p class="text-gray-600 mb-4">Kebaya klasik dengan motif batik khas Jawa Tengah untuk pernikahan adat</p>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-600 font-bold">Rp 350.000/hari</span>
                            <button class="sewa-btn bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300" data-name="Kebaya Jawa" data-price="350000">
                                Sewa
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 2 - Graduation -->
                <div class="costume-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300" data-category="graduation">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1563170351-be82bc888aa4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=736&q=80" alt="Baju Adat Bali" class="w-full h-64 object-cover">
                        <div class="absolute top-2 right-2 bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-graduation-cap mr-1"></i> Wisuda
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Baju Adat Bali</h3>
                        <p class="text-gray-600 mb-4">Baju adat Bali lengkap dengan aksesoris untuk wisuda yang elegan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-600 font-bold">Rp 250.000/hari</span>
                            <button class="sewa-btn bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300" data-name="Baju Adat Bali" data-price="250000">
                                Sewa
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 3 - Carnaval -->
                <div class="costume-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300" data-category="carnaval">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1551969014-7d2c4cddf0b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Baju Adat Dayak" class="w-full h-64 object-cover">
                        <div class="absolute top-2 right-2 bg-purple-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-mask mr-1"></i> Carnaval
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Baju Adat Dayak</h3>
                        <p class="text-gray-600 mb-4">Baju adat Dayak dengan aksesoris lengkap untuk karnaval budaya</p>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-600 font-bold">Rp 300.000/hari</span>
                            <button class="sewa-btn bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300" data-name="Baju Adat Dayak" data-price="300000">
                                Sewa
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 4 - Wedding -->
                <div class="costume-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300" data-category="wedding">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Baju Adat Minang" class="w-full h-64 object-cover">
                        <div class="absolute top-2 right-2 bg-yellow-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-heart mr-1"></i> Pernikahan
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Baju Adat Minang</h3>
                        <p class="text-gray-600 mb-4">Baju pengantin Minangkabau dengan hiasan kepala yang megah</p>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-600 font-bold">Rp 450.000/hari</span>
                            <button class="sewa-btn bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300" data-name="Baju Adat Minang" data-price="450000">
                                </i>Sewa
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 5 - Graduation -->
                <div class="costume-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300" data-category="graduation">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1563170351-be82bc888aa4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=736&q=80" alt="Baju Adat Sunda" class="w-full h-64 object-cover">
                        <div class="absolute top-2 right-2 bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-graduation-cap mr-1"></i> Wisuda
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Baju Adat Sunda</h3>
                        <p class="text-gray-600 mb-4">Baju adat Sunda modern dengan sentuhan tradisional untuk wisuda</p>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-600 font-bold">Rp 280.000/hari</span>
                            <button class="sewa-btn bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300" data-name="Baju Adat Sunda" data-price="280000">
                                Sewa
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 6 - Carnaval -->
                <div class="costume-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300" data-category="carnaval">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1551969014-7d2c4cddf0b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Baju Adat Papua" class="w-full h-64 object-cover">
                        <div class="absolute top-2 right-2 bg-purple-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-mask mr-1"></i> Carnaval
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Baju Adat Papua</h3>
                        <p class="text-gray-600 mb-4">Baju adat Papua dengan hiasan bulu dan ornamen khas untuk karnaval</p>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-600 font-bold">Rp 320.000/hari</span>
                            <button class="sewa-btn bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300" data-name="Baju Adat Papua" data-price="320000">
                                Sewa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 md:pr-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Tentang Galeri Bu Nunuk Sahid</h2>
                    <p class="text-gray-600 mb-4">
                        Galeri Bu Nunuk Sahid adalah penyedia jasa sewa baju adat Indonesia yang telah berpengalaman lebih dari 10 tahun. Kami berkomitmen untuk melestarikan dan mempromosikan kekayaan budaya Indonesia melalui pakaian tradisional.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Dengan koleksi lebih dari 100 jenis baju adat dari berbagai daerah di Indonesia, kami siap memenuhi kebutuhan Anda untuk berbagai acara seperti pernikahan, wisuda, festival budaya, dan acara penting lainnya.
                    </p>
                    <p class="text-gray-600">
                        Setiap baju yang kami sewakan dirawat dengan baik dan selalu dalam kondisi prima untuk memberikan pengalaman terbaik bagi pelanggan kami.
                    </p>
                </div>
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1563170351-be82bc888aa4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="About Us" class="rounded-lg shadow-lg w-full h-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Apa Kata Pelanggan Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Testimoni dari pelanggan yang telah menggunakan jasa sewa baju adat di Galeri Bu Nunuk Sahid</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Testimonial" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-800">Sarah Wijaya</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">
                        "Sangat puas dengan pelayanan Galeri Bu Nunuk Sahid. Baju adat Jawa yang saya sewa untuk pernikahan adat sangat bagus dan nyaman dipakai. Harganya juga terjangkau."
                    </p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Testimonial" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-800">Budi Santoso</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">
                        "Baju adat Bali untuk wisuda anak saya sangat elegan. Pelayanan cepat dan ramah. Pasti akan sewa lagi di Galeri Bu Nunuk Sahid untuk acara keluarga berikutnya."
                    </p>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Testimonial" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-800">Dewi Lestari</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">
                        "Pengalaman menyewa baju adat Minang di Galeri Bu Nunuk Sahid sangat menyenangkan. Baju bersih dan wangi, ukuran pas, dan aksesorisnya lengkap. Recommended!"
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Hubungi Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Silakan hubungi kami untuk informasi lebih lanjut atau pemesanan baju adat</p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-8">
                <div class="md:w-1/2">
                    <form class="bg-white p-6 rounded-lg shadow-md">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                            <input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600">
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600">
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                            <input type="tel" id="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600">
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Pesan</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
                
                <div class="md:w-1/2">
                    <div class="bg-white p-6 rounded-lg shadow-md h-full">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Kontak</h3>
                        
                        <div class="flex items-start mb-4">
                            <div class="text-yellow-600 mr-4 mt-1">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Alamat</h4>
                                <p class="text-gray-600">Jl. Kebon Jeruk Raya No. 27, Jakarta Barat 11530</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-4">
                            <div class="text-yellow-600 mr-4 mt-1">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Telepon</h4>
                                <p class="text-gray-600">021-56781234 / 0812-3456-7890</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-4">
                            <div class="text-yellow-600 mr-4 mt-1">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Email</h4>
                                <p class="text-gray-600">info@galeribununuksahid.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-6">
                            <div class="text-yellow-600 mr-4 mt-1">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Jam Operasional</h4>
                                <p class="text-gray-600">Senin - Sabtu: 09.00 - 17.00 WIB</p>
                                <p class="text-gray-600">Minggu: Libur</p>
                            </div>
                        </div>
                        
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.85415631529484!3d-6.2089904627172475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJl.%20Kebon%20Jeruk%20Raya%20No.27%2C%20RT.1%2FRW.9%2C%20Kb.%20Jeruk%2C%20Kec.%20Kb.%20Jeruk%2C%20Kota%20Jakarta%20Barat%2C%20Daerah%20Khusus%20Ibukota%20Jakarta%2011530!5e0!3m2!1sen!2sid!4v1629780983453!5m2!1sen!2sid" class="responsive-iframe" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="flex items-center">
                        <img src="https://via.placeholder.com/50" alt="Galeri Bu Nunuk Sahid Logo" class="h-10 w-10 rounded-full">
                        <span class="ml-3 text-xl font-bold">Galeri Bu Nunuk Sahid</span>
                    </a>
                    <p class="mt-2 text-gray-400">Sewa Baju Adat Indonesia Berkualitas</p>
                </div>
                
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="https://wa.me/6282142544486" class="text-gray-400 hover:text-white transition duration-300">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>
                </div>
            </div>
            
            <hr class="border-gray-700 my-6">
            
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">&copy; 2023 Galeri Bu Nunuk Sahid. All rights reserved.</p>
                
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">FAQ</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Filter Products
        const filterButtons = document.querySelectorAll('.filter-btn');
        const costumeCards = document.querySelectorAll('.costume-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                button.classList.add('active');
                
                const filter = button.dataset.filter;
                
                costumeCards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // WhatsApp Integration
        const sewaButtons = document.querySelectorAll('.sewa-btn');
        
        sewaButtons.forEach(button => {
            button.addEventListener('click', () => {
                const name = button.dataset.name;
                const price = button.dataset.price;
                
                // Format the WhatsApp message
                const message = `Halo Bu Nunuk Sahid, saya ingin menanyakan ketersediaan baju adat berikut:\n\nNama Baju: ${name}\nHarga: Rp ${parseInt(price).toLocaleString('id-ID')}/hari\n\nApakah baju ini tersedia untuk disewa?`;
                
                // Encode the message for URL
                const encodedMessage = encodeURIComponent(message);
                
                // Create WhatsApp link
                const whatsappLink = `https://wa.me/6282142544486?text=${encodedMessage}`;
                
                // Open WhatsApp in a new tab
                window.open(whatsappLink, '_blank');
            });
        });
    </script>
</body>
</html>