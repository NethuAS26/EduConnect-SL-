<?php
session_start();
require_once 'config.php';

// Set proper headers
header('Content-Type: application/json');

// Debug: Log session information
error_log("get_reviews.php - Session data: " . print_r($_SESSION, true));

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    error_log("get_reviews.php - Authentication failed. admin_id: " . ($_SESSION['admin_id'] ?? 'NOT SET') . ", user_type: " . ($_SESSION['user_type'] ?? 'NOT SET'));
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Debug: Log successful authentication
error_log("get_reviews.php - Authentication successful for admin_id: " . $_SESSION['admin_id']);

// Get the campus parameter from the request
$campus = $_GET['campus'] ?? '';

if (empty($campus)) {
    http_response_code(400);
    echo json_encode(['error' => 'Campus parameter is required']);
    exit;
}

error_log("get_reviews.php - Requested campus: " . $campus);

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    error_log("get_reviews.php - Database connection successful");
    
    // Get reviews for the specific campus
    $stmt = $pdo->prepare("
        SELECT r.*, s.first_name, s.last_name, s.email, r.course_name, r.university_id as university_name 
        FROM reviews r 
        JOIN students s ON r.student_id = s.id 
        WHERE r.university_id = ? 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$campus]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("get_reviews.php - Found " . count($reviews) . " reviews for campus: " . $campus);
    
    // Get review statistics
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_reviews,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_reviews,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_reviews,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_reviews
        FROM reviews 
        WHERE university_id = ?
    ");
    $stmt->execute([$campus]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    error_log("get_reviews.php - Stats: " . print_r($stats, true));
    
    // Format the response
    $response = [
        'success' => true,
        'reviews' => $reviews,
        'stats' => [
            'total' => (int)$stats['total_reviews'],
            'pending' => (int)$stats['pending_reviews'],
            'approved' => (int)$stats['approved_reviews'],
            'rejected' => (int)$stats['rejected_reviews']
        ]
    ];
    
    error_log("get_reviews.php - Sending response: " . json_encode($response));
    echo json_encode($response);
    
} catch (PDOException $e) {
    error_log("Database error in get_reviews.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    error_log("General error in get_reviews.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred']);
    exit;
}
?>
