<?php
require_once 'auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

// ambil semua pesanan user
$stmt = $koneksi->prepare("
    SELECT p.*, k.nama AS baju_nama, k.gambar AS baju_gambar, k.kategori AS baju_kategori
    FROM penyewaan p
    JOIN koleksi_baju k ON k.id = p.koleksi_id
    WHERE p.user_id = ?
    ORDER BY p.tanggal DESC, p.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pesanan = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Saya</title>
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
  <div class="min-h-screen flex flex-col md:flex-row">
    <?php include "_sidebar.php"; ?>
    <main class="flex-1 p-6 md:p-8">
      <?php include "_topbar.php"; ?>

      <div class="mt-2 space-y-2 text-center pb-2">
      <h1 class="text-3xl font-bold">Pesanan Saya</h1>
      <p class="text-gray-600 dark:text-gray-300">Lihat semua pesanan baju yang telah Anda lakukan.</p>
      </div>

      <?php if ($pesanan->num_rows === 0): ?>
        <p class="text-gray-600 dark:text-gray-300">Belum ada pesanan.</p>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="w-full table-auto border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
            <thead class="bg-gray-100 dark:bg-gray-700">
              <tr>
                <th class="px-4 py-2 text-left">Baju</th>
                <th class="px-4 py-2 text-left">Kategori</th>
                <th class="px-4 py-2 text-left">Tanggal</th>
                <th class="px-4 py-2 text-left">Sesi</th>
                <th class="px-4 py-2 text-left">Ukuran</th>
                <th class="px-4 py-2 text-left">Jumlah</th>
                <th class="px-4 py-2 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $pesanan->fetch_assoc()): ?>
                <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-4 py-2 flex items-center gap-3">
                    <img src="../gambar/<?php echo htmlspecialchars($row['baju_gambar']); ?>" alt="" class="w-12 h-12 object-cover rounded">
                    <span><?php echo htmlspecialchars($row['baju_nama']); ?></span>
                  </td>
                  <td class="px-4 py-2"><?php echo htmlspecialchars($row['baju_kategori']); ?></td>
                  <td class="px-4 py-2"><?php echo htmlspecialchars($row['tanggal']); ?></td>
                  <td class="px-4 py-2"><?php echo htmlspecialchars($row['sesi']); ?></td>
                  <td class="px-4 py-2"><?php echo htmlspecialchars($row['ukuran']); ?></td>
                  <td class="px-4 py-2"><?php echo (int)$row['jumlah']; ?></td>
                  <td class="px-4 py-2">
                    <?php
                      $status = $row['status'];
                      $statusClass = match($status) {
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'disetujui' => 'bg-green-100 text-green-800',
                        'ditolak' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                      };
                    ?>
                    <span class="px-2 py-1 rounded-full text-sm font-medium <?php echo $statusClass; ?>">
                      <?php echo htmlspecialchars(ucfirst($status)); ?>
                    </span>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </main>
  </div>
</body>
</html>
