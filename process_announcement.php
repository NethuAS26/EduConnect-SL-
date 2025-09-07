<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
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
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $audience = trim($_POST['audience'] ?? '');
    $expires_at = trim($_POST['expires_at'] ?? null);
    
    // Get campus from session
    $campus = $_SESSION['campus'] ?? '';
    
    // Validate required fields
    if (empty($title) || empty($body) || empty($audience) || empty($campus)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    // Validate audience
    $valid_audiences = ['all', 'students', 'admins'];
    if (!in_array($audience, $valid_audiences)) {
        echo json_encode(['success' => false, 'message' => 'Invalid audience']);
        exit;
    }
    
    // Connect to database
    $pdo = getDBConnection();
    
    // Prepare expires_at date
    $expires_at_sql = null;
    if (!empty($expires_at)) {
        $expires_at_sql = date('Y-m-d H:i:s', strtotime($expires_at));
    }
    
    // Deactivate all previous announcements for this campus
    $stmt = $pdo->prepare("UPDATE announcements SET is_active = FALSE WHERE campus = ?");
    $stmt->execute([$campus]);
    
    // Insert new announcement into database
    $stmt = $pdo->prepare("INSERT INTO announcements (title, body, audience, campus, admin_id, expires_at, is_active) VALUES (?, ?, ?, ?, ?, ?, TRUE)");
    $stmt->execute([$title, $body, $audience, $campus, $_SESSION['admin_id'], $expires_at_sql]);
    
    $announcement_id = $pdo->lastInsertId();
    
    // Create notifications for users based on audience
    $notification_title = "New Announcement: " . $title;
    $notification_message = substr($body, 0, 100) . (strlen($body) > 100 ? '...' : '');
    
    if ($audience === 'all' || $audience === 'students') {
        // Notify all students
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) 
                              SELECT id, 'student', ?, ?, 'announcement', 'courses.php' FROM students");
        $stmt->execute([$notification_title, $notification_message]);
    }
    
    if ($audience === 'all' || $audience === 'admins') {
        // Notify all admins
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) 
                              SELECT id, 'admin', ?, ?, 'announcement', 'admin-dashboard.php' FROM admins");
        $stmt->execute([$notification_title, $notification_message]);
    }
    
    // Log the announcement creation
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, description) VALUES (?, 'CREATE_ANNOUNCEMENT', ?)");
    $stmt->execute([$_SESSION['admin_id'], "Created announcement for {$campus}: {$title}"]);
    
    // Return JSON success response
    echo json_encode(['success' => true, 'message' => 'Announcement created successfully']);
    exit;
    
} catch (PDOException $e) {
    // Log error
    error_log("Database error in process_announcement.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    // Log error
    error_log("General error in process_announcement.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
}
?>
