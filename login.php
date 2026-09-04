<?php
session_start();
require_once 'app/config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  // Prepared statement to fetch admin user
  $stmt = $koneksi->prepare('SELECT id, password FROM admin WHERE username = ?');
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res && $row = $res->fetch_assoc()) {
    $stored = $row['password'];

    // Support both password_hash() and legacy MD5. If MD5 matches, rehash to password_hash.
    if (password_verify($password, $stored)) {
      // ok
    } elseif (md5($password) === $stored) {
      // rehash with password_hash
      $newHash = password_hash($password, PASSWORD_DEFAULT);
      $up = $koneksi->prepare('UPDATE admin SET password = ? WHERE id = ?');
      $up->bind_param('si', $newHash, $row['id']);
      $up->execute();
    } else {
      $error = "Username atau password salah!";
    }

    if (empty($error)) {
      // login success
      session_regenerate_id(true);
      $_SESSION['admin_login'] = true;
      header("Location: admin_list.php");
      exit;
    }
  } else {
    $error = "Username atau password salah!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    <h2 class="text-2xl font-bold mb-6 text-indigo-700 text-center">Login Admin</h2>
    
    <?php if (isset($error)) : ?>
      <p class="text-red-600 text-sm mb-4"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post">
      <div class="mb-4">
        <label class="block mb-1 text-gray-600">Username</label>
        <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
      </div>
      <div class="mb-6">
        <label class="block mb-1 text-gray-600">Password</label>
        <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
      </div>
      <button type="submit" class="w-full bg-indigo-700 text-white py-2 rounded hover:bg-indigo-600">Login</button>
    </form>
  </div>

</body>
</html>
