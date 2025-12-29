<?php
require_once 'auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Ambil pesanan user + cek apakah sudah ada testimoni
|--------------------------------------------------------------------------
*/
$stmt = $koneksi->prepare("
    SELECT 
        p.*,
        k.nama AS baju_nama,
        k.gambar AS baju_gambar,
        k.kategori AS baju_kategori,
        t.id AS testimoni_id
    FROM penyewaan p
    JOIN koleksi_baju k ON k.id = p.koleksi_id
    LEFT JOIN testimoni t ON t.penyewaan_id = p.id
    WHERE p.user_id = ?
    ORDER BY p.tanggal DESC, p.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pesanan = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesanan Saya</title>
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

<div class="min-h-screen flex">
  <?php include "_sidebar.php"; ?>

  <main class="flex-1 px-6 py-4">
    <?php include "_topbar.php"; ?>

    <h1 class=" mt-2 space-y-2 text-center text-3xl font-bold">Pesanan Saya</h1>
    <p class=" mt-2 space-y-2 text-center text-gray-600 dark:text-gray-300">
      Daftar semua pesanan baju Anda
    </p>

    <?php if ($pesanan->num_rows === 0): ?>
      <p class="text-gray-500">Belum ada pesanan.</p>
    <?php else: ?>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left">Baju</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3">Ukuran</th>
            <th class="px-4 py-3">Jumlah</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>

        <?php while ($row = $pesanan->fetch_assoc()): ?>
          <tr class="border-t dark:border-gray-700">
            <td class="px-4 py-3 flex items-center gap-3">
              <img src="../gambar/<?= htmlspecialchars($row['baju_gambar']) ?>"
                   class="w-12 h-12 rounded object-cover">
              <div>
                <div class="font-medium"><?= htmlspecialchars($row['baju_nama']) ?></div>
                <div class="text-xs text-gray-500"><?= htmlspecialchars($row['baju_kategori']) ?></div>
              </div>
            </td>

            <td class="px-4 py-3"><?= htmlspecialchars($row['tanggal']) ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['ukuran']) ?></td>
            <td class="px-4 py-3"><?= (int)$row['jumlah'] ?></td>

            <td class="px-4 py-3">
              <?php
                $badge = match ($row['status']) {
                  'pending'   => 'bg-yellow-100 text-yellow-800',
                  'disetujui' => 'bg-green-100 text-green-800',
                  'ditolak'   => 'bg-red-100 text-red-800',
                  default     => 'bg-gray-100 text-gray-800'
                };
              ?>
              <span class="px-2 py-1 rounded text-xs <?= $badge ?>">
                <?= ucfirst($row['status']) ?>
              </span>
            </td>

            <td class="px-4 py-3">
              <?php if (
                in_array($row['status'], ['disetujui','selesai']) &&
                !$row['testimoni_id']
              ): ?>
                <button
                  onclick="openReviewModal(<?= $row['id'] ?>,'<?= addslashes($row['baju_nama']) ?>')"
                  class="px-3 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">
                  Beri Testimoni
                </button>
              <?php elseif ($row['testimoni_id']): ?>
                <span class="text-green-600 text-xs">Sudah direview</span>
              <?php else: ?>
                <span class="text-gray-400 text-xs">–</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>

        </tbody>
      </table>
    </div>

    <?php endif; ?>
  </main>
</div>

<!-- MODAL REVIEW -->
<div id="reviewModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
    <h3 class="text-lg font-bold mb-4">
      Review: <span id="modalBajuNama"></span>
    </h3>

    <form method="POST" action="simpan_testimoni.php">
      <input type="hidden" name="penyewaan_id" id="modalPenyewaanId">

      <label class="block mb-1 font-medium">Rating</label>
      <select name="rating" required class="w-full border px-3 py-2 rounded mb-3">
        <option value="">Pilih</option>
        <option value="5">★★★★★</option>
        <option value="4">★★★★</option>
        <option value="3">★★★</option>
        <option value="2">★★</option>
        <option value="1">★</option>
      </select>

      <label class="block mb-1 font-medium">Komentar</label>
      <textarea name="komentar" required rows="4"
        class="w-full border px-3 py-2 rounded mb-4"></textarea>

      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeReviewModal()"
          class="px-4 py-2 border rounded">
          Batal
        </button>
        <button type="submit"
          class="px-4 py-2 bg-indigo-600 text-white rounded">
          Kirim
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openReviewModal(id, nama) {
  document.getElementById('modalPenyewaanId').value = id;
  document.getElementById('modalBajuNama').innerText = nama;
  document.getElementById('reviewModal').classList.remove('hidden');
  document.getElementById('reviewModal').classList.add('flex');
}

function closeReviewModal() {
  document.getElementById('reviewModal').classList.add('hidden');
  document.getElementById('reviewModal').classList.remove('flex');
}
</script>

</body>
</html>
