<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password']; // Password will be hashed
    $confirmPassword = $_POST['confirmPassword']; // For server-side comparison

    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT); 
    $nationality = htmlspecialchars(trim($_POST['nationality']));
    $mobilenumber=htmlspecialchars(trim($_POST['mobilenumber']));
    $address=htmlspecialchars(trim($_POST['address']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $university = htmlspecialchars(trim($_POST['university']));
    $program = htmlspecialchars(trim($_POST['program']));
    $universityID = htmlspecialchars(trim($_POST['universityID']));

    // Basic server-side validation
    $errors = [];

    // Account Information Validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Personal Profile Validation
    if (empty($fullName)) {
        $errors[] = "Full Name is required.";
    }

    if (empty($age) || !filter_var($age, FILTER_VALIDATE_INT, array("options" => array("min_range"=>16, "max_range"=>99)))) {
        $errors[] = "A valid age between 16 and 99 is required.";
    }

    if (empty($nationality)) {
        $errors[] = "Nationality is required.";
    }

    $allowedGenders = ["Male", "Female", "Other", "Prefer not to say"];
    if (empty($gender) || !in_array($gender, $allowedGenders)) {
        $errors[] = "Please select a valid gender.";
    }
    if (empty($mobilenumber)) {
        $errors[]="please enter the mobile number.";
    }
    if (empty($address)) {
        $errors[]="please enter the address.";
    }
    // University Details Validation
    if (empty($university)) {
        $errors[] = "University Name is required.";
    }

    if (empty($program)) {
        $errors[] = "Program/Course is required.";
    }

    if (empty($universityID)) {
        $errors[] = "University ID is required.";
    }

    if (count($errors) === 0) {
        // --- In a real application, you would do the following: ---
        // 1. Hash the password
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 2. Connect to a database (e.g., MySQLi or PDO)
        // Example (replace with your actual database credentials):
        // $servername = "localhost";
        // $dbusername = "root";
        // $dbpassword = "";
        // $dbname = "your_database_name";

        // try {
        //     $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //     // 3. Prepare and execute an INSERT statement to store all user data
        //     // This is a simplified example; you might have separate tables for users and profiles,
        //     // and link them via foreign keys.
        //     // $stmt = $conn->prepare("INSERT INTO students (email, password, full_name, age, nationality, gender,mobile number,address , university, program, university_id) VALUES (:email, :password, :fullName, :age, :nationality, :gender, :university, :program, :universityID)");
        //     // $stmt->bindParam(':email', $email);
        //     // $stmt->bindParam(':password', $hashedPassword);
        //     // $stmt->bindParam(':fullName', $fullName);
        //     // $stmt->bindParam(':age', $age);
        //     // $stmt->bindParam(':nationality', $nationality);
        //     // $stmt->bindParam(':gender', $gender);
        //     // $stmt->bindParam(':university', $university);
        //     // $stmt->bindParam(':program', $program);
        //     // $stmt->bindParam(':universityID', $universityID);
        //     // $stmt->execute();

        //     echo "Registration and Profile completion successful!"; // Or redirect to a success page
        // } catch(PDOException $e) {
        //     echo "Error: " . $e->getMessage();
        // }
        // -----------------------------------------------------------

        echo "<h4>Registration and Profile Data Received Successfully (Server-Side)</h4>";
        echo "<p><strong>Email:</strong> " . $email . "</p>";
        
        echo "<p><strong>Password (for demonstration, do NOT do this in production!):</strong> " . $password . "</p>";
        echo "<p><strong>Full Name:</strong> " . $fullName . "</p>";
        echo "<p><strong>Age:</strong> " . $age . "</p>";
        echo "<p><strong>Nationality:</strong> " . $nationality . "</p>";
        echo "<p><strong>Gender:</strong> " . $gender . "</p>";
        echo "<p><strong>University:</strong> " . $university . "</p>";
        echo "<p><strong>Program:</strong> " . $program . "</p>";
        echo "<p><strong>University ID:</strong> " . $universityID . "</p>";

    } else {
        
        echo "<h4>Registration and Profile Failed. Please fix the following errors:</h4>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
        echo '<button onclick="history.back()" class="btn btn-secondary">Go Back</button>';
    }
} else {
    
    echo "Access denied. Please submit the form.";
}
?>