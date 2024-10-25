<?php
session_start();
require 'config.php'; // Koneksi ke database

// Jika user belum login, redirect ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah list_id ada di URL
if (!isset($_GET['list_id'])) {
    die("Error: list_id tidak ditemukan.");
}

$list_id = $_GET['list_id'];

// Ambil data list berdasarkan user yang login
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM todo_lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$list = $stmt->get_result()->fetch_assoc();

// Cek apakah list ada
if (!$list) {
    die("Error: To-Do List tidak ditemukan atau Anda tidak memiliki akses.");
}

// Proses filter dan pencarian task
$status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'all';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Hapus karakter '%' dari search term
$search_term = str_replace('%', '', $search_term);

// Query untuk mengambil tasks sesuai dengan filter dan pencarian
$query = "SELECT * FROM tasks WHERE todo_list_id = ?";

// Tambahkan kondisi untuk filter status
if ($status_filter == 'completed') {
    $query .= " AND status = 'completed'";
} elseif ($status_filter == 'incomplete') {
    $query .= " AND status = 'incomplete'";
}

// Tambahkan kondisi untuk pencarian
if (!empty($search_term)) {
    $query .= " AND description LIKE ?";
    $search_term = "%" . $search_term . "%";
}

// Urutkan task berdasarkan priority, status, dan ID
$query .= " ORDER BY 
    CASE priority
        WHEN 'high' THEN 1
        WHEN 'medium' THEN 2
        WHEN 'low' THEN 3
    END, 
    status ASC, 
    id DESC";

// Lanjutkan dengan eksekusi perintah dan penarikan hasil
$stmt = $conn->prepare($query);
if (!empty($search_term)) {
    $stmt->bind_param("is", $list_id, $search_term);
} else {
    $stmt->bind_param("i", $list_id);
}
$stmt->execute();
$tasks = $stmt->get_result();

// Cek jika tidak ada task yang ada untuk menentukan apakah menampilkan form untuk task pertama
$first_task_added = $tasks->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fdfbfb 100%, #f6d365 100%, #fda085 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .container {
            margin-top: 30px;
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #ff6f61;
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
        .btn-primary {
            background-color: #f6d365;
            border: none;
        }
        .btn-danger {
            background-color: #ff9a9e;
            border: none;
        }
        .table {
            border: none;
        }
        .table thead th {
            background-color: #ffd3b6;
            color: #333;
        }
        .table tbody td {
            background-color: #fff3e0;
        }
        .table tbody tr:hover {
            background-color: #ffe0b2;
        }
    </style>
</head>
<body>
    
<div class="container">
    <h2 class="mt-5"><?php echo htmlspecialchars($list['title']); ?></h2>
    <a href="dashboard.php" class="btn btn-secondary mb-4">Back to Dashboard</a>
    
    <!-- Form untuk filter dan pencarian -->
    <form action="view_list.php" method="GET" class="mb-3">
        <input type="hidden" name="list_id" value="<?php echo $list_id; ?>">
        <div class="row">
            <div class="col-md-4">
                <select name="status_filter" class="form-select">
                    <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Tasks</option>
                    <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed Tasks</option>
                    <option value="incomplete" <?php echo $status_filter == 'incomplete' ? 'selected' : ''; ?>>Incomplete Tasks</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search task..." value="<?php echo htmlspecialchars($search_term); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Form untuk menambahkan task pertama, tampil hanya jika belum ada task -->
    <?php if (!$first_task_added): ?>
    <div class="mb-3">
        <h5>Tambahkan Task Pertama</h5>
        <form action="add_task.php" method="POST">
            <input type="hidden" name="list_id" value="<?php echo $list['id']; ?>">
            <div class="input-group">
                <input type="text" name="description" class="form-control" placeholder="Deskripsi Task" required>
                <select name="priority" class="form-select" required>
                    <option value="high">High Priority</option>
                    <option value="medium" selected>Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
                <!-- New date and time inputs for task -->
                <input type="date" name="due_date" class="form-control" required>
                <input type="time" name="due_time" class="form-control" required>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- List tasks berdasarkan hasil pencarian dan filter -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th> <!-- New column for due date -->
                <th>Due Time</th> <!-- New column for due time -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch_assoc()): ?>
            <tr>
                <td>
                    <form action="update_task_status.php" method="POST" class="d-inline">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <input type="checkbox" name="status" value="completed" <?php echo $task['status'] == 'completed' ? 'checked' : ''; ?> onchange="this.form.submit()">
                        <span><?php echo htmlspecialchars($task['description']); ?></span>
                    </form>
                </td>
                <td>
                    <span class="badge bg-<?php echo $task['status'] == 'completed' ? 'success' : 'warning'; ?>">
                        <?php echo ucfirst($task['status']); ?>
                    </span>
                </td>
                <td>
                    <span class="badge bg-<?php echo $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'secondary'); ?>">
                        <?php echo ucfirst($task['priority']); ?>
                    </span>
                </td>
                <!-- Display due date and time -->
                <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                <td><?php echo htmlspecialchars($task['due_time']); ?></td>
                <td>
                
                    <!-- Tombol Edit Task -->
                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editTaskModal<?php echo $task['id']; ?>">Edit</button>
                    <!-- Tombol untuk membuka modal tambah task -->
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add</button>
                </td>
            </tr>

            <!-- Modal untuk menambahkan task baru -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Make a new Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_task.php" method="POST">
                    <input type="hidden" name="list_id" value="<?php echo $list['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description Task</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority Task</label>
                        <select name="priority" class="form-select" required>
                            <option value="high">High Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="due_time" class="form-label">Due Time</label>
                        <input type="time" name="due_time" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Undo</button>
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

            <!-- Modal untuk mengedit task -->
            <div class="modal fade" id="editTaskModal<?php echo $task['id']; ?>" tabindex="-1" aria-labelledby="editTaskModalLabel<?php echo $task['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTaskModalLabel<?php echo $task['id']; ?>">Edit Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="edit_task.php" method="POST">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <input type="hidden" name="list_id" value="<?php echo $list_id; ?>">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi Task</label>
                                    <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($task['description']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Prioritas Task</label>
                                    <select name="priority" class="form-select" required>
                                        <option value="high" <?php echo $task['priority'] == 'high' ? 'selected' : ''; ?>>High Priority</option>
                                        <option value="medium" <?php echo $task['priority'] == 'medium' ? 'selected' : ''; ?>>Medium Priority</option>
                                        <option value="low" <?php echo $task['priority'] == 'low' ? 'selected' : ''; ?>>Low Priority</option>
                                    </select>
                                </div>
                                <!-- New inputs for editing due date and time -->
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" name="due_date" class="form-control" value="<?php echo $task['due_date']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="due_time" class="form-label">Due Time</label>
                                    <input type="time" name="due_time" class="form-control" value="<?php echo $task['due_time']; ?>" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Update Task</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
