<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || $_SESSION['role'] !== 'hod') {
    header("Location: hod_login.php?error=Access denied.");
    exit();
}

$newQuota = $_POST['quota'] ?? null;
$departmentName = $_SESSION['department'];

if (!is_numeric($newQuota) || $newQuota < 0) {
    header("Location: hod_dashboard.php?error=Invalid quota value.");
    exit();
}

// Load existing departments data or create a new array
$departments = [];
if (file_exists('departments.json')) {
    $departments = json_decode(file_get_contents('departments.json'), true);
}

// Update the quota for the HOD's department
// Keep existing data, or initialize if department is new
$departments[$departmentName] = $departments[$departmentName] ?? ['quota' => 0, 'assigned_students' => []];
$departments[$departmentName]['quota'] = (int)$newQuota;

// Save the updated data
if (file_put_contents('departments.json', json_encode($departments, JSON_PRETTY_PRINT))) {
    header("Location: hod_dashboard.php?success=Quota for " . urlencode($departmentName) . " updated successfully.");
} else {
    header("Location: hod_dashboard.php?error=Failed to save quota data.");
}
exit();
?>
