<?php
// Prevent any output before JSON response
ob_start();

// Suppress warnings and notices that might output HTML
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

session_start();
require_once 'config.php';

// Clear any output buffer
ob_clean();

// Set proper headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get form data
    $studentId = $_SESSION['user_id'];
    $courseId = $_POST['course_id'] ?? null;
    $universityId = $_POST['university_id'] ?? null;
    $departmentId = $_POST['department_id'] ?? null;
    $programId = $_POST['program_id'] ?? null;
    $fullName = $_POST['fullName'] ?? '';
    $nicPassport = $_POST['nicPassport'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    // Validate required fields
    if (!$courseId || !$universityId || !$departmentId || !$programId) {
        throw new Exception('Missing required course information');
    }

    if (!$fullName || !$nicPassport || !$email || !$phone || !$address) {
        throw new Exception('Missing required personal information');
    }

    // Get database connection
    $pdo = getDBConnection();
    
    // First try to find course by exact title match
    $courseStmt = $pdo->prepare("
        SELECT id, title, campus, department, fee FROM courses 
        WHERE title = ?
    ");
    $courseStmt->execute([$courseId]);
    $course = $courseStmt->fetch(PDO::FETCH_ASSOC);
    
    // If not found by title, try by ID
    if (!$course) {
        $courseStmt = $pdo->prepare("
            SELECT id, title, campus, department, fee FROM courses 
            WHERE id = ?
        ");
        $courseStmt->execute([$courseId]);
        $course = $courseStmt->fetch(PDO::FETCH_ASSOC);
    }
    
    if (!$course) {
        throw new Exception('Course not found. Please try again. Course ID/Title: ' . $courseId);
    }
    
    $actualCourseId = $course['id'];

    // Check if user already registered for this course
    $checkStmt = $pdo->prepare("
        SELECT id FROM course_registrations 
        WHERE student_id = ? AND course_id = ? AND status != 'withdrawn'
    ");
    $checkStmt->execute([$studentId, $actualCourseId]);
    
    if ($checkStmt->fetch()) {
        throw new Exception('You have already registered for this course');
    }

    // Insert registration
    $insertStmt = $pdo->prepare("
        INSERT INTO course_registrations (
            student_id, course_id, course_name, university_name, department_name, program_name,
            campus, registration_date, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'pending')
    ");
    
    $result = $insertStmt->execute([
        $studentId, $actualCourseId, $course['title'], $universityId, $departmentId, $programId, $course['campus']
    ]);

    if (!$result) {
        throw new Exception('Failed to save registration');
    }

    $registrationId = $pdo->lastInsertId();

    // Log the registration for admin dashboard
    $logStmt = $pdo->prepare("
        INSERT INTO admin_logs (
            action, table_name, record_id, user_id, details, timestamp
        ) VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $logDetails = json_encode([
        'course_title' => $course['title'],
        'student_name' => $fullName,
        'university' => $universityId,
        'department' => $departmentId,
        'program' => $programId
    ]);
    
    $logStmt->execute([
        'CREATE', 'course_registrations', $registrationId, $studentId, $logDetails
    ]);

    // Clear any output buffer before sending response
    ob_clean();
    
    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Course registration submitted successfully!',
        'registration_id' => $registrationId,
        'redirect_url' => 'student-dashboard.php'
    ]);

} catch (Exception $e) {
    ob_clean();
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    ob_clean();
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again.'
    ]);
}

// End output buffer
ob_end_flush();
?>
