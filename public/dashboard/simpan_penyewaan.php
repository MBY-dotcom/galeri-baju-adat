<?php
require_once 'auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'] ?? 0;
$id_baju = (int)($data['id_baju'] ?? 0);
$tanggal = trim($data['tanggal'] ?? '');
$sesi    = trim(strtolower($data['sesi'] ?? ''));
$ukuran  = trim($data['ukuran'] ?? '');
$jumlah  = (int)($data['jumlah'] ?? 1);

// =======================
// Validasi dasar
// =======================
if(!$user_id || !$id_baju || !$tanggal || !$sesi || !$ukuran || $jumlah < 1){
    echo json_encode(['success'=>false,'message'=>'Parameter tidak lengkap']);
    exit;
}

// =======================
// Time buffer function 
// =======================
function getOverlappingSessions($sesi){
    $sesi = strtolower(trim($sesi));
    return match($sesi){
        'pagi'  => ['pagi'],              // pagi hanya bentrok pagi
        'siang' => ['pagi','siang'],       // siang bentrok pagi + siang
        'malam' => ['siang','malam'],      // malam bentrok siang + malam
        default => [$sesi]
    };
}
// Panggil fungsi y
$sessions = getOverlappingSessions($sesi); 

// =======================
// Mulai transaksi
// =======================
$koneksi->begin_transaction();

try{
    // =======================
    // Ambil stok & lock
    // =======================
    $stmt = $koneksi->prepare("
        SELECT stok 
        FROM koleksi_stok 
        WHERE koleksi_id = ? 
          AND ukuran = ?
        FOR UPDATE
    ");
    $stmt->bind_param("is", $id_baju, $ukuran);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if(!$r){
        throw new Exception('Stok tidak ditemukan');
    }

    $stok = (int)$r['stok'];

    // =======================
    // Hitung terpakai (pakai LIKE agar sinkron)
    // =======================
    $likeClauses = [];
    $likeParams  = [];

    // Menggunakan variabel $sessions 
    foreach($sessions as $s){
        $likeClauses[] = "LOWER(sesi) LIKE ?";
        $likeParams[]  = "%".$s."%";
    }

    $likeSql = implode(" OR ", $likeClauses);

    $sql = "
        SELECT COALESCE(SUM(jumlah),0) AS terpakai
        FROM penyewaan
        WHERE koleksi_id = ?
          AND ukuran = ?
          AND tanggal = ?
          AND ($likeSql)
          AND LOWER(status) IN ('pending','disetujui')
    ";

    $params = array_merge([$id_baju, $ukuran, $tanggal], $likeParams);
    $types  = 'iss' . str_repeat('s', count($likeParams));

    $stmt = $koneksi->prepare($sql);

    $bind = [];
    $bind[] = $types;
    foreach($params as $k => $v){
        $bind[] = &$params[$k];
    }
    call_user_func_array([$stmt,'bind_param'],$bind);

    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $terpakai = (int)($row['terpakai'] ?? 0);

    $sisa = $stok - $terpakai;
    if($sisa < $jumlah){
        throw new Exception("Stok tidak cukup. Sisa: $sisa");
    }

    // =======================
    // Simpan penyewaan
    // =======================
    $ins = $koneksi->prepare("
        INSERT INTO penyewaan 
        (user_id, koleksi_id, ukuran, jumlah, tanggal, sesi, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");
    $ins->bind_param(
        'iissss',
        $user_id,
        $id_baju,
        $ukuran,
        $jumlah,
        $tanggal,
        $sesi
    );
    $ins->execute();
    $ins->close();

    $koneksi->commit();

    echo json_encode([
        'success'=>true,
        'message'=>'Pesanan berhasil disimpan'
    ]);

}catch(Exception $e){
    $koneksi->rollback();
    echo json_encode([
        'success'=>false,
        'message'=>$e->getMessage()
    ]);
}