<?php
require_once 'auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

header('Content-Type: application/json; charset=utf-8');

// ====================================
// Ambil data JSON dari fetch
// ====================================
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode([
        'success'=>false,
        'message'=>'JSON tidak valid'
    ]);
    exit;
}

$id_baju = (int)($data['id_baju'] ?? 0);
$tanggal = $data['tanggal'] ?? '';
$sesi    = strtolower(trim($data['sesi'] ?? ''));
$ukuran  = $data['ukuran'] ?? '';
$jumlah  = (int)($data['jumlah'] ?? 1);

// ====================================
// Validasi input
// ====================================
if(!$id_baju || !$tanggal || !$sesi || !$ukuran || $jumlah < 1){
    echo json_encode([
        'success'=>false,
        'message'=>'Parameter tidak lengkap'
    ]);
    exit;
}

// ====================================
// Fungsi OVERLAP session (INI YANG PENTING)
// ====================================
function getOverlappingSessions($sesi){
    return match($sesi){
        'pagi'  => ['pagi'],              // pagi hanya bentrok pagi
        'siang' => ['pagi','siang'],       // siang bentrok pagi + siang
        'malam' => ['siang','malam'],      // malam bentrok siang + malam
        default => [$sesi]
    };
}

$sessions = getOverlappingSessions($sesi);

// ====================================
// Ambil stok
// ====================================
$stmt = $koneksi->prepare("SELECT stok FROM koleksi_stok WHERE koleksi_id = ? AND ukuran = ?");
$stmt->bind_param('is', $id_baju, $ukuran);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stok_total = $res ? (int)$res['stok'] : 0;

if($stok_total <= 0){
    echo json_encode([
        'success'=>true,
        'available'=>false,
        'available_count'=>0
    ]);
    exit;
}

// ====================================
// Hitung jumlah terpakai
// ====================================
$placeholders = implode(',', array_fill(0, count($sessions), '?'));
$types = 'iss' . str_repeat('s', count($sessions));
$params = array_merge([$id_baju, $ukuran, $tanggal], $sessions);

$sql = "
    SELECT COALESCE(SUM(jumlah),0) AS terpakai
    FROM penyewaan
    WHERE koleksi_id = ?
      AND ukuran = ?
      AND tanggal = ?
      AND sesi IN ($placeholders)
      AND status IN ('pending','disetujui')
";

$stmt = $koneksi->prepare($sql);

$bind = [];
$bind[] = $types;
foreach($params as $i=>$v){
    $bind[] = &$params[$i];
}
call_user_func_array([$stmt,'bind_param'],$bind);

$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$terpakai = (int)($row['terpakai'] ?? 0);

// ====================================
// Hasil akhir
// ====================================
$available_count = max(0, $stok_total - $terpakai);
$available = ($available_count >= $jumlah);

// ====================================
// Kirim response
// ====================================
echo json_encode([
    'success' => true,
    'available' => $available,
    'available_count' => $available_count
]);
exit;
