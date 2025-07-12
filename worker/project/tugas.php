<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['pekerja']);
$user_id = $_SESSION['user_id'];

// Handle action tandai selesai
if (isset($_GET['action']) && isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $action = $_GET['action'];
    $new_status = '';
    
    if ($action == 'start') $new_status = 'Berjalan';
    if ($action == 'complete') $new_status = 'Selesai';

    if ($new_status) {
        // Pastikan task milik user yg login
        $stmt = $db->prepare("UPDATE project_tasks SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$new_status, $task_id, $user_id]);
        header('Location: tugas.php?success=1'); exit;
    }
}

$tasks = $db->prepare("
    SELECT t.*, p.nama_proyek
    FROM project_tasks t
    JOIN projects p ON t.project_id = p.id
    WHERE t.user_id = ?
    ORDER BY CASE WHEN t.status = 'Selesai' THEN 1 ELSE 0 END, t.deadline ASC
");
$tasks->execute([$user_id]);
$my_tasks = $tasks->fetchAll();
?>
<main class="container mt-4">
    <h1 class="mb-4">Tugas Saya</h1>
    <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Tugas berhasil diperbarui.</div><?php endif; ?>
    <div class="card shadow-sm"><div class="card-body"><div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light"><tr><th>Tugas</th><th>Proyek</th><th>Deadline</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if(empty($my_tasks)): ?>
                    <tr><td colspan="5" class="text-center">Tidak ada tugas untuk Anda.</td></tr>
                <?php else: ?>
                    <?php foreach ($my_tasks as $t): ?>
                    <tr class="<?= $t['status'] == 'Selesai' ? 'table-light text-muted' : '' ?>">
                        <td><strong><?= htmlspecialchars($t['nama_tugas']) ?></strong></td>
                        <td><?= htmlspecialchars($t['nama_proyek']) ?></td>
                        <td><?= date('d M Y', strtotime($t['deadline'])) ?></td>
                        <td><span class="badge status-badge bg-<?= $t['status'] == 'Selesai' ? 'success' : ($t['status'] == 'Berjalan' ? 'primary' : 'warning text-dark') ?>"><?= $t['status'] ?></span></td>
                        <td>
                            <?php if ($t['status'] == 'Ditugaskan'): ?>
                                <a href="?action=start&task_id=<?= $t['id'] ?>" class="btn btn-primary btn-sm">Mulai Tugas</a>
                            <?php elseif ($t['status'] == 'Berjalan'): ?>
                                <a href="?action=complete&task_id=<?= $t['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin menandai tugas ini selesai?')">
                                    <i class="bi bi-check2-circle"></i> Selesaikan
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div></div></div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>