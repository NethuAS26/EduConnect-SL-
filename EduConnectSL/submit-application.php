<?php
header('Content-Type: application/json');
require_once 'config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'You must be logged in as a student to submit applications']);
        exit;
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit;
    }
    
    // Get form data from JSON input
    $firstName = trim($input['firstName'] ?? '');
    $lastName = trim($input['lastName'] ?? '');
    $email = trim($input['email'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $dateOfBirth = trim($input['dateOfBirth'] ?? '');
    
    // Educational background fields
    $highestQualification = trim($input['highestQualification'] ?? '');
    $institution = trim($input['institution'] ?? '');
    $graduationYear = !empty($input['graduationYear']) ? (int)$input['graduationYear'] : null;
    
    // Declaration fields
    $declaration = isset($input['declaration']) ? 1 : 0;
    $termsConditions = isset($input['termsConditions']) ? 1 : 0;
    
    // Course information - can come from form fields or JavaScript variables
    $courseName = trim($input['courseName'] ?? $input['course'] ?? '');
    $universityName = trim($input['universityName'] ?? $input['university'] ?? '');
    $studyLevel = trim($input['studyLevel'] ?? $input['program'] ?? '');
    $program = trim($input['program'] ?? $input['departmentName'] ?? '');
    
    // Validate required fields (matching JavaScript validation)
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($dateOfBirth) || empty($highestQualification)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }
    
    // Validate declarations
    if (!$declaration || !$termsConditions) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please accept the declaration and terms & conditions']);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
        exit;
    }
    
    // Validate phone
    if (!preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $phone)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please enter a valid phone number']);
        exit;
    }
    
    // Generate application number
    $applicationNumber = 'APP-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Prepare the application data for course_applications table
    $applicationData = [
        'user_id' => $_SESSION['user_id'],
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'date_of_birth' => $dateOfBirth,
        'course_name' => $courseName,
        'university' => $universityName,
        'study_level' => $studyLevel,
        'program' => $program,
        'highest_qualification' => $highestQualification,
        'institution' => $institution,
        'graduation_year' => $graduationYear,
        'declaration_accepted' => $declaration,
        'terms_accepted' => $termsConditions,
        'application_number' => $applicationNumber,
        'status' => 'pending'
    ];
    
    // Insert into database
    try {
        $pdo = getDBConnection();
        
        // Insert into course_applications table (the correct table with proper structure)
        $stmt = $pdo->prepare("INSERT INTO course_applications (user_id, first_name, last_name, email, phone, date_of_birth, course_name, university, study_level, program, highest_qualification, institution, graduation_year, declaration_accepted, terms_accepted, application_number, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $applicationData['user_id'],
            $applicationData['first_name'],
            $applicationData['last_name'],
            $applicationData['email'],
            $applicationData['phone'],
            $applicationData['date_of_birth'],
            $applicationData['course_name'],
            $applicationData['university'],
            $applicationData['study_level'],
            $applicationData['program'],
            $applicationData['highest_qualification'],
            $applicationData['institution'],
            $applicationData['graduation_year'],
            $applicationData['declaration_accepted'],
            $applicationData['terms_accepted'],
            $applicationData['application_number'],
            $applicationData['status']
        ]);
        
        $registrationId = $pdo->lastInsertId();
        
        // Log the successful application
        error_log("Course application submitted successfully. Application ID: {$registrationId}, Student: {$applicationData['first_name']} {$applicationData['last_name']}, Course: {$applicationData['course_name']}");
        
        // Add notification for admin
        addAdminNotification($pdo, $applicationData, $registrationId);
        
        // Return success response with redirect information
        echo json_encode([
            'success' => true,
            'message' => 'Application submitted successfully!',
            'applicationId' => $registrationId,
            'studentDashboardUrl' => 'student-dashboard.php',
            'courseName' => $applicationData['course_name'],
            'university' => $applicationData['university']
        ]);
        
    } catch (PDOException $e) {
        error_log("Database error in submit-application.php: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred. Please try again later.']);
    }
    
} catch (Exception $e) {
    error_log("Error in submit-application.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred. Please try again later.']);
}

// Function to add admin notification
function addAdminNotification($pdo, $applicationData, $applicationId) {
    try {
        // Map university to campus for admin notification
        $campusMap = [
            'ICBT Campus' => 'ICBT',
            'NIBM' => 'NIBM',
            'University of Peradeniya' => 'Peradeniya',
            'University of Moratuwa' => 'Moratuwa'
        ];
        
        $campus = $campusMap[$applicationData['university']] ?? 'ICBT'; // Default to ICBT
        
        // Check if notifications table exists, create if not
        $pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_campus VARCHAR(50) NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
            is_read TINYINT(1) DEFAULT 0,
            application_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_campus (admin_campus),
            INDEX idx_read (is_read)
        )");
        
        // Insert notification for the campus admin
        $stmt = $pdo->prepare("INSERT INTO notifications (admin_campus, title, message, type, application_id) VALUES (?, ?, ?, ?, ?)");
        
        $title = "New Course Application";
        $message = "New application received for {$applicationData['course_name']} from {$applicationData['first_name']} {$applicationData['last_name']} ({$applicationData['email']})";
        
        $stmt->execute([$campus, $title, $message, 'info', $applicationId]);
        
        error_log("Admin notification created for campus: $campus, Application ID: $applicationId");
        
    } catch (Exception $e) {
        error_log("Error creating admin notification: " . $e->getMessage());
        // Don't throw error as it's not critical to the application submission
    }
}
?>
