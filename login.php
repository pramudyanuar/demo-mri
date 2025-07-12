<?php
session_start();
require_once 'includes/database.php';
$base_url = '';

if (isset($_SESSION['user_id'])) {
    // Arahkan ke dasbor baru masing-masing peran
    $redirect_path = $_SESSION['user_role'] == 'admin' ? "{$base_url}/admin/" : "{$base_url}/worker/";
    header('Location: ' . $redirect_path);
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nama_lengkap'];
        $_SESSION['user_role'] = $user['role'];

        // Arahkan ke dasbor baru masing-masing peran
        $redirect_path = $user['role'] == 'admin' ? "{$base_url}/admin/" : "{$base_url}/worker/";
        header('Location: ' . $redirect_path);
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login - ERP</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="login-container card p-4 shadow-lg border-0">
            <h2 class="text-center mb-4 fw-bold"><i class="bi bi-building"></i> ERP System</h2>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'unauthorized'): ?><div class="alert alert-warning">Anda tidak punya hak akses ke halaman tersebut.</div><?php endif; ?>
            <form method="POST">
                <div class="mb-3"><label for="username" class="form-label">Username</label><input type="text" id="username" name="username" class="form-control" required autofocus></div>
                <div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" id="password" name="password" class="form-control" required></div>
                <div class="d-grid"><button type="submit" class="btn btn-primary">Login</button></div>
            </form>
        </div>
    </div>
</body></html>