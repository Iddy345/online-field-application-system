<?php
session_start();

// This is a simple data store for demonstration purposes.
// In a real application, you would use a database.
$users = [];
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Server-side validation
    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check if user already exists
    if (isset($users[$email])) {
        $errors[] = "An account with this email already exists. Please log in.";
    }

    if (count($errors) === 0) {
        // Create a new user record
        $users[$email] = [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'profile_completed' => false,
            'profile_data' => []
        ];

        // Save the updated users array to the JSON file
        file_put_contents('users.json', json_encode($users));

        // Redirect to the login page with a success message
        header("Location: login.html?success=Registration successful! Please log in.");
        exit();

    } else {
        echo "<h4>Registration Failed. Please fix the following errors:</h4>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
    }
} else {
    echo "Access denied.";
}
?>
