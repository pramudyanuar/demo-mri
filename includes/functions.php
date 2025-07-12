<?php
// /includes/functions.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function check_auth(array $allowed_roles) {
    $base_url = '';
    if (!isset($_SESSION['user_id'])) {
        header("Location: {$base_url}/login.php");
        exit;
    }
    if (!in_array($_SESSION['user_role'], $allowed_roles)) {
        header("Location: {$base_url}/login.php?error=unauthorized");
        exit;
    }
}

function format_rupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}