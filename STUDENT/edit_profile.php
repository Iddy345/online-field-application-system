<?php
session_start();
// Check if the user is logged in. If not, redirect them to the login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Please login to access this page.");
    exit();
}

$email = $_SESSION['user_id'];
$users = json_decode(file_get_contents('users.json'), true);
$user = $users[$email];

// Get existing profile data or empty strings if it doesn't exist yet
$profileData = isset($user['profile_data']) ? $user['profile_data'] : [
    'fullName' => '', 'age' => '', 'nationality' => '', 'gender' => '',
    'university' => '', 'program' => '', 'universityID' => ''
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h3><?php echo (isset($user['profile_data']) ? "Edit Your Profile" : "Complete Your Profile"); ?></h3>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" action="handle_profile.php" method="POST">
                            <h4 class="mb-3">Personal Profile</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($profileData['fullName']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter your full name.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="age" name="age" min="16" max="99" value="<?php echo htmlspecialchars($profileData['age']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid age (e.g., between 16 and 99).
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="<?php echo htmlspecialchars($profileData['nationality']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter your nationality.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select your gender</option>
                                        <option value="Male" <?php echo ($profileData['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($profileData['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select your gender.
                                    </div>
                                </div>
                            </div>
                            <h4 class="mt-4 mb-3">University Details</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="university" class="form-label">University Name</label>
                                    <input type="text" class="form-control" id="university" name="university" value="<?php echo htmlspecialchars($profileData['university']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter your university name.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="program" class="form-label">Program/Course</label>
                                    <input type="text" class="form-control" id="program" name="program" value="<?php echo htmlspecialchars($profileData['program']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter your program/course.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="universityID" class="form-label">University ID</label>
                                <input type="text" class="form-control" id="universityID" name="universityID" value="<?php echo htmlspecialchars($profileData['universityID']); ?>" required>
                                <div class="invalid-feedback">
                                    Please enter your university ID.
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Save Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <script src="script.js"></script>
</body>
</html>
