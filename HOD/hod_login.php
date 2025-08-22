<?php
session_start();

// Ensure there is no output before this header function is called.
// This handles the form submission and redirects the user.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Load the HODs data from a JSON file
    $hods = [];
    if (file_exists('hods.json')) {
        $hods = json_decode(file_get_contents('hods.json'), true);
    }

    $authenticated = false;
    foreach ($hods as $hod) {
        if ($hod['email'] === $email && $hod['password'] === $password) {
            $authenticated = true;
            $_SESSION['user_id'] = $hod['user_id'];
            $_SESSION['full_name'] = $hod['full_name'];
            $_SESSION['email'] = $hod['email'];
            $_SESSION['role'] = $hod['role'];
            $_SESSION['department'] = $hod['department'];

            header("Location: hod_dashboard.php");
            exit();
        }
    }

    if (!$authenticated) {
        header("Location: hod_login.php?error=Invalid email or password.");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e9ecef; }
        .login-container { max-width: 400px; margin-top: 100px; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card login-container p-4">
                    <h2 class="card-title text-center mb-4">HOD Login</h2>
                    <?php 
                    if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>
                    <form action="hod_login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
