<?php
require 'config.php'; // Panggil config.php untuk koneksi ke database

// Initialize variables for messages
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Enkripsi password

    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error_message = "Email sudah terdaftar!";
    } else {
        // Simpan user baru ke database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $success_message = "Registrasi berhasil! Anda sekarang dapat <a href='login.php'>login</a>.";
        } else {
            $error_message = "Terjadi kesalahan saat registrasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .register-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%;
        }

        @media (min-width: 768px) {
            .register-container {
                flex-direction: row;
            }
        }

        /* Section Kiri: Tidak ada gambar, bisa hanya warna */
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

        /* Section Kanan: Background dan form */
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

        /* Kotak untuk form yang melayang */
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

<div class="register-container">
    <!-- Bagian Kiri: Hanya warna atau teks -->
    <div class="left-section">
        <div>
            <h1>Selamat Datang di DoNow</h1>
            <p>Daftar untuk memulai to do list anda!</p>
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Background + Kotak Form -->
    <div class="background-section">
        <div class="form-box">
            <h2>Register</h2>

            <!-- Tampilkan pesan sukses atau error -->
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-custom">Register</button>
            </form>

            <p class="mt-3">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
