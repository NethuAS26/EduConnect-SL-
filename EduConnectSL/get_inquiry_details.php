<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if it's a GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit();
}

try {
    // Get inquiry ID
    $inquiry_id = trim($_GET['id'] ?? '');
    
    if (empty($inquiry_id)) {
        echo json_encode(['success' => false, 'message' => 'Missing inquiry ID']);
        exit();
    }
    
    // Connect to database
    $pdo = getDBConnection();
    
    // Get admin's campus from session
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
    
    // Fetch detailed inquiry information
    $stmt = $pdo->prepare("
        SELECT 
            i.id,
            i.subject,
            i.message,
            i.response,
            i.response_status,
            i.university_id,
            i.created_at,
            i.response_date,
            s.first_name,
            s.last_name,
            s.email,
            s.phone
        FROM inquiries i 
        JOIN students s ON i.student_id = s.id 
        WHERE i.id = ? AND i.university_id = ?
    ");
    $stmt->execute([$inquiry_id, $university_id]);
    $inquiry = $stmt->fetch(PDO::FETCH_ASSOC);
    
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
    
    echo json_encode([
        'success' => true,
        'inquiry' => $inquiry
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get_inquiry_details.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General error in get_inquiry_details.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
?>
