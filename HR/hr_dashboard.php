<?php
session_start();

// Simplified HR user login check
// In a real system, this would be a more robust authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: hr_login.html?error=Access denied.");
    exit();
}

$applications = [];
if (file_exists('applications.json')) {
    $applications = json_decode(file_get_contents('applications.json'), true);
}

// Filter applications that are pending HR review (status is 'pending')
$pendingApplications = array_filter($applications, function($app) {
    return ($app['status'] ?? '') === 'pending';
});

// Load departments for the dropdown
$departments = [];
if (file_exists('departments.json')) {
    $departments = json_decode(file_get_contents('departments.json'), true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 20px; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .table thead th { background-color: #e9ecef; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">HR Dashboard</a>
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
        <div class="card p-4">
            <h2 class="card-title text-center mb-4">Pending Applications for HR Review</h2>
            <?php if (!empty($pendingApplications)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Submitted On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingApplications as $app_id => $app): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($app['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($app['submitted_at']); ?></td>
                                    <td>
                                        <form action="hr_process_application.php" method="POST" class="d-flex">
                                            <input type="hidden" name="app_id" value="<?php echo htmlspecialchars($app_id); ?>">
                                            <select name="department" class="form-select me-2" required>
                                                <option value="">Assign to Department</option>
                                                <?php foreach ($departments as $dept_name => $dept_data): ?>
                                                    <option value="<?php echo htmlspecialchars($dept_name); ?>"><?php echo htmlspecialchars($dept_name); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Assign</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle"></i> No pending applications to review.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
