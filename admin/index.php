<?php
// Jalur yang benar adalah satu tingkat ke atas (../)
require_once __DIR__ . '/../templates/header.php';
check_auth(['admin']);

// Mengambil data statistik
$total_projects = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$total_tasks = $db->query("SELECT COUNT(*) FROM project_tasks")->fetchColumn();
$total_workers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'pekerja'")->fetchColumn();
$ongoing_tasks = $db->query("SELECT COUNT(*) FROM project_tasks WHERE status = 'Berjalan'")->fetchColumn();

?>
<main class="container mt-4">
    <h1 class="mb-4">Dasbor Admin</h1>
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-briefcase-fill"></i> Total Proyek</h5>
                    <p class="card-text fs-2 fw-bold"><?= $total_projects ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-list-task"></i> Total Tugas</h5>
                    <p class="card-text fs-2 fw-bold"><?= $total_tasks ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill"></i> Jumlah Pekerja</h5>
                    <p class="card-text fs-2 fw-bold"><?= $total_workers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-dark bg-warning shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person-workspace"></i> Tugas Berjalan</h5>
                    <p class="card-text fs-2 fw-bold"><?= $ongoing_tasks ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h4>Akses Cepat</h4>
        </div>
        <div class="card-body">
            <a href="<?= $base_url ?>/admin/project/" class="btn btn-lg btn-outline-primary me-2"><i class="bi bi-kanban"></i> Manajemen Proyek</a>
            <a href="<?= $base_url ?>/admin/inventory/" class="btn btn-lg btn-outline-secondary"><i class="bi bi-box-seam"></i> Manajemen Barang</a>
        </div>
    </div>
</main>
<?php 
// Perbaiki juga jalur untuk footer
require_once __DIR__ . '/../templates/footer.php'; 
?>