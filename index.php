<?php
// index.php
// File ini berfungsi sebagai router utama aplikasi.

// Memulai session untuk mengakses data login pengguna.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tentukan path dasar URL untuk fleksibilitas saat deployment.
// Di Replit atau server web standar, ini biasanya kosong.
$base_url = ''; 

// Periksa apakah pengguna sudah login dengan melihat apakah 'user_id' ada di session.
if (isset($_SESSION['user_id'])) {
    // Jika sudah login, periksa perannya (role).
    if ($_SESSION['user_role'] == 'admin') {
        // Jika perannya adalah 'admin', arahkan ke dasbor admin.
        header("Location: {$base_url}/admin/");
        exit;
    } else {
        // Jika perannya adalah 'pekerja' atau peran lain, arahkan ke dasbor pekerja.
        header("Location: {$base_url}/worker/");
        exit;
    }
} else {
    // Jika pengguna belum login (tidak ada 'user_id' di session),
    // arahkan mereka ke halaman login.
    header("Location: {$base_url}/login.php");
    exit;
}

?>
