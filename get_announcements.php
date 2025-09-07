<?php
// Disable error reporting for production
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
require_once 'config.php';

// Allow CORS for AJAX requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get campus parameter
    $campus = $_GET['campus'] ?? '';
    
    if (empty($campus)) {
        echo json_encode(['success' => false, 'message' => 'Campus parameter is required']);
        exit;
    }
    
    // Connect to database
    $pdo = getDBConnection();
    
    // Get the most recent active announcement for the campus
    $stmt = $pdo->prepare("
        SELECT id, title, body, audience, created_at, expires_at 
        FROM announcements 
        WHERE campus = ? 
        AND is_active = TRUE 
        AND (expires_at IS NULL OR expires_at > NOW())
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$campus]);
    $announcement = $stmt->fetch();
    
    if ($announcement) {
        echo json_encode([
            'success' => true,
            'announcement' => $announcement
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'announcement' => null
        ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'General error occurred']);
}
?>
