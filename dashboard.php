<?php
session_start();
require 'config.php'; // Koneksi ke database

// Jika user belum login, redirect ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ambil data user dari database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, profile_photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Set default profile picture if none exists
$profile_photo = !empty($user['profile_photo']) ? htmlspecialchars($user['profile_photo']) : 'image/icon_profile.jpg';

// Ambil data to-do list dari database berdasarkan user yang login
$stmt = $conn->prepare("SELECT * FROM todo_lists WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$lists = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fdfbfb 100%, #f6d365 100%, #fda085 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
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
            transform: translateX(0); /* Default: Sidebar tampil */
            transition: transform 0.3s ease;
            z-index: 1000; /* Membuat sidebar di atas konten lain */
        }

        .sidebar.active {
            transform: translateX(-100%); /* Sidebar sembunyi ketika ada class 'active' */
        }

        /* Sidebar di atas konten untuk layar kecil */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%); /* Tersembunyi secara default di layar kecil */
            }

            .sidebar.active {
                transform: translateX(0); /* Muncul ketika 'active' di layar kecil */
            }
        }

        .profile-card {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .nav-link {
            font-size: 18px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            padding: 10px;
            text-decoration: none;
            color: #333;
            transition: background 0.3s ease;
        }

        .nav-link:hover {
            background-color: #f8d7da;
            border-radius: 10px;
        }

        .nav-link img {
            margin-right: 10px;
            width: 24px;
            height: 24px;
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            font-size: 30px;
            cursor: pointer;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1100; /* Membuat hamburger menu di atas sidebar */
        }

        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            position: relative; /* Tambahkan untuk memastikan sidebar di atas konten */
            z-index: 900; /* Pastikan konten di bawah sidebar */
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }

        .btn-custom {
        background-color: #f78ca0; /* Same background color as btn-success */
        border: none; /* Remove border */
        padding: 10px 20px; /* Padding for the button */
        border-radius: 50px; /* Rounded corners */
        color: #fff; /* Text color */
        font-size: 14px; /* Font size */
        transition: background-color 0.3s ease; /* Transition for hover effect */
        }

        .btn-custom:hover {
            background-color: #ff9a9e; /* Hover background color */
        }

        h2 {
            color: #ff6f61;
        }

        .sticky-note-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .sticky-note {
            padding: 20px;
            width: 200px;
            height: 200px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            min-height: 150px;
            position: relative;
        }

        .sticky-note-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sticky-note-actions {
            position: absolute;
            bottom: 10px;
            right: 10px;
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
    <!-- Profile Section -->
    <div class="profile-card">
        <img src="<?php echo $profile_photo; ?>" alt="Profile Photo">
        <h4><?php echo htmlspecialchars($user['username']); ?></h4>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <!-- Navbar Links -->
    <nav>
        <a href="profile.php" class="nav-link">
            <img src="image/icon_profile.jpg" alt="Profile Icon"> 
            Profile
        </a>
        <a href="dashboard.php" class="nav-link">
            <img src="image/dashboard.jpg" alt="Dashboard Icon"> 
            Dashboard
        </a>
        <a href="about_us.php" class="nav-link">
            <img src="image/aboutus.jpg" alt="About Us Icon"> 
            About Us
        </a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Welcome Section -->
    <div class="welcome-section text-center mb-4">
        <h2>Welcome to DoNow</h2>
        <img src="image/banner1.png" alt="Welcome Image" class="img-fluid" style="max-width: 100%; object-fit: cover; height: auto; border-radius: 20px;">
    </div>

    <!-- Sticky Notes Grid -->
    <div class="sticky-note-container">
        <!-- Tambahkan Sticky Note Baru -->
        <div class="sticky-note" style="display: flex; justify-content: center; align-items: center; font-size: 50px; background-color: #ddd;">
            <a href="create_list.php" style="text-decoration: none; color: #333;">+</a>
        </div>
        
        <!-- Loop list to-do dari database -->
        <?php 
        // Array warna pastel
        $colors = ['#809bce', '#95b8d1', '#b8e0d2', '#d6eadf', '#eac4d5', '#e8dff5', '#fce1e4', '#daeaf6', '#ddedea'];
        while ($list = $lists->fetch_assoc()): 
            // Pilih warna acak dari array
            $random_color = $colors[array_rand($colors)];
        ?>
        <div class="sticky-note" style="background-color: <?php echo $random_color; ?>;">
            <div class="sticky-note-title"><?php echo htmlspecialchars($list['title']); ?></div>
            <p><?php echo !empty($list['description']) ? htmlspecialchars($list['description']) : 'Tidak ada deskripsi'; ?></p>
            <div class="sticky-note-actions">
                <a href="view_list.php?list_id=<?php echo $list['id']; ?>" class="btn btn-custom btn-sm">View List</a>
                <a href="delete_list.php?list_id=<?php echo $list['id']; ?>" class="btn btn-custom btn-sm" title="Hapus">
                <i class="bi bi-trash"></i> 
            </a>

            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {

        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
</script>
</body>
</html>
