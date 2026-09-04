<?php
require_once 'app/config/koneksi.php';

$kategori_aktif = $_GET['kategori'] ?? 'Semua';

if ($kategori_aktif !== 'Semua') {
    // Prepare statement to avoid SQL injection
    $stmt = $koneksi->prepare('SELECT * FROM koleksi_baju WHERE kategori = ? ORDER BY created_at DESC');
    $stmt->bind_param('s', $kategori_aktif);
    $stmt->execute();
    $baju = $stmt->get_result();
} else {
    $stmt = $koneksi->prepare('SELECT * FROM koleksi_baju ORDER BY created_at DESC');
    $stmt->execute();
    $baju = $stmt->get_result();
}
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
    /* styles omitted for brevity */
  </style>
</head>
<body class="text-gray-800">

  <!-- page content rendering items should escape output where appropriate -->

  <?php // Example loop (ensure escaping when echoing fields) ?>
  <?php while($row = $baju->fetch_assoc()): ?>
    <!-- Render items -->
  <?php endwhile; ?>

</body>
</html>
