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
    <title>My Courses - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .courses-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .courses-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .courses-header {
            background: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .courses-header h1 {
            margin: 0;
            font-size: 2rem;
        }
        
        .courses-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .courses-content {
            padding: 40px;
            text-align: center;
        }
        
        .empty-state {
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 15px;
        }
        
        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 30px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
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
    
    <div class="courses-container">
        <div class="courses-card">
            <div class="courses-header">
                <h1><i class="fas fa-graduation-cap"></i> My Courses</h1>
                <p>Track your enrolled courses and progress</p>
            </div>
            
            <div class="courses-content">
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>No Courses Enrolled Yet</h3>
                    <p>You haven't enrolled in any courses yet. Start exploring our course catalog to find the perfect program for you.</p>
                    <div class="btn-group" style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                        <a href="courses.php" class="btn btn-primary">
                            <i class="fas fa-search"></i> Browse Courses
                        </a>
                        <a href="student-dashboard.php" class="btn btn-outline">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
