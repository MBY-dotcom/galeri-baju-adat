<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header('Location: login.php');
    exit;
}

// Sesuaikan path koneksi Anda
require_once __DIR__ . '/../../app/config/koneksi.php';

if (!isset($koneksi) || $koneksi->connect_error) {
    die("Koneksi Database Gagal: " . $koneksi->connect_error);
}

$action = $_GET['action'] ?? '';

// =======================================================
// KONFIGURASI PATH GAMBAR
// =======================================================
$uploadFileDir = __DIR__ . '/../gambar/'; 

// =======================================================
// FUNGSI UTILITY
// =======================================================
function sanitize_input($koneksi, $data) {
    return $koneksi->real_escape_string($data);
}

function handle_upload($koneksi) {
    global $uploadFileDir; // Menggunakan path direktori global
    
    if (!is_dir($uploadFileDir)) {
        // Coba buat folder jika belum ada
        @mkdir($uploadFileDir, 0777, true);
        if (!is_dir($uploadFileDir)) {
             die("Folder upload tidak ditemukan atau tidak bisa dibuat: " . $uploadFileDir);
        }
    }
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['gambar']['tmp_name'];
        $fileName      = $_FILES['gambar']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $newFileName   = time() . '-' . uniqid() . '.' . $fileExtension;
        $destPath      = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            return $newFileName;
        } else {
            return false;
        }
    }
    return null;
}


// =======================================================
// PROSES TAMBAH BAJU
// =======================================================
if ($action === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nama        = sanitize_input($koneksi, $_POST['nama'] ?? '');
    $deskripsi   = sanitize_input($koneksi, $_POST['deskripsi'] ?? '');
    $kategori    = sanitize_input($koneksi, $_POST['kategori'] ?? '');
    $harga       = (int)($_POST['harga'] ?? 0);
    $ukuran_arr  = $_POST['ukuran'] ?? [];
    $stok_arr    = $_POST['stok'] ?? [];

    $gambar_nama = handle_upload($koneksi);
    
    if ($gambar_nama === false) { die("Gagal upload gambar."); }
    if ($gambar_nama === null) { die("Gambar wajib diupload."); }

    $sql_baju = "INSERT INTO koleksi_baju (nama, deskripsi, kategori, harga, gambar) 
                 VALUES ('$nama', '$deskripsi', '$kategori', $harga, '$gambar_nama')";
    
    if ($koneksi->query($sql_baju)) {
        
        $koleksi_id = $koneksi->insert_id; 
        
        if (is_array($ukuran_arr) && count($ukuran_arr) > 0) {
            $stmt = $koneksi->prepare("INSERT INTO koleksi_stok (koleksi_id, ukuran, stok) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $koleksi_id, $ukuran, $stok);

            for ($i = 0; $i < count($ukuran_arr); $i++) {
                $ukuran = sanitize_input($koneksi, $ukuran_arr[$i]);
                $stok   = (int)($stok_arr[$i] ?? 0);
                if ($stok > 0) {
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        header('Location: admin_list.php?status=sukses_tambah');
        exit;

    } else {
        die("Error menyimpan data baju: " . $koneksi->error);
    }
}


// =======================================================
// PROSES EDIT / UPDATE BAJU
// =======================================================
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { die("ID Baju tidak valid."); }

    $nama        = sanitize_input($koneksi, $_POST['nama'] ?? '');
    $deskripsi   = sanitize_input($koneksi, $_POST['deskripsi'] ?? '');
    $kategori    = sanitize_input($koneksi, $_POST['kategori'] ?? '');
    $harga       = (int)($_POST['harga'] ?? 0);
    $ukuran_arr  = $_POST['ukuran'] ?? [];
    $stok_arr    = $_POST['stok'] ?? [];

    $gambar_update = '';
    $newFileName = handle_upload($koneksi); 
    
    if ($newFileName === false) { die("Gagal upload gambar."); } 
    elseif ($newFileName !== null) {
        $gambar_update = ", gambar = '$newFileName'";
        
        // Opsional: Hapus gambar lama (perlu query untuk mendapatkan nama file lama)
        // (kode ini tidak disertakan untuk menjaga kompleksitas, tapi sangat disarankan di aplikasi nyata)
    }
    
    // Update data utama di koleksi_baju
    $sql_baju = "UPDATE koleksi_baju SET 
                 nama = '$nama', 
                 deskripsi = '$deskripsi', 
                 kategori = '$kategori', 
                 harga = $harga 
                 $gambar_update 
                 WHERE id = $id";
    
    if ($koneksi->query($sql_baju)) {
        
        // Hapus semua stok lama, lalu masukkan semua stok baru
        $koneksi->query("DELETE FROM koleksi_stok WHERE koleksi_id = $id");
        
        if (is_array($ukuran_arr) && count($ukuran_arr) > 0) {
            
            $stmt = $koneksi->prepare("INSERT INTO koleksi_stok (koleksi_id, ukuran, stok) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $id, $ukuran, $stok);

            for ($i = 0; $i < count($ukuran_arr); $i++) {
                $ukuran = sanitize_input($koneksi, $ukuran_arr[$i]);
                $stok   = (int)($stok_arr[$i] ?? 0);

                if ($stok > 0) {
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        header('Location: admin_list.php?status=sukses_edit');
        exit;

    } else {
        die("Error mengupdate data baju: " . $koneksi->error);
    }
}

// =======================================================
// PROSES HAPUS BAJU (DENGAN HAPUS FILE GAMBAR)
// =======================================================
if ($action === 'hapus' && isset($_GET['id'])) {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { die("ID Baju tidak valid."); }
    
    // 1. Ambil nama gambar sebelum data dihapus dari DB
    $gambar_nama = null;
    $stmt = $koneksi->prepare("SELECT gambar FROM koleksi_baju WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_baju = $result->fetch_assoc();
    if ($data_baju) {
        $gambar_nama = $data_baju['gambar'] ?? null;
    }
    $stmt->close();

    $koneksi->begin_transaction();
    
    try {
        // 2. Hapus data dari DB
        $koneksi->query("DELETE FROM koleksi_stok WHERE koleksi_id = $id");
        $koneksi->query("DELETE FROM koleksi_baju WHERE id = $id");
        
        $koneksi->commit();
        
        // 3. Hapus file gambar dari server setelah data DB berhasil dihapus
        if ($gambar_nama) {
            $filePath = $uploadFileDir . $gambar_nama;
            if (file_exists($filePath)) {
                // Gunakan @unlink untuk menekan peringatan jika file tidak dapat dihapus
                @unlink($filePath); 
            }
        }
        
        header('Location: admin_list.php?status=sukses_hapus');
        exit;
        
    } catch (mysqli_sql_exception $exception) {
        $koneksi->rollback();
        die("Error menghapus data: " . $exception->getMessage());
    }
}


// Default jika tidak ada action yang cocok
header('Location: admin_list.php');
exit;
?>