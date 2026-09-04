<?php
session_start();
require_once 'app/config/koneksi.php';

if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
    header('Location: login.php');
    exit;
}

// Ensure CSRF token exists
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf_token'];

$baju = $koneksi->prepare('SELECT * FROM koleksi_baju ORDER BY created_at DESC');
$baju->execute();
$result = $baju->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Data Koleksi Baju</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6 text-indigo-700">📦 Data Koleksi Baju</h1>
    <a href="index.php" class="inline-block bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">🏠 Kembali ke Home</a>
    <a href="admin.php" class="inline-block mb-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ Tambah Baju Baru</a>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full table-auto text-sm">
        <thead class="bg-indigo-600 text-white">
          <tr>
            <th class="px-4 py-2 text-left">#</th>
            <th class="px-4 py-2 text-left">Gambar</th>
            <th class="px-4 py-2 text-left">Nama</th>
            <th class="px-4 py-2 text-left">Harga</th>
            <th class="px-4 py-2 text-left">Kategori</th>
            <th class="px-4 py-2 text-left">Ukuran</th>
            <th class="px-4 py-2 text-left">Deskripsi</th>
            <th class="px-4 py-2 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while($row = $result->fetch_assoc()): ?>
            <tr class="border-b">
              <td class="px-4 py-2"><?php echo $no++; ?></td>
              <td class="px-4 py-2"><img src="gambar/<?php echo htmlspecialchars($row['gambar'], ENT_QUOTES, 'UTF-8'); ?>" alt="" class="w-16 h-16 object-cover rounded"></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['harga'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['kategori'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['ukuran'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="px-4 py-2"><?php echo nl2br(htmlspecialchars($row['deskripsi'], ENT_QUOTES, 'UTF-8')); ?></td>
              <td class="px-4 py-2 space-x-2">
                <a href="edit_baju.php?id=<?php echo intval($row['id']); ?>" class="text-blue-600 hover:underline">Edit</a>
                <form method="POST" action="hapus_baju.php" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                  <input type="hidden" name="id" value="<?php echo intval($row['id']); ?>">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
                  <button type="submit" class="text-red-600 hover:underline bg-transparent border-0 p-0">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
