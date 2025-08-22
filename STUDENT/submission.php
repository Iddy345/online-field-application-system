<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Please login to access this page.");
    exit();
}

$user_id = $_SESSION['user_id'];
$users = [];
$profile_data = [];

// Load user data to display in the letter template
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
    if (isset($users[$user_id]) && isset($users[$user_id]['profile_data'])) {
        $profile_data = $users[$user_id]['profile_data'];
    }
}

// Generate the placeholder letter content
function generateLetterContent($profile) {
    if (empty($profile)) {
        return "Please complete your profile first.";
    }

    $date = date("F j, Y");
    $content = <<<EOT
[Your Name]
[Your Address]
[Your Phone Number]
[Your Email Address]

$date

[Recipient's Name/Title]
[Recipient's Company/Organization]
[Recipient's Address]

Dear [Recipient's Name/Title],

I am writing to formally request a field placement opportunity with your organization as a student from {$profile['university']}. I am currently enrolled in the {$profile['program']} program, and my student ID is {$profile['universityID']}.

EOT;

    return $content;
}

$letter_content = generateLetterContent($profile_data);
$user_email = $_SESSION['user_id'];
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
        <div class="card">
            <div class="card-header text-center">
                <h3>Submit Your Field Letter</h3>
            </div>
            <div class="card-body">
                <form id="letterForm" action="handle_letter.php" method="POST">
                    <div class="mb-3">
                        <label for="letter_content" class="form-label">Draft of Your Field Letter</label>
                        <textarea class="form-control" id="letter_content" name="letter_content" rows="20" required><?php echo htmlspecialchars($letter_content); ?></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Letter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <script src="script.js"></script>
</body>
</html>
