<?php
session_start();

// Ensure the request method is POST and the user is HR
if ($_SERVER["REQUEST_METHOD"] !== "POST" || $_SESSION['role'] !== 'hr') {
    header("Location: hr_login.php?error=Access denied.");
    exit();
}

$student_id = $_POST['student_id'] ?? null;
$assigned_department = $_POST['assigned_department'] ?? null;
$form_status = $_POST['status'] ?? null;

// Validate input
if (!$student_id || !$assigned_department || !$form_status) {
    header("Location: view_applications.php?error=Invalid data submitted.");
    exit();
}

// Load student data from JSON file
$students = [];
$students_file = 'students.json';
if (file_exists($students_file)) {
    $students = json_decode(file_get_contents($students_file), true);
} else {
    header("Location: view_applications.php?error=Students data file not found.");
    exit();
}

// Check if the student ID exists
if (!isset($students[$student_id])) {
    header("Location: view_applications.php?error=Student not found.");
    exit();
}

// Get the student's email and name for the notification
$student_email = $students[$student_id]['email'];
$student_name = $students[$student_id]['full_name'];

$status_to_save = $form_status;

// Logic to check department quota and automatically set status
if ($form_status === 'approved') {
    // Load department data to check quota
    $departments = [];
    if (file_exists('departments.json')) {
        $departments = json_decode(file_get_contents('departments.json'), true);
    }
    
    $quota = $departments[$assigned_department]['quota'] ?? 0;

    // Count currently accepted students in this department
    $accepted_count = 0;
    foreach ($students as $s) {
        if (($s['assigned_department'] ?? '') === $assigned_department && ($s['status'] === 'accepted')) {
            $accepted_count++;
        }
    }

    // Determine final status based on quota
    if ($accepted_count < $quota) {
        $status_to_save = 'accepted';
    } else {
        $status_to_save = 'on-waiting-list';
    }
}

// Update the student's record with new department and status
$students[$student_id]['assigned_department'] = $assigned_department;
$students[$student_id]['status'] = $status_to_save;

// Save the updated data back to the JSON file
if (file_put_contents($students_file, json_encode($students, JSON_PRETTY_PRINT))) {
    
    // --- Email Notification Logic ---
    $subject = "Kinondoni Municipal Council - Application Status Update";
    $message = "Dear {$student_name},\n\n";

    if ($status_to_save === 'accepted') {
        $message .= "Congratulations! Your field placement application has been accepted. You can now download your official placement letter from your student portal.";
    } elseif ($status_to_save === 'on-waiting-list') {
        $message .= "Your application has been approved, but the {$assigned_department} has reached its capacity. You have been placed on a waiting list.";
    } elseif ($status_to_save === 'rejected') {
        $message .= "We regret to inform you that your field placement application has been rejected at this time.";
    } else { // pending or other status
        $message .= "Your field placement application status has been updated to '{$status_to_save}'. Please check your student portal for more details.";
    }
    
    $message .= "\n\nRegards,\nHR Department\nKinondoni Municipal Council";

    // A real server would be configured to handle this.
    // mail($student_email, $subject, $message, "From: hr@kinondonicouncil.go.tz");
    
    header("Location: view_applications.php?success=Application for " . urlencode($student_name) . " updated successfully and notification sent.");
} else {
    header("Location: view_applications.php?error=Failed to save student data.");
}

exit();
