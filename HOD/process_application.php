<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || $_SESSION['role'] !== 'hod') {
    header("Location: hod_login.php?error=Access denied.");
    exit();
}

$app_id = $_POST['app_id'] ?? null;
$action = $_POST['action'] ?? null; // 'approve' or 'reject'
$hod_user_id = $_SESSION['user_id'];
$departmentName = $_SESSION['department'];

if (!$app_id || !in_array($action, ['approve', 'reject'])) {
    header("Location: review_applications.php?error=Invalid action or application ID.");
    exit();
}

$applications = [];
if (file_exists('applications.json')) {
    $applications = json_decode(file_get_contents('applications.json'), true);
}

// Ensure the application exists and belongs to the HOD's department and is pending
if (!isset($applications[$app_id]) || $applications[$app_id]['department'] !== $departmentName || $applications[$app_id]['status'] !== 'pending') {
    header("Location: review_applications.php?error=Application not found, not for your department, or already processed.");
    exit();
}

// Update application status
$applications[$app_id]['status'] = ($action === 'approve') ? 'hod_approved' : 'rejected';
$applications[$app_id]['hod_action_date'] = date('Y-m-d H:i:s');
$applications[$app_id]['hod_comments'] = 'Actioned by ' . $_SESSION['full_name'] . ' (' . ucfirst($action) . ')';


// Optionally, record this action in an 'approvals' system if a separate log is needed
// For simplicity, we'll just update the application status directly.
// In a more complex system, you'd add to an 'approvals.json' as per the PDF[cite: 140].

if (file_put_contents('applications.json', json_encode($applications, JSON_PRETTY_PRINT))) {
    header("Location: review_applications.php?success=Application " . htmlspecialchars($app_id) . " " . htmlspecialchars($action) . " successfully.");
} else {
    header("Location: review_applications.php?error=Failed to process application.");
}
exit();