<?php
// CRITICAL: Ensure this is the very first line of the file.
// There should be no spaces or blank lines before this tag.
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    $users = [];
    if (file_exists('users.json')) {
        $users = json_decode(file_get_contents('users.json'), true);
    }

    // Check if the user exists and is an admin
    if (isset($users[$email]) && $users[$email]['password'] === $password && $users[$email]['role'] === 'admin') {
        // Login successful, set session variables
        $_SESSION['user_id'] = $email;
        $_SESSION['role'] = 'admin';
        
        // Redirect to the dashboard
        header("Location: admin_dashboard.php");
        exit(); // CRITICAL: Immediately stop script execution after redirection
    } else {
        // Login failed
        header("Location: admin_login.html?error=Invalid email or password.");
        exit(); // CRITICAL: Immediately stop script execution after redirection
    }
}
?>
