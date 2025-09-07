<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get form data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $userType = $_POST['userType'] ?? '';
    $campus = $_POST['campus'] ?? '';
    
    // Validation
    if (empty($email) || empty($password) || empty($userType)) {
        throw new Exception('All fields are required');
    }
    
    if ($userType === 'admin' && empty($campus)) {
        throw new Exception('Please select a campus for admin login');
    }
    
    if ($userType === 'student') {
        // Student login
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, password_hash FROM students WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            throw new Exception('Invalid email or password');
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            throw new Exception('Invalid email or password');
        }
        
        // Start session and store user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = 'student';
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_last_name'] = $user['last_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_phone'] = $user['phone'];
        
        echo json_encode([
          'success' => true,
          'message' => 'Student login successful!',
          'userType' => 'student',
          'redirect' => 'index.php'
        ]);
        
    } elseif ($userType === 'admin') {
        // Admin login
        error_log("Attempting admin login for email: $email, campus: $campus");
        
        // First check if admins table exists and has data
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
            if ($stmt->rowCount() == 0) {
                error_log("Admins table doesn't exist");
                throw new Exception('Database setup error. Please contact administrator.');
            }
            
            // Check if there are any admin records
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM admins");
            $count = $stmt->fetch()['count'];
            error_log("Total admin records: $count");
            
            if ($count == 0) {
                error_log("No admin records found, database might not be properly initialized");
                throw new Exception('No admin accounts found. Please contact administrator.');
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception('Database connection error');
        }
        
        $stmt = $pdo->prepare("SELECT id, email, password_hash, campus FROM admins WHERE email = ? AND campus = ?");
        $stmt->execute([$email, $campus]);
        $admin = $stmt->fetch();
        
        error_log("Admin query result: " . ($admin ? "Found admin ID: {$admin['id']}" : "No admin found"));
        error_log("Query parameters - email: $email, campus: $campus");
        if ($admin) {
            error_log("Admin found - ID: {$admin['id']}, Campus: {$admin['campus']}");
        }
        
        if (!$admin) {
            // Let's also check what admins exist for debugging
            $stmt = $pdo->query("SELECT email, campus FROM admins");
            $allAdmins = $stmt->fetchAll();
            error_log("Available admins: " . print_r($allAdmins, true));
            throw new Exception('Invalid admin credentials for the selected campus');
        }
        
        if (!password_verify($password, $admin['password_hash'])) {
            error_log("Password verification failed for admin: $email");
            throw new Exception('Invalid admin credentials for the selected campus');
        }
        
        error_log("Password verification successful for admin: $email");
        
        // Start session and store admin data
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['user_type'] = 'admin';
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['campus'] = $admin['campus'];
        
        // Check if admin dashboard file exists, otherwise redirect to a generic admin page
        // Handle special cases for campus names
        $campusKey = '';
        switch ($admin['campus']) {
            case 'ICBT Campus':
                $campusKey = 'icbt';
                break;
            case 'NIBM':
                $campusKey = 'nibm';
                break;
            case 'University of Peradeniya':
                $campusKey = 'peradeniya';
                break;
            case 'University of Moratuwa':
                $campusKey = 'moratuwa';
                break;
            default:
                $campusKey = str_replace(' ', '', strtolower($admin['campus']));
        }
        $dashboardFile = $campusKey . '-admin-dashboard.php';
        error_log("Generated dashboard filename: $dashboardFile");
        error_log("File exists check: " . (file_exists($dashboardFile) ? 'YES' : 'NO'));
        
        if (file_exists($dashboardFile)) {
            $redirectUrl = $dashboardFile;
            error_log("Using specific dashboard: $redirectUrl");
        } else {
            error_log("Dashboard file not found: $dashboardFile, using generic admin-dashboard.php");
            $redirectUrl = 'admin-dashboard.php';
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Admin login successful!',
            'userType' => 'admin',
            'campus' => $admin['campus'],
            'redirect' => $redirectUrl
        ]);
        
    } else {
        throw new Exception('Invalid user type');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}
?>
