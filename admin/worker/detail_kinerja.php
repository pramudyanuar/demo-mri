<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

$worker_id = $_GET['id'] ?? null;
if (!$worker_id) {
    header('Location: kinerja.php');
    exit;
}

// Ambil data pekerja
$stmt_user = $db->prepare("SELECT nama_lengkap FROM users WHERE id = ?");
$stmt_user->execute([$worker_id]);
$worker_name = $stmt_user->fetchColumn();

// Ambil semua tugas pekerja tersebut
$stmt_tasks = $db->prepare("
    SELECT t.*, p.nama_proyek
    FROM project_tasks t
    JOIN projects p ON t.project_id = p.id
    WHERE t.user_id = ?
    ORDER BY t.deadline DESC
");
$stmt_tasks->execute([$worker_id]);
$tasks = $stmt_tasks->fetchAll();
?>
<main class="container mt-4">
    <a href="kinerja.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    <h1 class="mb-4">Detail Kinerja: <?= htmlspecialchars($worker_name) ?></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tugas</th>
                            <th>Proyek</th>
                            <th>Deadline</th>
                            <th>Tanggal Selesai</th>
                            <th>Status Kinerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tasks)): ?>
                            <tr><td colspan="5" class="text-center">Pekerja ini belum memiliki tugas.</td></tr>
                        <?php else: ?>
                            <?php foreach ($tasks as $t):
                                $kinerja_badge = '';
                                if ($t['status'] == 'Selesai') {
                                    if ($t['tgl_selesai'] <= $t['deadline']) {
                                        $kinerja_badge = '<span class="badge bg-success">Tepat Waktu</span>';
                                    } else {
                                        $kinerja_badge = '<span class="badge bg-danger">Terlambat</span>';
                                    }
                                } else {
                                     $kinerja_badge = '<span class="badge bg-secondary">' . $t['status'] . '</span>';
                                }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($t['nama_tugas']) ?></td>
                                <td><?= htmlspecialchars($t['nama_proyek']) ?></td>
                                <td><?= date('d M Y', strtotime($t['deadline'])) ?></td>
                                <td><?= $t['tgl_selesai'] ? date('d M Y', strtotime($t['tgl_selesai'])) : '-' ?></td>
                                <td><?= $kinerja_badge ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>