<?php
session_start();
if (!isset($_SESSION['admin_login'])) { header("Location: login_admin.php"); exit; }

require_once __DIR__ . '/../../app/config/koneksi.php';

$total_baju = $koneksi->query("SELECT COUNT(*) AS jml FROM koleksi_baju")->fetch_assoc()['jml'];
$total_stok = $koneksi->query("SELECT SUM(stok) AS jml FROM koleksi_stok")->fetch_assoc()['jml'];

$title = "Dashboard Admin";

ob_start();
?>

<h1 class="text-3xl text-center font-bold">Dashboard Admin</h1>
<p class="text-gray-600 dark:text-gray-300 text-center">Statistik Galeri.</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow border border-gray-200 dark:border-gray-700">
        <h3 class="text-gray-600 dark:text-gray-300 mb-1">Total Koleksi</h3>
        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400"><?= $total_baju ?></p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow border border-gray-200 dark:border-gray-700">
        <h3 class="text-gray-600 dark:text-gray-300 mb-1">Total Stok</h3>
        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400"><?= $total_stok ?></p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow border border-gray-200 dark:border-gray-700">
        <h3 class="text-gray-600 dark:text-gray-300 mb-1">Status Admin</h3>
        <p class="text-xl font-semibold text-green-600 dark:text-green-400">Aktif</p>
    </div>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/layout/layout.php";
?>