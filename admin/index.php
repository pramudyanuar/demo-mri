<?php
require_once __DIR__ . '/../templates/header.php';
check_auth(['admin']);

// Mengambil data statistik
$total_projects = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$total_workers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'pekerja'")->fetchColumn();

// Statistik Kinerja Pekerja
$kinerja = $db->query("
    SELECT 
        SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as tugas_selesai,
        SUM(CASE WHEN status = 'Selesai' AND tgl_selesai <= deadline THEN 1 ELSE 0 END) as tepat_waktu,
        SUM(CASE WHEN status = 'Selesai' AND tgl_selesai > deadline THEN 1 ELSE 0 END) as terlambat
    FROM project_tasks
")->fetch();

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
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill"></i> Jumlah Pekerja</h5>
                    <p class="card-text fs-2 fw-bold"><?= $total_workers ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card bg-light shadow-sm h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-line-fill"></i> Ringkasan Kinerja Tugas</h5>
                </div>
                <div class="card-body d-flex justify-content-around align-items-center">
                    <div class="text-center">
                        <p class="mb-1 text-muted">Selesai</p>
                        <h3 class="fw-bold text-info"><?= $kinerja['tugas_selesai'] ?? 0 ?></h3>
                    </div>
                    <div class="text-center">
                        <p class="mb-1 text-muted">Tepat Waktu</p>
                        <h3 class="fw-bold text-success"><?= $kinerja['tepat_waktu'] ?? 0 ?></h3>
                    </div>
                    <div class="text-center">
                        <p class="mb-1 text-muted">Terlambat</p>
                        <h3 class="fw-bold text-danger"><?= $kinerja['terlambat'] ?? 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h4>Akses Cepat</h4>
        </div>
        <div class="card-body d-flex flex-wrap">
            <a href="<?= $base_url ?>/admin/project/" class="btn btn-lg btn-outline-primary me-2 mb-2"><i class="bi bi-kanban"></i> Manajemen Proyek</a>
            <a href="<?= $base_url ?>/admin/inventory/" class="btn btn-lg btn-outline-secondary me-2 mb-2"><i class="bi bi-box-seam"></i> Manajemen Barang</a>
            <a href="<?= $base_url ?>/admin/worker/" class="btn btn-lg btn-outline-dark me-2 mb-2"><i class="bi bi-person-video3"></i> Manajemen Pekerja</a>
            <a href="<?= $base_url ?>/admin/worker/kinerja.php" class="btn btn-lg btn-outline-info me-2 mb-2"><i class="bi bi-graph-up-arrow"></i> Laporan Kinerja</a>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>