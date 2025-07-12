<?php
// Jalur yang benar adalah satu tingkat ke atas (../)
require_once __DIR__ . '/../templates/header.php';
check_auth(['pekerja']);
$user_id = $_SESSION['user_id'];

// Mengambil data statistik tugas untuk pekerja yang login
$my_tasks_total = $db->prepare("SELECT COUNT(*) FROM project_tasks WHERE user_id = ?");
$my_tasks_total->execute([$user_id]);
$total_tugas = $my_tasks_total->fetchColumn();

$my_tasks_ongoing = $db->prepare("SELECT COUNT(*) FROM project_tasks WHERE user_id = ? AND status = 'Berjalan'");
$my_tasks_ongoing->execute([$user_id]);
$tugas_berjalan = $my_tasks_ongoing->fetchColumn();

$my_tasks_completed = $db->prepare("SELECT COUNT(*) FROM project_tasks WHERE user_id = ? AND status = 'Selesai'");
$my_tasks_completed->execute([$user_id]);
$tugas_selesai = $my_tasks_completed->fetchColumn();
?>
<main class="container mt-4">
    <h1 class="mb-4">Dasbor Pekerja</h1>
    <div class="alert alert-light shadow-sm" role="alert">
        <h4 class="alert-heading">Selamat Datang, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h4>
        <p>Ini adalah pusat kendali Anda. Di sini Anda dapat melihat ringkasan tugas dan mengakses halaman lain dengan cepat.</p>
        <hr>
        <p class="mb-0">Tetap semangat dan produktif!</p>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-secondary shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-journal-check"></i> Total Tugas Anda</h5>
                    <p class="card-text fs-2 fw-bold"><?= $total_tugas ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-hourglass-split"></i> Tugas Sedang Dikerjakan</h5>
                    <p class="card-text fs-2 fw-bold"><?= $tugas_berjalan ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-check2-circle"></i> Tugas Selesai</h5>
                    <p class="card-text fs-2 fw-bold"><?= $tugas_selesai ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <h4>Akses Cepat</h4>
        </div>
        <div class="card-body">
            <a href="<?= $base_url ?>/worker/project/tugas.php" class="btn btn-lg btn-outline-primary me-2"><i class="bi bi-list-task"></i> Lihat Tugas Saya</a>
            <a href="<?= $base_url ?>/worker/inventory/" class="btn btn-lg btn-outline-info"><i class="bi bi-tools"></i> Pinjam Barang</a>
        </div>
    </div>

</main>
<?php 
// Perbaiki juga jalur untuk footer
require_once __DIR__ . '/../templates/footer.php'; 
?>