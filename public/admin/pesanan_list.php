<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_admin.php");
    exit;
}

require_once __DIR__ . '/../../app/config/koneksi.php';

/* ===============================
   AMBIL PARAMETER FILTER DARI URL
================================ */
$filter_bulan = $_GET['filter_bulan'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';

/* ===============================
   PROSES UPDATE STATUS (ADMIN)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {

    $pesanan_id = (int) $_POST['pesanan_id'];
    $status     = $_POST['status'];

    $allowed_status = ['pending', 'disetujui', 'dibatalkan'];

    if (in_array($status, $allowed_status, true)) {
        $stmt = $koneksi->prepare("UPDATE penyewaan SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $pesanan_id);
        $stmt->execute();
    }

    // Redirect kembali dengan membawa filter yang sedang aktif
    header("Location: pesanan_list.php?filter_bulan=$filter_bulan&filter_status=$filter_status");
    exit;
}

/* ===============================
   AMBIL SEMUA PESANAN (DENGAN FILTER)
================================ */
$query = "
    SELECT 
        p.*,
        u.nama AS nama_penyewa,
        u.no_telp AS kontak_penyewa,
        k.nama AS baju_nama,
        k.gambar AS baju_gambar,
        k.kategori AS baju_kategori
    FROM penyewaan p
    JOIN koleksi_baju k ON k.id = p.koleksi_id
    JOIN users u ON u.id = p.user_id
    WHERE 1=1
";

if (!empty($filter_bulan)) {
    $query .= " AND DATE_FORMAT(p.tanggal, '%Y-%m') = ? ";
}
if (!empty($filter_status)) {
    $query .= " AND p.status = ? ";
}

$query .= " ORDER BY p.tanggal DESC, p.created_at DESC";
$stmt = $koneksi->prepare($query);

if (!empty($filter_bulan) && !empty($filter_status)) {
    $stmt->bind_param("ss", $filter_bulan, $filter_status);
} elseif (!empty($filter_bulan)) {
    $stmt->bind_param("s", $filter_bulan);
} elseif (!empty($filter_status)) {
    $stmt->bind_param("s", $filter_status);
}

$stmt->execute();
$pesanan = $stmt->get_result();
$title = "Daftar Pemesanan";
ob_start();
?>

<div class="mt-2 space-y-2 text-center pb-4">
    <h1 class="text-3xl font-bold">Daftar Pemesanan</h1>
    <p class="text-gray-600 dark:text-gray-300">Semua pesanan penyewaan dari seluruh user</p>
</div>

<div class="overflow-x-auto rounded-xl bg-white dark:bg-gray-800 shadow-md pt-2 px-4 pb-4">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2 border-b border-gray-100 dark:border-gray-700 pb-2">
        <div class="flex items-center gap-6 overflow-x-auto">
            <?php
            $statuses = [
                ''           => ['label' => 'Semua', 'hover' => 'hover:text-indigo-600', 'active' => 'text-indigo-600 border-indigo-600'],
                'pending'    => ['label' => 'Pending', 'hover' => 'hover:text-yellow-500', 'active' => 'text-yellow-500 border-yellow-500'],
                'disetujui'  => ['label' => 'Disetujui', 'hover' => 'hover:text-green-600', 'active' => 'text-green-600 border-green-600'],
                'dibatalkan' => ['label' => 'Dibatalkan', 'hover' => 'hover:text-red-600', 'active' => 'text-red-600 border-red-600'],
            ];

            foreach ($statuses as $key => $val):
                $isActive = ($filter_status === $key);
                $url = "?filter_bulan=" . ($filter_bulan) . "&filter_status=" . $key;
                $baseClass = "relative pb-3 text-sm font-semibold transition-all whitespace-nowrap border-b-2 ";
                $displayClass = $isActive ? $val['active'] : "text-gray-500 border-transparent " . $val['hover'];
            ?>
                <a href="<?= $url ?>" class="<?= $baseClass . $displayClass ?>">
                    <?= $val['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <form method="GET" class="flex flex-row items-center gap-2 bg-gray-50 dark:bg-gray-900/50 p-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <input type="hidden" name="filter_status" value="<?= htmlspecialchars($filter_status) ?>">
            <input
                type="month"
                name="filter_bulan"
                value="<?= htmlspecialchars($filter_bulan ?: date('Y-m')) ?>"
                style="height: 32px; min-width: 140px;"
                class="rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-white text-xs px-2 focus:ring-1 focus:ring-indigo-500 outline-none"
            />
            <button type="submit" style="height: 32px;" class="flex items-center gap-1.5 px-3 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>Filter</span>
            </button>
            <div class="hidden sm:block w-px h-5 bg-gray-300 dark:bg-gray-600 mx-1"></div>
            <button type="button" onclick="window.open('cetak_laporan.php?bulan=<?= $filter_bulan ?>&status=<?= $filter_status ?>', '_blank')" style="height: 32px;" class="flex items-center gap-1.5 px-3 rounded-md bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-all flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak</span>
            </button>
        </form>
    </div>

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
                <th class="px-4 py-2 text-left text-center">Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($pesanan->num_rows > 0): ?>
            <?php while ($row = $pesanan->fetch_assoc()): ?>
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-4 py-2 align-top">
                        <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($row['nama_penyewa']) ?></div>
                        <div class="text-xs text-gray-500"><?= htmlspecialchars($row['kontak_penyewa']) ?></div>
                    </td>
                    <td class="px-4 py-2 flex items-center gap-3">
                        <img src="../gambar/<?= htmlspecialchars($row['baju_gambar']) ?>" class="w-12 h-12 object-cover rounded shadow-sm" alt="">
                        <span class="font-medium"><?= htmlspecialchars($row['baju_nama']) ?></span>
                    </td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['baju_kategori']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td class="px-4 py-2 capitalize"><?= htmlspecialchars($row['sesi']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['ukuran']) ?></td>
                    <td class="px-4 py-2"><?= (int) $row['jumlah'] ?></td>
                    <td class="px-4 py-2 text-center">
                        <form method="POST" action="?filter_bulan=<?= $filter_bulan ?>&filter_status=<?= $filter_status ?>">
                            <input type="hidden" name="pesanan_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status" onchange="this.form.submit()" 
                                    class="px-3 py-1 rounded text-xs font-bold cursor-pointer outline-none shadow-sm
                                    <?= match($row['status']) {
                                        'pending'    => 'bg-yellow-100 text-yellow-800',
                                        'disetujui'  => 'bg-green-100 text-green-800',
                                        'dibatalkan' => 'bg-red-100 text-red-800',
                                        default      => 'bg-gray-100 text-gray-800'
                                    } ?>">
                                <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending</option>
                                <option value="disetujui" <?= $row['status']=='disetujui'?'selected':'' ?>>Disetujui</option>
                                <option value="dibatalkan" <?= $row['status']=='dibatalkan'?'selected':'' ?>>Dibatalkan</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="px-4 py-12 text-center text-gray-400 italic">
                    <div class="flex flex-col items-center">
                        <span class="text-lg">Tidak ada data pemesanan ditemukan</span>
                        <span class="text-xs">Sesuaikan filter status atau bulan Anda</span>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/layout/layout.php";
?>