<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

$id = $_GET['id'] ?? null;
$item = ['nama_barang' => '', 'kode_barang' => '', 'stok_total' => ''];
$page_title = "Tambah Barang Baru";
$error = '';

if ($id) {
    $stmt = $db->prepare("SELECT * FROM inventory_items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) { header('Location: index.php'); exit; }
    $page_title = "Edit Barang";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $kode_barang = $_POST['kode_barang'];
    $stok_total = (int)$_POST['stok_total'];

    try {
        if ($id) {
            // Edit: Cari tahu selisih stok untuk menyesuaikan stok tersedia
            $selisih_stok = $stok_total - $item['stok_total'];
            $stok_tersedia_baru = $item['stok_tersedia'] + $selisih_stok;

            $stmt = $db->prepare("UPDATE inventory_items SET nama_barang = ?, kode_barang = ?, stok_total = ?, stok_tersedia = ? WHERE id = ?");
            $stmt->execute([$nama_barang, $kode_barang, $stok_total, $stok_tersedia_baru, $id]);
        } else {
            // Tambah baru
            $stmt = $db->prepare("INSERT INTO inventory_items (nama_barang, kode_barang, stok_total, stok_tersedia) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama_barang, $kode_barang, $stok_total, $stok_total]);
        }
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $error = "Gagal menyimpan. Kode barang mungkin sudah ada.";
    }
}
?>
<main class="container mt-4">
    <div class="row justify-content-center"><div class="col-md-6">
        <div class="card shadow-sm"><div class="card-header">
            <h4 class="mb-0"><?= $page_title ?></h4>
        </div><div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3"><label for="nama_barang" class="form-label">Nama Barang</label><input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= htmlspecialchars($item['nama_barang']) ?>" required></div>
                <div class="mb-3"><label for="kode_barang" class="form-label">Kode Barang</label><input type="text" name="kode_barang" id="kode_barang" class="form-control" value="<?= htmlspecialchars($item['kode_barang']) ?>" required></div>
                <div class="mb-3"><label for="stok_total" class="form-label">Stok Total</label><input type="number" name="stok_total" id="stok_total" class="form-control" value="<?= htmlspecialchars($item['stok_total']) ?>" required min="0"></div>
                <div class="d-flex justify-content-end"><a href="index.php" class="btn btn-secondary me-2">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div></div>
    </div></div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>