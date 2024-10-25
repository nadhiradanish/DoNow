<?php
session_start();
require_once 'db_connection.php'; // Koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari session
$user_id = $_SESSION['user_id'];

// Query untuk mengambil data pengguna
$query = "SELECT username, email, profile_photo FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

// Proses upload foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if ($check !== false) {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $update_query = "UPDATE users SET profile_photo = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('si', $target_file, $user_id);
            $update_stmt->execute();
            header('Location: profile.php');
            exit();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "File is not an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fdfbfb 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar */
            .sidebar {
                width: 250px;
                background-color: rgba(255, 255, 255, 0.8);
                padding: 20px;
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                display: flex;
                flex-direction: column;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transform: translateX(0);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .sidebar.active {
                transform: translateX(-100%);
            }

            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                }

                .sidebar.active {
                    transform: translateX(0);
                }
            }

            /* Profile card styling */
            .profile-card {
                text-align: center;
                margin-bottom: 30px;
            }

            .profile-card img {
                width: 80px;  /* Ukuran lebih kecil agar tidak terlalu besar */
                height: 80px;
                border-radius: 50%;
                margin-bottom: 10px;
            }

            /* Styling untuk link pada sidebar */
            .nav-link {
                font-size: 16px;  /* Sesuaikan ukuran font agar proporsional */
                margin: 10px 0;
                display: flex;
                align-items: center;
                padding: 10px;
                text-decoration: none;
                color: #333;
                transition: background 0.3s ease;
            }

            .nav-link img {
                width: 24px;  /* Atur ukuran icon agar tidak terlalu besar */
                height: 24px; /* Ukuran icon juga dibuat seragam */
                margin-right: 10px;
            }

            .nav-link:hover {
                background-color: #f8d7da;
                border-radius: 10px;
            }

            /* Hamburger Menu */
            .hamburger {
                display: none;
                font-size: 30px;
                cursor: pointer;
                position: absolute;
                top: 20px;
                left: 20px;
                z-index: 1100;
            }

            @media (max-width: 768px) {
                .hamburger {
                    display: block;
                }
            }

            h2 {
                        color: #ff6f61;
                    }
                    .btn {
                        border-radius: 50px;
                        padding: 10px 20px;
                        font-size: 14px;
                    }
                    .btn-primary { background-color: #ff6f61; border: none; }
                    .btn-primary:hover { background-color: #ff9a9e; }
                    .btn-danger { background-color: #f78ca0; border: none; }
                    .btn-danger:hover { background-color: #ff9a9e; }

            /* Main Content */
            .main-content {
                margin-left: 250px;
                padding: 40px;
                width: 100%;
                background-size: cover;
                background-position: center;
                min-height: 100vh;
            }

            @media (max-width: 768px) {
                .main-content {
                    margin-left: 0;
                    padding: 20px;
                }
            }

    </style>
</head>
<body>

<!-- Hamburger Menu -->
<div class="hamburger" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="profile-card">
        <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo">
        <h4><?php echo htmlspecialchars($user['username']); ?></h4>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <!-- Sidebar Links -->
    <nav>
       <a href="profile.php" class="nav-link">
            <img src="image/icon_profile.jpg" alt="Profile Icon"> Profile
        </a>
        <a href="dashboard.php" class="nav-link">
            <img src="image/dashboard.jpg" alt="Dashboard Icon"> Dashboard
        </a>
        <a href="about_us.php" class="nav-link">
            <img src="image/aboutus.jpg" alt="About Us Icon"> About Us
        </a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container mt-5">
        <h2>Profil Pengguna</h2>

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="profile_photo" class="form-label">Foto Profil</label>
                <input type="file" name="profile_photo" class="form-control">
                <?php if ($user['profile_photo']): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo" style="width: 100px; height: auto; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Upload Foto</button>
        </form>

        <form action="update_profile.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>

        <form action="logout.php" method="POST" class="mt-4">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
