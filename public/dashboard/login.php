<?php
session_start();
require_once __DIR__ . '/../../app/config/koneksi.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if ($password === '') $errors[] = 'Password wajib diisi.';

    if (empty($errors)) {
        $stmt = $koneksi->prepare("SELECT id, nama, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // berhasil
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['nama'];
                header("Location: ../dashboard/index.php"); // ganti sesuai path dashboard
                exit;
            } else {
                $errors[] = 'Email atau password salah.';
            }
        } else {
            $errors[] = 'Email atau password salah.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login - Galeri</title>
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
<body class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
  <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold mb-4 text-center">Masuk</h1>

    <?php if (!empty($errors)): ?>
      <div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">
        <?php foreach ($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm mb-1">Email</label>
        <input name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
               type="email" class="w-full border rounded px-3 py-2 dark:bg-gray-900" required>
      </div>

      <div>
        <label class="block text-sm mb-1">Password</label>
        <input name="password" type="password" class="w-full border rounded px-3 py-2 dark:bg-gray-900" required>
      </div>

      <button class="w-full bg-blue-600 text-white py-2 rounded">Masuk</button>

      <p class="text-center text-sm text-gray-600 mt-3">
        Belum punya akun? <a href="register.php" class="text-blue-600">Daftar</a>
      </p>
    </form>
  </div>
</body>
</html>
