<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Please log in to view your application status.");
    exit();
}

$user_id = $_SESSION['user_id'];
$students = [];
$application_status = "Pending Submission"; // Default status

// Load user data from students.json
if (file_exists('students.json')) {
    $students = json_decode(file_get_contents('students.json'), true);
    if (isset($students[$user_id])) {
        // Use the status from our HR system
        $application_status = htmlspecialchars($students[$user_id]['status']);
    }
}

$fullName = isset($students[$user_id]['full_name']) ? $students[$user_id]['full_name'] : $user_id;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }
        .navbar-sm .nav-link,
        .navbar-sm .navbar-brand {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-size: 1rem;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top navbar-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Welcome, <?php echo htmlspecialchars($fullName); ?>!</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">View Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="application_status.php">Application Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card text-center">
            <div class="card-header">
                <h3>Your Application Status</h3>
            </div>
            <div class="card-body">
                <p class="fs-4">Current Status: 
                    <span class="badge 
                        <?php 
                            if ($application_status === 'accepted') {
                                echo 'bg-success';
                            } elseif ($application_status === 'on-waiting-list') {
                                echo 'bg-info';
                            } elseif ($application_status === 'rejected') {
                                echo 'bg-danger';
                            } else {
                                echo 'bg-warning text-dark';
                            }
                        ?>">
                        <?php echo htmlspecialchars(ucfirst($application_status)); ?>
                    </span>
                </p>
                <hr>

                <?php if ($application_status === 'accepted'): ?>
                    <p class="text-success">
                        <i class="fas fa-check-circle"></i> Congratulations! Your application has been accepted. You can now download your official field placement letter.
                    </p>
                    <a href="generate_letter.php" class="btn btn-success mt-3">
                        <i class="fas fa-download"></i> Download Placement Letter
                    </a>
                <?php elseif ($application_status === 'on-waiting-list'): ?>
                    <p class="text-info">
                        <i class="fas fa-info-circle"></i> Your application has been approved, but you are currently on the waiting list.
                    </p>
                <?php elseif ($application_status === 'rejected'): ?>
                    <p class="text-danger">
                        <i class="fas fa-times-circle"></i> We regret to inform you that your application was not successful at this time.
                    </p>
                <?php else: ?>
                    <p class="text-warning">
                        <i class="fas fa-hourglass-half"></i> Your application is currently under review. Please check back later for updates.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
