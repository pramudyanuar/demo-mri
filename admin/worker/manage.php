<?php
require_once __DIR__ . '/../../templates/header.php';
check_auth(['admin']);

$id = $_GET['id'] ?? null;
$worker = ['username' => '', 'nama_lengkap' => ''];
$page_title = "Tambah Pekerja Baru";
$error = '';

if ($id) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? AND role = 'pekerja'");
    $stmt->execute([$id]);
    $worker = $stmt->fetch();
    if (!$worker) { header('Location: index.php'); exit; }
    $page_title = "Edit Data Pekerja";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $password = $_POST['password'];

    try {
        if ($id) {
            // Edit data pekerja
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET username = ?, nama_lengkap = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $nama_lengkap, $hashed_password, $id]);
            } else {
                // Jangan update password jika kolom dikosongkan
                $stmt = $db->prepare("UPDATE users SET username = ?, nama_lengkap = ? WHERE id = ?");
                $stmt->execute([$username, $nama_lengkap, $id]);
            }
        } else {
            // Tambah pekerja baru
            if (empty($password)) {
                throw new Exception("Password wajib diisi untuk pekerja baru.");
            }
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, nama_lengkap, password, role) VALUES (?, ?, ?, 'pekerja')");
            $stmt->execute([$username, $nama_lengkap, $hashed_password]);
        }
        header('Location: index.php?success=1');
        exit;
    } catch (Exception $e) {
        if (str_contains($e->getMessage(), 'UNIQUE constraint failed: users.username')) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            $error = $e->getMessage();
        }
    }
}
?>
<main class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header"><h4 class="mb-0"><?= $page_title ?></h4></div>
                <div class="card-body">
                    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= htmlspecialchars($worker['nama_lengkap']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($worker['username']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" <?= !$id ? 'required' : '' ?>>
                            <small class="text-muted"><?= $id ? 'Kosongkan jika tidak ingin mengubah password.' : 'Password wajib diisi.' ?></small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="index.php" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>