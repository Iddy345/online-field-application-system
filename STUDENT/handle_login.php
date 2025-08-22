<?php
session_start();

$users = [];
// Check if the users data file exists
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim the email input
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // Check if a user with this email exists in the data
    if (isset($users[$email])) {
        $user = $users[$email];
        // Verify the provided password against the stored hash
        if (password_verify($password, $user['password_hash'])) {
            // If password is correct, set the user's session ID
            $_SESSION['user_id'] = $email;
            
            // Check for a completed profile by looking for a non-empty full name
            $profile_completed = false;
            if (isset($user['profile_data']) && isset($user['profile_data']['fullName']) && !empty($user['profile_data']['fullName'])) {
                $profile_completed = true;
            }

            if ($profile_completed) {
                // If profile is complete, redirect to the field letter page
                header("Location: field_letter.php");
            } else {
                // If profile is not complete, redirect to the profile form page
                header("Location: profile.php");
            }
            // It's crucial to call exit() after a header redirect
            exit();
        }
    }

    // If the login failed (invalid email or password), redirect back to the login page with an error
    header("Location: login.html?error=Invalid email or password.");
    exit();
} else {
    // If the page is accessed directly without a POST request, deny access
    echo "Access denied.";
}
?>
