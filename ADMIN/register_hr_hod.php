<?php
session_start();

// Ensure the request method is POST and the user is an admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Collect and sanitize form data
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $mobileNumber = htmlspecialchars(trim($_POST['mobileNumber']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password']; // In a real application, you would hash this password
    $role = htmlspecialchars(trim($_POST['role']));
    $department = ($role === 'hod') ? htmlspecialchars(trim($_POST['department'])) : '';

    // Simple validation
    if (empty($fullName) || empty($mobileNumber) || empty($email) || empty($password) || empty($role)) {
        header("Location: admin_dashboard.php?error=All fields are required.");
        exit();
    }
    
    // Load existing users from the JSON file
    $users = [];
    if (file_exists('users.json')) {
        $users = json_decode(file_get_contents('users.json'), true);
    }

    // Check if the email already exists
    if (isset($users[$email])) {
        header("Location: admin_dashboard.php?error=Email already registered.");
        exit();
    }

    // Prepare the new user data
    $newUser = [
        'full_name' => $fullName,
        'mobile_number' => $mobileNumber,
        'password' => $password, // WARNING: Not secure. Use password_hash() in a production environment.
        'role' => $role
    ];

    if ($role === 'hod') {
        $newUser['department'] = $department;
    }

    // Add the new user to the array
    $users[$email] = $newUser;
    
    // Save the updated users array back to the JSON file
    if (file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT))) {
        // Redirect with a success message
        header("Location: admin_dashboard.php?success=User registered successfully.");
    } else {
        // Redirect with a file write error
        header("Location: admin_dashboard.php?error=Could not save user data.");
    }

    exit();

} else {
    // If not an admin or not a POST request, redirect to login
    header("Location: admin_login.html?error=Access denied.");
    exit();
}
?>
