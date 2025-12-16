<?php
session_start();
require_once __DIR__ . '/../../app/config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // ✅ Prepared statement (AMAN)
    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Username atau password salah!";
    } else {
        $admin = $result->fetch_assoc();

        // ✅ KUNCI UTAMA LOGIN
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_login'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
  <h2 class="text-2xl font-bold mb-6 text-indigo-700 text-center">Login Admin</h2>

  <?php if (isset($error)): ?>
    <p class="text-red-600 text-sm mb-4"><?= $error ?></p>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-4">
      <label class="block mb-1 text-gray-600">Username</label>
      <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
    </div>

    <div class="mb-6">
      <label class="block mb-1 text-gray-600">Password</label>
      <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
    </div>

    <button class="w-full bg-indigo-700 text-white py-2 rounded hover:bg-indigo-600">
      Login
    </button>
  </form>
</div>

</body>
</html>
