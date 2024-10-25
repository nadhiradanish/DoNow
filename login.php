<?php
session_start();
require 'config.php'; // Panggil config.php untuk koneksi dan pengecekan database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    
    // Cek apakah email terdaftar
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php"); // Redirect ke dashboard
        exit();
    } else {
        echo "<div class='alert alert-danger'>Login gagal! Email atau password salah.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%;
        }

        @media (min-width: 768px) {
            .login-container {
                flex-direction: row;
            }
        }

        /* Bagian kiri */
        .left-section {
            background-color: #f8f9fa;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        /* Bagian kanan */
        .background-section {
            background-image: url('image/background_uts.gif');
            background-size: cover;
            background-position: center;
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Kotak form melayang */
        .form-box {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-box h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .btn-custom {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        /* Media query untuk layar kecil */
        @media (max-width: 767px) {
            .form-box {
                padding: 20px;
            }

            .left-section {
                display: none;
            }

            .background-section {
                flex: none;
                height: 100%;
                background-position: top;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Bagian Kiri -->
    <div class="left-section">
        <div>
            <h1>Selamat Datang di To-Do List App</h1>
            <p>Silakan login untuk melanjutkan!</p>
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Background dan Form Login -->
    <div class="background-section">
        <div class="form-box">
            <h2>Login</h2>

            <!-- Form login -->
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-custom">Login</button>
            </form>

            <p class="mt-3">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            <p class="mt-2"><a href="forgot_password.php">Lupa Password?</a></p>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
