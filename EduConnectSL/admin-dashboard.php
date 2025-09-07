<?php
session_start();
require_once 'config.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$campus = $_SESSION['campus'] ?? 'Unknown Campus';
$adminEmail = $_SESSION['admin_email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo htmlspecialchars($campus); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .admin-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #667eea;
        }
        
        .admin-card h3 {
            color: #333;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .admin-actions {
            margin-top: 1rem;
        }
        
        .admin-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            transition: background 0.3s ease;
        }
        
        .admin-btn:hover {
            background: #5a6fd8;
        }
        
        .logout-btn {
            background: #e74c3c;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .campus-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .campus-info h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .campus-info p {
            color: #666;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1><i class="fas fa-user-shield"></i> Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($adminEmail); ?></p>
        <a href="logout.php" class="admin-btn logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
    
    <div class="admin-container">
        <div class="campus-info">
            <h2><?php echo htmlspecialchars($campus); ?> Campus</h2>
            <p>Administrative Control Panel</p>
        </div>
        
        <div class="admin-grid">
            <div class="admin-card">
                <h3><i class="fas fa-users"></i> Student Management</h3>
                <p>Manage student registrations, view student information, and handle course applications.</p>
                <div class="admin-actions">
                    <a href="student-dashboard.php" class="admin-btn">View Students</a>
                </div>
            </div>
            
            <div class="admin-card">
                <h3><i class="fas fa-book"></i> Course Management</h3>
                <p>Manage course offerings, view course registrations, and handle academic programs.</p>
                <div class="admin-actions">
                    <a href="courses.php" class="admin-btn">View Courses</a>
                </div>
            </div>
            
            <div class="admin-card">
                <h3><i class="fas fa-chart-bar"></i> Reports & Analytics</h3>
                <p>Generate reports, view statistics, and analyze campus data.</p>
                <div class="admin-actions">
                    <a href="#" class="admin-btn">Generate Reports</a>
                </div>
            </div>
            
            <div class="admin-card">
                <h3><i class="fas fa-cog"></i> System Settings</h3>
                <p>Configure system settings, manage user permissions, and system maintenance.</p>
                <div class="admin-actions">
                    <a href="settings.php" class="admin-btn">Settings</a>
                </div>
            </div>
            
            <div class="admin-card">
                <h3><i class="fas fa-bell"></i> Notifications</h3>
                <p>Manage system notifications, announcements, and communication with students.</p>
                <div class="admin-actions">
                    <a href="notifications.php" class="admin-btn">Notifications</a>
                </div>
            </div>
            
            <div class="admin-card">
                <h3><i class="fas fa-home"></i> Back to Home</h3>
                <p>Return to the main EduConnect SL homepage.</p>
                <div class="admin-actions">
                    <a href="index.php" class="admin-btn">Go Home</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Add any JavaScript functionality here
        console.log('Admin dashboard loaded for campus: <?php echo $campus; ?>');
    </script>
</body>
</html>
