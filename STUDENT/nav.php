<?php
$email = $_SESSION['user_id'];
$users = json_decode(file_get_contents('users.json'), true);
$user = $users[$email];
$fullName = isset($user['profile_data']['fullName']) ? $user['profile_data']['fullName'] : $email;
?>
<style>
.navbar-sm .nav-link,
.navbar-sm .navbar-brand {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
    font-size: 0.85rem;
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top navbar-sm">
    <div class="container">
        <a class="navbar-brand" href="#">Welcome, <?php echo htmlspecialchars($fullName); ?>!</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">View Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="field_letter.php">Field Letter</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
