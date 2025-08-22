<?php
session_start();

// Check if the user is logged in and is an HOD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hod') {
    header("Location: hod_login.php?error=Access denied.");
    exit();
}

$departments = [];
if (file_exists('departments.json')) {
    $departments = json_decode(file_get_contents('departments.json'), true);
}

// Get the current quota for the HOD's department
$currentQuota = $departments[$_SESSION['department']]['quota'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Quota</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .quota-card { max-width: 500px; margin: 50px auto; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="hod_dashboard.php">HOD Dashboard</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="quota-card card">
            <h2 class="text-center mb-4">Set Quota for <?php echo htmlspecialchars($_SESSION['department']); ?></h2>
            <p class="text-center">Current Quota: <strong><?php echo $currentQuota; ?></strong> students</p>
            <form action="update_quota.php" method="POST">
                <div class="mb-3">
                    <label for="quota" class="form-label">New Quota</label>
                    <input type="number" class="form-control" id="quota" name="quota" value="<?php echo $currentQuota; ?>" min="0" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Quota</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
