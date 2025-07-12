<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

$id = $_GET['id'] ?? null;
$p = ['nama_proyek' => '', 'deskripsi' => '', 'total_budget' => '', 'tgl_mulai' => '', 'deadline' => ''];
$page_title = "Buat Proyek Baru";

if ($id) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();
    if (!$p) { header('Location: index.php'); exit; }
    $page_title = "Edit Proyek";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_proyek'];
    $deskripsi = $_POST['deskripsi'];
    $budget = $_POST['total_budget'];
    $mulai = $_POST['tgl_mulai'];
    $deadline = $_POST['deadline'];
    
    if ($id) {
        $stmt = $db->prepare("UPDATE projects SET nama_proyek=?, deskripsi=?, total_budget=?, tgl_mulai=?, deadline=? WHERE id=?");
        $stmt->execute([$nama, $deskripsi, $budget, $mulai, $deadline, $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO projects (nama_proyek, deskripsi, total_budget, tgl_mulai, deadline) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $deskripsi, $budget, $mulai, $deadline]);
    }
    header('Location: index.php');
    exit;
}
?>
<main class="container mt-4">
    <div class="row justify-content-center"><div class="col-md-8">
        <div class="card shadow-sm"><div class="card-header">
            <h4 class="mb-0"><?= $page_title ?></h4>
        </div><div class="card-body">
            <form method="POST">
                <div class="mb-3"><label class="form-label">Nama Proyek</label><input type="text" name="nama_proyek" class="form-control" value="<?= htmlspecialchars($p['nama_proyek']) ?>" required></div>
                <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control"><?= htmlspecialchars($p['deskripsi']) ?></textarea></div>
                <div class="mb-3"><label class="form-label">Total Budget</label><input type="number" step="any" name="total_budget" class="form-control" value="<?= htmlspecialchars($p['total_budget']) ?>" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Tanggal Mulai</label><input type="date" name="tgl_mulai" class="form-control" value="<?= htmlspecialchars($p['tgl_mulai']) ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Deadline</label><input type="date" name="deadline" class="form-control" value="<?= htmlspecialchars($p['deadline']) ?>" required></div>
                </div>
                <div class="d-flex justify-content-end"><a href="index.php" class="btn btn-secondary me-2">Batal</a><button type="submit" class="btn btn-primary">Simpan Proyek</button></div>
            </form>
        </div></div>
    </div></div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>