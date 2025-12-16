<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    exit("Akses ditolak");
}

// Sesuaikan path koneksi Anda
require_once __DIR__ . '/../../app/config/koneksi.php';

// Pastikan koneksi ada dan berhasil
if (!isset($koneksi) || $koneksi->connect_error) {
    exit("Koneksi Database Gagal: " . $koneksi->connect_error);
}

$action = $_GET['action'] ?? '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ============== Helper ==============
function getBaju($koneksi, $id) {
    // Menggunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM koleksi_baju WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getStok($koneksi, $id) {
    $stmt = $koneksi->prepare("SELECT * FROM koleksi_stok WHERE koleksi_id=? ORDER BY ukuran ASC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] relative flex flex-col overflow-hidden">
        
        <div class="bg-white z-10 px-6 py-4 border-b flex justify-between items-center flex-shrink-0">
            <h2 class="text-xl font-bold text-gray-800">
                <?= ($action == 'edit') ? 'Edit Koleksi' : (($action == 'hapus') ? 'Hapus Koleksi' : 'Tambah Koleksi'); ?>
            </h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">

<?php
// ===============================================================
// TAMBAH BAJU
// ===============================================================
if ($action === "tambah"):
?>
        <form method="POST" action="baju_proses.php?action=tambah" enctype="multipart/form-data" class="space-y-3">

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Baju</label>
                <input type="text" name="nama" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="2" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori</label>
                    <select name="kategori" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="TK">TK</option>
                        <option value="SD">SD</option>
                        <option value="SMA">SMA</option>
                        <option value="Dewasa">Dewasa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Stok per Ukuran</label>
                <div class="grid grid-cols-2 gap-3">
                    <?php foreach (['S','M','L','XL'] as $u): ?>
                    <div class="flex items-center">
                        <span class="w-10 bg-gray-200 text-gray-700 text-center py-2 text-sm font-bold rounded-l-lg border-y border-l border-gray-300">
                            <?= $u ?>
                        </span>
                        <input type="number" name="stok[]" placeholder="0" min="0" value="0"
                               class="w-full border border-gray-300 rounded-r-lg py-2 px-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                        <input type="hidden" name="ukuran[]" value="<?= $u ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Gambar</label>
                <input type="file" name="gambar" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="pt-4 mt-2 border-t flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:scale-105">
                    Simpan Data
                </button>
            </div>

        </form>
<?php
exit; endif;


// ===============================================================
// EDIT BAJU (Perbaikan: Menggunakan Slot Stok Default)
// ===============================================================
if ($action === "edit" && $id > 0):

    $data = getBaju($koneksi, $id);
    $stok_result = getStok($koneksi, $id);

    if (!$data) { exit("Data tidak ditemukan"); }
    
    // Siapkan array stok saat ini agar mudah diakses
    $current_stok = [];
    while($row = $stok_result->fetch_assoc()) {
        $current_stok[$row['ukuran']] = $row['stok'];
    }
    $all_ukuran = ['S', 'M', 'L', 'XL']; // Ukuran default yang ditampilkan
?>
        <form method="POST" action="baju_proses.php?action=edit&id=<?= $id ?>" enctype="multipart/form-data" class="space-y-3">

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Baju</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori</label>
                    <input type="text" name="kategori" value="<?= htmlspecialchars($data['kategori']) ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= $data['harga'] ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Stok per Ukuran</label>
                <div class="grid grid-cols-2 gap-3">
                    <?php 
                    // Looping melalui ukuran default (S, M, L, XL)
                    foreach ($all_ukuran as $u): 
                        $stok_val = $current_stok[$u] ?? 0;
                    ?>
                    <div class="flex items-center">
                        <span class="w-1/3 bg-gray-200 text-gray-700 text-center py-2 text-sm font-bold rounded-l-lg border-y border-l border-gray-300">
                            <?= $u ?>
                        </span>
                        <input type="number" name="stok[]" placeholder="0" min="0" value="<?= $stok_val ?>"
                            class="w-2/3 border border-gray-300 rounded-r-lg py-2 px-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                        <input type="hidden" name="ukuran[]" value="<?= $u ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Gambar (Kosongkan jika tidak ganti)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full text-xs">
            </div>

            <div class="pt-4 mt-2 border-t flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 shadow-md">Update</button>
            </div>

        </form>

<?php
exit; endif;


// ===============================================================
// HAPUS BAJU
// ===============================================================
if ($action === "hapus" && $id > 0):
?>
        <div class="text-center py-4">
            <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Hapus Koleksi?</h2>
            <p class="text-gray-600 mb-6">Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="flex justify-center gap-3">
                <button onclick="closeModal()" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
                <a href="baju_proses.php?action=hapus&id=<?= $id ?>" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold shadow-lg">Ya, Hapus</a>
            </div>
        </div>
<?php
exit; endif;
?>

        </div> </div> </div>

<script>
function addUkuran() {
    const list = document.getElementById("listUkuran");
    if (!list) return;

    const div = document.createElement("div");
    div.className = "flex";
    div.innerHTML = `
        <input type="text" name="ukuran[]" placeholder="Size" class="w-1/3 border border-gray-300 rounded-l-lg py-1 px-2 text-center text-sm">
        <input type="number" name="stok[]" placeholder="Stok" class="w-2/3 border border-gray-300 rounded-r-lg py-1 px-2 text-sm">
    `;
    list.appendChild(div);
}

function closeModal() {
    const modal = document.getElementById("ajaxModal"); 
    if(modal) {
        modal.classList.add("hidden");
        document.getElementById("modalContent").innerHTML = "";
    } else {
        window.history.back(); 
    }
}

// **INI ADALAH PERBAIKAN UTAMA UNTUK TOMBOL TAMBAH SLOT**
// Mendaftarkan event listener secara dinamis setelah konten dimuat
document.addEventListener('DOMContentLoaded', () => {
    const tambahBtn = document.getElementById('tambahSlotBtn');
    if (tambahBtn) {
        tambahBtn.addEventListener('click', addUkuran);
    }
});
// Jika DOMContentLoaded tidak bekerja dalam konteks AJAX Anda, listener ini akan tetap aktif
const tambahBtnOnLoad = document.getElementById('tambahSlotBtn');
if (tambahBtnOnLoad) {
    tambahBtnOnLoad.addEventListener('click', addUkuran);
}
</script>