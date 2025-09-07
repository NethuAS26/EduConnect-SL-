<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get form data
    $review_id = intval($_POST['review_id'] ?? 0);
    $action = trim($_POST['action'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    
    // Validate required fields
    if (empty($review_id) || empty($action) || empty($notes)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Validate action
    $valid_actions = ['approve', 'reject'];
    if (!in_array($action, $valid_actions)) {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }
    
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verify the review exists and get basic info
    $stmt = $pdo->prepare("SELECT r.*, s.id as student_id, s.email, s.first_name, s.last_name, r.course_name 
                          FROM reviews r 
                          JOIN students s ON r.student_id = s.id 
                          WHERE r.id = ?");
    $stmt->execute([$review_id]);
    $review = $stmt->fetch();
    
    if (!$review) {
        echo json_encode(['success' => false, 'message' => 'Review not found']);
        exit;
    }
    
    // Determine new status
    $new_status = ($action === 'approve') ? 'approved' : 'rejected';
    
    // Update the review status
    $stmt = $pdo->prepare("UPDATE reviews SET 
                              status = ?, 
                              moderated_by = ?, 
                              moderation_notes = ?,
                              updated_at = CURRENT_TIMESTAMP 
                          WHERE id = ?");
    $stmt->execute([$new_status, $_SESSION['admin_id'], $notes, $review_id]);
    
    // Create notification for the student
    $course_title = $review['course_name'] ?: 'General Course';
    $notification_title = "Your review has been " . $new_status;
    $notification_message = "Your review for '{$course_title}' has been " . $new_status . " by our moderation team.";
    
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) VALUES (?, 'student', ?, ?, 'info', 'reviews.php')");
    $stmt->execute([$review['student_id'], $notification_title, $notification_message]);
    
    // Log the admin action
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, description) VALUES (?, 'MODERATE_REVIEW', ?)");
    $stmt->execute([$_SESSION['admin_id'], "Review #{$review_id} {$action}d for {$course_title}"]);
    
    // Return JSON success response
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Review ' . $action . 'd successfully']);
    exit;
    
} catch (PDOException $e) {
    // Log error
    error_log("Database error in process_review_moderation.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    // Log error
    error_log("General error in process_review_moderation.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
    exit;
}
?>
