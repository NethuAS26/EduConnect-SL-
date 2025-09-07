<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php?error=unauthorized');
    exit();
}

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: courses.php?error=invalid_method');
    exit();
}

// Validate required fields
$required_fields = ['fullName', 'nicPassport', 'email', 'phone', 'courseId', 'courseName', 'universityName', 'departmentName', 'programName'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        header('Location: courses.php?error=missing_field&field=' . $field);
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    // Get student ID from session
    $student_id = $_SESSION['user_id'];
    
    // Insert registration into database
    $sql = "INSERT INTO course_registrations (
        student_id, course_id, course_name, university_name, department_name, program_name,
        student_full_name, student_nic_passport, student_email, student_phone, student_address
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $student_id,
        $_POST['courseId'],
        $_POST['courseName'],
        $_POST['universityName'],
        $_POST['departmentName'],
        $_POST['programName'],
        $_POST['fullName'],
        $_POST['nicPassport'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'] ?? ''
    ]);
    
    if ($result) {
        // Redirect to success page
        header('Location: courses.php?success=registration_submitted');
    } else {
        header('Location: courses.php?error=insert_failed');
    }
    
} catch (Exception $e) {
    header('Location: courses.php?error=database_error&message=' . urlencode($e->getMessage()));
}
?>
