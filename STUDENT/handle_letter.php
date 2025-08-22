<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Please log in to submit your letter.");
    exit();
}

$user_id = $_SESSION['user_id'];
$upload_dir = 'uploads/';
$file_name = $_FILES['pdfFile']['name'];
$file_tmp = $_FILES['pdfFile']['tmp_name'];
$file_type = $_FILES['pdfFile']['type'];
$file_size = $_FILES['pdfFile']['size'];
$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
$errors = array();

// Create the uploads directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Check for file errors
if ($file_ext != "pdf") {
    $errors[] = "File must be a PDF.";
}
if ($file_size > 2097152) { // 2 MB
    $errors[] = 'File size must be less than 2 MB';
}

if (empty($errors)) {
    // Generate a unique file name to prevent conflicts
    $final_file_name = $user_id . '_' . uniqid() . '.' . $file_ext;
    $destination = $upload_dir . $final_file_name;

    if (move_uploaded_file($file_tmp, $destination)) {
        // Update user's application status
        $users = [];
        if (file_exists('users.json')) {
            $users = json_decode(file_get_contents('users.json'), true);
        }

        if (isset($users[$user_id])) {
            $users[$user_id]['application_status'] = 'Submitted';
            $users[$user_id]['field_letter_path'] = $destination;
            file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
        }

        // Redirect to a confirmation page or status page
        header("Location: application_status.php?message=Letter submitted successfully!");
        exit();
    } else {
        header("Location: application_status.php?error=File upload failed.");
        exit();
    }
} else {
    // If there were errors, redirect back with the error message
    $error_message = implode(" ", $errors);
    header("Location: application_status.php?error=" . urlencode($error_message));
    exit();
}
?>
