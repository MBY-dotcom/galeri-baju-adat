<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_admin.php");
    exit;
}

require_once __DIR__ . '/../../app/config/koneksi.php';

// 1. Tangkap parameter bulan dari URL
$filter_bulan = $_GET['bulan'] ?? '';

// Inisialisasi variabel agar tidak error saat dipanggil di judul
$tahun = "";
$bulan = "";

if ($filter_bulan) {
    $time = strtotime($filter_bulan);
    $tahun = date('Y', $time);
    $bulan = date('m', $time);

    $sql = "SELECT p.*, u.nama AS nama_penyewa, u.no_telp AS kontak_penyewa, 
                   k.nama AS baju_nama, k.kategori AS baju_kategori, k.harga AS baju_harga
            FROM penyewaan p
            JOIN koleksi_baju k ON k.id = p.koleksi_id
            JOIN users u ON u.id = p.user_id
            WHERE MONTH(p.tanggal) = ? AND YEAR(p.tanggal) = ?
            ORDER BY p.tanggal ASC";
    
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $bulan, $tahun);
} else {
    $sql = "SELECT p.*, u.nama AS nama_penyewa, u.no_telp AS kontak_penyewa, 
                   k.nama AS baju_nama, k.kategori AS baju_kategori, k.harga AS baju_harga
            FROM penyewaan p
            JOIN koleksi_baju k ON k.id = p.koleksi_id
            JOIN users u ON u.id = p.user_id
            ORDER BY p.tanggal DESC";
    $stmt = $koneksi->prepare($sql);
}

$stmt->execute();
$pesanan = $stmt->get_result();

$nama_bulan_indo = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', 
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// Set Judul Periode
if ($filter_bulan && isset($nama_bulan_indo[$bulan])) {
    $judul_periode = $nama_bulan_indo[$bulan] . " " . $tahun;
} else {
    $judul_periode = "Seluruh Periode";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan - <?= $judul_periode ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; color: #333; }
        header { text-align: center; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #666; padding: 10px; font-size: 11px; }
        th { background-color: #f2f2f2; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .status-badge { font-size: 9px; padding: 2px 4px; border: 1px solid #ccc; text-transform: uppercase; }
        @media print { 
            .no-print { display: none; } 
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <header>
        <h2 style="margin:0;">LAPORAN PENDAPATAN PENYEWAAN</h2>
        <p style="margin:5px 0;">Periode: <?= $judul_periode ?></p>
    </header>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #059669; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">
            Cetak / Simpan PDF
        </button>
        <p style="font-size: 12px; color: #666;">*Hanya status <b>DISETUJUI</b> yang masuk ke total pendapatan.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Penyewa</th>
                <th>Baju</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_pendapatan = 0;
            if($pesanan && $pesanan->num_rows > 0):
                while ($row = $pesanan->fetch_assoc()): 
                    $harga = (int) $row['baju_harga'];
                    $jumlah = (int) $row['jumlah'];
                    
                    // Logika Filter Status
                    if ($row['status'] === 'disetujui') {
                        $subtotal = $harga * $jumlah;
                        $total_pendapatan += $subtotal;
                        $tampil_subtotal = "Rp " . number_format($subtotal, 0, ',', '.');
                    } else {
                        $tampil_subtotal = "-";
                    }
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                <td>
                    <b><?= htmlspecialchars($row['nama_penyewa']) ?></b><br>
                    <small><?= htmlspecialchars($row['kontak_penyewa']) ?></small>
                </td>
                <td><?= htmlspecialchars($row['baju_nama']) ?></td>
                <td class="text-right">Rp <?= number_format($harga, 0, ',', '.') ?></td>
                <td class="text-center"><?= $jumlah ?></td>
                <td class="text-right font-bold"><?= $tampil_subtotal ?></td>
                <td class="text-center">
                    <span class="status-badge"><?= $row['status'] ?></span>
                </td>
            </tr>
            <?php 
                endwhile; 
            ?>
            <tr class="font-bold" style="background-color: #f2f2f2; font-size: 13px;">
                <td colspan="6" class="text-right">TOTAL PENDAPATAN (DISETUJUI)</td>
                <td class="text-right" style="color: #059669;">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                <td></td>
            </tr>
            <?php
            else:
                echo "<tr><td colspan='8' class='text-center'>Tidak ada data ditemukan.</td></tr>";
            endif;
            ?>
        </tbody>
    </table>

    <footer style="margin-top: 40px; text-align: right; font-size: 12px;">
        <p>Dicetak pada: <?= date('d/m/Y H:i') ?></p>
        <div style="margin-top: 60px;">
            <p>( _______________________ )</p>
            <p style="margin-right: 40px;">Admin Operasional</p>
        </div>
    </footer>

</body>
</html>