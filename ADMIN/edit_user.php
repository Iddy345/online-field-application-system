<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.html?error=Access denied.");
    exit();
}

$userEmail = $_GET['email'] ?? '';

if (empty($userEmail)) {
    header("Location: manage_users.php?error=User email not specified.");
    exit();
}

$users = json_decode(file_get_contents('users.json'), true);
$userToEdit = $users[$userEmail] ?? null;

if (!$userToEdit) {
    header("Location: manage_users.php?error=User not found.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="col-md-8 mx-auto">
            <div class="card p-4">
                <h2 class="card-title text-center mb-4">Edit User: <?php echo htmlspecialchars($userToEdit['full_name']); ?></h2>
                <form action="update_user.php" method="POST">
                    <input type="hidden" name="original_email" value="<?php echo htmlspecialchars($userEmail); ?>">
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($userToEdit['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobileNumber" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" value="<?php echo htmlspecialchars($userToEdit['mobile_number']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="hr" <?php echo ($userToEdit['role'] === 'hr') ? 'selected' : ''; ?>>HR</option>
                            <option value="hod" <?php echo ($userToEdit['role'] === 'hod') ? 'selected' : ''; ?>>HOD</option>
                        </select>
                    </div>
                    <div class="mb-3" id="department-field">
                        <label for="department" class="form-label">Department (for HODs)</label>
                        <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($userToEdit['department'] ?? ''); ?>">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const departmentField = document.getElementById('department-field');
            
            // Set initial visibility
            if (roleSelect.value === 'hod') {
                departmentField.style.display = 'block';
                departmentField.querySelector('input').required = true;
            } else {
                departmentField.style.display = 'none';
                departmentField.querySelector('input').required = false;
            }

            roleSelect.addEventListener('change', function() {
                if (this.value === 'hod') {
                    departmentField.style.display = 'block';
                    departmentField.querySelector('input').required = true;
                } else {
                    departmentField.style.display = 'none';
                    departmentField.querySelector('input').required = false;
                }
            });
        });
    </script>
</body>
</html>
