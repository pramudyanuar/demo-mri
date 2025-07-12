<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['pekerja']);

$items = $db->query("SELECT * FROM inventory_items WHERE stok_tersedia > 0 ORDER BY nama_barang")->fetchAll();
?>
<main class="container mt-4">
    <h1 class="mb-4">Pinjam Barang</h1>
    <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Transaksi berhasil dicatat.</div><?php endif; ?>
    <div class="row">
        <?php if(empty($items)): ?>
            <div class="col-12"><div class="alert alert-info">Saat ini tidak ada barang yang tersedia untuk dipinjam.</div></div>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($item['nama_barang']) ?></h5>
                        <p class="card-text text-muted">Kode: <?= htmlspecialchars($item['kode_barang']) ?></p>
                        <div class="mt-auto">
                            <p>Stok Tersedia: <span class="badge bg-success fs-6"><?= $item['stok_tersedia'] ?></span></p>
                            <a href="transaksi.php?id=<?= $item['id'] ?>&tipe=Pinjam" class="btn btn-primary w-100">Pinjam</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <hr class="my-4">
    <h2 class="mb-4">Kembalikan Barang</h2>
    <p>Jika Anda sudah selesai menggunakan barang, silakan klik tombol di bawah ini untuk proses pengembalian.</p>
    <a href="transaksi.php?tipe=Kembali" class="btn btn-info">Form Pengembalian Barang</a>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>