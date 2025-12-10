<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

// --- Pagination setup ---
$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$keyword = $_GET['cari'] ?? '';

// ------------------
// QUERY DATA
// ------------------
if ($keyword) {
    $stmt = $koneksi->prepare("
        SELECT * FROM koleksi_baju 
        WHERE nama LIKE ? OR kategori LIKE ?
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");
    $param = "%$keyword%";
    $stmt->bind_param("ssii", $param, $param, $limit, $offset);
    $stmt->execute();
    $baju = $stmt->get_result();

    $countStmt = $koneksi->prepare("
        SELECT COUNT(*) AS total FROM koleksi_baju
        WHERE nama LIKE ? OR kategori LIKE ?
    ");
    $countStmt->bind_param("ss", $param, $param);
    $countStmt->execute();
    $total_data = $countStmt->get_result()->fetch_assoc()['total'];
} else {
    $baju = $koneksi->query("
        SELECT * FROM koleksi_baju 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset
    ");
    $total_data = $koneksi
        ->query("SELECT COUNT(*) AS total FROM koleksi_baju")
        ->fetch_assoc()['total'];
}

$total_page = max(1, (int)ceil($total_data / $limit));
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Koleksi Baju</title>
  <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
  <div class="flex min-h-screen">
    <?php include "_sidebar.php"; ?>
    <main class="flex-1 pt-2 px-6 pb-6 md:pt-2 md:px-8 md:pb-8">
      <?php include "_topbar.php"; ?>

      <!-- Header -->
      <div class="mt-2 space-y-2 text-center">
        <h1 class="text-3xl font-bold">Koleksi Baju</h1>
        <p class="text-gray-600 dark:text-gray-300">Pilih baju favorit Anda untuk disewa.</p>
      </div>

      <!-- Search -->
      <section class="mx-auto px-4 py-4 w-full">
        <div class="mb-6 flex justify-center">
          <form method="GET" class="w-full max-w-md flex">
            <input type="text" name="cari" value="<?php echo htmlspecialchars($keyword); ?>"
                   placeholder="Cari nama baju..."
                   class="flex-1 px-4 py-2 rounded-l-lg border dark:bg-gray-800 dark:border-gray-700 focus:outline-none" />
            <button class="px-4 py-2 bg-blue-600 text-white rounded-r-lg">Cari</button>
          </form>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <?php while ($row = $baju->fetch_assoc()) : 
            $id = (int)$row['id'];
            $nama = htmlspecialchars($row['nama']);
            $gambar = htmlspecialchars($row['gambar']);
            $kategori = htmlspecialchars($row['kategori']);
            $ukuran = htmlspecialchars($row['ukuran']);
            $harga = number_format($row['harga'], 0, ',', '.');
            $deskripsi_short = htmlspecialchars(substr($row['deskripsi'], 0, 80));
          ?>
            <div onclick="openModal(<?php echo $id; ?>)"
                 class="bg-white dark:bg-gray-800 rounded-xl shadow-md border overflow-hidden flex flex-col cursor-pointer hover:scale-[1.02] transition-transform">
              
              <!-- Image (object-cover - crop rapi) -->
              <div class="w-full h-56 bg-gray-100 dark:bg-gray-700">
                <img src="../gambar/<?php echo $gambar; ?>"
                     alt="<?php echo $nama; ?>"
                     class="object-cover w-full h-full" />
              </div>

              <!-- Info -->
              <div class="p-4 flex flex-col justify-between h-full">
                <div>
                  <h4 class="font-bold text-lg uppercase mb-1"><?php echo $nama; ?></h4>
                  <p class="text-sm text-gray-600 dark:text-gray-300 mb-1"><?php echo $kategori; ?> - <?php echo $ukuran; ?></p>
                  <p class="text-sm text-gray-700 dark:text-gray-400"><?php echo $deskripsi_short; ?>...</p>
                </div>

                <p class="mt-3 font-bold text-blue-700 dark:text-blue-300 text-lg">Rp <?php echo $harga; ?></p>
              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8 gap-2">
          <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&cari=<?php echo urlencode($keyword); ?>"
               class="px-3 py-1 bg-gray-300 dark:bg-gray-700 rounded-lg">Prev</a>
          <?php endif; ?>

          <?php
            // show max 7 page links with current centered when possible
            $range = 3;
            $start = max(1, $page - $range);
            $end = min($total_page, $page + $range);
            if ($start > 1) {
              echo '<span class="px-3 py-1 text-gray-500">...</span>';
            }
            for ($i = $start; $i <= $end; $i++): 
          ?>
            <a href="?page=<?php echo $i; ?>&cari=<?php echo urlencode($keyword); ?>"
               class="px-3 py-1 rounded-lg <?php echo ($i == $page) ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor;
            if ($end < $total_page) {
              echo '<span class="px-3 py-1 text-gray-500">...</span>';
            }
          ?>

          <?php if ($page < $total_page): ?>
            <a href="?page=<?php echo $page + 1; ?>&cari=<?php echo urlencode($keyword); ?>"
               class="px-3 py-1 bg-gray-300 dark:bg-gray-700 rounded-lg">Next</a>
          <?php endif; ?>
        </div>
      </section>

    </main>
  </div>

  <!-- MODAL (single modal with 2 steps) -->
  <div id="modalDetail" class="fixed inset-0 hidden flex items-center justify-center z-50 bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
    <div id="modalBox" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full scale-90 opacity-0 transition-all duration-300 relative overflow-hidden">
      
      <!-- Close -->
      <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-2xl z-40">&times;</button>

      <!-- CONTENT WRAPPER -->
      <div id="modalInner" class="p-6">

        <!-- STEP 1: DETAIL (AJAX will fill) -->
            <div id="modal-step-1" class="transition-all duration-300">
            <div id="detailArea">Memuat...</div>
            <div class="mt-6 flex justify-end gap-3">
                <button id="btnClose1" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Tutup</button>
                <button id="btnToStep2" class="px-4 py-2 rounded bg-blue-600 text-white">Cek Ketersediaan</button>
            </div>
            </div>

        <!-- STEP 2: FORM CEK KETERSEDIAAN -->
            <div id="modal-step-2" class="hidden transition-all duration-300">
            <h3 class="text-xl font-bold mb-3">Cek Ketersediaan</h3>

            <form id="cekForm" class="space-y-4">
                <input type="hidden" name="id_baju" id="id_baju" value="0">

                <div>
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input id="tanggal" name="tanggal" type="date"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                <label class="block text-sm font-medium mb-1">Sesi</label>
                <select id="sesi" name="sesi"
                        class="w-full border rounded px-3 py-2" required>
                    <option value="Pagi">Pagi</option>
                    <option value="Siang">Siang</option>
                    <option value="Malam">Malam</option>
                </select>
                </div>

                <!-- ✅ FIX: UKURAN -->
                <div>
                <label class="block text-sm font-medium mb-1">Ukuran</label>
                <select id="ukuran" name="ukuran"
                        class="w-full border rounded px-3 py-2" required>
                    <option value="">Pilih Ukuran</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
                </div>

                <div>
                <label class="block text-sm font-medium mb-1">Jumlah</label>
                <input id="jumlah" name="jumlah" type="number" min="1" value="1"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div id="cekResult" class="text-sm hidden"></div>

                <div class="flex justify-between mt-4">
                <button type="button" id="btnBack"
                        class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">
                    Kembali
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded bg-green-600 text-white">
                    Cek
                </button>
                </div>
                
            </form>
            </div>
            
        <!-- Modal Hasil Ketersediaan -->
            <div id="modalHasil"
                class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

            <div class="bg-white rounded-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">Hasil Ketersediaan</h2>

                <p id="hasilText" class="mb-4 text-gray-700"></p>

                <div class="flex justify-end gap-2">
                <button onclick="tutupModalHasil()"
                        class="px-4 py-2 border rounded">
                    Tutup
                </button>

                <button id="btnPesan"
                        onclick="pesanSekarang()"
                        class="px-4 py-2 bg-green-600 text-white rounded hidden">
                    Pesan Sekarang
                </button>
                </div>
            </div>
            </div>

      </div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function() {

  const modal     = document.getElementById('modalDetail');
  const box       = document.getElementById('modalBox');
  const step1     = document.getElementById('modal-step-1');
  const step2     = document.getElementById('modal-step-2');
  const cekResult = document.getElementById('cekResult');

  let sudahCek = false;

  // ======================================
  // Buka modal detail
  // ======================================
  window.openModal = function(id) {
    step1.classList.remove('hidden');
    step2.classList.add('hidden');
    cekResult.innerHTML = '';
    document.getElementById('cekForm').reset();
    sudahCek = false;

    modal.classList.remove('hidden');
    setTimeout(()=> {
      modal.classList.add('opacity-100');
      box.classList.remove('scale-90','opacity-0');
      box.classList.add('scale-100','opacity-100');
    }, 10);

    document.getElementById('id_baju').value = id;

    document.getElementById('detailArea').innerHTML = '<p class="text-center">Memuat...</p>';
    fetch('detail_ajax.php?id=' + encodeURIComponent(id))
      .then(res => res.text())
      .then(html => document.getElementById('detailArea').innerHTML = html)
      .catch(() => {
        document.getElementById('detailArea').innerHTML =
          '<p class="text-red-500 text-center">Gagal memuat detail.</p>';
      });
  }

  // ======================================
  // Tutup modal
  // ======================================
  window.closeModal = function() {
    box.classList.remove('scale-100','opacity-100');
    box.classList.add('scale-90','opacity-0');
    modal.classList.remove('opacity-100');
    setTimeout(()=> modal.classList.add('hidden'), 250);
  }

  modal.addEventListener('click', e=>{
    if(e.target === modal) closeModal();
  });

  document.getElementById('btnToStep2').addEventListener('click', ()=> {
    step1.classList.add('hidden');
    step2.classList.remove('hidden');
  });

  document.getElementById('btnBack').addEventListener('click', ()=> {
    step2.classList.add('hidden');
    step1.classList.remove('hidden');
  });

  // ======================================
  // CEK KETERSEDIAAN
  // ======================================
  document.getElementById('cekForm').addEventListener('submit', function(e){
    e.preventDefault();
    sudahCek = false;

    const id_baju = document.getElementById('id_baju').value;
    const tanggal = document.getElementById('tanggal').value;
    const sesi    = document.getElementById('sesi').value.toLowerCase();
    const ukuran  = document.getElementById('ukuran').value;
    const jumlah  = document.getElementById('jumlah').value;

    if(!tanggal){
      cekResult.innerHTML = '<span class="text-red-500">Pilih tanggal terlebih dahulu.</span>';
      return;
    }
    if(!ukuran){
      cekResult.innerHTML = '<span class="text-red-500">Pilih ukuran baju.</span>';
      return;
    }

    cekResult.innerHTML = 'Memeriksa ketersediaan...';

    fetch('fetch_ketersediaan.php',{
      method: 'POST',
      headers:{ 'Content-Type':'application/json; charset=utf-8' },
      body: JSON.stringify({
        id_baju, tanggal, sesi, ukuran, jumlah
      })
    })
    .then(res => res.json())
    .then(data => {

      const modalHasil = document.getElementById('modalHasil');
      const hasilText  = document.getElementById('hasilText');
      const btnPesan   = document.getElementById('btnPesan');

      modalHasil.classList.remove('hidden');
      modalHasil.classList.add('flex');

      if(!data.success){
        hasilText.innerHTML = `<b class="text-red-600">${data.message}</b>`;
        btnPesan.classList.add('hidden');
        return;
      }

      if(data.available){
        hasilText.innerHTML = `<b>Tersedia ${data.available_count} unit</b>`;
        btnPesan.classList.remove('hidden','bg-gray-400');
        btnPesan.classList.add('bg-green-600');
        btnPesan.disabled = false;
        sudahCek = true;
      }else{
        hasilText.innerHTML =
          `<b class="text-red-600">Tidak tersedia (stok tersisa ${data.available_count})</b>`;
        btnPesan.classList.remove('hidden','bg-green-600');
        btnPesan.classList.add('bg-gray-400');
        btnPesan.disabled = true;
        sudahCek = false;
      }
    })
    .catch(() => {
      cekResult.innerHTML = '<span class="text-red-500">Terjadi kesalahan saat memeriksa stok.</span>';
    });
  });

  // ======================================
  // SIMPAN PESANAN
  // ======================================
  document.getElementById('btnPesan').addEventListener('click', function(){

    if(!sudahCek){
      alert('Silakan cek ketersediaan terlebih dahulu.');
      return;
    }

    const id_baju = document.getElementById('id_baju').value;
    const tanggal = document.getElementById('tanggal').value;
    const sesi    = document.getElementById('sesi').value.toLowerCase();
    const ukuran  = document.getElementById('ukuran').value;
    const jumlah  = document.getElementById('jumlah').value;

    fetch('simpan_penyewaan.php',{
      method:'POST',
      headers:{ 'Content-Type':'application/json; charset=utf-8' },
      body: JSON.stringify({
        id_baju, tanggal, sesi, ukuran, jumlah
      })
    })
    .then(res => res.json())
    .then(res => {
      if(res.success){
        alert('Pesanan berhasil disimpan ✅');
        location.reload();
      }else{
        alert(res.message || 'Terjadi kesalahan saat menyimpan');
      }
    })
    .catch(()=>{
      alert('Gagal mengirim data ke server');
    });
  });

  window.tutupModalHasil = function(){
    const modal = document.getElementById('modalHasil');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

});
</script>


</body>


</body>
</html>
