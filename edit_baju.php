<?php
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

// Ambil data baju berdasarkan ID
$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM koleksi_baju WHERE id = $id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Baju</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="max-w-xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6 text-indigo-700">✏️ Edit Data Baju</h1>

    <form action="update_baju.php" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
      <input type="hidden" name="id" value="<?= $data['id']; ?>">

      <div>
        <label class="block mb-1 font-medium">Nama Baju</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block mb-1 font-medium">Kategori</label>
        <input type="text" name="kategori" value="<?= $data['kategori']; ?>" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block mb-1 font-medium">Ukuran</label>
        <input type="text" name="ukuran" value="<?= $data['ukuran']; ?>" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block mb-1 font-medium">Deskripsi</label>
        <textarea name="deskripsi" required class="w-full border rounded px-3 py-2"><?= $data['deskripsi']; ?></textarea>
      </div>

      <div>
        <label class="block mb-1 font-medium">Gambar Sekarang</label>
        <img src="gambar/<?= $data['gambar']; ?>" alt="" class="w-32 h-32 object-cover rounded mb-2">
        <input type="file" name="gambar" class="block">
        <small class="text-gray-500 text-sm">Kosongkan jika tidak ingin mengganti gambar.</small>
      </div>

      <div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Simpan Perubahan</button>
        <a href="admin_list.php" class="ml-2 text-gray-600 hover:underline">Kembali</a>
      </div>
    </form>
  </div>

</body>
</html>
