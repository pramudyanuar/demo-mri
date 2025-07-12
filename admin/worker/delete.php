<?php
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';

check_auth(['admin']);

$id = $_GET['id'] ?? null;

if ($id) {
    // Untuk mencegah penghapusan akun admin sendiri atau akun lain yang bukan pekerja
    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'pekerja'");
    $stmt->execute([$id]);
}

header('Location: index.php?success=1');
exit;
?>