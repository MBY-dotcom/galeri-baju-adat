<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Koleksi Baju</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
  <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
    Tambah Koleksi Baju
  </h2>

  <form action="simpan_baju.php" method="POST" enctype="multipart/form-data" class="space-y-4">

    <div>
      <label class="block text-sm font-medium">Nama Baju</label>
      <input type="text" name="nama" required class="w-full border rounded-xl p-2">
    </div>

    <div>
      <label class="block text-sm font-medium">Deskripsi</label>
      <textarea name="deskripsi" rows="3" required class="w-full border rounded-xl p-2"></textarea>
    </div>

    <div>
      <label class="block text-sm font-medium">Kategori</label>
      <select name="kategori" required class="w-full border rounded-xl p-2">
        <option value="TK">TK</option>
        <option value="SD">SD</option>
        <option value="SMA">SMA</option>
        <option value="Dewasa">Dewasa</option>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium">Harga Sewa (Rp)</label>
      <input type="number" name="harga" required class="w-full border rounded-xl p-2">
    </div>

    <!-- STOK PER UKURAN -->
    <div>
      <label class="block text-sm font-medium mb-2">Stok per Ukuran</label>

      <?php foreach (['S','M','L','XL'] as $u): ?>
        <div class="flex gap-2 mb-2">
          <input type="text" name="ukuran[]" value="<?= $u ?>" readonly
                 class="w-1/3 border rounded p-2 bg-gray-100">
          <input type="number" name="stok[]" placeholder="Jumlah"
                 class="w-2/3 border rounded p-2" min="0">
        </div>
      <?php endforeach; ?>
    </div>

    <div>
      <label class="block text-sm font-medium">Gambar</label>
      <input type="file" name="gambar" required class="w-full text-sm">
    </div>

    <button class="w-full bg-blue-600 text-white py-2 rounded-xl">
      Simpan
    </button>

  </form>
</div>

</body>
</html>
