<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

$projects = $db->query("SELECT * FROM projects ORDER BY tgl_mulai DESC")->fetchAll();
?>
<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Manajemen Proyek</h1>
        <a href="manage.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Buat Proyek Baru</a>
    </div>
    <div class="card shadow-sm"><div class="card-body"><div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light"><tr><th>Nama Proyek</th><th>Status</th><th>Budget</th><th>Deadline</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($projects as $p): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['nama_proyek']) ?></strong></td>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($p['status']) ?></span></td>
                    <td><?= format_rupiah($p['total_budget']) ?></td>
                    <td><?= date('d M Y', strtotime($p['deadline'])) ?></td>
                    <td>
                        <a href="detail.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info">Detail</a>
                        <a href="manage.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div></div></div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>