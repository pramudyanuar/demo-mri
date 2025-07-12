<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

$transactions = $db->query("
    SELECT t.tanggal, t.tipe, t.jumlah, i.nama_barang, u.nama_lengkap
    FROM inventory_transactions t
    JOIN inventory_items i ON t.item_id = i.id
    JOIN users u ON t.user_id = u.id
    ORDER BY t.tanggal DESC
")->fetchAll();
?>
<main class="container mt-4">
    <h1 class="mb-4">Riwayat Transaksi Barang</h1>
    <div class="card shadow-sm"><div class="card-body"><div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light"><tr><th>Tanggal</th><th>Nama Barang</th><th>Pekerja</th><th>Tipe</th><th>Jumlah</th></tr></thead>
            <tbody>
                <?php foreach ($transactions as $t): 
                    $badge = $t['tipe'] == 'Pinjam' ? 'bg-warning text-dark' : 'bg-info';
                ?>
                <tr>
                    <td><?= date('d M Y, H:i', strtotime($t['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($t['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($t['nama_lengkap']) ?></td>
                    <td><span class="badge <?= $badge ?>"><?= $t['tipe'] ?></span></td>
                    <td><?= $t['jumlah'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div></div></div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>