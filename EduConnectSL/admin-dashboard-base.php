<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$campus = $_SESSION['campus'];
$admin_email = $_SESSION['admin_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $campus; ?> Admin Dashboard - EduConnect SL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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
            transition: all 0.3s ease;
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
            background: white;
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
            color: var(--dark-color);
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .admin-details h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .admin-details p {
            font-size: 0.75rem;
            color: var(--secondary-color);
        }

        .logout-btn {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .card-icon.primary { background: var(--primary-color); }
        .card-icon.success { background: var(--success-color); }
        .card-icon.warning { background: var(--warning-color); }
        .card-icon.info { background: var(--info-color); }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .card-description {
            font-size: 0.875rem;
            color: var(--secondary-color);
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .add-btn {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .add-btn:hover {
            background: #059669;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            background: var(--light-color);
            font-weight: 600;
            color: var(--dark-color);
        }

        .data-table tr:hover {
            background: var(--light-color);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
        }

        .btn-delete {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
        }

        /* Mobile Responsiveness */
        .mobile-toggle {
            display: none;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 1.125rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .top-bar {
                padding: 1rem;
            }

            .content-area {
                padding: 1rem;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--border-color);
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><?php echo $campus; ?></h2>
                <p>Admin Dashboard</p>
            </div>
            
            <div class="sidebar-nav">
                <div class="nav-item">
                    <a href="#overview" class="nav-link active" data-section="overview">
                        <i class="fas fa-chart-line"></i>
                        Overview
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#courses" class="nav-link" data-section="courses">
                        <i class="fas fa-graduation-cap"></i>
                        Courses
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#students" class="nav-link" data-section="students">
                        <i class="fas fa-users"></i>
                        Students
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#enrollments" class="nav-link" data-section="enrollments">
                        <i class="fas fa-user-plus"></i>
                        Enrollments
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#analytics" class="nav-link" data-section="analytics">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#settings" class="nav-link" data-section="settings">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <h1><?php echo $campus; ?> Admin Dashboard</h1>
                </div>
                
                <div class="top-bar-right">
                    <button class="mobile-toggle" id="mobileToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="admin-info">
                        <div class="admin-avatar">
                            <?php echo strtoupper(substr($admin_email, 0, 1)); ?>
                        </div>
                        <div class="admin-details">
                            <h4><?php echo $campus; ?> Admin</h4>
                            <p><?php echo $admin_email; ?></p>
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
                <!-- Overview Section -->
                <section id="overview" class="dashboard-section active">
                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Total Students</h3>
                                <div class="card-icon primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="card-value" id="totalStudents">0</div>
                            <p class="card-description">Enrolled students this semester</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Active Courses</h3>
                                <div class="card-icon success">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="card-value" id="activeCourses">0</div>
                            <p class="card-description">Available courses/programs</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">New Enrollments</h3>
                                <div class="card-icon warning">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                            <div class="card-value" id="newEnrollments">0</div>
                            <p class="card-description">This month</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Completion Rate</h3>
                                <div class="card-icon info">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="card-value" id="completionRate">0%</div>
                            <p class="card-description">Student success rate</p>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Recent Activity</h3>
                        </div>
                        <div id="recentActivity">
                            <!-- Recent activity will be loaded here -->
                        </div>
                    </div>
                </section>

                <!-- Courses Section -->
                <section id="courses" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Campus Courses</h3>
                            <button class="add-btn" onclick="openAddCourseModal()">
                                <i class="fas fa-plus"></i>
                                Add Course
                            </button>
                        </div>
                        <div id="coursesTable">
                            <!-- Courses table will be loaded here -->
                        </div>
                    </div>
                </section>

                <!-- Students Section -->
                <section id="students" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Student Directory</h3>
                        </div>
                        <div id="studentsTable">
                            <!-- Students table will be loaded here -->
                        </div>
                    </div>
                </section>

                <!-- Enrollments Section -->
                <section id="enrollments" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Course Enrollments</h3>
                        </div>
                        <div id="enrollmentsTable">
                            <!-- Enrollments table will be loaded here -->
                        </div>
                    </div>
                </section>

                <!-- Analytics Section -->
                <section id="analytics" class="dashboard-section" style="display: none;">
                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Enrollment Trends</h3>
                            </div>
                            <div id="enrollmentChart">
                                <!-- Chart will be loaded here -->
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Popular Courses</h3>
                            </div>
                            <div id="popularCoursesChart">
                                <!-- Chart will be loaded here -->
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Settings Section -->
                <section id="settings" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Dashboard Settings</h3>
                        </div>
                        <div id="settingsContent">
                            <!-- Settings content will be loaded here -->
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <script>
        // Dashboard functionality
        class AdminDashboard {
            constructor() {
                this.currentSection = 'overview';
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.loadOverviewData();
                this.setupMobileToggle();
            }

            setupEventListeners() {
                // Navigation
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const section = link.getAttribute('data-section');
                        this.showSection(section);
                    });
                });
            }

            setupMobileToggle() {
                const mobileToggle = document.getElementById('mobileToggle');
                const sidebar = document.getElementById('sidebar');
                
                mobileToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', (e) => {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                            sidebar.classList.remove('open');
                        }
                    }
                });
            }

            showSection(sectionName) {
                // Hide all sections
                document.querySelectorAll('.dashboard-section').forEach(section => {
                    section.style.display = 'none';
                });

                // Remove active class from all nav links
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });

                // Show selected section
                const selectedSection = document.getElementById(sectionName);
                if (selectedSection) {
                    selectedSection.style.display = 'block';
                }

                // Add active class to selected nav link
                const selectedLink = document.querySelector(`[data-section="${sectionName}"]`);
                if (selectedLink) {
                    selectedLink.classList.add('active');
                }

                this.currentSection = sectionName;
                this.loadSectionData(sectionName);
            }

            async loadSectionData(sectionName) {
                switch (sectionName) {
                    case 'overview':
                        await this.loadOverviewData();
                        break;
                    case 'courses':
                        await this.loadCoursesData();
                        break;
                    case 'students':
                        await this.loadStudentsData();
                        break;
                    case 'enrollments':
                        await this.loadEnrollmentsData();
                        break;
                    case 'analytics':
                        await this.loadAnalyticsData();
                        break;
                    case 'settings':
                        await this.loadSettingsData();
                        break;
                }
            }

            async loadOverviewData() {
                try {
                    // Load dashboard statistics
                    await this.loadDashboardStats();
                    await this.loadRecentActivity();
                } catch (error) {
                    console.error('Error loading overview data:', error);
                }
            }

            async loadDashboardStats() {
                // Simulate loading dashboard statistics
                // In a real application, this would fetch from your API
                document.getElementById('totalStudents').textContent = '1,247';
                document.getElementById('activeCourses').textContent = '23';
                document.getElementById('newEnrollments').textContent = '89';
                document.getElementById('completionRate').textContent = '94%';
            }

            async loadRecentActivity() {
                const recentActivity = document.getElementById('recentActivity');
                recentActivity.innerHTML = `
                    <div style="padding: 1rem; text-align: center; color: #64748b;">
                        <i class="fas fa-clock" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Recent activity will be displayed here</p>
                    </div>
                `;
            }

            async loadCoursesData() {
                const coursesTable = document.getElementById('coursesTable');
                coursesTable.innerHTML = `
                    <div style="padding: 1rem; text-align: center; color: #64748b;">
                        <i class="fas fa-graduation-cap" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Courses data will be loaded here</p>
                    </div>
                `;
            }

            async loadStudentsData() {
                const studentsTable = document.getElementById('studentsTable');
                studentsTable.innerHTML = `
                    <div style="padding: 1rem; text-align: center; color: #64748b;">
                        <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Students data will be loaded here</p>
                    </div>
                `;
            }

            async loadEnrollmentsData() {
                const enrollmentsTable = document.getElementById('enrollmentsTable');
                enrollmentsTable.innerHTML = `
                    <div style="padding: 1rem; text-align: center; color: #64748b;">
                        <i class="fas fa-user-plus" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Enrollments data will be loaded here</p>
                    </div>
                `;
            }

            async loadAnalyticsData() {
                const enrollmentChart = document.getElementById('enrollmentChart');
                const popularCoursesChart = document.getElementById('popularCoursesChart');
                
                enrollmentChart.innerHTML = `
                    <div style="padding: 1rem; text-align: center; color: #64748b;">
                        <i class="fas fa-chart-line" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Enrollment trends chart will be displayed here</p>
                    </div>
                `;
                
                popularCoursesChart.innerHTML = `
                    <div style="padding: 1rem; text-align: center; color: #64748b;">
                        <i class="fas fa-chart-bar" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Popular courses chart will be displayed here</p>
                    </div>
                `;
            }

            async loadSettingsData() {
                const settingsContent = document.getElementById('settingsContent');
                settingsContent.innerHTML = `
                    <div style="padding: 1rem; text-align: center; color: #64748b;">
                        <i class="fas fa-cog" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Dashboard settings will be displayed here</p>
                    </div>
                `;
            }
        }

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new AdminDashboard();
        });

        // Global functions for modals and actions
        function openAddCourseModal() {
            alert('Add Course modal will be implemented here');
        }
    </script>
</body>
</html>
