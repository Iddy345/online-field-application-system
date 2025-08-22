<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || $_SESSION['role'] !== 'hr') {
    header("Location: hr_login.html?error=Access denied.");
    exit();
}

$app_id = $_POST['app_id'] ?? null;
$department = $_POST['department'] ?? null;

if (!$app_id || !$department) {
    header("Location: hr_dashboard.php?error=Invalid action or application ID.");
    exit();
}

$applications = [];
if (file_exists('applications.json')) {
    $applications = json_decode(file_get_contents('applications.json'), true);
}

// Check if the application exists and is still pending
if (!isset($applications[$app_id]) || $applications[$app_id]['status'] !== 'pending') {
    header("Location: hr_dashboard.php?error=Application not found or already processed.");
    exit();
}

// Update the application status and assign the department
$applications[$app_id]['status'] = 'hr_assigned';
$applications[$app_id]['department'] = $department;
$applications[$app_id]['hr_action_date'] = date('Y-m-d H:i:s');
$applications[$app_id]['hr_comments'] = 'Assigned to ' . $department . ' by HR.';

// Save the updated data
if (file_put_contents('applications.json', json_encode($applications, JSON_PRETTY_PRINT))) {
    header("Location: hr_dashboard.php?success=Application assigned to " . urlencode($department) . " successfully.");
    exit();
} else {
    header("Location: hr_dashboard.php?error=Failed to save application data.");
    exit();
}
?>
