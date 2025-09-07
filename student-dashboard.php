<?php
require_once 'session_check.php';
require_once 'config.php';
requireStudent();

$user = getCurrentUser();

// Get database connection
$pdo = getDBConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard - EduConnect SL</title>
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

        .welcome-section {
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            text-align: center;
        }
        
        .welcome-section h2 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .welcome-section p {
            color: var(--text-secondary);
            font-size: 1.125rem;
            font-weight: 500;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .action-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            background: var(--primary-light);
            width: 60px;
            height: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .action-card:hover .action-icon {
            transform: scale(1.1);
            background: var(--primary-color);
            color: white;
        }

        .action-title {
            font-size: 1.25rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .action-description {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        
        .action-btn {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
            width: 100%;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .dashboard-section {
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            display: none; /* Hidden by default */
        }

        .dashboard-section.active {
            display: block; /* Show when active */
        }
        
        .section-header {
            margin-bottom: 1.5rem;
        }
        
        .section-header h3 {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-header h3 i {
            color: var(--primary-color);
        }
        
        .section-header p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h4 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .empty-state p {
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .applications-grid,
        .inquiries-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .application-card,
        .inquiry-card {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            transition: var(--transition);
            position: relative;
            background: var(--bg-primary);
        }
        
        .application-card:hover,
        .inquiry-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .application-card.status-pending,
        .inquiry-card.status-pending {
            border-left: 4px solid var(--accent-color);
        }
        
        .application-card.status-approved,
        .inquiry-card.status-answered {
            border-left: 4px solid var(--secondary-color);
        }
        
        .application-card.status-rejected {
            border-left: 4px solid #e74c3c;
        }
        
        .inquiry-card.status-closed {
            border-left: 4px solid var(--text-light);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .card-header h4 {
            color: var(--text-primary);
            font-size: 1.125rem;
            margin: 0;
            flex: 1;
            margin-right: 1rem;
            font-weight: 600;
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
        
        .status-badge.status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-badge.status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.status-answered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.status-closed {
            background: #e5e7eb;
            color: #374151;
        }

        .card-details {
            margin-bottom: 1rem;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            padding: 0.25rem 0;
        }
        
        .detail-item .label {
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        .detail-item .value {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .card-actions {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
        
        .status-text {
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .status-text.pending {
            color: var(--accent-color);
        }

        .status-text.success {
            color: var(--secondary-color);
        }

                 .status-text.error {
             color: #e74c3c;
         }

         /* Message Content Styles */
         .message-content {
             background: var(--bg-secondary);
             border: 1px solid var(--border-color);
             border-radius: var(--border-radius);
             padding: 1rem;
             margin-top: 0.5rem;
             font-size: 0.875rem;
             line-height: 1.5;
             color: var(--text-primary);
         }

         .admin-response {
             background: #f0f9ff;
             border: 1px solid #bae6fd;
             border-radius: var(--border-radius);
             padding: 1rem;
             margin-top: 0.5rem;
             font-size: 0.875rem;
             line-height: 1.5;
             color: var(--text-primary);
         }

         .response-timestamp {
             margin-top: 0.75rem;
             color: var(--text-secondary);
             font-style: italic;
             font-size: 0.75rem;
         }

                 .detail-item .label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .detail-item .value {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Enhanced card styling for better visual hierarchy */
        .application-card,
        .inquiry-card {
            position: relative;
            overflow: hidden;
        }

        .application-card::before,
        .inquiry-card::before {
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

        .application-card:hover::before,
        .inquiry-card:hover::before {
            opacity: 1;
        }

        /* Improved status badge styling */
        .status-badge {
            position: relative;
            overflow: hidden;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .status-badge:hover::before {
            left: 100%;
        }

        /* Responsive Design */
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

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .applications-grid,
            .inquiries-grid {
                grid-template-columns: 1fr;
            }

            .top-bar {
                padding: 1rem;
            }

            .top-bar-left h1 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .welcome-section h2 {
                font-size: 1.75rem;
            }

            .action-icon {
                width: 50px;
                height: 50px;
                font-size: 2rem;
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
                    <a href="#dashboard" class="nav-link active" data-section="dashboard">
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
                    <a href="#applications" class="nav-link" data-section="applications">
                        <i class="fas fa-file-alt"></i>
                        Applications
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#inquiries" class="nav-link" data-section="inquiries">
                        <i class="fas fa-comments"></i>
                        Inquiries
                    </a>
                </div>
                <div class="nav-item">
                    <a href="profile.php" class="nav-link">
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
                    <h1>Student Dashboard</h1>
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
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h2>Welcome to Your Dashboard</h2>
                    <p>Manage your courses, track applications, and stay updated with your academic journey</p>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="action-card">
                        <div class="action-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                        <h3 class="action-title"> Courses</h3>
                        <p class="action-description">Explore Program and Courses</p>
                        <a href="courses.php" class="action-btn">View Courses</a>
            </div>
            
                    <div class="action-card">
                        <div class="action-icon">
                    <i class="fas fa-university"></i>
                </div>
                        <h3 class="action-title">Universities</h3>
                        <p class="action-description">Explore universities </p>
                        <a href="universities.php" class="action-btn">Browse Universities</a>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="action-title">My Profile</h3>
                        <p class="action-description">Update your personal information</p>
                        <a href="profile.php" class="action-btn">Edit Profile</a>
            </div>
        </div>
        
        <!-- Course Applications Section -->
                <div class="dashboard-section" id="applications" style="display: none;">
            <div class="section-header">
                        <h3><i class="fas fa-file-alt"></i> My Course Applications</h3>
                        <p>Track your course applications and their current status</p>
            </div>
            
            <div class="applications-container">
                <?php
                // Fetch student's course applications
                $applicationsStmt = $pdo->prepare("
                    SELECT 
                        ca.id,
                        ca.application_number,
                        ca.course_name,
                        ca.university,
                        ca.study_level,
                        ca.program,
                        ca.status,
                        ca.application_date,
                        ca.review_date
                    FROM course_applications ca
                    WHERE ca.user_id = ?
                    ORDER BY ca.application_date DESC
                ");
                $applicationsStmt->execute([$_SESSION['user_id']]);
                $applications = $applicationsStmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($applications)): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                                <h4>No Course Applications</h4>
                                <p>You haven't applied for any courses yet. Start your academic journey by exploring available courses.</p>
                                <a href="courses.php" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                    Browse Courses
                                </a>
                    </div>
                <?php else: ?>
                    <div class="applications-grid">
                        <?php foreach ($applications as $application): ?>
                            <div class="application-card status-<?php echo $application['status'] ?? 'pending'; ?>">
                                        <div class="card-header">
                                            <h4><?php echo htmlspecialchars($application['course_name'] ?? 'Course Name Not Available'); ?></h4>
                                    <span class="status-badge status-<?php echo $application['status'] ?? 'pending'; ?>">
                                        <?php echo ucfirst($application['status'] ?? 'pending'); ?>
                                    </span>
                                </div>
                                
                                        <div class="card-details">
                                    <div class="detail-item">
                                        <span class="label">Application #:</span>
                                        <span class="value"><?php echo htmlspecialchars($application['application_number'] ?? '#' . ($application['id'] ?? 'N/A')); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">University:</span>
                                        <span class="value"><?php echo htmlspecialchars($application['university'] ?? 'University Not Specified'); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Study Level:</span>
                                        <span class="value"><?php echo htmlspecialchars($application['study_level'] ?? 'Not Specified'); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Program:</span>
                                        <span class="value"><?php echo htmlspecialchars($application['program'] ?? 'Not Specified'); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Applied:</span>
                                        <span class="value"><?php echo isset($application['application_date']) ? date('M d, Y', strtotime($application['application_date'])) : 'Date Not Available'; ?></span>
                                    </div>
                                    <?php if (isset($application['review_date']) && $application['review_date']): ?>
                                        <div class="detail-item">
                                            <span class="label">Reviewed:</span>
                                            <span class="value"><?php echo date('M d, Y', strtotime($application['review_date'])); ?></span>
                                        </div>
                                    <?php endif; ?>
                                                                </div>
                                
                                <div class="card-actions">
                                    <?php if ($application['status'] === 'pending'): ?>
                                                <span class="status-text pending">
                                                    <i class="fas fa-clock"></i>
                                                    Under Review
                                                </span>
                                    <?php elseif ($application['status'] === 'approved'): ?>
                                                <span class="status-text success">
                                                    <i class="fas fa-check-circle"></i>
                                                    Application Approved!
                                                </span>
                                    <?php elseif ($application['status'] === 'rejected'): ?>
                                                <span class="status-text error">
                                                    <i class="fas fa-times-circle"></i>
                                                    Application Rejected
                                                </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- My Inquiries Section -->
                <div class="dashboard-section" id="inquiries" style="display: none;">
            <div class="section-header">
                        <h3><i class="fas fa-comments"></i> My Inquiries</h3>
                        <p>Track your submitted inquiries and responses from university administrators</p>
            </div>
            
            <div class="inquiries-container">
                <?php
                // Fetch student's inquiries
                $inquiriesStmt = $pdo->prepare("
                    SELECT 
                        i.id,
                        i.subject,
                        i.message,
                        i.response,
                        i.response_status,
                        i.university_id,
                        i.created_at,
                        i.response_date,
                        u.name as university_name
                    FROM inquiries i 
                    LEFT JOIN universities u ON i.university_id = u.id
                    WHERE i.student_id = ? 
                    ORDER BY i.created_at DESC
                ");
                $inquiriesStmt->execute([$user['id']]);
                $inquiries = $inquiriesStmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($inquiries)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                                <h4>No Inquiries Yet</h4>
                        <p>You haven't submitted any inquiries yet. Use the contact form to ask questions about courses and universities.</p>
                                <a href="contact.php" class="btn btn-primary">
                                    <i class="fas fa-envelope"></i>
                                    Submit Inquiry
                                </a>
                    </div>
                <?php else: ?>
                    <div class="inquiries-grid">
                        <?php foreach ($inquiries as $inquiry): ?>
                            <div class="inquiry-card status-<?php echo $inquiry['response_status']; ?>">
                                         <div class="card-header">
                                             <h4><?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $inquiry['subject']))); ?></h4>
                                    <span class="status-badge status-<?php echo $inquiry['response_status']; ?>">
                                        <?php echo ucfirst($inquiry['response_status']); ?>
                                    </span>
                                </div>
                                
                                         <div class="card-details">
                                             <div class="detail-item">
                                                 <span class="label">University:</span>
                                                 <span class="value"><?php echo htmlspecialchars($inquiry['university_name'] ?? $inquiry['university_id']); ?></span>
                                             </div>
                                             <div class="detail-item">
                                                 <span class="label">Submitted:</span>
                                                 <span class="value"><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></span>
                                             </div>
                                </div>
                                
                                         <div class="card-details">
                                             <div class="detail-item">
                                                 <span class="label">Your Message:</span>
                                                 <div class="message-content">
                                        <?php echo nl2br(htmlspecialchars($inquiry['message'])); ?>
                                                 </div>
                                    </div>
                                    
                                    <?php if ($inquiry['response']): ?>
                                                 <div class="detail-item">
                                                     <span class="label">Admin Response:</span>
                                                     <div class="admin-response">
                                            <?php echo nl2br(htmlspecialchars($inquiry['response'])); ?>
                                                         <div class="response-timestamp">
                                                             <small>Responded on <?php echo date('M d, Y g:i A', strtotime($inquiry['response_date'])); ?></small>
                                                         </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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

        // Handle navigation links
        document.querySelectorAll('.nav-link[data-section]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Show/hide sections based on navigation
                const targetSection = this.getAttribute('data-section');
                showSection(targetSection);
            });
        });

        // Function to show specific section
        function showSection(sectionName) {
            // Hide all sections first
            const allSections = document.querySelectorAll('.dashboard-section, .welcome-section, .quick-actions');
            allSections.forEach(section => {
                section.classList.remove('active');
                section.style.display = 'none';
            });

            // Update page title
            const topBarTitle = document.querySelector('.top-bar-left h1');
            
            // Show the selected section
            if (sectionName === 'dashboard') {
                // Show dashboard content
                document.querySelector('.welcome-section').style.display = 'block';
                document.querySelector('.quick-actions').style.display = 'grid';
                topBarTitle.textContent = 'Student Dashboard';
            } else if (sectionName === 'applications') {
                // Show only applications section
                const applicationsSection = document.getElementById('applications');
                applicationsSection.style.display = 'block';
                applicationsSection.classList.add('active');
                topBarTitle.textContent = 'My Applications';
            } else if (sectionName === 'inquiries') {
                // Show only inquiries section
                const inquiriesSection = document.getElementById('inquiries');
                inquiriesSection.style.display = 'block';
                inquiriesSection.classList.add('active');
                topBarTitle.textContent = 'My Inquiries';
            }
        }

        // Initialize dashboard view
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading animation
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.3s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);

            // Check if there's a specific section to show
            const sectionToShow = sessionStorage.getItem('showSection');
            if (sectionToShow) {
                // Remove the stored value
                sessionStorage.removeItem('showSection');
                // Show the specific section
                showSection(sectionToShow);
                // Update the active nav link
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('data-section') === sectionToShow) {
                        link.classList.add('active');
                    }
                });
            } else {
                // Show dashboard by default
                showSection('dashboard');
            }
        });
    </script>
</body>
</html>
