<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Admin not logged in']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $action = $_POST['action'] ?? '';
    $registrationId = $_POST['registration_id'] ?? null;
    $adminNotes = $_POST['admin_notes'] ?? '';
    
    if (!$registrationId || !in_array($action, ['approve', 'reject'])) {
        throw new Exception('Invalid action or registration ID');
    }
    
    // Get registration details
    $regStmt = $pdo->prepare("
        SELECT cr.*, c.title as course_title, c.campus, u.email as student_email, u.full_name as student_name
        FROM course_registrations cr
        JOIN courses c ON cr.course_id = c.id
        JOIN users u ON cr.student_id = u.id
        WHERE cr.id = ?
    ");
    $regStmt->execute([$registrationId]);
    $registration = $regStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$registration) {
        throw new Exception('Registration not found');
    }
    
    // Check if admin has access to this campus
    $adminCampus = $_SESSION['campus'] ?? '';
    if ($adminCampus && $registration['campus'] !== $adminCampus) {
        throw new Exception('You can only manage registrations for your campus');
    }
    
    // Update registration status
    $updateStmt = $pdo->prepare("
        UPDATE course_registrations 
        SET status = ?, admin_notes = ?, admin_decision_date = NOW(), admin_id = ?
        WHERE id = ?
    ");
    
    $newStatus = ($action === 'approve') ? 'approved' : 'rejected';
    $result = $updateStmt->execute([$newStatus, $adminNotes, $_SESSION['admin_id'], $registrationId]);
    
    if (!$result) {
        throw new Exception('Failed to update registration status');
    }
    
    // Log the admin action
    $logStmt = $pdo->prepare("
        INSERT INTO admin_logs (
            action, table_name, record_id, user_id, details, timestamp
        ) VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $logDetails = json_encode([
        'action' => $action,
        'course_title' => $registration['course_title'],
        'student_name' => $registration['student_name'],
        'campus' => $registration['campus'],
        'admin_notes' => $adminNotes
    ]);
    
    $logStmt->execute([
        'UPDATE', 'course_registrations', $registrationId, $_SESSION['admin_id'], $logDetails
    ]);
    
    // Send success response
    echo json_encode([
        'success' => true,
        'message' => "Registration {$action} successfully",
        'new_status' => $newStatus
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again.'
    ]);
}
?>
