<?php
// Config.php untuk koneksi database dan pembuatan database otomatis

$host = 'localhost';
$user = 'root'; // Ubah sesuai dengan username MySQL Anda
$password = ''; // Ubah sesuai dengan password MySQL Anda
$dbname = 'todo_app'; // Nama database

// Koneksi ke MySQL
$conn = new mysqli($host, $user, $password);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Gagal membuat database: " . $conn->error);
}

// Pilih database yang sudah dibuat
$conn->select_db($dbname);

// Buat tabel users jika belum ada (diperbarui untuk menambahkan kolom profile_photo)
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL -- Menambahkan kolom profile_photo
)";

if ($conn->query($sql_users) !== TRUE) {
    die("Gagal membuat tabel users: " . $conn->error);
}

// Buat tabel todo_lists jika belum ada (diperbarui untuk menambahkan kolom description)
$sql_todo_lists = "CREATE TABLE IF NOT EXISTS todo_lists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255) DEFAULT NULL, 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_todo_lists) !== TRUE) {
    die("Gagal membuat tabel todo_lists: " . $conn->error);
}

// Buat tabel tasks jika belum ada (diperbarui untuk menambahkan kolom due_date dan priority)
$sql_tasks = "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    todo_list_id INT NOT NULL,
    description VARCHAR(255) NOT NULL,
    status ENUM('completed', 'incomplete') DEFAULT 'incomplete',
    priority ENUM('high', 'medium', 'low') NOT NULL DEFAULT 'medium', -- Menambahkan kolom priority
    due_date DATETIME DEFAULT NULL, -- Menambahkan kolom due_date
    FOREIGN KEY (todo_list_id) REFERENCES todo_lists(id) ON DELETE CASCADE
)";

if ($conn->query($sql_tasks) !== TRUE) {
    die("Gagal membuat tabel tasks: " . $conn->error);
}

$sql_password_resets = "CREATE TABLE IF NOT EXISTS password_resets (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_password_resets) !== TRUE) {
    die("Gagal membuat tabel tasks: " . $conn->error);
}

// Selesai
?>
