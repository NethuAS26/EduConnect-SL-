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
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit();
}

try {
    // Get form data (handle both POST and JSON)
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input) {
        // JSON input
        $inquiry_id = trim($input['inquiry_id'] ?? '');
        $response = trim($input['response'] ?? '');
        $action = trim($input['action'] ?? 'respond');
    } else {
        // POST input
        $inquiry_id = trim($_POST['inquiry_id'] ?? '');
        $response = trim($_POST['response'] ?? '');
        $action = trim($_POST['action'] ?? 'respond');
    }
    
    // Validate required fields
    if (empty($inquiry_id)) {
        echo json_encode(['success' => false, 'message' => 'Missing inquiry ID']);
        exit();
    }
    
    // Validate response for respond action
    if ($action === 'respond' && empty($response)) {
        echo json_encode(['success' => false, 'message' => 'Missing response text']);
        exit();
    }
    
    // Connect to database
    $pdo = getDBConnection();
    
    // Get admin's campus from session or determine from inquiry
    $admin_campus = $_SESSION['campus'] ?? 'ICBT';
    
    // Map admin campus to inquiry university_id
    $campus_mapping = [
        'ICBT' => 'ICBT Campus',
        'NIBM' => 'NIBM Campus',
        'Peradeniya' => 'Peradeniya Campus',
        'Moratuwa' => 'Moratuwa Campus',
        'University of Peradeniya' => 'Peradeniya Campus',
        'University of Moratuwa' => 'Moratuwa Campus',
        'University of NIBM' => 'NIBM Campus',
        'University of ICBT' => 'ICBT Campus'
    ];
    
    $university_id = $campus_mapping[$admin_campus] ?? $admin_campus;
    
    // Verify the inquiry exists and belongs to the admin's campus
    $stmt = $pdo->prepare("SELECT i.*, s.first_name, s.last_name, s.email 
                          FROM inquiries i 
                          JOIN students s ON i.student_id = s.id 
                          WHERE i.id = ? AND i.university_id = ?");
    $stmt->execute([$inquiry_id, $university_id]);
    $inquiry = $stmt->fetch();
    
    if (!$inquiry) {
        // If not found with admin's campus, try to find the inquiry to provide better error message
        $stmt = $pdo->prepare("SELECT i.university_id FROM inquiries i WHERE i.id = ?");
        $stmt->execute([$inquiry_id]);
        $inquiry_check = $stmt->fetch();
        
        if (!$inquiry_check) {
            echo json_encode(['success' => false, 'message' => 'Inquiry not found']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Inquiry not found or unauthorized for this campus']);
        }
        exit();
    }
    
    // Handle different actions
    if ($action === 'delete') {
        // Delete the inquiry permanently
        $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
        $stmt->execute([$inquiry_id]);
        
        // Create notification for the student about deletion
        $notification_title = "Inquiry deleted: " . ucfirst(str_replace('-', ' ', $inquiry['subject']));
        $notification_message = "Your inquiry regarding: " . $inquiry['subject'] . " has been deleted by an administrator.";
        
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) VALUES (?, 'student', ?, ?, 'warning', ?)");
        $stmt->execute([$inquiry['student_id'], $notification_title, $notification_message, 'profile.php']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Inquiry deleted successfully!'
        ]);
    } else if ($action === 'close') {
        // Close the inquiry without adding a response
        $stmt = $pdo->prepare("UPDATE inquiries 
                              SET response_status = 'closed', response_date = CURRENT_TIMESTAMP 
                              WHERE id = ?");
        $stmt->execute([$inquiry_id]);
        
        // Create notification for the student
        $notification_title = "Inquiry closed: " . ucfirst(str_replace('-', ' ', $inquiry['subject']));
        $notification_message = "Your inquiry regarding: " . $inquiry['subject'] . " has been closed.";
        
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) VALUES (?, 'student', ?, ?, 'info', ?)");
        $stmt->execute([$inquiry['student_id'], $notification_title, $notification_message, 'profile.php']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Inquiry closed successfully!'
        ]);
    } else {
        // Update the inquiry with the response
        $stmt = $pdo->prepare("UPDATE inquiries 
                              SET response = ?, response_status = 'answered', response_date = CURRENT_TIMESTAMP 
                              WHERE id = ?");
        $stmt->execute([$response, $inquiry_id]);
        
        // Create notification for the student
        $notification_title = "Response to your inquiry: " . ucfirst(str_replace('-', ' ', $inquiry['subject']));
        $notification_message = "An admin has responded to your inquiry regarding: " . $inquiry['subject'];
        
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, user_type, title, message, type, related_url) VALUES (?, 'student', ?, ?, 'info', ?)");
        $stmt->execute([$inquiry['student_id'], $notification_title, $notification_message, 'profile.php']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Response sent successfully!'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Database error in process_inquiry_response.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General error in process_inquiry_response.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
?>
