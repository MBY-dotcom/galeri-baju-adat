<?php
require_once 'auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

$id = $_GET['id'] ?? 0;

$stmt = $koneksi->prepare("
    SELECT 
        k.*,
        GROUP_CONCAT(
            CONCAT(s.ukuran)
            ORDER BY s.ukuran
            SEPARATOR ', '
        ) AS ukuran_list
    FROM koleksi_baju k
    LEFT JOIN koleksi_stok s ON s.koleksi_id = k.id
    WHERE k.id = ?
    GROUP BY k.id
");

$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo '<p class="text-red-500">Data tidak ditemukan</p>';
    exit;
}
?>

<div class="flex flex-col md:flex-row gap-4">

    <!-- Gambar -->
    <div class="w-full md:w-1/2">
        <img src="../gambar/<?php echo htmlspecialchars($data['gambar']); ?>"
             class="rounded-xl object-cover w-full h-72">
    </div>

    <!-- Detail -->
    <div class="w-full md:w-1/2">
        <h2 class="text-2xl font-bold mb-2">
            <?php echo htmlspecialchars($data['nama']); ?>
        </h2>

        <p class="text-gray-600 dark:text-gray-300 mb-1">
            Kategori: <?php echo htmlspecialchars($data['kategori']); ?>
        </p>

        <p class="text-gray-600 dark:text-gray-300 mb-3">
            Ukuran tersedia: 
            <strong>
                <?php echo htmlspecialchars($data['ukuran_list'] ?? '-'); ?>
            </strong>
        </p>

        <p class="mb-4">
            <?php echo nl2br(htmlspecialchars($data['deskripsi'])); ?>
        </p>

        <p class="text-xl font-bold text-blue-600 dark:text-blue-300">
            Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?>
        </p>
    </div>

</div>
