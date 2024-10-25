<?php
session_start();
require 'config.php'; // Panggil koneksi database

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['list_id'])) {
    $list_id = $_GET['list_id'];

    // Prepare statement to delete the todo list
    $stmt = $conn->prepare("DELETE FROM todo_lists WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $list_id, $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Successful deletion
        header("Location: dashboard.php?message=List deleted successfully");
    } else {
        // Deletion failed
        header("Location: dashboard.php?error=Failed to delete list");
    }
} else {
    header("Location: dashboard.php?error=No list ID provided");
}
?>

