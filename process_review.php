<?php
session_start();
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Login required']);
    exit;
}

try {
    // Get form data
    $university = trim($_POST['university'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $recommend = trim($_POST['recommend'] ?? '');
    
    // Validate required fields
    if (empty($university) || empty($rating) || empty($recommend)) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'error' => 'Invalid rating']);
        exit;
    }
    
    // Validate recommendation
    if (!in_array($recommend, ['yes', 'no'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid recommendation']);
        exit;
    }
    
    // Connect to database
    $pdo = getDBConnection();
    
    // Map university codes to campus names
    $university_map = [
        'icbt' => 'ICBT Campus',
        'nibm' => 'NIBM',
        'peradeniya' => 'University of Peradeniya',
        'moratuwa' => 'University of Moratuwa'
    ];
    
    $university_name = $university_map[$university] ?? $university;
    
    // Get or create course ID if course is specified
    $course_name = null;
    if (!empty($course)) {
        // Use the course name as provided
        $course_name = $course;
    }
    
    // Create comment content
    $comment = "Course: " . ($course_name ?: 'Not specified') . "\n";
    $comment .= "Recommendation: " . ($recommend === 'yes' ? 'Yes, I would recommend this university' : 'No, I would not recommend this university');
    
    // Insert review into database
    $stmt = $pdo->prepare("INSERT INTO reviews (student_id, university_name, courses, rating, recommend_or_not_recommended, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $university_name, $course_name, $rating, $recommend]);
    
    $review_id = $pdo->lastInsertId();
    
    // Create notification for admins about new review
    $notification_title = "New Review Submitted";
    $notification_message = "New review submitted by " . ($_SESSION['user_name'] ?? 'Student') . " for " . $university_name;
    
    // Get all admins to notify
    $stmt = $pdo->prepare("SELECT id FROM admins");
    $stmt->execute();
    $admins = $stmt->fetchAll();
    
    foreach ($admins as $admin) {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) VALUES (?, 'admin', ?, ?, 'info', ?)");
        $stmt->execute([$admin['id'], $notification_title, $notification_message, 'admin-dashboard.php']);
    }
    
    // Log the review submission
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, description) VALUES (?, 'NEW_REVIEW', ?)");
    $stmt->execute([1, "New review #{$review_id} submitted by student {$_SESSION['user_id']} for {$university_name}"]);
    
    // Return success response
    echo json_encode([
        'success' => true, 
        'message' => 'Review submitted successfully!',
        'review_id' => $review_id
    ]);
    
} catch (PDOException $e) {
    // Log error
    error_log("Database error in process_review.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    // Log error
    error_log("General error in process_review.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred']);
    exit;
}
?>
