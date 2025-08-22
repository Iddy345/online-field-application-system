<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Please login to save your profile.");
    exit();
}

$user_id = $_SESSION['user_id'];
$users = [];
$success = false;

// Check if the users data file exists
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim all input data
    $profile_data = [
        'fullName' => htmlspecialchars(trim($_POST['fullName'])),
        'age' => htmlspecialchars(trim($_POST['age'])),
        'nationality' => htmlspecialchars(trim($_POST['nationality'])),
        'gender' => htmlspecialchars(trim($_POST['gender'])),
        'mobileNumber' => htmlspecialchars(trim($_POST['mobileNumber'])),
        'address' => htmlspecialchars(trim($_POST['address'])),
        'university' => htmlspecialchars(trim($_POST['university'])),
        'program' => htmlspecialchars(trim($_POST['program'])),
        'universityID' => htmlspecialchars(trim($_POST['universityID']))
    ];

    // Add the user's email from the session to the profile data
    $profile_data['email'] = $user_id;

    // Check if the user exists in the data
    if (isset($users[$user_id])) {
        // Update the user's profile data
        $users[$user_id]['profile_data'] = $profile_data;
        $success = true;
    } else {
        // This case should not be reached if the session check is working, but it's a good fallback
        $success = false;
    }

    // Save the updated user data back to the JSON file
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
}

// Redirect the user to the field letter upload page after a successful profile save
if ($success) {
    header("Location: field_letter_upload.php");
    exit();
} else {
    // Redirect back to the profile page with an error if something went wrong
    header("Location: profile.php?error=Profile save failed. Please try again.");
    exit();
}
?>
