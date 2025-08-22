<?php
session_start();

// MOCK AUTHENTICATION
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Unauthorized access.");
    exit();
}

$student_data = null;
if (isset($_GET['email'])) {
    $email = htmlspecialchars($_GET['email']);

    // Load data from our new central JSON file
    $data = [];
    if (file_exists('applications.json')) {
        $data = json_decode(file_get_contents('applications.json'), true);
        if (isset($data['students'][$email])) {
            $student_data = $data['students'][$email];
        }
    }
}

if (!$student_data) {
    header("Location: hr_dashboard.php?message=" . urlencode("Student application not found."));
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Application Details</a>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="hr_dashboard.php">Back to Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Application for <?php echo htmlspecialchars($student_data['profile_data']['fullName']); ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Full Name</h6>
                        <p><?php echo htmlspecialchars($student_data['profile_data']['fullName']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>University ID</h6>
                        <p><?php echo htmlspecialchars($student_data['profile_data']['universityID']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Program</h6>
                        <p><?php echo htmlspecialchars($student_data['profile_data']['program']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Email</h6>
                        <p><?php echo htmlspecialchars($student_data['profile_data']['email']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Application Status</h6>
                        <p>
                            <?php if ($student_data['application_status'] === 'Accepted'): ?>
                                <span class="badge bg-success">Accepted</span>
                            <?php elseif ($student_data['application_status'] === 'Rejected'): ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Under Review</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Field Letter</h6>
                        <?php if (!empty($student_data['field_letter_path'])): ?>
                            <a href="<?php echo htmlspecialchars($student_data['field_letter_path']); ?>" class="btn btn-sm btn-secondary" target="_blank"><i class="fas fa-file-pdf"></i> View Field Letter</a>
                        <?php else: ?>
                            <p class="text-muted">No letter submitted yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="hr_dashboard.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
