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
    header('Location: content-management.php?error=invalid_method');
    exit;
}

try {
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $audience = trim($_POST['audience'] ?? '');
    $tags = $_POST['tags'] ?? [];
    
    // Validate required fields
    if (empty($title) || empty($body) || empty($audience)) {
        header('Location: content-management.php?error=missing_fields');
        exit;
    }
    
    // Validate audience
    $valid_audiences = ['all', 'students', 'prospective_students', 'parents'];
    if (!in_array($audience, $valid_audiences)) {
        header('Location: content-management.php?error=invalid_audience');
        exit;
    }
    
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Insert article into database
        $stmt = $pdo->prepare("INSERT INTO articles (title, body, audience, admin_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $body, $audience, $_SESSION['admin_id']]);
        
        $article_id = $pdo->lastInsertId();
        
        // Insert article tags if any
        if (!empty($tags) && is_array($tags)) {
            $stmt = $pdo->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
            foreach ($tags as $tag_id) {
                if (is_numeric($tag_id)) {
                    $stmt->execute([$article_id, $tag_id]);
                }
            }
        }
        
        // Create notifications for users based on audience
        $notification_title = "New Article: " . $title;
        $notification_message = substr($body, 0, 100) . (strlen($body) > 100 ? '...' : '');
        
        if ($audience === 'all' || $audience === 'students') {
            // Notify all students
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) 
                                  SELECT id, 'student', ?, ?, 'info', 'index.php' FROM students");
            $stmt->execute([$notification_title, $notification_message]);
        }
        
        if ($audience === 'all' || $audience === 'prospective_students') {
            // For prospective students, we'll notify students who haven't applied yet
            // This is a simplified approach - in a real system you might have a separate table
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) 
                                  SELECT s.id, 'student', ?, ?, 'info', 'index.php' 
                                  FROM students s 
                                  LEFT JOIN course_applications ca ON s.id = ca.user_id 
                                  WHERE ca.id IS NULL");
            $stmt->execute([$notification_title, $notification_message]);
        }
        
        // Log the article creation
        $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, description) VALUES (?, 'CREATE_ARTICLE', ?)");
        $stmt->execute([$_SESSION['admin_id'], "Created article: {$title}"]);
        
        // Commit transaction
        $pdo->commit();
        
        // Return JSON success response
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Article created successfully']);
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    // Log error
    error_log("Database error in process_article.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    // Log error
    error_log("General error in process_article.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
}
?>
