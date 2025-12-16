<?php
require_once __DIR__ . '/../../app/config/koneksi.php';

$action = $_GET['action'] ?? "";

// Ambil data user (EDIT)
if ($action === "get") {
    $id = intval($_GET['id']);
    $user = $koneksi->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
    echo json_encode($user);
    exit;
}

// Hapus user
if ($action === "delete") {
    $id = intval($_GET['id']);
    $koneksi->query("DELETE FROM users WHERE id = $id");
    echo "User berhasil dihapus";
    exit;
}

// Tambah / Edit
$id = $_POST['id'] ?? "";

// Sanitasi
$nama    = $koneksi->real_escape_string($_POST['nama']);
$email   = $koneksi->real_escape_string($_POST['email']);
$no_telp = $koneksi->real_escape_string($_POST['no_telp']);
$alamat  = $koneksi->real_escape_string($_POST['alamat']);
$password = $_POST['password'] ?? "";

// ===== EDIT =====
if ($id !== "") {
    $id = intval($id);

    if ($password !== "") {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $koneksi->query("
            UPDATE users SET
                nama='$nama',
                email='$email',
                no_telp='$no_telp',
                alamat='$alamat',
                password='$hash'
            WHERE id=$id
        ");
    } else {
        $koneksi->query("
            UPDATE users SET
                nama='$nama',
                email='$email',
                no_telp='$no_telp',
                alamat='$alamat'
            WHERE id=$id
        ");
    }

    echo "User berhasil diperbarui";
    exit;
}

// ===== TAMBAH =====
if ($password === "") {
    echo "Password wajib saat tambah user";
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$koneksi->query("
    INSERT INTO users (nama, email, no_telp, alamat, password)
    VALUES ('$nama', '$email', '$no_telp', '$alamat', '$hash')
");

echo "User berhasil ditambahkan";
?>