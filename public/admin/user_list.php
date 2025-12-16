<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_admin.php");
    exit;
}

require_once __DIR__ . '/../../app/config/koneksi.php';

// --- Sesuaikan nama tabel di sini jika berbeda (misal: 'users') ---
$tableName = 'users'; // <-- GANTI jika tabel Anda bernama lain, mis. 'user'

// Search
$keyword = isset($_GET['search']) ? trim($_GET['search']) : "";

// Bangun query dasar (aman menggunakan real_escape_string)
$safeKeyword = $koneksi->real_escape_string($keyword);
$sql = "SELECT * FROM `{$tableName}`";

if ($safeKeyword !== "") {
    $sql .= " WHERE nama LIKE '%{$safeKeyword}%' 
               OR email LIKE '%{$safeKeyword}%'
               OR no_telp LIKE '%{$safeKeyword}%'
               OR alamat LIKE '%{$safeKeyword}%'";
}

$sql .= " ORDER BY id DESC";

// Jalankan query dengan pengecekan error
$users = $koneksi->query($sql);
if ($users === false) {
    // Jika query gagal (mis. tabel tidak ada), tampilkan pesan yang ramah
    $dbErr = htmlspecialchars($koneksi->error);
    echo "<div style='padding:20px;background:#fee;border:1px solid #f99;color:#900'>
            Terjadi kesalahan saat mengambil data user.<br>
            Pesan DB: <strong>{$dbErr}</strong><br>
            Pastikan nama tabel di file ini benar: <code>\$tableName</code> saat ini <code>{$tableName}</code>.
          </div>";
    exit;
}

$title = "Data User";
ob_start();
?>

<div class="mt-2">
    <h1 class="text-3xl font-bold text-center">Data User</h1>
    <p class="text-gray-600 dark:text-gray-300 text-center">Kelola data user penyewa.</p>

    <div class="overflow-x-auto mt-4 bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">

        <!-- Search + Button -->
        <div class="flex justify-between items-center mb-6">

            <!-- Search -->
            <form method="GET" class="flex flex-1 mr-8">
                <input type="text" name="search"
                    value="<?= htmlspecialchars($keyword) ?>"
                    placeholder="Cari nama / email / nomor..."
                    class="flex-1 px-4 py-2 rounded-l-lg border dark:bg-gray-800 dark:border-gray-700 focus:outline-none" />
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700">Cari</button>
            </form>

            <!-- Button Tambah User -->
            <button onclick="openModal('add')"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Tambah User
            </button>
        </div>

        <!-- TABLE -->
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">No Telp</th>
                    <th class="px-4 py-2 text-left">Alamat</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $users->fetch_assoc()): ?>
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2"><?= $no++ ?></td>
                    <td class="px-4 py-2 font-medium"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['no_telp'] ?? '') ?></td>
                    <td class="px-4 py-2 max-w-lg"><?= htmlspecialchars($row['alamat'] ?? '') ?></td>
                    <td class="px-4 py-2 space-x-2 whitespace-nowrap">
                        <button onclick='openModal("edit", <?= json_encode($row) ?>)'
                                class="text-blue-600 hover:underline">Edit</button>

                        <a href="user_ajax.php?action=delete&id=<?= $row['id'] ?>"
                           onclick="return confirm('Yakin hapus user ini?')"
                           class="text-red-600 hover:underline">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</div>

<!-- MODAL (CENTER + BLUR) -->
<div id="modalWrapper"
     class="hidden fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
        <h2 id="modalTitle" class="text-xl font-semibold mb-4">Tambah User</h2>

        <form id="formUser" method="POST" class="space-y-3">

            <input type="hidden" name="id" id="userId">

            <div>
                <label class="block mb-1">Nama</label>
                <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border rounded dark:bg-gray-700" />
            </div>

            <div>
                <label class="block mb-1">Email</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded dark:bg-gray-700" />
            </div>

            <div>
                <label class="block mb-1">No Telp</label>
                <input type="text" name="no_telp" id="no_telp" class="w-full px-3 py-2 border rounded dark:bg-gray-700" />
            </div>

            <div>
                <label class="block mb-1">Alamat</label>
                <textarea name="alamat" id="alamat" class="w-full px-3 py-2 border rounded dark:bg-gray-700"></textarea>
            </div>

            <div class="mb-3">
                <label class="block mb-1">Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Simpan</button>
            </div>

        </form>
    </div>
</div>

<script>
// Open modal
function openModal(mode, data = null) {
    document.getElementById("modalWrapper").classList.remove("hidden");

    if (mode === "add") {
        document.getElementById("modalTitle").innerText = "Tambah User";
        document.getElementById("formUser").reset();
        document.getElementById("userId").value = "";
        document.getElementById("formUser").action = "user_ajax.php?action=tambah";
    }

    if (mode === "edit") {
        document.getElementById("modalTitle").innerText = "Edit User";
        document.getElementById("formUser").action = "user_ajax.php?action=edit";

        document.getElementById("userId").value = data.id;
        document.getElementById("nama").value = data.nama;
        document.getElementById("email").value = data.email;
        document.getElementById("no_telp").value = data.no_telp ?? '';
        document.getElementById("alamat").value = data.alamat ?? '';
    }
}

// Close modal
function closeModal() {
    document.getElementById("modalWrapper").classList.add("hidden");
}

// Optional: submit form via AJAX to user_ajax.php?action=tambah or edit
document.getElementById('formUser').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const actionUrl = form.getAttribute('action') || 'user_ajax.php?action=tambah';

    const fd = new FormData(form);

    fetch(actionUrl, {
        method: 'POST',
        body: fd
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        location.reload();
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mengirim data ke server');
    });
};
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/layout/layout.php";
