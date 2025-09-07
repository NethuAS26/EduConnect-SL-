<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'education_system');

// Create database connection
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Initialize database if it doesn't exist
function initializeDatabase() {
    try {
        // Connect without database name first
        $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Connect to the created database
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create tables
        createTables($pdo);
        
        return true;
    } catch(PDOException $e) {
        die("Database initialization failed: " . $e->getMessage());
    }
}

// Create necessary tables
function createTables($pdo) {
    // Students table
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(20) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        nic_passport VARCHAR(20),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    // Admins table
    $sql = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        campus VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    // Course registrations table
    $sql = "CREATE TABLE IF NOT EXISTS course_registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        course_id VARCHAR(100) NOT NULL,
        course_name VARCHAR(255) NOT NULL,
        university_name VARCHAR(100) NOT NULL,
        department_name VARCHAR(100) NOT NULL,
        program_name VARCHAR(100) NOT NULL,
        student_full_name VARCHAR(100) NOT NULL,
        student_nic_passport VARCHAR(20) NOT NULL,
        student_email VARCHAR(100) NOT NULL,
        student_phone VARCHAR(20) NOT NULL,
        student_address TEXT,
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        admin_notes TEXT,
        admin_id INT,
        admin_response_date TIMESTAMP NULL,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    
    // Inquiries table
    $sql = "CREATE TABLE IF NOT EXISTS inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        university_id INT NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        response_status ENUM('pending', 'answered', 'closed') DEFAULT 'pending',
        response TEXT,
        response_date TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    
    // Notifications table
    $sql = "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        user_type ENUM('student', 'admin') NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type VARCHAR(50) DEFAULT 'info',
        related_url VARCHAR(255),
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    // Insert default admin accounts
    insertDefaultAdmins($pdo);
}

// Insert default admin accounts
function insertDefaultAdmins($pdo) {
    $admins = [
        ['email' => 'icbtadmin@gmail.com', 'password' => 'icbt123', 'campus' => 'ICBT Campus'],
        ['email' => 'nibmadmin@gmail.com', 'password' => 'nibm123', 'campus' => 'NIBM'],
        ['email' => 'peradeniyaadmin@gmail.com', 'password' => 'pera123', 'campus' => 'University of Peradeniya'],
        ['email' => 'moratuwaadmin@gmail.com', 'password' => 'moratuwa123', 'campus' => 'University of Moratuwa']
    ];
    
    foreach ($admins as $admin) {
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->execute([$admin['email']]);
        
        if (!$stmt->fetch()) {
            $passwordHash = password_hash($admin['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admins (email, password_hash, campus) VALUES (?, ?, ?)");
            $stmt->execute([$admin['email'], $passwordHash, $admin['campus']]);
        }
    }
}

// Initialize database when this file is included
try {
    initializeDatabase();
} catch (Exception $e) {
    // Log error but don't die - allow the page to load
    error_log("Database initialization warning: " . $e->getMessage());
}
?>
