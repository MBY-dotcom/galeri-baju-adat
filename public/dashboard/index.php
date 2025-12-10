<?php
require_once __DIR__ . '/auth.php';
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex min-h-screen">

        <?php include "_sidebar.php"; ?>

        <main class="flex-1 pt-2 px-6 pb-6 md:pt-2 md:px-8 md:pb-8">

            <?php include "_topbar.php"; ?>

            <div class="mt-2 space-y-2">
                <h1 class="text-3xl text-center font-bold">Selamat Datang di Galeri Bu Nunuk Sahid</h1>
                <p class="text-gray-600 text-center dark:text-gray-300">Panduan lengkap cara melakukan pemesanan baju di website.</p>

                <!-- CARD TUTORIAL -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl mt-10 p-6 shadow-md border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold mb-4">Panduan Cara Memesan Baju</h2>

                    <ol class="list-decimal ml-6 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>Buka halaman <strong>Koleksi Baju</strong> dan cari model yang Anda inginkan.</li>
                        <li>Klik salah satu baju untuk melihat detail.</li>
                        <li>Tekan tombol <strong>Lihat Ketersediaan</strong>.</li>
                        <li>Isi formulir tanggal peminjaman dan jumlah baju.</li>
                        <li>Klik <strong>Cek Ketersediaan</strong>. Jika tersedia, tekan <strong>Masukkan ke Keranjang</strong>.</li>
                        <li>Buka halaman <strong>Keranjang</strong> untuk melanjutkan pemesanan.</li>
                        <li>Selesaikan proses pemesanan dan lihat progres di halaman <strong>Pesanan Saya</strong>.</li>
                    </ol>
                </div>

            </div>
        </main>

    </div>
</body>
</html>