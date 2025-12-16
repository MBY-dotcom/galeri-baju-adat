<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_admin.php");
    exit;
}

require_once __DIR__ . '/../../app/config/koneksi.php';

/*
  CATATAN STRUKTUR (berdasarkan kode USER Anda):
  penyewaan:
  - id
  - user_id
  - koleksi_id
  - tanggal
  - sesi
  - ukuran
  - jumlah
  - status
  - created_at
*/

// Ambil semua pesanan (ADMIN = TANPA filter user)
$stmt = $koneksi->prepare("
    SELECT 
        p.*,
        u.nama AS nama_penyewa,
        u.no_telp AS email_penyewa,
        k.nama AS baju_nama,
        k.gambar AS baju_gambar,
        k.kategori AS baju_kategori
    FROM penyewaan p
    JOIN koleksi_baju k ON k.id = p.koleksi_id
    JOIN users u ON u.id = p.user_id
    ORDER BY p.tanggal DESC, p.created_at DESC
");
$stmt->execute();
$pesanan = $stmt->get_result();

$title = "Daftar Pemesanan";
ob_start();
?>

<div class="mt-2 space-y-2 text-center pb-4">
    <h1 class="text-3xl font-bold">Daftar Pemesanan</h1>
    <p class="text-gray-600 dark:text-gray-300">
        Semua pesanan penyewaan dari seluruh user
    </p>
</div>

<div class="overflow-x-auto rounded-xl bg-white dark:bg-gray-800 shadow-md p-4">

<?php if ($pesanan->num_rows === 0): ?>
    <p class="text-gray-600 dark:text-gray-300 text-center">
        Belum ada data pemesanan.
    </p>
<?php else: ?>

<table class="min-w-full table-auto text-sm">
    <thead class="bg-indigo-600 text-white">
        <tr>
            <th class="px-4 py-2 text-left">Penyewa</th>
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
        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

            <!-- Penyewa -->
            <td class="px-4 py-2 align-top">
                <div class="font-medium"><?= htmlspecialchars($row['nama_penyewa']) ?></div>
                <div class="text-xs text-gray-500"><?= htmlspecialchars($row['email_penyewa']) ?></div>
            </td>

            <!-- Baju -->
            <td class="px-4 py-2 flex items-center gap-3">
                <img src="../gambar/<?= htmlspecialchars($row['baju_gambar']) ?>"
                     class="w-12 h-12 object-cover rounded"
                     alt="">
                <span><?= htmlspecialchars($row['baju_nama']) ?></span>
            </td>

            <td class="px-4 py-2"><?= htmlspecialchars($row['baju_kategori']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['sesi']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['ukuran']) ?></td>
            <td class="px-4 py-2"><?= (int)$row['jumlah'] ?></td>

            <!-- Status -->
            <td class="px-4 py-2">
                <?php
                $status = $row['status'];
                $statusClass = match ($status) {
                    'pending'   => 'bg-yellow-100 text-yellow-800',
                    'disetujui' => 'bg-green-100 text-green-800',
                    'ditolak'   => 'bg-red-100 text-red-800',
                    default     => 'bg-gray-100 text-gray-800'
                };
                ?>
                <span class="px-2 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                    <?= htmlspecialchars(ucfirst($status)) ?>
                </span>
            </td>

        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php endif; ?>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/layout/layout.php";
