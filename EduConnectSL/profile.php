<?php
require_once 'config.php';
require_once 'session_check.php';
requireStudent();

$user = getCurrentUser();
$message = '';
$error = '';

// Check what user data we have
if (!$user) {
    die("Error: No user data found in session");
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getDBConnection();
        
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        
        // Validation
        if (empty($firstName) || empty($lastName) || empty($phone)) {
            throw new Exception('First name, last name, and phone are required');
        }
        
        // If user wants to change password
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                throw new Exception('Current password is required to change password');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('New passwords do not match');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('New password must be at least 8 characters long');
            }
            
            // Verify current password
            $stmt = $pdo->prepare("SELECT password_hash FROM students WHERE id = ?");
            $stmt->execute([$user['id']]);
            $currentUser = $stmt->fetch();
            
            if (!password_verify($currentPassword, $currentUser['password_hash'])) {
                throw new Exception('Current password is incorrect');
            }
            
            // Hash new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update with new password
            $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, phone = ?, password_hash = ? WHERE id = ?");
            $stmt->execute([$firstName, $lastName, $phone, $newPasswordHash, $user['id']]);
        } else {
            // Update without password change
            $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$firstName, $lastName, $phone, $user['id']]);
        }
        
        // Update session data
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        
        $message = 'Profile updated successfully!';
        
        // Refresh user data
        $user = getCurrentUser();
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        $error = 'Database error occurred';
    }
}

