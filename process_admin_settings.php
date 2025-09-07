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
    // Get form data
    $action = trim($_POST['action'] ?? '');
    $settings = $_POST['settings'] ?? '';
    
    if ($action !== 'save_settings') {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit();
    }
    
    // Parse settings JSON
    $settingsData = json_decode($settings, true);
    if (!$settingsData) {
        echo json_encode(['success' => false, 'message' => 'Invalid settings data']);
        exit();
    }
    
    // Connect to database
    $pdo = getDBConnection();
    
    // Get admin's campus
    $admin_campus = $_SESSION['campus'] ?? 'ICBT';
    
    // Check if settings record exists for this admin
    $stmt = $pdo->prepare("SELECT id FROM admin_settings WHERE admin_id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $existingSettings = $stmt->fetch();
    
    if ($existingSettings) {
        // Update existing settings
        $stmt = $pdo->prepare("UPDATE admin_settings 
                              SET email_notifications = ?, dashboard_alerts = ?, theme = ?, updated_at = CURRENT_TIMESTAMP 
                              WHERE admin_id = ?");
        $stmt->execute([
            $settingsData['emailNotifications'] ? 1 : 0,
            $settingsData['dashboardAlerts'] ? 1 : 0,
            $settingsData['theme'],
            $_SESSION['admin_id']
        ]);
    } else {
        // Create new settings record
        $stmt = $pdo->prepare("INSERT INTO admin_settings (admin_id, campus, email_notifications, dashboard_alerts, theme, created_at, updated_at) 
                              VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $_SESSION['admin_id'],
            $admin_campus,
            $settingsData['emailNotifications'] ? 1 : 0,
            $settingsData['dashboardAlerts'] ? 1 : 0,
            $settingsData['theme']
        ]);
    }
    
    // Return success response
    echo json_encode([
        'success' => true, 
        'message' => 'Settings saved successfully!'
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in process_admin_settings.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General error in process_admin_settings.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
?>
