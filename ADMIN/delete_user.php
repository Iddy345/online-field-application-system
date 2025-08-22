<?php
session_start();
// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.html?error=Access denied.");
    exit();
}

$userEmail = $_GET['email'] ?? '';

if (empty($userEmail)) {
    header("Location: manage_users.php?error=User email not specified for deletion.");
    exit();
}

$users = json_decode(file_get_contents('users.json'), true);

if (!isset($users[$userEmail])) {
    header("Location: manage_users.php?error=User to delete not found.");
    exit();
}

// Remove the user from the array
unset($users[$userEmail]);

// Save the updated array back to the JSON file
if (file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT))) {
    header("Location: manage_users.php?success=User deleted successfully.");
} else {
    header("Location: manage_users.php?error=Failed to delete user.");
}

exit();
?>
