<?php
session_start();

// Check if the user is logged in and is an HOD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hod') {
    header("Location: hod_login.php?error=Access denied.");
    exit();
}

$departmentName = $_SESSION['department'];
$students = [];
if (file_exists('students.json')) {
    $students = json_decode(file_get_contents('students.json'), true);
}

// Filter students who are assigned to this HOD's department AND have an 'accepted' status
$assignedStudents = [];
foreach ($students as $student_data) {
    if (($student_data['assigned_department'] ?? '') === $departmentName && ($student_data['status'] ?? '') === 'accepted') {
        $assignedStudents[] = $student_data;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assigned Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 20px; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
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
        <div class="card p-4">
            <h2 class="card-title text-center mb-4">Assigned Students for <?php echo htmlspecialchars($departmentName); ?></h2>
            <?php if (count($assignedStudents) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignedStudents as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['full_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($student['mobile_number'] ?? 'N/A'); ?></td>
                                    <td><span class="badge bg-success"><?php echo htmlspecialchars(ucfirst($student['status'] ?? 'N/A')); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle"></i> No students have been assigned to your department yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
