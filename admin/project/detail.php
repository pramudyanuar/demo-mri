<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);
$project_id = $_GET['id'] ?? null;
if (!$project_id) { header('Location: index.php'); exit; }

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add Expense
    if (isset($_POST['add_expense'])) {
        $stmt = $db->prepare("INSERT INTO project_expenses (project_id, deskripsi, jumlah, tanggal) VALUES (?, ?, ?, ?)");
        $stmt->execute([$project_id, $_POST['deskripsi'], $_POST['jumlah'], $_POST['tanggal']]);
    }
    // Add Task
    if (isset($_POST['add_task'])) {
        $stmt = $db->prepare("INSERT INTO project_tasks (project_id, user_id, nama_tugas, deadline) VALUES (?, ?, ?, ?)");
        $stmt->execute([$project_id, $_POST['user_id'], $_POST['nama_tugas'], $_POST['deadline']]);
    }
    header("Location: detail.php?id=$project_id"); exit;
}

// Fetch project details
$stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

// Fetch related data
$tasks = $db->query("SELECT t.*, u.nama_lengkap FROM project_tasks t JOIN users u ON t.user_id = u.id WHERE t.project_id = $project_id")->fetchAll();
$expenses = $db->query("SELECT * FROM project_expenses WHERE project_id = $project_id ORDER BY tanggal DESC")->fetchAll();
$workers = $db->query("SELECT id, nama_lengkap FROM users WHERE role = 'pekerja'")->fetchAll();
$total_expenses = $db->query("SELECT SUM(jumlah) as total FROM project_expenses WHERE project_id = $project_id")->fetchColumn() ?? 0;
$budget_sisa = $project['total_budget'] - $total_expenses;
?>
<main class="container mt-4">
    <h1 class="mb-3"><?= htmlspecialchars($project['nama_proyek']) ?></h1>
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h4><i class="bi bi-wallet2"></i> Keuangan Proyek</h4></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between"><strong>Total Budget:</strong> <span><?= format_rupiah($project['total_budget']) ?></span></li>
                        <li class="list-group-item d-flex justify-content-between text-danger"><strong>Total Pengeluaran:</strong> <span><?= format_rupiah($total_expenses) ?></span></li>
                        <li class="list-group-item d-flex justify-content-between text-success"><strong>Sisa Budget:</strong> <span><?= format_rupiah($budget_sisa) ?></span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header"><h5><i class="bi bi-journal-plus"></i> Tambah & Riwayat Pengeluaran</h5></div>
                <div class="card-body">
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="add_expense" value="1">
                        <div class="mb-2"><input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi Pengeluaran" required></div>
                        <div class="mb-2"><input type="number" step="any" name="jumlah" class="form-control" placeholder="Jumlah" required></div>
                        <div class="mb-2"><input type="date" name="tanggal" class="form-control" required></div>
                        <div class="d-grid"><button type="submit" class="btn btn-success btn-sm">Tambah</button></div>
                    </form>
                    <ul class="list-group">
                        <?php foreach($expenses as $ex): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <div><?= htmlspecialchars($ex['deskripsi']) ?><br><small class="text-muted"><?= date('d M Y', strtotime($ex['tanggal'])) ?></small></div>
                            <span class="text-danger fw-bold"><?= format_rupiah($ex['jumlah']) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header"><h4><i class="bi bi-list-task"></i> Tugas Proyek</h4></div>
                <div class="card-body">
                    <form method="POST" class="row g-2 align-items-end mb-4 p-3 bg-light rounded">
                        <input type="hidden" name="add_task" value="1">
                        <div class="col-md-4"><label class="form-label">Tugas</label><input type="text" name="nama_tugas" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Pekerja</label><select name="user_id" class="form-select" required><?php foreach($workers as $w) echo "<option value='{$w['id']}'>{$w['nama_lengkap']}</option>"; ?></select></div>
                        <div class="col-md-4"><label class="form-label">Deadline</label><input type="date" name="deadline" class="form-control" required></div>
                        <div class="col-12 d-grid"><button type="submit" class="btn btn-primary mt-2">Assign Tugas</button></div>
                    </form>
                    <table class="table">
                        <thead><tr><th>Tugas</th><th>Pekerja</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach($tasks as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['nama_tugas']) ?><br><small class="text-muted">Deadline: <?= date('d M Y', strtotime($t['deadline'])) ?></small></td>
                                <td><?= htmlspecialchars($t['nama_lengkap']) ?></td>
                                <td><span class="badge status-badge bg-<?= $t['status'] == 'Selesai' ? 'success' : ($t['status'] == 'Berjalan' ? 'primary' : 'warning text-dark') ?>"><?= $t['status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>