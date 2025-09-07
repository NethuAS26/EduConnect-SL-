<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Fetch only admin-added Peradeniya programs (those with PERA + numbers pattern)
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
        FROM peradeniya_programs 
        WHERE campus = 'University of Peradeniya' 
        AND program_code LIKE 'PERA%' 
        AND program_code REGEXP '^PERA[0-9]+$'
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
    error_log("Error fetching Peradeniya programs: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching programs: ' . $e->getMessage()
    ]);
}
?>
