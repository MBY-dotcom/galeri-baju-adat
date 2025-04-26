<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
  header("Location: login.php");
  exit;
}
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");
$baju = $koneksi->query("SELECT * FROM koleksi_baju ORDER BY created_at DESC");
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
          <?php $no = 1; while($row = $baju->fetch_assoc()): ?>
            <tr class="border-b">
              <td class="px-4 py-2"><?php echo $no++; ?></td>
              <td class="px-4 py-2"><img src="gambar/<?php echo $row['gambar']; ?>" alt="" class="w-16 h-16 object-cover rounded"></td>
              <td class="px-4 py-2"><?php echo $row['nama']; ?></td>
              <td class="px-4 py-2"><?php echo $row['harga']; ?></td>
              <td class="px-4 py-2"><?php echo $row['kategori']; ?></td>
              <td class="px-4 py-2"><?php echo $row['ukuran']; ?></td>
              <td class="px-4 py-2"><?php echo $row['deskripsi']; ?></td>
              <td class="px-4 py-2 space-x-2">
                <a href="edit_baju.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline">Edit</a>
                <a href="hapus_baju.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>
