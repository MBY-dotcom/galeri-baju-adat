<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Koleksi Baju</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Tambah Koleksi Baju</h2>
    
    <form action="simpan_baju.php" method="POST" enctype="multipart/form-data" class="space-y-4">
      <div>
        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Baju</label>
        <input type="text" name="nama" id="nama" required class="mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="3" required class="mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
      </div>

       <div>
        <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="kategori" id="kategori" required class="mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="Pernikahan">Pernikahan</option>
            <option value="Wisuda">Wisuda</option>
            <option value="Baju Adat Carnaval">Baju Adat Carnaval</option>
        </select>
       </div>


      <div>
        <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran</label>
        <input type="text" name="ukuran" id="ukuran" placeholder="S / M / L / XL" required class="mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Harga Sewa (Rp)</label>
        <input type="number" name="harga" class="mt-1 block w-full border rounded-md p-2" required>
      </div>


      <div>
        <label for="gambar" class="block text-sm font-medium text-gray-700">Upload Gambar</label>
        <input type="file" name="gambar" id="gambar" accept="image/*" required class="mt-1 w-full text-sm text-gray-600">
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-xl hover:bg-blue-700 transition">Simpan</button>
    </form>
  </div>

</body>
</html>
