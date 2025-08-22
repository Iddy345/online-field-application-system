<?php
session_start();
// Check if the user is an admin and the request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST" || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.html?error=Access denied.");
    exit();
}

$originalEmail = $_POST['original_email'] ?? '';
$fullName = htmlspecialchars(trim($_POST['fullName']));
$mobileNumber = htmlspecialchars(trim($_POST['mobileNumber']));
$email = htmlspecialchars(trim($_POST['email']));
$role = htmlspecialchars(trim($_POST['role']));
$department = ($role === 'hod') ? htmlspecialchars(trim($_POST['department'])) : '';

if (empty($originalEmail) || empty($fullName) || empty($mobileNumber) || empty($email) || empty($role)) {
    header("Location: manage_users.php?error=Missing required data.");
    exit();
}

$users = json_decode(file_get_contents('users.json'), true);

if (!isset($users[$originalEmail])) {
    header("Location: manage_users.php?error=User to update not found.");
    exit();
}

// Remove the old entry
unset($users[$originalEmail]);

// Add the new or updated entry
$users[$email] = [
    'full_name' => $fullName,
    'mobile_number' => $mobileNumber,
    'password' => $users[$originalEmail]['password'] ?? '', // Preserve the old password
    'role' => $role,
    'department' => $department
];

if (file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT))) {
    header("Location: manage_users.php?success=User updated successfully.");
} else {
    header("Location: manage_users.php?error=Failed to save user data.");
}

exit();
?>
