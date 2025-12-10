<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

$user_id  = $_SESSION['user_id'];
$editMode = isset($_GET['edit']);

$errors  = [];
$success = false;

// Ambil data user
$stmt = $koneksi->prepare("
    SELECT nama, email, no_telp, alamat 
    FROM users 
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']);
    $no_telp  = trim($_POST['no_telp']);
    $alamat   = trim($_POST['alamat']);
    $password = $_POST['password'];

    if ($nama === '')   $errors[] = "Nama wajib diisi.";
    if ($alamat === '') $errors[] = "Alamat wajib diisi.";

    if (empty($errors)) {
        if ($password !== '') {
            if (strlen($password) < 6) {
                $errors[] = "Password minimal 6 karakter.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $up = $koneksi->prepare("
                    UPDATE users 
                    SET nama=?, no_telp=?, alamat=?, password=? 
                    WHERE id=?
                ");
                $up->bind_param("ssssi", $nama, $no_telp, $alamat, $hash, $user_id);
            }
        } else {
            $up = $koneksi->prepare("
                UPDATE users 
                SET nama=?, no_telp=?, alamat=? 
                WHERE id=?
            ");
            $up->bind_param("sssi", $nama, $no_telp, $alamat, $user_id);
        }

        if (empty($errors) && $up->execute()) {
            $success  = true;
            $editMode = false;

            // refresh data tampilan
            $user['nama']    = $nama;
            $user['no_telp'] = $no_telp;
            $user['alamat']  = $alamat;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">

    <script>
        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem(
                'darkMode',
                document.documentElement.classList.contains('dark') ? 'on' : 'off'
            );
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (localStorage.getItem('darkMode') === 'on') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <?php include "_sidebar.php"; ?>

    <!-- MAIN -->
    <main class="flex-1 pt-2 px-6 pb-6 md:pt-2 md:px-8 md:pb-8">

        <!-- TOPBAR -->
        <?php include "_topbar.php"; ?>

        <!-- CONTENT -->
        <div class=" mx-auto mt-4 space-y-4">

            <!-- HEADER -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Pengaturan Profil</h1>

                <?php if (!$editMode): ?>
                    <a href="?edit=1"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Edit Profil
                    </a>
                <?php endif; ?>
            </div>

            <!-- ALERT -->
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded-lg">
                    Profil berhasil diperbarui ✅
                </div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg">
                    <ul class="list-disc ml-5">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- VIEW MODE -->
            <?php if (!$editMode): ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 space-y-4">

                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-semibold"><?= htmlspecialchars($user['nama']) ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold"><?= htmlspecialchars($user['email']) ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nomor Telepon</p>
                        <p class="font-semibold"><?= htmlspecialchars($user['no_telp']) ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Alamat</p>
                        <p class="font-semibold"><?= nl2br(htmlspecialchars($user['alamat'])) ?></p>
                    </div>

                </div>
            <?php endif; ?>

            <!-- EDIT MODE -->
            <?php if ($editMode): ?>
                <form method="POST"
                      class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 space-y-4">

                    <div>
                        <label class="text-sm">Nama</label>
                        <input name="nama"
                               value="<?= htmlspecialchars($user['nama']) ?>"
                               class="w-full border px-3 py-2 rounded-lg dark:bg-gray-900">
                    </div>

                    <div>
                        <label class="text-sm">Email</label>
                        <input disabled
                               value="<?= htmlspecialchars($user['email']) ?>"
                               class="w-full border px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="text-sm">Nomor Telepon</label>
                        <input name="no_telp"
                               value="<?= htmlspecialchars($user['no_telp']) ?>"
                               class="w-full border px-3 py-2 rounded-lg dark:bg-gray-900">
                    </div>

                    <div>
                        <label class="text-sm">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="w-full border px-3 py-2 rounded-lg dark:bg-gray-900"><?= htmlspecialchars($user['alamat']) ?></textarea>
                    </div>

                    <div>
                        <label class="text-sm">
                            Password Baru
                            <span class="text-xs text-gray-500">(opsional)</span>
                        </label>
                        <input type="password" name="password"
                               class="w-full border px-3 py-2 rounded-lg dark:bg-gray-900">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                        <a href="profil.php"
                           class="px-5 py-2 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Batal
                        </a>
                    </div>

                </form>
            <?php endif; ?>

        </div>
    </main>
</div>

</body>
</html>
