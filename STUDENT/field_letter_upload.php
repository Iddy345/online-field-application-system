<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Please log in to submit your letter.");
    exit();
}

$user_id = $_SESSION['user_id'];
$users = [];

// Load user data to check for profile completion
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
    // Redirect to profile page if profile is not complete
    if (!isset($users[$user_id]['profile_data']['fullName']) || empty($users[$user_id]['profile_data']['fullName'])) {
        header("Location: profile.php?error=Please complete your profile first.");
        exit();
    }
} else {
    // If users.json doesn't exist, something is wrong, redirect to login
    header("Location: login.html?error=System error, please try again.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Letter Submission</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card text-center">
            <div class="card-header">
                <h3>Submit Your Field Letter</h3>
            </div>
            <div class="card-body">
                <p>Please upload your field letter as a PDF document.</p>
                <form action="handle_letter.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="pdfFile" class="form-label">Select PDF File</label>
                        <input class="form-control" type="file" id="pdfFile" name="pdfFile" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload Letter</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>
