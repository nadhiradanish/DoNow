<?php
session_start();
require 'config.php'; // Panggil koneksi database

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']); // Ambil deskripsi
    $user_id = $_SESSION['user_id'];

    // Tambah to-do list ke database
    $stmt = $conn->prepare("INSERT INTO todo_lists (user_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $description);

    if ($stmt->execute()) {
        echo "To-Do List berhasil ditambahkan!";
        header("Location: dashboard.php"); // Redirect ke dashboard
        exit(); // Pastikan untuk keluar setelah redirect
    } else {
        echo "Terjadi kesalahan saat membuat to-do list.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fdfbfb 100%, #f6d365 100%, #fda085 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh; /* Memastikan background menutupi seluruh tinggi tampilan */
            display: flex; /* Gunakan flex untuk menengah konten */
            justify-content: center; /* Rata tengah secara horizontal */
            align-items: center; /* Rata tengah secara vertikal */
        }

        .container {
            background: rgba(255, 255, 255, 0.8); /* Buat kontainer semi-transparan */
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%; /* Pastikan kontainer mengambil lebar penuh */
            max-width: 600px; /* Batasi lebar kontainer untuk keterbacaan yang lebih baik */
        }

        h2 {
            color: #ff6f61;
            text-align: center; /* Rata tengah header */
        }

        .btn {
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 14px;
        }

        .btn-success {
            background-color: #f78ca0;
            border: none;
        }

        .btn-success:hover {
            background-color: #ff9a9e;
        }

        .form-label {
            color: #ff6f61;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mt-5">Make a new To-do List</h2>
    <form action="create_list.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="title" class="form-label">To-Do List Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">To-Do List Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Let's Go!</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
