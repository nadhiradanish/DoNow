<?php
session_start();
require 'config.php'; // Koneksi ke database

// Pastikan user sudah login
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet"> <!-- Add Bootstrap Icons -->
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fdfbfb 100%, #f6d365 100%, #fda085 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            min-height: 100vh;
        }
        .container {
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: calc(100% - 550px);
            margin-left: 400px;
        }

        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 20px;
                width: 100%;
            }
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

        /* Sidebar for small screens */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
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
            z-index: 1100;
        }

        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }
        }

        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .team-member h5 {
            margin-top: 10px;
            color: #ff6f61;
        }
        .navbar {
            background-color: #f8f9fa;
        }

        /* Additional styles for responsiveness */
        @media (max-width: 576px) {
            .team-member img {
                width: 120px;
                height: 120px;
            }
            .team-member h5 {
                font-size: 16px;
            }
        }

        @media (max-width: 400px) {
            .nav-link {
                font-size: 16px;
                padding: 8px;
            }
            .profile-card img {
                width: 60px;
                height: 60px;
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
    <!-- Profile Section -->
    <div class="profile-card">
        <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo">
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

<!-- About Us content -->
<div class="container main-content mt-5">
    <h2>About us</h2>
    <p>We are the creators of the DoNow website, a platform designed to help you manage your daily activities more easily. With an intuitive interface and comprehensive features, DoNow assists you in organizing tasks, setting priorities, and achieving your daily goals more efficiently.</p>

    <div class="row">
        <!-- Team Member 1 -->
        <div class="col-md-6 team-member">
            <img src="image/nadhs.png" alt="Nadhirah Danish Ammara">
            <h5>Nadhirah Danish Ammara</h5>
        </div>
        
        <!-- Team Member 2 -->
        <div class="col-md-6 team-member">
            <img src="image/aisyaa.png" alt="Aisya Adiyan">
            <h5>Aisya Adiyan</h5>
        </div>
    </div>
    
    <div class="row">
        <!-- Team Member 3 -->
        <div class="col-md-6 team-member">
            <img src="image/liaa.png" alt="Vanessa Audrelia Christianto">
            <h5>Vanessa Audrelia Christianto</h5>
        </div>
        
        <!-- Team Member 4 -->
        <div class="col-md-6 team-member">
            <img src="image/aryaa.png" alt="Aryabell Boston Tjugito">
            <h5>Aryabell Boston Tjugito</h5>
        </div>
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
