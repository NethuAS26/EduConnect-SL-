<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Get campus from query parameter or session
$campus = $_GET['campus'] ?? $_SESSION['campus'] ?? 'ICBT Campus';

// Map campus parameter to university_id
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

$university_id = $campus_mapping[$campus] ?? $campus;

// Connect to database
try {
    $pdo = getDBConnection();
    
    // Get inquiries for the specific campus
    $stmt = $pdo->prepare("SELECT i.*, s.first_name, s.last_name, s.email, s.phone 
                          FROM inquiries i 
                          JOIN students s ON i.student_id = s.id 
                          WHERE i.university_id = ? 
                          ORDER BY i.created_at DESC");
    $stmt->execute([$university_id]);
    $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get inquiry statistics for the specific campus
    $stmt = $pdo->prepare("SELECT 
                              COUNT(*) as total,
                              COUNT(CASE WHEN response_status = 'pending' THEN 1 END) as pending,
                              COUNT(CASE WHEN response_status = 'answered' THEN 1 END) as answered,
                              COUNT(CASE WHEN response_status = 'closed' THEN 1 END) as closed
                          FROM inquiries 
                          WHERE university_id = ?");
    $stmt->execute([$university_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'inquiries' => $inquiries,
        'stats' => $stats,
        'campus' => $campus,
        'university_id' => $university_id
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get_inquiries.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
} catch (Exception $e) {
    error_log("General error in get_inquiries.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred']);
}
?>
