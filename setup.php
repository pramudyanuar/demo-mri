<?php
// setup.php
// File ini hanya dijalankan sekali untuk membuat struktur database dan data awal.

require_once 'includes/database.php';

echo "<!doctype html><html lang='id'><head><title>Setup Database</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head>";
echo "<body class='container mt-4'><div class='card'><div class='card-body'>";
echo "<h3>Memulai Setup Database ERP...</h3>";

try {
    // Hapus tabel lama jika ada untuk memastikan setup yang bersih
    $db->exec("DROP TABLE IF EXISTS project_expenses");
    $db->exec("DROP TABLE IF EXISTS project_tasks");
    $db->exec("DROP TABLE IF EXISTS projects");
    $db->exec("DROP TABLE IF EXISTS inventory_transactions");
    $db->exec("DROP TABLE IF EXISTS inventory_items");
    $db->exec("DROP TABLE IF EXISTS users");

    echo "<p class='text-success'>Tabel lama berhasil dihapus (jika ada).</p>";

    // 1. Tabel Pengguna (Users)
    $db->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        nama_lengkap VARCHAR(100),
        role TEXT CHECK(role IN ('admin', 'pekerja')) NOT NULL
    )");
    echo "<p>Tabel 'users' berhasil dibuat.</p>";

    // 2. Tabel Master Barang Inventaris
    $db->exec("CREATE TABLE inventory_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_barang VARCHAR(100) NOT NULL,
        kode_barang VARCHAR(20) UNIQUE NOT NULL,
        stok_total INTEGER NOT NULL,
        stok_tersedia INTEGER NOT NULL
    )");
    echo "<p>Tabel 'inventory_items' berhasil dibuat.</p>";

    // 3. Tabel Transaksi Inventaris
    $db->exec("CREATE TABLE inventory_transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        item_id INTEGER,
        user_id INTEGER,
        tipe TEXT CHECK(tipe IN ('Pinjam', 'Kembali')) NOT NULL,
        jumlah INTEGER,
        tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES inventory_items(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");
    echo "<p>Tabel 'inventory_transactions' berhasil dibuat.</p>";

    // 4. Tabel Proyek
    $db->exec("CREATE TABLE projects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_proyek VARCHAR(100) NOT NULL,
        deskripsi TEXT,
        total_budget REAL DEFAULT 0,
        status VARCHAR(20) DEFAULT 'Baru',
        tgl_mulai DATE,
        deadline DATE
    )");
    echo "<p>Tabel 'projects' berhasil dibuat.</p>";

    // 5. Tabel Tugas Proyek
    $db->exec("CREATE TABLE project_tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        project_id INTEGER,
        user_id INTEGER, -- Pekerja yang ditugaskan
        nama_tugas VARCHAR(100),
        status VARCHAR(20) DEFAULT 'Ditugaskan',
        deadline DATE,
        tgl_selesai DATE -- Kolom baru untuk tanggal penyelesaian
    )");
    echo "<p>Tabel 'project_tasks' berhasil dibuat dengan kolom 'tgl_selesai'.</p>";

    // 6. Tabel Pengeluaran Proyek
    $db->exec("CREATE TABLE project_expenses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        project_id INTEGER,
        deskripsi VARCHAR(255),
        jumlah REAL,
        tanggal DATE,
        FOREIGN KEY (project_id) REFERENCES projects(id)
    )");
    echo "<p>Tabel 'project_expenses' berhasil dibuat.</p>";

    echo "<hr><h4>Memasukkan data awal...</h4>";

    // Hash password untuk keamanan
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $budi_pass = password_hash('budi123', PASSWORD_DEFAULT);
    $susi_pass = password_hash('susi123', PASSWORD_DEFAULT);

    $db->exec("INSERT INTO users (username, password, nama_lengkap, role) VALUES
        ('admin', '$admin_pass', 'Administrator', 'admin'),
        ('budi', '$budi_pass', 'Budi Santoso', 'pekerja'),
        ('susi', '$susi_pass', 'Susi Susanti', 'pekerja')
    ");

    $db->exec("INSERT INTO inventory_items (nama_barang, kode_barang, stok_total, stok_tersedia) VALUES
        ('Bor Listrik Makita', 'BR-001', 10, 10),
        ('Mesin Gerinda Bosch', 'GR-002', 5, 5),
        ('Tang Kombinasi', 'TK-003', 20, 15)
    ");

    $db->exec("INSERT INTO projects (nama_proyek, deskripsi, total_budget, status, tgl_mulai, deadline) VALUES
        ('Pembuatan Pagar Gudang B', 'Pembuatan pagar keliling untuk gudang B dengan material baja ringan', 15000000, 'Berjalan', '2025-07-15', '2025-08-15')
    ");

    $db->exec("INSERT INTO project_tasks (project_id, user_id, nama_tugas, status, deadline, tgl_selesai) VALUES
        (1, 2, 'Pengukuran Area Pagar', 'Selesai', '2025-07-18', '2025-07-17'),
        (1, 3, 'Pemotongan Baja Ringan', 'Berjalan', '2025-07-25', NULL),
        (1, 2, 'Perakitan Rangka Pagar', 'Ditugaskan', '2025-08-05', NULL)
    ");
    
    $db->exec("INSERT INTO inventory_transactions (item_id, user_id, tipe, jumlah) VALUES (3, 2, 'Pinjam', 5)");

    // Ini adalah baris yang telah diperbaiki ($db-exec menjadi $db->exec)
    $db->exec("INSERT INTO project_expenses (project_id, deskripsi, jumlah, tanggal) VALUES
        (1, 'Pembelian Baja Ringan 50 batang', 4500000, '2025-07-16'),
        (1, 'Sewa Mesin Las Harian', 250000, '2025-07-20')
    ");

    echo "<p class='text-success'>Data awal berhasil dimasukkan.</p>";
    echo "<h3 class='mt-4'>✅ Setup Selesai!</h3>";
    echo '<h4>Akun Demo:</h4>';
    echo '<ul><li>Admin: <code>admin</code> / <code>admin123</code></li><li>Pekerja: <code>budi</code> / <code>budi123</code></li><li>Pekerja: <code>susi</code> / <code>susi123</code></li></ul>';
    echo '<a href="login.php" class="btn btn-primary">Lanjutkan ke Halaman Login</a>';

} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Error saat setup database: " . $e->getMessage() . "</div>");
}

echo "</div></div></body></html>";
?>