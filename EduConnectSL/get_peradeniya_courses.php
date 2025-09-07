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
    
    // Fetch only courses associated with admin-added Peradeniya programs
    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.program_id,
            c.course_name,
            c.course_description,
            c.requirement,
            c.duration,
            c.status,
            c.created_at,
            p.program_name,
            p.program_code
        FROM peradeniya_courses c
        JOIN peradeniya_programs p ON c.program_id = p.id
        WHERE c.campus = 'University of Peradeniya' 
        AND p.program_code LIKE 'PERA%' 
        AND p.program_code REGEXP '^PERA[0-9]+$'
        ORDER BY p.program_name, c.course_name
    ");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'courses' => $courses
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching Peradeniya courses: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching courses: ' . $e->getMessage()
    ]);
}
?>
