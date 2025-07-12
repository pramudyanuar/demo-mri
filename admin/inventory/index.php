<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

$items = $db->query("SELECT * FROM inventory_items ORDER BY nama_barang")->fetchAll();
?>
<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Manajemen Barang</h1>
        <div>
            <a href="history.php" class="btn btn-outline-secondary"><i class="bi bi-clock-history"></i> Riwayat Transaksi</a>
            <a href="manage.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Barang Baru</a>
        </div>
    </div>
    <div class="card shadow-sm"><div class="card-body"><div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light"><tr><th>Nama Barang</th><th>Kode</th><th>Stok Tersedia</th><th>Stok Total</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($item['nama_barang']) ?></strong></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($item['kode_barang']) ?></span></td>
                    <td><h4><span class="badge bg-success"><?= $item['stok_tersedia'] ?></span></h4></td>
                    <td><?= $item['stok_total'] ?></td>
                    <td class="text-center"><a href="manage.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div></div></div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>