<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

// Mengambil data pekerja beserta statistik tugas mereka
$workers_performance = $db->query("
    SELECT 
        u.id, 
        u.nama_lengkap,
        COUNT(t.id) as total_tugas,
        SUM(CASE WHEN t.status = 'Selesai' THEN 1 ELSE 0 END) as tugas_selesai,
        SUM(CASE WHEN t.status = 'Selesai' AND t.tgl_selesai <= t.deadline THEN 1 ELSE 0 END) as tepat_waktu,
        SUM(CASE WHEN t.status = 'Selesai' AND t.tgl_selesai > t.deadline THEN 1 ELSE 0 END) as terlambat
    FROM users u
    LEFT JOIN project_tasks t ON u.id = t.user_id
    WHERE u.role = 'pekerja'
    GROUP BY u.id
    ORDER BY u.nama_lengkap
")->fetchAll();

?>
<main class="container mt-4">
    <h1 class="mb-4">Laporan Kinerja Pekerja</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pekerja</th>
                            <th class="text-center">Tugas Selesai</th>
                            <th class="text-center">Tepat Waktu</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($workers_performance as $p): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['nama_lengkap']) ?></strong></td>
                            <td class="text-center"><span class="badge bg-primary fs-6"><?= $p['tugas_selesai'] ?? 0 ?></span></td>
                            <td class="text-center"><span class="badge bg-success fs-6"><?= $p['tepat_waktu'] ?? 0 ?></span></td>
                            <td class="text-center"><span class="badge bg-danger fs-6"><?= $p['terlambat'] ?? 0 ?></span></td>
                            <td class="text-center">
                                <a href="detail_kinerja.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-search"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>