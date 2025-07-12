<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/database.php';
$base_url = '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= $_SESSION['user_role'] == 'admin' ? $base_url . '/admin/' : $base_url . '/worker/' ?>"><i class="bi bi-building"></i> ERP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($_SESSION['user_role'] == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/admin/">Dasbor</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/admin/project/">Manajemen Proyek</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/admin/inventory/">Manajemen Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/admin/worker/">Manajemen Pekerja</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/worker/">Dasbor</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/worker/project/tugas.php">Tugas Saya</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/worker/inventory/">Pinjam Barang</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_name']) ?> (<?= ucfirst($_SESSION['user_role']) ?>)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= $base_url ?>/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>