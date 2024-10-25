<?php
require 'config.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $status = isset($_POST['status']) ? 'completed' : 'incomplete';

    // Update status di database
    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $task_id);
    $stmt->execute();

    // Redirect kembali ke halaman view_list.php
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
