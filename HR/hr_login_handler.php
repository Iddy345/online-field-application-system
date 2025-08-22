<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Load HRs data from a JSON file
    $hrs = [];
    if (file_exists('hrs.json')) {
        $hrs = json_decode(file_get_contents('hrs.json'), true);
    }

    $authenticated = false;
    foreach ($hrs as $hr) {
        if ($hr['email'] === $email && $hr['password'] === $password) {
            $authenticated = true;
            $_SESSION['user_id'] = $hr['user_id'];
            $_SESSION['full_name'] = $hr['full_name'];
            $_SESSION['email'] = $hr['email'];
            $_SESSION['role'] = $hr['role'];

            header("Location: hr_dashboard.php");
            exit();
        }
    }

    if (!$authenticated) {
        header("Location: hr_login.html?error=Invalid email or password.");
        exit();
    }
}
?>
