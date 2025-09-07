<?php
session_start();
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: contact.php?error=invalid_method');
    exit;
}

// Check if user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: contact.php?error=login_required');
    exit;
}

try {
    // Get form data
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $campus = trim($_POST['campus'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($campus) || empty($subject) || empty($message)) {
        header('Location: contact.php?error=missing_fields');
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: contact.php?error=invalid_email');
        exit;
    }
    
    // Connect to database
    $pdo = getDBConnection();
    
    // Verify the logged-in student
    $student_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT id, email FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        header('Location: contact.php?error=invalid_student');
        exit;
    }
    
    // Verify email matches the logged-in student
    if ($student['email'] !== $email) {
        header('Location: contact.php?error=email_mismatch');
        exit;
    }
    
    // Map campus selection to university_id
    $campus_to_university = [
        'icbt' => 'ICBT Campus',        // ICBT
        'nibm' => 'NIBM Campus',        // NIBM
        'peradeniya' => 'Peradeniya Campus',  // Peradeniya
        'moratuwa' => 'Moratuwa Campus'     // Moratuwa
    ];
    
    $university_id = $campus_to_university[$campus] ?? 'ICBT Campus'; // Default to ICBT if invalid campus
    
    // Insert inquiry into database
    $stmt = $pdo->prepare("INSERT INTO inquiries (student_id, university_id, subject, message, response_status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$student_id, $university_id, $subject, $message]);
    
    $inquiry_id = $pdo->lastInsertId();
    
    // Create notification for campus-specific admins only
    $campus_names = [
        'icbt' => 'ICBT Campus',
        'nibm' => 'NIBM Campus',
        'peradeniya' => 'Peradeniya Campus',
        'moratuwa' => 'Moratuwa Campus'
    ];
    
    $campus_name = $campus_names[$campus] ?? 'Unknown Campus';
    $notification_title = "New Inquiry: " . ucfirst(str_replace('-', ' ', $subject));
    $notification_message = "New inquiry from " . $firstName . " " . $lastName . " regarding: " . $subject . " for " . $campus_name;
    
    // Get only admins for the specific campus
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE campus = ?");
    $stmt->execute([$campus_name]);
    $admins = $stmt->fetchAll();
    
    // If no campus-specific admins found, get all admins as fallback
    if (empty($admins)) {
        $stmt = $pdo->prepare("SELECT id FROM admins");
        $stmt->execute();
        $admins = $stmt->fetchAll();
    }
    
    foreach ($admins as $admin) {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) VALUES (?, 'admin', ?, ?, 'info', ?)");
        $stmt->execute([$admin['id'], $notification_title, $notification_message, 'admin-dashboard.php']);
    }
    
    // Redirect with success message
    header('Location: contact.php?success=inquiry_sent');
    exit;
    
} catch (PDOException $e) {
    // Log error
    error_log("Database error in process_contact.php: " . $e->getMessage());
    header('Location: contact.php?error=database_error');
    exit;
} catch (Exception $e) {
    // Log error
    error_log("General error in process_contact.php: " . $e->getMessage());
    header('Location: contact.php?error=general_error');
    exit;
}
?>
