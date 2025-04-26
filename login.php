<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "db_bajuadat");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $result = $koneksi->query("SELECT * FROM admin WHERE username = '$username' AND password = '$password'");

  if ($result->num_rows > 0) {
    $_SESSION['admin_login'] = true;
    header("Location: admin_list.php");
    exit;
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
      <p class="text-red-600 text-sm mb-4"><?= $error; ?></p>
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