// Get current user data
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone FROM students WHERE id = ?");
    $stmt->execute([$user['id']]);
    $userData = $stmt->fetch();
    
    if (!$userData) {
        $error = "Could not find user data in database";
    }
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #dbeafe;
            --secondary-color: #10b981;
            --secondary-dark: #059669;
            --accent-color: #f59e0b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --border-radius-lg: 12px;
            --transition: all 0.3s ease;
            --gradient-primary: linear-gradient(135deg, #1e40af, #1e3a8a);
            --gradient-secondary: linear-gradient(135deg, #10b981, #059669);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--gradient-primary);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .sidebar-header p {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        /* Top Bar */
        .top-bar {
            background: var(--bg-primary);
            padding: 1rem 2rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-bar-left h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .student-avatar:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        .student-details h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .student-details p {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        .profile-header {
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            text-align: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-header h2 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            position: relative;
        }

        .profile-header h2::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .profile-header h2 i {
            font-size: 2rem;
        }

        .profile-header p {
            color: var(--text-secondary);
            font-size: 1.125rem;
            font-weight: 500;
        }
        
        .profile-section {
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .profile-section:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .profile-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            opacity: 0;
            transition: var(--transition);
        }

        .profile-section:hover::before {
            opacity: 1;
        }

        .profile-section h3 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }

        .profile-section h3::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--gradient-primary);
            border-radius: 1px;
        }

        .profile-section h3 i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: var(--transition);
        }

        .form-group:focus-within label {
            color: var(--primary-color);
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            transform: translateY(-1px);
        }

        .form-group input:not([readonly]):hover {
            border-color: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }
        
        .form-group input[readonly] {
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            cursor: not-allowed;
        }
        
        .form-group small {
            display: block;
            margin-top: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-style: italic;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--border-color);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn {
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: var(--transition);
            min-width: 160px;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--gradient-secondary);
            color: white;
            box-shadow: var(--shadow-md);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .message {
            padding: 1.5rem;
            border-radius: var(--border-radius-lg);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            animation: slideIn 0.5s ease;
            box-shadow: var(--shadow-sm);
            border: 1px solid transparent;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .message.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: 1px solid #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }
        
        .message.error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }
        
        /* Messages Section Styles */
        .messages-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .messages-header h3 {
            margin: 0;
        }
        
        .btn-sm {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            min-width: auto;
        }
        
        .empty-messages {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }
        
        .empty-messages i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: var(--text-light);
        }

        .empty-messages h4 {
            margin-bottom: 0.75rem;
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .empty-messages a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .empty-messages a:hover {
            text-decoration: underline;
        }
        
        .messages-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .message-item {
            background: white;
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: var(--transition);
        }
        
        .message-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .message-item.pending {
            border-left: 4px solid var(--accent-color);
        }
        
        .message-item.answered {
            border-left: 4px solid var(--secondary-color);
        }
        
        .message-item.closed {
            border-left: 4px solid var(--text-light);
        }
        
        .message-header {
            background: var(--bg-secondary);
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .message-info h4 {
            margin: 0 0 0.75rem 0;
            color: var(--text-primary);
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .message-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .message-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-badge.status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-badge.status-answered {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-badge.status-closed {
            background: #e5e7eb;
            color: #374151;
        }
        
        .message-content {
            padding: 1.5rem;
        }
        
        .message-text {
            margin-bottom: 1.5rem;
            line-height: 1.6;
            color: var(--text-primary);
        }
        
        .admin-response {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-top: 1rem;
        }
        
        .admin-response strong {
            color: #0369a1;
        }
        
        .response-date {
            margin-top: 0.75rem;
            color: var(--text-secondary);
            font-style: italic;
            font-size: 0.875rem;
        }
        
        .error-message {
            text-align: center;
            padding: 2rem;
            color: #dc2626;
        }
        
        .error-message i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .content-area {
                padding: 1rem;
            }

            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .btn-group {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .top-bar {
                padding: 1rem;
            }

            .top-bar-left h1 {
                font-size: 1.5rem;
            }

            .profile-section,
            .profile-header,
            .btn-group {
                max-width: 100%;
                margin-left: 0;
                margin-right: 0;
            }
        }

        @media (max-width: 480px) {
            .profile-header h2 {
                font-size: 1.75rem;
            }

            .message-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .message-header,
            .message-content {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Student Portal</h2>
                <p>Welcome, <?php echo htmlspecialchars($user['name'] ?? 'Student'); ?></p>
            </div>
            
            <div class="sidebar-nav">
                <div class="nav-item">
                    <a href="student-dashboard.php" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="courses.php" class="nav-link">
                        <i class="fas fa-graduation-cap"></i>
                        My Courses
                    </a>
                </div>
                <div class="nav-item">
                    <a href="universities.php" class="nav-link">
                        <i class="fas fa-university"></i>
                        Universities
                    </a>
                </div>
                <div class="nav-item">
                    <a href="student-dashboard.php" class="nav-link" onclick="showApplications()">
                        <i class="fas fa-file-alt"></i>
                        Applications
                    </a>
                </div>
                <div class="nav-item">
                    <a href="student-dashboard.php" class="nav-link" onclick="showInquiries()">
                        <i class="fas fa-comments"></i>
                        Inquiries
                    </a>
                </div>
                <div class="nav-item">
                    <a href="profile.php" class="nav-link active">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <h1>My Profile</h1>
                </div>
                
                <div class="top-bar-right">
                    <button class="mobile-toggle" id="mobileToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="student-info">
                        <div class="student-avatar">
                            <?php echo strtoupper(substr($user['name'] ?? 'S', 0, 1)); ?>
                        </div>
                        <div class="student-details">
                            <h4><?php echo htmlspecialchars($user['name'] ?? 'Student'); ?></h4>
                            <p>Student</p>
                        </div>
                    </div>
                    
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content-area">
            <div class="profile-header">
                    <h2><i class="fas fa-user-circle"></i> My Profile</h2>
                <p>Manage your account information and settings</p>
            </div>
            
                <?php if ($message): ?>
                    <div class="message success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="message error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['registration_success'])): ?>
                    <div class="message success">
                        <i class="fas fa-check-circle"></i> Course registration successful! You will receive a confirmation email shortly. Your registration is pending admin approval.
                    </div>
                <?php endif; ?>
                
                                 <?php if (!$error && $userData): ?>
                 <form method="POST" action="profile.php">
                                                              <div class="profile-section">
                            <h3><i class="fas fa-user"></i> Personal Information</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">First Name</label>
                                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
                                    <small>Email cannot be changed</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
                                </div>
                            </div>
                        </div>
                     
                                             <div class="profile-section">
                            <h3><i class="fas fa-lock"></i> Change Password</h3>
                            <p style="color: var(--text-secondary); margin-bottom: 1rem; font-style: italic;">Leave password fields empty if you don't want to change your password</p>
                            
                            <div class="form-row-3">
                                <div class="form-group">
                                    <label for="currentPassword">Current Password</label>
                                    <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter your current password">
                                </div>
                                
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password (min 8 characters)">
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm New Password</label>
                                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>
                     
                     <div class="btn-group">
                         <a href="index.php" class="btn btn-secondary">
                             <i class="fas fa-home"></i> Back to Home
                         </a>
                         <a href="student-dashboard.php" class="btn btn-outline">
                             <i class="fas fa-tachometer-alt"></i> Dashboard
                         </a>
                         <button type="submit" class="btn btn-primary">
                             <i class="fas fa-save"></i> Save Changes
                         </button>
                     </div>
                 </form>
                 <?php endif; ?>
            </main>
                     </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobileToggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Function to show applications section on dashboard
        function showApplications() {
            // Store the section to show in sessionStorage
            sessionStorage.setItem('showSection', 'applications');
            // Navigate to dashboard
            window.location.href = 'student-dashboard.php';
        }

        // Function to show inquiries section on dashboard
        function showInquiries() {
            // Store the section to show in sessionStorage
            sessionStorage.setItem('showSection', 'inquiries');
            // Navigate to dashboard
            window.location.href = 'student-dashboard.php';
        }

        // Add loading animation
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.3s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
</body>
</html>
