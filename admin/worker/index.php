<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

// Mengambil semua pengguna dengan peran 'pekerja'
$workers = $db->query("SELECT id, username, nama_lengkap FROM users WHERE role = 'pekerja' ORDER BY nama_lengkap")->fetchAll();
?>

<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Manajemen Pekerja</h1>
        <a href="manage.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Pekerja Baru</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Aksi berhasil dilakukan.</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($workers)): ?>
                            <tr><td colspan="3" class="text-center">Belum ada data pekerja.</td></tr>
                        <?php else: ?>
                            <?php foreach ($workers as $worker): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($worker['nama_lengkap']) ?></strong></td>
                                <td><?= htmlspecialchars($worker['username']) ?></td>
                                <td>
                                    <a href="manage.php?id=<?= $worker['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="delete.php?id=<?= $worker['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pekerja ini? Tugas yang terkait dengannya tidak akan terhapus.')"><i class="bi bi-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>