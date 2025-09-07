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
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
        throw new Exception('All fields are required');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    if ($password !== $confirmPassword) {
        throw new Exception('Passwords do not match');
    }
    
    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('Email already registered');
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new student
    $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, email, phone, password_hash) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $phone, $passwordHash]);
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Redirecting to login...',
        'redirect' => 'login.php'
    ]);
    
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
