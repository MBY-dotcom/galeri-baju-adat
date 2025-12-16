<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_admin.php");
    exit;
}

require_once __DIR__ . '/../../app/config/koneksi.php';

// Search
$keyword = isset($_GET['search']) ? trim($_GET['search']) : "";

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$count_query = "SELECT COUNT(*) AS total FROM koleksi_baju";
if ($keyword !== "") {
    $safe = $koneksi->real_escape_string($keyword);
    $count_query .= "
        WHERE nama LIKE '%$safe%' 
        OR kategori LIKE '%$safe%' 
        OR deskripsi LIKE '%$safe%'
    ";
}
$total_data = $koneksi->query($count_query)->fetch_assoc()['total'];
$total_pages = ceil($total_data / $limit);

$base_query = "
    SELECT k.*,
           GROUP_CONCAT(CONCAT(s.ukuran,' (',s.stok,')') ORDER BY s.ukuran SEPARATOR ', ') AS ukuran_stok
    FROM koleksi_baju k
    LEFT JOIN koleksi_stok s ON s.koleksi_id = k.id
";

if ($keyword !== "") {
    $safe = $koneksi->real_escape_string($keyword);
    $base_query .= "
        WHERE k.nama LIKE '%$safe%' 
        OR k.kategori LIKE '%$safe%' 
        OR k.deskripsi LIKE '%$safe%'
    ";
}

$base_query .= "
    GROUP BY k.id
    ORDER BY k.created_at DESC
    LIMIT $offset, $limit
";

$baju = $koneksi->query($base_query);
$title = "Data Koleksi Baju";
ob_start();
?>

<div class="mt-2">
    <h1 class="text-3xl text-center font-bold">Data Koleksi Baju</h1>
    <p class="text-gray-600 dark:text-gray-300 text-center">Tabel data koleksi baju.</p>

    <div class="overflow-x-auto mt-4 bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">

        <div class="flex justify-between items-center mb-6">

            <!-- Search -->
            <form method="GET" class="flex flex-1 mr-8">
                <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>"
                       placeholder="Cari nama baju..."
                       class="flex-1 px-4 py-2 rounded-l-lg border dark:bg-gray-800 dark:border-gray-700 focus:outline-none" />
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-r-lg">Cari</button>
            </form>

            <!-- BUTTON TAMBAH -> buka modal AJAX -->
            <button onclick="openModal('tambah')" 
                    class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Tambah Baju Baru
            </button>
        </div>

        <!-- Tabel -->
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Gambar</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Harga</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-left">Ukuran & Stok</th>
                    <th class="px-4 py-2 text-left">Deskripsi</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $offset + 1; while ($row = $baju->fetch_assoc()): ?>
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2"><?= $no++ ?></td>

                    <td class="px-4 py-2">
                        <?php 
                        $path = __DIR__ . '/../gambar/' . $row['gambar'];
                        if (!empty($row['gambar']) && file_exists($path)): ?>
                            <img src="../gambar/<?= htmlspecialchars($row['gambar']) ?>" class="w-16 h-16 object-cover rounded">
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-xs text-gray-500">
                                No Image
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="px-4 py-2 font-medium"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="px-4 py-2">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['kategori']) ?></td>
                    <td class="px-4 py-2"><?= $row['ukuran_stok'] ?: '-' ?></td>
                    <td class="px-4 py-2 max-w-xl"><?= nl2br(htmlspecialchars(strlen($row['deskripsi']) > 300 ? substr($row['deskripsi'],0,300).'...' : $row['deskripsi'])) ?></td>

                    <td class="px-4 py-2 space-x-2 whitespace-nowrap">
                        <button onclick="openModal('edit', <?= $row['id'] ?>)" 
                                class="text-blue-600 hover:underline">Edit</button>

                        <button onclick="openModal('hapus', <?= $row['id'] ?>)" 
                                class="text-red-600 hover:underline">Hapus</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="flex justify-center mt-6 space-x-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&search=<?= urlencode($keyword) ?>" class="px-3 py-1 bg-gray-200 rounded">Prev</a>
            <?php else: ?>
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded">Prev</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($keyword) ?>"
                   class="px-3 py-1 rounded <?= $i==$page ? 'bg-indigo-600 text-white' : 'bg-gray-200' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page+1 ?>&search=<?= urlencode($keyword) ?>" class="px-3 py-1 bg-gray-200 rounded">Next</a>
            <?php else: ?>
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded">Next</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>


<!-- ================== MODAL AJAX ================== -->
<div id="ajaxModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6 relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600">X</button>
        <div id="modalContent">Memuat...</div>
    </div>
</div>

<!-- ================== SCRIPT AJAX ================== -->
<script>
function openModal(action, id = null) {
    document.getElementById('ajaxModal').classList.remove('hidden');

    fetch("baju_ajax.php?action=" + action + (id ? "&id=" + id : ""))
        .then(res => res.text())
        .then(html => document.getElementById('modalContent').innerHTML = html);
}

function closeModal() {
    document.getElementById('ajaxModal').classList.add('hidden');
    document.getElementById('modalContent').innerHTML = "";
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/layout/layout.php";
?>
