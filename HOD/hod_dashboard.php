<?php
session_start();

// Check if the user is logged in and is an HOD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hod') {
    header("Location: hod_login.php?error=Access denied.");
    exit();
}

// Load department data to show the current quota
$departments = [];
if (file_exists('departments.json')) {
    $departments = json_decode(file_get_contents('departments.json'), true);
}

// Get the quota for the logged-in HOD's department
$currentQuota = $departments[$_SESSION['department']]['quota'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .jumbotron { padding: 4rem 2rem; margin-bottom: 2rem; background-color: #e9ecef; border-radius: .3rem; }
        .card-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="hod_dashboard.php">HOD Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-light" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1>
            <p class="lead">This is your department dashboard. You are the Head of the <strong><?php echo htmlspecialchars($_SESSION['department']); ?></strong> Department.</p>
            <hr class="my-4">
            <p>From here, you can set the student intake quota and view students assigned to your department. You can also review new applications.</p>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="set_quota.php" class="btn btn-primary me-2"><i class="fas fa-edit"></i> Set Student Quota</a>
                <a href="view_assigned_students.php" class="btn btn-info me-2"><i class="fas fa-users"></i> View Assigned Students</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
