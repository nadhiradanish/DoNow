<?php
session_start();
require 'config.php'; // Include database config

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App - Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Full page layout for background */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 400;
             font-style: normal;
}
        

        /* Background image for the entire page */
        .bg-image {
            background-image: url('image/background_index.jpg'); /* Ganti dengan path gambar background kamu */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Floating form box in the center */
        .form-box {
            background-color: rgba(255, 255, 255, 0.85); /* White with transparency */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Shadow to make the form stand out */
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .form-box h1 {
            margin-bottom: 30px;
            font-size: 26px;
            font-weight: bolder;
        }

        .btn-custom {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }
        .btn-login {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #abdee6;
        }
        .btn-register {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #cce2cb;
        }

        /* Adjust button margins */
        .form-box a {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="bg-image">
    <!-- Kotak Form Melayang di Tengah Halaman -->
    <div class="form-box">
        <h1>Selamat Datang di <br>
        To-Do List App</h1>
        <p>Pilih salah satu opsi di bawah ini untuk melanjutkan:</p>
        <a href="login.php" class="btn btn-primary btn-login">Login</a>
        <a href="register.php" class="btn btn-success btn-register">Register</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
