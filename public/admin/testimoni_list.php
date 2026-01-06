<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_admin.php");
    exit;
}

require_once __DIR__ . '/../../app/config/koneksi.php';

// Ambil filter rating dari URL
$filter_rating = $_GET['filter_rating'] ?? '';

/* ===============================
   PROSES HAPUS TESTIMONI
================================ */
if (isset($_POST['hapus_testimoni'])) {
    $id_testi = (int)$_POST['id_testimoni'];
    $stmt_del = $koneksi->prepare("DELETE FROM testimoni WHERE id = ?");
    $stmt_del->bind_param("i", $id_testi);
    $stmt_del->execute();
    header("Location: testimoni_list.php?filter_rating=$filter_rating");
    exit;
}

/* ===============================
   AMBIL DATA TESTIMONI + NO TELP
================================ */
$query = "
    SELECT
        t.*,
        u.nama AS nama_user,
        u.no_telp AS telp_user,
        k.nama AS nama_baju,
        k.gambar AS gambar_baju
    FROM testimoni t
    JOIN users u ON u.id = t.user_id
    JOIN penyewaan p ON p.id = t.penyewaan_id
    JOIN koleksi_baju k ON k.id = p.koleksi_id
    WHERE 1=1
";

if (!empty($filter_rating)) {
    $query .= " AND t.rating = ? ";
}

$query .= " ORDER BY t.created_at DESC";

$stmt = $koneksi->prepare($query);
if (!empty($filter_rating)) {
    $stmt->bind_param("i", $filter_rating);
}
$stmt->execute();
$testimoni = $stmt->get_result();

$title = "Data Testimoni";
ob_start();
?>

<div class="mt-2 space-y-2 text-center pb-4">
    <h1 class="text-3xl font-bold">Data Testimoni</h1>
    <p class="text-gray-600 dark:text-gray-300">Pantau kepuasan pelanggan dan hubungi user jika ada kendala</p>
</div>

<div class="overflow-x-auto rounded-xl bg-white dark:bg-gray-800 shadow-md pt-2 px-4 pb-4">
    
    <div class="flex items-center justify-center gap-6 overflow-x-auto mb-6 border-b border-gray-100 dark:border-gray-700 pb-2">
        <?php
        $ratings = [
            ''  => 'Semua Review',
            '5' => 'Bintang 5',
            '4' => 'Bintang 4',
            '3' => 'Bintang 3',
            '2' => 'Bintang 2',
            '1' => 'Bintang 1',
        ];

        foreach ($ratings as $key => $label):
            $isActive = ($filter_rating === $key);
            $url = "?filter_rating=" . $key;
            $baseClass = "relative pb-3 text-sm font-semibold transition-all whitespace-nowrap border-b-2 ";
            $displayClass = $isActive ? "text-indigo-600 border-indigo-600" : "text-gray-500 border-transparent hover:text-indigo-400";
        ?>
            <a href="<?= $url ?>" class="<?= $baseClass . $displayClass ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <table class="min-w-full table-auto text-sm">
        <thead class="bg-indigo-600 text-white">
            <tr>
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3 text-left">Baju</th>
                <th class="px-4 py-3 text-center">Rating</th>
                <th class="px-4 py-3 text-left">Komentar</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($testimoni->num_rows > 0): ?>
                <?php while ($row = $testimoni->fetch_assoc()): ?>
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-3 align-top">
                            <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($row['nama_user']) ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($row['telp_user'] ?: '-') ?></div>
                            <div class="text-[10px] text-gray-400 mt-1"><?= date('d/m/Y', strtotime($row['created_at'])) ?></div>
                        </td>
                        
                        <td class="px-4 py-3 flex items-center gap-3 align-top">
                            <img src="../gambar/<?= htmlspecialchars($row['gambar_baju']) ?>" 
                                 style="width: 40px !important; height: 40px !important; object-fit: cover !important;" 
                                 class="rounded shadow-sm">
                            <span class="font-medium text-xs"><?= htmlspecialchars($row['nama_baju']) ?></span>
                        </td>
                       <td class="px-4 py-3 text-center align-top">
                            <div class="flex justify-center items-center gap-0.5">
                                <?php 
                                // Pastikan rating adalah angka (integer)
                                $current_rating = (int)$row['rating']; 
                                
                                for ($i = 1; $i <= 5; $i++): 
                                    // Cek apakah urutan bintang ($i) lebih kecil atau sama dengan rating user
                                    $isFilled = ($i <= $current_rating);
                                ?>
                                    <svg class="w-4 h-4" 
                                        viewBox="0 0 20 20" 
                                        style="fill: <?= $isFilled ? '#fbbf24' : '#d1d5db' ?> !important; display: inline-block;">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97h4.178c.969 0 1.371 1.24.588 1.81l-3.38 2.455 1.287 3.97c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.37 2.403c-.784.57-1.838-.197-1.539-1.118l1.287-3.97-3.38-2.455c-.783-.57-.38-1.81.588-1.81h4.178l1.285-3.97z"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <div class="text-[10px] text-gray-400 mt-1">(Rating: <?= $current_rating ?>)</div>
                        </td>
                        <td class="px-4 py-3 max-w-xs break-words italic text-gray-600 dark:text-gray-300 align-top">
                            "<?= htmlspecialchars($row['komentar']) ?>"
                        </td>
                        
                        <td class="px-4 py-3 text-center align-top">
                            <div style="display: flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important;">
                                
                                <?php if ((int)$row['rating'] < 4 && !empty($row['telp_user'])): ?>
                                    <?php 
                                        $clean_phone = preg_replace('/[^0-9]/', '', $row['telp_user']);
                                        if (str_starts_with($clean_phone, '0')) {
                                            $clean_phone = '62' . substr($clean_phone, 1);
                                        }
                                        $pesan_wa = "Halo " . $row['nama_user'] . ", kami dari Admin terkait review Anda tentang baju " . $row['nama_baju'] . ".";
                                        $wa_url = "https://api.whatsapp.com/send?phone=$clean_phone&text=" . urlencode($pesan_wa);
                                    ?>
                                    <a href="<?= $wa_url ?>" 
                                       target="_blank" 
                                       style="
                                        display: inline-flex !important;
                                        align-items: center !important;
                                        justify-content: center !important;
                                        gap: 5px !important;
                                        background-color: #22c55e !important; 
                                        color: white !important;
                                        padding: 5px 10px !important;
                                        border-radius: 6px !important;
                                        font-size: 11px !important;
                                        font-weight: bold !important;
                                        text-decoration: none !important;
                                        box-shadow: 0 1px 2px rgba(0,0,0,0.1) !important;
                                        border: none !important;
                                        min-width: 85px !important;
                                       ">
                                        <span style="color: white !important;">💬</span>
                                        <span style="color: white !important;">Hubungi</span>
                                    </a>
                                <?php endif; ?>

                                <form method="POST" onsubmit="return confirm('Hapus testimoni ini?')" style="display: inline-block !important; margin: 0 !important;">
                                    <input type="hidden" name="id_testimoni" value="<?= $row['id'] ?>">
                                    <button type="submit" name="hapus_testimoni" 
                                            style="
                                                background: none !important; 
                                                border: none !important; 
                                                color: #ef4444 !important; 
                                                cursor: pointer !important; 
                                                padding: 4px !important;
                                                display: flex !important;
                                            ">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400 italic">
                        Belum ada testimoni.
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