<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['pekerja']);

$tipe = $_GET['tipe'] ?? null;
$item_id = $_GET['id'] ?? null;
$error = '';

if (!$tipe || !in_array($tipe, ['Pinjam', 'Kembali'])) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id_form = $_POST['item_id'];
    $jumlah = (int)$_POST['jumlah'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $db->prepare("SELECT * FROM inventory_items WHERE id = ?");
    $stmt->execute([$item_id_form]);
    $item = $stmt->fetch();

    try {
        $db->beginTransaction();
        if ($tipe == 'Pinjam') {
            if ($jumlah > $item['stok_tersedia']) throw new Exception("Stok tidak mencukupi.");
            $stok_baru = $item['stok_tersedia'] - $jumlah;
        } else { // Kembali
            $stok_baru = $item['stok_tersedia'] + $jumlah;
            if ($stok_baru > $item['stok_total']) throw new Exception("Jumlah pengembalian melebihi stok total.");
        }
        
        $db->prepare("UPDATE inventory_items SET stok_tersedia = ? WHERE id = ?")->execute([$stok_baru, $item_id_form]);
        $db->prepare("INSERT INTO inventory_transactions (item_id, user_id, tipe, jumlah) VALUES (?, ?, ?, ?)")->execute([$item_id_form, $user_id, $tipe, $jumlah]);
        
        $db->commit();
        header('Location: index.php?success=1'); exit;
    } catch (Exception $e) {
        $db->rollBack();
        $error = $e->getMessage();
    }
}

$page_title = "Form {$tipe} Barang";
$items_list = $db->query("SELECT id, nama_barang, kode_barang FROM inventory_items ORDER BY nama_barang")->fetchAll();
?>
<main class="container mt-4">
    <div class="row justify-content-center"><div class="col-md-6">
        <div class="card shadow-sm"><div class="card-header">
            <h4 class="mb-0"><?= $page_title ?></h4>
        </div><div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="item_id" class="form-label">Pilih Barang</label>
                    <select name="item_id" id="item_id" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach($items_list as $i): ?>
                        <option value="<?= $i['id'] ?>" <?= ($i['id'] == $item_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($i['nama_barang']) ?> (<?= htmlspecialchars($i['kode_barang']) ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3"><label for="jumlah" class="form-label">Jumlah</label><input type="number" name="jumlah" id="jumlah" class="form-control" required min="1"></div>
                <div class="d-flex justify-content-end"><a href="index.php" class="btn btn-secondary me-2">Batal</a><button type="submit" class="btn btn-primary">Konfirmasi <?= $tipe ?></button></div>
            </form>
        </div></div>
    </div></div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>