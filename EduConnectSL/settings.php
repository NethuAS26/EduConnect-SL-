<?php
require_once 'session_check.php';
requireStudent();

$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .settings-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .settings-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .settings-header {
            background: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .settings-header h1 {
            margin: 0;
            font-size: 2rem;
        }
        
        .settings-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .settings-content {
            padding: 40px;
        }
        
        .settings-section {
            margin-bottom: 40px;
        }
        
        .settings-section h2 {
            color: var(--text-primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .setting-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .setting-item:last-child {
            border-bottom: none;
        }
        
        .setting-info h3 {
            margin: 0 0 5px 0;
            color: var(--text-primary);
        }
        
        .setting-info p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .toggle-switch {
            position: relative;
            width: 50px;
            height: 24px;
            background: var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .toggle-switch.active {
            background: var(--primary-color);
        }
        
        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }
        
        .toggle-switch.active::after {
            transform: translateX(26px);
        }
        
        .back-home {
            position: fixed;
            top: 20px;
            left: 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.3s ease;
            z-index: 2000;
        }
        
        .back-home:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-home">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>
    
    <div class="settings-container">
        <div class="settings-card">
            <div class="settings-header">
                <h1><i class="fas fa-cog"></i> Settings</h1>
                <p>Manage your account preferences and notifications</p>
            </div>
            
            <div class="settings-content">
                <div class="settings-section">
                    <h2><i class="fas fa-bell"></i> Notifications</h2>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Email Notifications</h3>
                            <p>Receive updates about new courses and universities</p>
                        </div>
                        <div class="toggle-switch active" id="emailToggle"></div>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Course Updates</h3>
                            <p>Get notified when your enrolled courses have updates</p>
                        </div>
                        <div class="toggle-switch active" id="courseToggle"></div>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Scholarship Alerts</h3>
                            <p>Receive notifications about new scholarship opportunities</p>
                        </div>
                        <div class="toggle-switch" id="scholarshipToggle"></div>
                    </div>
                </div>
                
                <div class="settings-section">
                    <h2><i class="fas fa-shield-alt"></i> Privacy & Security</h2>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Profile Visibility</h3>
                            <p>Allow other students to see your profile information</p>
                        </div>
                        <div class="toggle-switch active" id="profileToggle"></div>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Two-Factor Authentication</h3>
                            <p>Add an extra layer of security to your account</p>
                        </div>
                        <div class="toggle-switch" id="twoFactorToggle"></div>
                    </div>
                </div>
            </div>
            
            <div class="btn-group" style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <a href="student-dashboard.php" class="btn btn-outline">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle switch functionality
        document.querySelectorAll('.toggle-switch').forEach(toggle => {
            toggle.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
    </script>
    <script src="script.js"></script>
</body>
</html>
