<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_admin.php");
    exit;
}

require_once __DIR__ . '/../../app/config/koneksi.php';

// Filter Bulan Ini
$bulan_ini = date('m');
$tahun_ini = date('Y');
$label_bulan = date('F Y');

// --- Query Statistik Utama (Hanya Bulan Ini) ---
function getStatistik($koneksi, $status, $m, $y) {
    $stmt = $koneksi->prepare("SELECT COUNT(*) jml FROM penyewaan WHERE status=? AND MONTH(created_at)=? AND YEAR(created_at)=?");
    $stmt->bind_param("sss", $status, $m, $y);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['jml'];
}

$pending = getStatistik($koneksi, 'pending', $bulan_ini, $tahun_ini);
$disetujui = getStatistik($koneksi, 'disetujui', $bulan_ini, $tahun_ini);
$dibatalkan = getStatistik($koneksi, 'dibatalkan', $bulan_ini, $tahun_ini);

$hari_ini = $koneksi->query("SELECT COUNT(*) jml FROM penyewaan WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['jml'];

// --- Query Data untuk Modal Preview ---
// Kita ambil detail singkat untuk ditampilkan di modal
function getListData($koneksi, $type, $m, $y) {
    if ($type === 'hari_ini') {
        $sql = "SELECT p.*, u.nama as penyewa, k.nama as baju FROM penyewaan p 
                JOIN users u ON p.user_id = u.id JOIN koleksi_baju k ON p.koleksi_id = k.id 
                WHERE DATE(p.created_at) = CURDATE() ORDER BY p.created_at DESC LIMIT 10";
    } else {
        $sql = "SELECT p.*, u.nama as penyewa, k.nama as baju FROM penyewaan p 
                JOIN users u ON p.user_id = u.id JOIN koleksi_baju k ON p.koleksi_id = k.id 
                WHERE p.status = '$type' AND MONTH(p.created_at) = '$m' AND YEAR(p.created_at) = '$y' 
                ORDER BY p.created_at DESC LIMIT 10";
    }
    return $koneksi->query($sql);
}

$list_pending = getListData($koneksi, 'pending', $bulan_ini, $tahun_ini);
$list_disetujui = getListData($koneksi, 'disetujui', $bulan_ini, $tahun_ini);
$list_dibatalkan = getListData($koneksi, 'dibatalkan', $bulan_ini, $tahun_ini);
$list_hari_ini = getListData($koneksi, 'hari_ini', $bulan_ini, $tahun_ini);

// Statistik Lainnya (Tetap Keseluruhan)
$total_user = $koneksi->query("SELECT COUNT(*) jml FROM users")->fetch_assoc()['jml'];
$total_testimoni = $koneksi->query("SELECT COUNT(*) jml FROM testimoni")->fetch_assoc()['jml'];
$avg_rating = $koneksi->query("SELECT ROUND(AVG(rating),1) avg FROM testimoni")->fetch_assoc()['avg'];

$title = "Advanced Dashboard";
ob_start();
?>
<h1 class="text-3xl font-bold text-center mb-2">Dashboard Admin</h1>
<p class="text-center text-gray-500 mb-4">Ringkasan aktivitas bulan <strong><?= $label_bulan ?></strong></p>

<h2 class="text-lg font-semibold mb-4">Statistik Pemesanan</h2>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">

    <div onclick="openModal('modalPending')" class="group cursor-pointer bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent hover:border-yellow-400 transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-yellow-100 rounded-lg text-yellow-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 group-hover:text-yellow-500">Lihat Detail →</span>
        </div>
        <p class="text-gray-500 text-sm">Pending (Bulan Ini)</p>
        <p class="text-4xl font-bold text-yellow-500"><?= $pending ?></p>
    </div>

    <div onclick="openModal('modalDisetujui')" class="group cursor-pointer bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent hover:border-green-400 transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-green-100 rounded-lg text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 group-hover:text-green-500">Lihat Detail →</span>
        </div>
        <p class="text-gray-500 text-sm">Disetujui (Bulan Ini)</p>
        <p class="text-4xl font-bold text-green-600"><?= $disetujui ?></p>
    </div>

    <div onclick="openModal('modalDibatalkan')" class="group cursor-pointer bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent hover:border-red-400 transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-red-100 rounded-lg text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 group-hover:text-red-500">Lihat Detail →</span>
        </div>
        <p class="text-gray-500 text-sm">Dibatalkan (Bulan Ini)</p>
        <p class="text-4xl font-bold text-red-500"><?= $dibatalkan ?></p>
    </div>

    <div onclick="openModal('modalHariIni')" class="group cursor-pointer bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent hover:border-indigo-400 transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 group-hover:text-indigo-500">Lihat Detail →</span>
        </div>
        <p class="text-gray-500 text-sm">Pesanan Hari Ini</p>
        <p class="text-4xl font-bold text-indigo-600"><?= $hari_ini ?></p>
    </div>

</div>

<h2 class="text-lg font-semibold mb-4">Statistik Pelanggan</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border">
        <p class="text-sm text-gray-500 mb-1">Total User Terdaftar</p>
        <p class="text-3xl font-semibold"><?= number_format($total_user) ?></p>
        <p class="text-xs text-green-500 mt-2">↑ Pertumbuhan user stabil</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border">
        <p class="text-sm text-gray-500 mb-1">Total Testimoni</p>
        <p class="text-3xl font-semibold"><?= $total_testimoni ?></p>
        <p class="text-xs text-green-500 mt-2">↑ Aktif meningkatkan reputasi</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border">
        <p class="text-sm text-gray-500 mb-1">Rating Rata-rata</p>
        <div class="flex items-end gap-2">
            <p class="text-4xl font-bold text-yellow-500"><?= $avg_rating ?: '0.0' ?></p>
            <p class="text-gray-400 mb-1">/ 5.0</p>
        </div>
        <div class="flex text-yellow-400 mt-2">
            <?php for($i=0; $i<5; $i++) echo '<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>'; ?>
        </div>
    </div>
</div>

<?php 
// Helper function untuk render isi tabel di modal
function renderModalTable($data, $title, $id) {
?>
<div id="<?= $id ?>" class="fixed inset-0 hidden items-center justify-center z-50 bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">

    <div id="box-<?= $id ?>" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full scale-90 opacity-0 transition-all duration-300 relative overflow-hidden">

        <!-- HEADER -->
        <div class="p-6 border-b dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    <?= $title ?>
                </h3>
                <p class="text-sm text-gray-500">
                    Data aktivitas penyewaan bulan ini
                </p>
            </div>

            <!-- CLOSE BUTTON -->
            <button
                onclick="closeModal('<?= $id ?>')"
                class="ml-4 w-10 h-10 flex items-center justify-center
                       rounded-lg text-gray-400 hover:text-red-500
                       hover:bg-gray-200/60 dark:hover:bg-gray-700
                       transition-all text-2xl leading-none"
                aria-label="Close modal">
                &times;
            </button>
        </div>

        <!-- CONTENT -->
        <div class="p-6">
            <div class="overflow-x-auto rounded-xl border dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Penyewa</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Baju</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <?php if ($data->num_rows > 0): ?>
                            <?php while ($row = $data->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold">
                                        <?= htmlspecialchars($row['penyewa']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        <?= htmlspecialchars($row['baju']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            <?= $row['status'] == 'pending'
                                                ? 'bg-yellow-100 text-yellow-700'
                                                : ($row['status'] == 'disetujui'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700') ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    Tidak ada data ditemukan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="p-6 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700 flex justify-end">
            <a href="pesanan_list.php"
               class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-all shadow-lg shadow-blue-200 dark:shadow-none">
                Lihat Semua Pesanan →
            </a>
        </div>

    </div>
</div>
<?php } ?>

<?php 
renderModalTable($list_pending, "Daftar Pesanan Pending", "modalPending");
renderModalTable($list_disetujui, "Daftar Pesanan Disetujui", "modalDisetujui");
renderModalTable($list_dibatalkan, "Daftar Pesanan Dibatalkan", "modalDibatalkan");
renderModalTable($list_hari_ini, "Daftar Pesanan Hari Ini", "modalHariIni");
?>

<script>
// Fungsi Buka Modal dengan Animasi Smooth
function openModal(id) {
    const modal = document.getElementById(id);
    const box = document.getElementById('box-' + id);

    // Tampilkan Overlay dulu
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Trigger animasi scaling & opacity setelah jeda mikro (agar CSS transisi jalan)
    setTimeout(() => {
        modal.classList.add('opacity-100');
        box.classList.remove('scale-90', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    }, 10);

    document.body.style.overflow = 'hidden'; // Kunci scroll layar belakang
}

// Fungsi Tutup Modal dengan Animasi Smooth
function closeModal(id) {
    const modal = document.getElementById(id);
    const box = document.getElementById('box-' + id);

    // Kembalikan ke posisi awal (kecil dan transparan)
    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-90', 'opacity-0');
    modal.classList.remove('opacity-100');

    // Sembunyikan element setelah animasi selesai (300ms sesuai durasi di CSS)
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 250);
}

// Tutup modal jika klik di luar box (pada overlay)
window.onclick = function(event) {
    // Mencari element modal yang sedang aktif
    const modals = ['modalPending', 'modalDisetujui', 'modalDibatalkan', 'modalHariIni'];
    modals.forEach(id => {
        const modal = document.getElementById(id);
        if (event.target === modal) {
            closeModal(id);
        }
    });
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/layout/layout.php";
?>