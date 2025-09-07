<?php
session_start();
require_once 'config.php';

// Check if user is logged in (either student or admin)
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Fetch only admin-added Moratuwa programs (those with generated program codes)
    $stmt = $pdo->prepare("
        SELECT 
            id,
            program_code,
            program_name,
            description,
            level,
            category,
            duration,
            status,
            created_at
        FROM moratuwa_programs 
        WHERE campus = 'Moratuwa' 
        AND program_code LIKE 'MOR%' 
        AND program_code REGEXP '^MOR[0-9]+$'
        ORDER BY level, category, program_name
    ");
    $stmt->execute();
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'programs' => $programs
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching Moratuwa programs: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching programs: ' . $e->getMessage()
    ]);
}
?>
