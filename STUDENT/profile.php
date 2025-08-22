<?php
session_start();
// Check if the user is logged in. If not, redirect them to the login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Please login to complete your profile.");
    exit();
}

$user_id = $_SESSION['user_id'];
$users = [];
$profile_data = [];

// Load user data from the JSON file
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
    // Check if the current user exists and has profile data
    if (isset($users[$user_id]) && isset($users[$user_id]['profile_data'])) {
        $profile_data = $users[$user_id]['profile_data'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Complete Your Profile</h3>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" action="handle_profile.php" method="POST">
                            <h4 class="mb-3">Personal Profile</h4>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" required value="<?php echo htmlspecialchars(isset($profile_data['fullName']) ? $profile_data['fullName'] : ''); ?>">
                                <div class="invalid-feedback">
                                    Please enter your full name.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="age" name="age" min="16" max="99" required value="<?php echo htmlspecialchars(isset($profile_data['age']) ? $profile_data['age'] : ''); ?>">
                                <div class="invalid-feedback">
                                    Please enter a valid age (e.g., between 16 and 99).
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" required value="<?php echo htmlspecialchars(isset($profile_data['nationality']) ? $profile_data['nationality'] : ''); ?>">
                                <div class="invalid-feedback">
                                    Please enter your nationality.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select your gender</option>
                                    <option value="Male" <?php echo (isset($profile_data['gender']) && $profile_data['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (isset($profile_data['gender']) && $profile_data['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select your gender.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mobileNumber" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" pattern="[0-9]{10}" required value="<?php echo htmlspecialchars(isset($profile_data['mobileNumber']) ? $profile_data['mobileNumber'] : ''); ?>">
                                <div class="invalid-feedback">
                                    Please enter a 10-digit mobile number.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars(isset($profile_data['address']) ? $profile_data['address'] : ''); ?></textarea>
                                <div class="invalid-feedback">
                                    Please enter your address.
                                </div>
                            </div>

                            <h4 class="mt-4 mb-3">University Details</h4>
                            <div class="mb-3">
                                <label for="university" class="form-label">University Name</label>
                                <input type="text" class="form-control" id="university" name="university" required value="<?php echo htmlspecialchars(isset($profile_data['university']) ? $profile_data['university'] : ''); ?>">
                                <div class="invalid-feedback">
                                    Please enter your university name.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="program" class="form-label">Program/Course</label>
                                <input type="text" class="form-control" id="program" name="program" required value="<?php echo htmlspecialchars(isset($profile_data['program']) ? $profile_data['program'] : ''); ?>">
                                <div class="invalid-feedback">
                                    Please enter your program/course.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="universityID" class="form-label">University ID</label>
                                <input type="text" class="form-control" id="universityID" name="universityID" required value="<?php echo htmlspecialchars(isset($profile_data['universityID']) ? $profile_data['universityID'] : ''); ?>">
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
