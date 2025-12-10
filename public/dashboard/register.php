<?php

require_once __DIR__ . '/../../app/config/koneksi.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $no_telp  = trim($_POST['no_telp'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // VALIDASI
    if ($nama === '') $errors[] = "Nama wajib diisi.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";

    if (!preg_match('/^[0-9+\-\s]{8,20}$/', $no_telp)) {
        $errors[] = "Nomor telepon tidak valid.";
    }

    if ($alamat === '') $errors[] = "Alamat wajib diisi.";
    if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter.";
    if ($password !== $confirm) $errors[] = "Konfirmasi password tidak cocok.";

    // Cek email
    $cek = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        $errors[] = "Email sudah terdaftar.";
    }

    // INSERT
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $ins = $koneksi->prepare("
            INSERT INTO users (nama, email, no_telp, alamat, password)
            VALUES (?, ?, ?, ?, ?)
        ");
        $ins->bind_param("sssss", $nama, $email, $no_telp, $alamat, $hash);

        if ($ins->execute()) {
            $success = true;
        } else {
            $errors[] = "Terjadi kesalahan sistem.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- head: letakkan ini DIATAS link stylesheet -->
<script>
  // inisialisasi darkmode sesederhana & se-early mungkin
  (function () {
    try {
      // gunakan 1 standar value: 'on' / 'off'
      var m = localStorage.getItem('darkMode');
      if (m === 'on') {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    } catch (e) {
      console.error('dark init error', e);
    }
  })();
</script>
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">

    <h1 class="text-2xl font-bold text-center mb-4">Daftar Akun</h1>

    <?php if ($errors): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <div>
            <label class="text-sm">Nama Lengkap</label>
            <input name="nama" required
                value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                class="w-full border px-3 py-2 rounded dark:bg-gray-900">
        </div>

        <div>
            <label class="text-sm">Email</label>
            <input type="email" name="email" required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                class="w-full border px-3 py-2 rounded dark:bg-gray-900">
        </div>

        <div>
            <label class="text-sm">Nomor Telepon</label>
            <input name="no_telp" required
                value="<?= htmlspecialchars($_POST['no_telp'] ?? '') ?>"
                class="w-full border px-3 py-2 rounded dark:bg-gray-900">
        </div>

        <div>
            <label class="text-sm">Alamat</label>
            <textarea name="alamat" rows="3" required
                class="w-full border px-3 py-2 rounded dark:bg-gray-900"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
        </div>

        <div>
            <label class="text-sm">Password</label>
            <input type="password" name="password" required
                class="w-full border px-3 py-2 rounded dark:bg-gray-900">
        </div>

        <div>
            <label class="text-sm">Konfirmasi Password</label>
            <input type="password" name="confirm_password" required
                class="w-full border px-3 py-2 rounded dark:bg-gray-900">
        </div>

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition">
            Daftar
        </button>

        <p class="text-center text-sm">
            Sudah punya akun?
            <a href="login.php" class="text-blue-600 dark:text-blue-400 font-semibold">Login</a>
        </p>

    </form>

</div>

<?php if ($success): ?>
<!-- MODAL SUCCESS -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-sm text-center
                shadow-xl transform transition duration-300 scale-100">

        <div class="text-4xl mb-3">✅</div>

        <h2 class="text-xl font-bold mb-2">Registrasi Berhasil</h2>

        <p class="text-gray-600 dark:text-gray-300 text-sm mb-5">
            Akun Anda berhasil dibuat.<br>
            Silakan login untuk melanjutkan.
        </p>

        <a href="login.php"
           class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            Login Sekarang
        </a>
    </div>
</div>
<?php endif; ?>

</body>
</html>
