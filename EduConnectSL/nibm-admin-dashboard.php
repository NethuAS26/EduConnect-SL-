<?php
session_start();
require_once 'config.php';

// Check if admin is logged in and is from NIBM campus
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin' || $_SESSION['campus'] !== 'NIBM') {
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
    <title>NIBM Admin Dashboard - EduConnect SL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #1e3a8a;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --success-color: #059669;
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
            background: #1e40af;
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

        /* Enhanced Courses Section Styles */
        .courses-header {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .courses-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .courses-title-section {
            flex: 1;
        }

        .courses-main-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .courses-subtitle {
            color: var(--secondary-color);
            font-size: 1rem;
            margin: 0;
        }

        .courses-header-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary-action {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary-action:hover {
            background: var(--primary-dark);
        }

        .btn-secondary-action {
            background: var(--info-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-secondary-action:hover {
            background: #2563eb;
        }

        .course-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .course-stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0;
        }

        .study-levels-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .study-level-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: box-shadow 0.3s ease;
        }

        .study-level-card:hover {
            box-shadow: var(--shadow-md);
        }

        .study-level-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .study-level-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .study-level-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .study-level-details {
            flex: 1;
        }

        .study-level-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .study-level-description {
            color: var(--secondary-color);
            margin: 0;
        }

        .study-level-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-edit-study-level {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-edit-study-level:hover {
            background: #d97706;
        }

        .btn-add-program-primary {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-add-program-primary:hover {
            background: #1e40af;
        }

        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .program-card {
            background: var(--light-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .program-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .program-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .program-info h4 {
            color: var(--dark-color);
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .program-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--secondary-color);
            flex-wrap: wrap;
        }

        .program-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-add-course {
            background: var(--info-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: background-color 0.3s ease;
        }

        .btn-add-course:hover {
            background: #2563eb;
        }

        .btn-edit-program {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: background-color 0.3s ease;
        }

        .btn-edit-program:hover {
            background: #d97706;
        }

        .btn-delete-program {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: background-color 0.3s ease;
        }

        .btn-delete-program:hover {
            background: #dc2626;
        }

        .courses-list {
            margin-top: 1rem;
        }

        .course-item {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .course-item:hover {
            background: var(--light-color);
        }

        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .course-title {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.875rem;
        }

        .course-actions {
            display: flex;
            gap: 0.25rem;
        }

        .btn-edit-course {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.625rem;
            transition: background-color 0.3s ease;
        }

        .btn-edit-course:hover {
            background: #d97706;
        }

        .btn-delete-course {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.625rem;
            transition: background-color 0.3s ease;
        }

        .btn-delete-course:hover {
            background: #dc2626;
        }

        .course-description {
            font-size: 0.75rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .course-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.625rem;
            color: var(--secondary-color);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 1rem;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--secondary-color);
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .close-modal:hover {
            background: var(--light-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-actions {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .btn-back {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .btn-apply {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-apply:hover {
            background: #1e40af;
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

        /* Dark Theme Styles */
        .dark-theme {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --border-color: #404040;
            --light-color: #333333;
        }
        
        .dark-theme .sidebar {
            background: #1a1a1a;
        }
        
        .dark-theme .main-content {
            background: #2d2d2d;
        }
        
        .dark-theme .dashboard-card {
            background: #333333;
            border-color: #404040;
        }
        
        .dark-theme .table-container {
            background: #333333;
        }
        
        .dark-theme .table-header {
            background: #404040;
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

            /* Course Management Responsive */
            .courses-header-content {
                flex-direction: column;
                align-items: stretch;
            }
            
            .courses-header-actions {
                justify-content: center;
            }
            
            .study-level-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .study-level-actions {
                justify-content: center;
            }
            
            .programs-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                width: 95%;
                margin: 2% auto;
            }
        }

        /* Review Moderation Styles */
        .filter-tabs {
            display: flex;
            background: white;
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .filter-tab {
            flex: 1;
            padding: 1rem;
            border: none;
            background: transparent;
            color: var(--secondary-color);
            font-weight: 500;
            cursor: pointer;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .filter-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .filter-tab:hover:not(.active) {
            background: var(--light-color);
        }

        /* Content Management Styles */
        .content-tabs {
            display: flex;
            background: white;
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .content-tab {
            flex: 1;
            padding: 1rem;
            border: none;
            background: transparent;
            color: var(--secondary-color);
            font-weight: 500;
            cursor: pointer;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .content-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .content-tab:hover:not(.active) {
            background: var(--light-color);
        }

        .content-tab-content {
            display: none;
        }

        .content-tab-content.active {
            display: block;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--light-color);
            border-radius: 0.5rem;
        }

        .content-header h4 {
            color: var(--dark-color);
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .content-form {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background: #475569;
        }

        .btn-submit {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        .content-list {
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            min-height: 200px;
        }

        /* Review and Inquiry Item Styles */
        .review-item, .inquiry-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .review-item:hover, .inquiry-item:hover {
            background: var(--light-color);
        }

        .review-item:last-child, .inquiry-item:last-child {
            border-bottom: none;
        }

        .review-header, .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .review-info h3, .inquiry-info h3 {
            color: var(--dark-color);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .review-meta, .inquiry-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--secondary-color);
            flex-wrap: wrap;
        }

        .review-status, .inquiry-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved, .status-answered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected, .status-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .review-content, .inquiry-content {
            margin-bottom: 1rem;
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .stars {
            color: #fbbf24;
        }

        .review-comment, .inquiry-message {
            background: var(--light-color);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }

        .review-actions, .inquiry-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #1e40af;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        .moderation-form, .response-form {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 0.375rem;
        }

        .moderation-form.active, .response-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary-color);
            font-weight: 500;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
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

        .status-badge.status-waitlisted {
            background: #fef3c7;
            color: #92400e;
        }

        /* Table Actions */
        .table-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .table-actions select {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: white;
            font-size: 14px;
        }

        /* Action Buttons */
        .btn-view, .btn-approve, .btn-reject, .btn-waitlist {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view {
            background: var(--secondary-color);
            color: white;
        }

        .btn-view:hover {
            background: #5a6268;
        }

        .btn-approve {
            background: var(--success-color);
            color: white;
        }

        .btn-approve:hover {
            background: #1e40af;
        }

        .btn-reject {
            background: var(--danger-color);
            color: white;
        }

        .btn-reject:hover {
            background: #dc2626;
        }

        .btn-waitlist {
            background: var(--warning-color);
            color: white;
        }

        .btn-waitlist:hover {
            background: #d97706;
        }

        /* Chart Controls */
        .chart-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .chart-control-btn {
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--secondary-color);
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-control-btn:hover {
            background: var(--light-color);
            border-color: var(--primary-color);
        }

        .chart-control-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .filter-tabs, .content-tabs {
                flex-direction: column;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .review-meta, .inquiry-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .review-actions, .inquiry-actions {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .chart-controls {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>NIBM Campus</h2>
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
                        NIBM Courses
                    </a>
                </div>
    
                
                <div class="nav-item">
                    <a href="#applications" class="nav-link" data-section="applications">
                        <i class="fas fa-file-alt"></i>
                        Course Applications
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#analytics" class="nav-link" data-section="analytics">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="#inquiries" class="nav-link" data-section="inquiries">
                        <i class="fas fa-inbox"></i>
                        Inquiry Management
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#content" class="nav-link" data-section="content">
                        <i class="fas fa-file-alt"></i>
                        Content Management
                    </a>
                </div>
                
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <h1>NIBM Campus Admin Dashboard</h1>
                </div>
                
                <div class="top-bar-right">
                    <button class="mobile-toggle" id="mobileToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="admin-info">
                        <div class="admin-avatar">
                            N
                        </div>
                        <div class="admin-details">
                            <h4>NIBM Admin</h4>
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
                                <h3 class="card-title">NIBM Students</h3>
                                <div class="card-icon primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="card-value">700</div>
                            <p class="card-description">Enrolled at NIBM Campus</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">NIBM Programs</h3>
                                <div class="card-icon success">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="card-value">3</div>
                            <p class="card-description">Management, IT, and Languages</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Campus Locations</h3>
                                <div class="card-icon warning">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            <div class="card-value">4</div>
                            <p class="card-description">Kurunegala, Kandy, Galle, Matara</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Established</h3>
                                <div class="card-icon info">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="card-value">1996</div>
                            <p class="card-description">Kurunegala Campus</p>
                        </div>
                    </div>

                    <!-- NIBM Campus Info -->
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">NIBM Campus Information</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Locations</h4>
                                <p>Kurunegala, Kandy, Galle, and Matara</p>
                            </div>
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--success-color); margin-bottom: 0.5rem;">Established</h4>
                                <p>1996 (Kurunegala Campus)</p>
                            </div>
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--warning-color); margin-bottom: 0.5rem;">Accreditation</h4>
                                <p>UGC Recognized</p>
                            </div>
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--info-color); margin-bottom: 0.5rem;">Specialization</h4>
                                <p>Management, IT, and Languages</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Courses Section -->
                <section id="courses" class="dashboard-section" style="display: none;">
                    <!-- Enhanced Course Management Header -->
                    <div class="courses-header">
                        <div class="courses-header-content">
                            <div class="courses-title-section">
                                <h2 class="courses-main-title">
                                    <i class="fas fa-graduation-cap"></i>
                                    NIBM Course Management
                                </h2>
                                <p class="courses-subtitle">Manage academic programs, courses, and curriculum structure</p>
                            </div>
                            <div class="courses-header-actions">
                                <button class="btn-primary-action" onclick="openAddStudyLevelModal()">
                                    <i class="fas fa-plus"></i>
                                    Add Study Level
                                </button>
                                <button class="btn-secondary-action" onclick="exportCourseData()">
                                    <i class="fas fa-download"></i>
                                    Export Data
                                </button>
                            </div>
                            </div>
                        </div>
                        
                    <!-- Course Statistics Cards -->
                    <div class="course-stats-grid">
                        <div class="course-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number" id="totalStudyLevels">5</h3>
                                <p class="stat-label">Study Levels</p>
                            </div>
                        </div>
                        <div class="course-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number" id="totalPrograms">10</h3>
                                <p class="stat-label">Programs</p>
                            </div>
                        </div>
                        <div class="course-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number" id="totalCourses">40</h3>
                                <p class="stat-label">Courses</p>
                            </div>
                        </div>
                        <div class="course-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number" id="activeStudents">623</h3>
                                <p class="stat-label">Active Students</p>
                            </div>
                        </div>
                        </div>
                        
                    <!-- Enhanced Study Levels Container -->
                    <div class="study-levels-container">
                        <!-- Master's Programme Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">Masters Programme</h3>
                                        <p class="study-level-description">Advanced degree programs for graduates</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('Masters Programme')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('Masters Programme')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                </button>
                            </div>
                                </div>
                            
                            <div class="programs-grid" id="masters-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Degree (Undergraduate) Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">Degree (Undergraduate)</h3>
                                        <p class="study-level-description">Bachelor's degree programs</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('Degree (Undergraduate)')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('Degree (Undergraduate)')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                </button>
                            </div>
                                </div>
                            
                            <div class="programs-grid" id="undergraduate-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Advanced Diploma / Diploma Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">Advanced Diploma / Diploma</h3>
                                        <p class="study-level-description">Advanced diploma and diploma programs</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('Advanced Diploma / Diploma')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('Advanced Diploma / Diploma')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                </button>
                            </div>
                                </div>
                            
                            <div class="programs-grid" id="advanced-diploma-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Certificate & Advanced Certificate Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">Certificate & Advanced Certificate</h3>
                                        <p class="study-level-description">Certificate and advanced certificate programs</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('Certificate & Advanced Certificate')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('Certificate & Advanced Certificate')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                </button>
                            </div>
                                </div>
                            
                            <div class="programs-grid" id="certificate-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Foundation Programme Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">Foundation Programme</h3>
                                        <p class="study-level-description">Foundation and preparatory programs</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('Foundation Programme')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('Foundation Programme')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                </button>
                            </div>
                                </div>
                            
                            <div class="programs-grid" id="foundation-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Students Section -->
                <section id="students" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">NIBM Student Directory</h3>
                        </div>
                        <div id="studentsTable">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Program</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Enrollment Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>NIBM001</td>
                                        <td>Sarah Johnson</td>
                                        <td>BBA</td>
                                        <td>sarah.johnson@nibm.edu.lk</td>
                                        <td>+94 71 345 6789</td>
                                        <td>2024-01-10</td>
                                        <td><span style="color: var(--success-color);">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>NIBM002</td>
                                        <td>Michael Brown</td>
                                        <td>MBA</td>
                                        <td>michael.brown@nibm.edu.lk</td>
                                        <td>+94 77 456 7890</td>
                                        <td>2024-01-15</td>
                                        <td><span style="color: var(--success-color);">Active</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Course Registrations Section -->
                <section id="enrollments" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">NIBM Course Registration Requests</h3>
                            <div class="table-actions">
                                <select id="statusFilter" onchange="filterRegistrations()">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div id="enrollmentsTable">
                            <?php
                            try {
                                $pdo = getDBConnection();
                                $stmt = $pdo->prepare("
                                    SELECT cr.*, s.first_name, s.last_name, s.email, s.phone
                                    FROM course_registrations cr
                                    JOIN students s ON cr.student_id = s.id
                                    WHERE cr.campus = 'NIBM'
                                    ORDER BY cr.registration_date DESC
                                ");
                                $stmt->execute();
                                $registrations = $stmt->fetchAll();
                                
                                if ($registrations): ?>
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Registration ID</th>
                                                <th>Student Name</th>
                                                <th>Email</th>
                                                <th>Course</th>
                                                <th>Department</th>
                                                <th>Registration Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($registrations as $reg): ?>
                                                <tr data-status="<?php echo $reg['status']; ?>">
                                                    <td>#<?php echo $reg['id']; ?></td>
                                                    <td><?php echo htmlspecialchars($reg['first_name'] . ' ' . $reg['last_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($reg['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($reg['course_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($reg['department_name']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($reg['registration_date'])); ?></td>
                                                    <td>
                                                        <span class="status-badge status-<?php echo $reg['status']; ?>">
                                                            <?php echo ucfirst($reg['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="action-buttons">
                                                        <?php if ($reg['status'] === 'pending'): ?>
                                                            <button class="btn-approve" onclick="approveRegistration(<?php echo $reg['id']; ?>)">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                            <button class="btn-reject" onclick="rejectRegistration(<?php echo $reg['id']; ?>)">
                                                                <i class="fas fa-times"></i> Reject
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn-view" onclick="viewRegistration(<?php echo $reg['id']; ?>)">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="no-data">
                                        <i class="fas fa-inbox"></i>
                                        <p>No course registrations found for NIBM Campus</p>
                                    </div>
                                <?php endif;
                            } catch (Exception $e) {
                                echo '<div class="error">Error loading registrations: ' . htmlspecialchars($e->getMessage()) . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </section>

                <!-- Course Applications Section -->
                <section id="applications" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Course Applications - NIBM Campus</h3>
                            <p style="margin-top: 0.5rem; color: var(--secondary-color);">Review and manage student course applications</p>
                        </div>
                        <div id="applicationsContent">
                            <?php
                            try {
                                // Fetch course applications for NIBM campus
                                $applicationsStmt = $pdo->prepare("
                                    SELECT 
                                        ca.id,
                                        ca.application_number,
                                        ca.course_name,
                                        ca.study_level,
                                        ca.program,
                                        ca.first_name,
                                        ca.last_name,
                                        ca.email,
                                        ca.phone,
                                        ca.highest_qualification,
                                        ca.institution,
                                        ca.graduation_year,
                                        ca.status,
                                        ca.application_date,
                                        ca.review_date
                                    FROM course_applications ca
                                    WHERE ca.university = 'NIBM' OR ca.university = 'NIBM Campus'
                                    ORDER BY ca.application_date DESC
                                ");
                                $applicationsStmt->execute();
                                $applications = $applicationsStmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (!empty($applications)): ?>
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>App #</th>
                                                <th>Student Name</th>
                                                <th>Course</th>
                                                <th>Program</th>
                                                <th>Qualification</th>
                                                <th>Applied Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($applications as $app): ?>
                                                <tr data-status="<?php echo $app['status']; ?>">
                                                    <td><?php echo htmlspecialchars($app['application_number']); ?></td>
                                                    <td>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></strong>
                                                            <br><small><?php echo htmlspecialchars($app['email']); ?></small>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($app['course_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($app['program']); ?></td>
                                                    <td><?php echo htmlspecialchars($app['highest_qualification']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                                    <td>
                                                        <span class="status-badge status-<?php echo $app['status']; ?>">
                                                            <?php echo ucfirst($app['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="action-buttons">
                                                        <button class="btn-view" onclick="viewApplication(<?php echo $app['id']; ?>)">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        <?php if ($app['status'] === 'pending'): ?>
                                                            <button class="btn-approve" onclick="updateApplicationStatus(<?php echo $app['id']; ?>, 'approved')">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                            <button class="btn-waitlist" onclick="updateApplicationStatus(<?php echo $app['id']; ?>, 'waitlisted')">
                                                                <i class="fas fa-clock"></i> Waitlist
                                                            </button>
                                                            <button class="btn-reject" onclick="updateApplicationStatus(<?php echo $app['id']; ?>, 'rejected')">
                                                                <i class="fas fa-times"></i> Reject
                                                            </button>
                                                        <?php elseif ($app['status'] === 'waitlisted'): ?>
                                                            <button class="btn-approve" onclick="updateApplicationStatus(<?php echo $app['id']; ?>, 'approved')">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                            <button class="btn-reject" onclick="updateApplicationStatus(<?php echo $app['id']; ?>, 'rejected')">
                                                                <i class="fas fa-times"></i> Reject
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="no-data">
                                        <i class="fas fa-file-alt"></i>
                                        <p>No course applications found for NIBM Campus</p>
                                    </div>
                                <?php endif;
                            } catch (Exception $e) {
                                echo '<div class="error">Error loading applications: ' . htmlspecialchars($e->getMessage()) . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </section>

                <!-- Analytics Section -->
                <section id="analytics" class="dashboard-section" style="display: none;">
                    <!-- Analytics Charts -->
                    <div class="dashboard-grid">
                        <!-- Student Enrollment Trends Chart -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Student Enrollment Trends</h3>
                                <div class="chart-controls">
                                    <button class="chart-control-btn active" onclick="updateEnrollmentChart('yearly')">Yearly</button>
                                    <button class="chart-control-btn" onclick="updateEnrollmentChart('monthly')">Monthly</button>
                                    <button class="chart-control-btn" onclick="updateEnrollmentChart('semester')">Semester</button>
                                </div>
                            </div>
                            <div id="enrollmentChart" style="height: 400px; position: relative;">
                                <canvas id="enrollmentCanvas" width="400" height="400"></canvas>
                            </div>
                        </div>

                        <!-- Program Demand Chart -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Program Demand Analysis</h3>
                                <div class="chart-controls">
                                    <button class="chart-control-btn active" onclick="updateProgramChart('enrollment')">Enrollment</button>
                                    <button class="chart-control-btn" onclick="updateProgramChart('applications')">Applications</button>
                                    <button class="chart-control-btn" onclick="updateProgramChart('completion')">Completion</button>
                                </div>
                            </div>
                            <div id="programChart" style="height: 400px; position: relative;">
                                <canvas id="programCanvas" width="400" height="400"></canvas>
                            </div>
                        </div>

                        <!-- Campus Performance Chart -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Campus Performance Comparison</h3>
                                <div class="chart-controls">
                                    <button class="chart-control-btn active" onclick="updateCampusChart('students')">Students</button>
                                    <button class="chart-control-btn" onclick="updateCampusChart('programs')">Programs</button>
                                    <button class="chart-control-btn" onclick="updateCampusChart('satisfaction')">Satisfaction</button>
                                </div>
                            </div>
                            <div id="campusChart" style="height: 400px; position: relative;">
                                <canvas id="campusCanvas" width="400" height="400"></canvas>
                            </div>
                        </div>

                        <!-- Accreditation Monitoring Chart -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Accreditation Status Overview</h3>
                                <div class="chart-controls">
                                    <button class="chart-control-btn active" onclick="updateAccreditationChart('status')">Status</button>
                                    <button class="chart-control-btn" onclick="updateAccreditationChart('renewal')">Renewal</button>
                                    <button class="chart-control-btn" onclick="updateAccreditationChart('compliance')">Compliance</button>
                                </div>
                            </div>
                            <div id="accreditationChart" style="height: 400px; position: relative;">
                                <canvas id="accreditationCanvas" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Analytics Insights -->
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">NIBM Analytics Insights</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-chart-line"></i>
                                    Enrollment Growth
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>Current Year:</strong> 700 students</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Growth Rate:</strong> +12% from previous year</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Peak Season:</strong> January - March</p>
                                <p style="color: var(--success-color); font-weight: 600;">📈 Steady growth trend observed</p>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--success-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-trophy"></i>
                                    Program Performance
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>Top Program:</strong> Management (45% enrollment)</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Fastest Growing:</strong> IT Programs (+18%)</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Completion Rate:</strong> 92% average</p>
                                <p style="color: var(--success-color); font-weight: 600;">🎯 Excellent program performance</p>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--warning-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-map-marked-alt"></i>
                                    Campus Distribution
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>Largest Campus:</strong> Kandy (700 students)</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Regional Spread:</strong> 4 locations</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Coverage:</strong> Central, Western, Southern</p>
                                <p style="color: var(--info-color); font-weight: 600;">🌍 Strong regional presence</p>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--info-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-shield-alt"></i>
                                    Accreditation Health
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>UGC Recognition:</strong> 100% programs</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Last Review:</strong> 2023</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Next Review:</strong> 2025</p>
                                <p style="color: var(--success-color); font-weight: 600;">✅ All programs compliant</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Review Moderation Section -->
                <section id="reviews" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Review Moderation</h3>
                            <p class="table-subtitle">Moderate student reviews and ratings for NIBM courses</p>
                        </div>
                        
                        <!-- Review Statistics -->
                        <div class="stats-grid" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-number" id="totalReviews">0</div>
                                <div class="stat-label">Total Reviews</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="pendingReviews">0</div>
                                <div class="stat-label">Pending</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="approvedReviews">0</div>
                                <div class="stat-label">Approved</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="rejectedReviews">0</div>
                                <div class="stat-label">Rejected</div>
                            </div>
                        </div>

                        <!-- Filter Tabs -->
                        <div class="filter-tabs" style="margin-bottom: 2rem;">
                            <button class="filter-tab active" onclick="filterReviews('all')">All Reviews</button>
                            <button class="filter-tab" onclick="filterReviews('pending')">Pending</button>
                            <button class="filter-tab" onclick="filterReviews('approved')">Approved</button>
                            <button class="filter-tab" onclick="filterReviews('rejected')">Rejected</button>
                        </div>

                        <!-- Reviews List -->
                        <div id="reviewsList" class="table-content">
                            <div style="text-align: center; padding: 3rem; color: var(--secondary-color);">
                                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <p>Loading reviews...</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Inquiry Management Section -->
                <section id="inquiries" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Inquiry Management</h3>
                            <p class="table-subtitle">Manage and respond to student inquiries for NIBM courses</p>
                        </div>
                        
                        <!-- Inquiry Statistics -->
                        <div class="stats-grid" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-number" id="totalInquiries">0</div>
                                <div class="stat-label">Total Inquiries</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="pendingInquiries">0</div>
                                <div class="stat-label">Pending</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="answeredInquiries">0</div>
                                <div class="stat-label">Answered</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="closedInquiries">0</div>
                                <div class="stat-label">Closed</div>
                            </div>
                        </div>

                        <!-- Inquiries List -->
                        <div id="inquiriesList" class="table-content">
                            <div style="text-align: center; padding: 3rem; color: var(--secondary-color);">
                                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <p>Loading inquiries...</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Content Management Section -->
                <section id="content" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Content Management</h3>
                            <p class="table-subtitle">Create and manage articles, announcements, and content for NIBM students</p>
                        </div>
                        
                        <!-- Content Tabs -->
                        <div class="content-tabs" style="margin-bottom: 2rem;">
                            <button class="content-tab active" onclick="showContentTab('announcements')">Announcements</button>
                            <button class="content-tab" onclick="showContentTab('articles')">Articles</button>
                        </div>

                        <!-- Announcements Tab -->
                        <div id="announcementsTab" class="content-tab-content active">
                            <div class="content-header">
                                <h4>Create New Announcement</h4>
                                <button class="btn btn-primary" onclick="showAnnouncementForm()">
                                    <i class="fas fa-plus"></i> New Announcement
                                </button>
                            </div>
                            
                            <div id="announcementForm" class="content-form" style="display: none;">
                                <form id="newAnnouncementForm" onsubmit="submitAnnouncement(event)">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="announcementTitle">Title *</label>
                                            <input type="text" id="announcementTitle" name="title" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="announcementAudience">Audience *</label>
                                            <select id="announcementAudience" name="audience" required>
                                                <option value="">Select Audience</option>
                                                <option value="all">All Students</option>
                                                <option value="students">Current Students</option>
                                                <option value="admins">Administrators</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="announcementBody">Content *</label>
                                        <textarea id="announcementBody" name="body" rows="5" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="announcementExpiry">Expiry Date (Optional)</label>
                                        <input type="datetime-local" id="announcementExpiry" name="expires_at">
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">Publish Announcement</button>
                                        <button type="button" class="btn btn-outline" onclick="hideAnnouncementForm()">Cancel</button>
                                    </div>
                                </form>
                            </div>

                            <div id="announcementsList" class="content-list">
                                <div style="text-align: center; padding: 3rem; color: var(--secondary-color);">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <p>Loading announcements...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Articles Tab -->
                        <div id="articlesTab" class="content-tab-content" style="display: none;">
                            <div class="content-header">
                                <h4>Create New Article</h4>
                                <button class="btn btn-primary" onclick="showArticleForm()">
                                    <i class="fas fa-plus"></i> New Article
                                </button>
                            </div>
                            
                            <div id="articleForm" class="content-form" style="display: none;">
                                <form id="newArticleForm" onsubmit="submitArticle(event)">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="articleTitle">Title *</label>
                                            <input type="text" id="articleTitle" name="title" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="articleAudience">Audience *</label>
                                            <select id="articleAudience" name="audience" required>
                                                <option value="">Select Audience</option>
                                                <option value="all">All Students</option>
                                                <option value="students">Current Students</option>
                                                <option value="prospective_students">Prospective Students</option>
                                                <option value="parents">Parents</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="articleBody">Content *</label>
                                        <textarea id="articleBody" name="body" rows="8" required></textarea>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">Publish Article</button>
                                        <button type="button" class="btn btn-outline" onclick="hideArticleForm()">Cancel</button>
                                    </div>
                                </form>
                            </div>

                            <div id="articlesList" class="content-list">
                                <div style="text-align: center; padding: 3rem; color: var(--secondary-color);">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <p>Loading articles...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Settings Section -->
                <section id="settings" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">NIBM Dashboard Settings</h3>
                        </div>
                        <div id="settingsContent">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.5rem;">
                                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Notification Settings</h4>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" id="emailNotifications" checked> Email notifications
                                        </label>
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" id="dashboardAlerts" checked> Dashboard alerts
                                        </label>
                                    </div>
                                </div>
                                
                                <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.5rem;">
                                    <h4 style="color: var(--success-color); margin-bottom: 1rem;">Display Settings</h4>
                                    <div style="margin-bottom: 1rem;">
                                        <label>Theme:</label>
                                        <select id="themeSelect" style="margin-top: 0.5rem; width: 100%;">
                                            <option value="light">Light</option>
                                            <option value="dark">Dark</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="margin-top: 2rem; text-align: center;">
                                <button onclick="saveSettings()" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <script>
        // Dashboard functionality
        class NIBMAdminDashboard {
            constructor() {
                this.currentSection = 'overview';
                this.init();
            }

            init() {
                this.setupEventListeners();
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
                
                // Load data for specific sections
                if (sectionName === 'inquiries') {
                    setTimeout(loadInquiries, 100);
                } else if (sectionName === 'reviews') {
                    setTimeout(loadReviews, 100);
                } else if (sectionName === 'settings') {
                    setTimeout(loadSettings, 100);
                } else if (sectionName === 'analytics') {
                    setTimeout(() => {
                        if (!enrollmentChart) {
                            initializeAnalyticsCharts();
                        }
                    }, 300);
                }
            }
        }

        // Enhanced Course Management Functions
        let currentStudyLevel = '';
        let lastAddedProgram = null;

        function openAddProgramModal(studyLevel) {
            currentStudyLevel = studyLevel;
            document.getElementById('addProgramModal').style.display = 'block';
        }

        function closeAddProgramModal() {
            document.getElementById('addProgramModal').style.display = 'none';
            document.getElementById('addProgramForm').reset();
        }

        function submitNewProgram(event) {
            event.preventDefault();
            const form = document.getElementById('addProgramForm');
            const formData = new FormData(form);
            formData.append('action', 'add_nibm_program');
            formData.append('level', currentStudyLevel);
            formData.append('program_code', 'NIBM' + Date.now()); // Generate unique program code
            formData.append('category', 'Business'); // Default category
            formData.append('duration', '3 Years'); // Default duration
            formData.append('description', ''); // Empty description
            
            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                if (data.success) {
                    alert('Program added successfully!');
                    
                    // Store the program details for course addition
                    lastAddedProgram = {
                        id: data.program_id,
                        name: document.getElementById('programName').value,
                        studyLevel: currentStudyLevel
                    };
                    
                    // Close the program modal
                    closeAddProgramModal();
                    
                    // Automatically open the Add Course modal
                    setTimeout(() => {
                        openAddCourseModal(lastAddedProgram.id, lastAddedProgram.name);
                    }, 500);
                    
                    } else {
                    alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the program: ' + error.message);
            });
        }

        function openAddCourseModal(programId, programName) {
            document.getElementById('courseProgramId').value = programId;
            document.getElementById('courseStudyLevel').value = currentStudyLevel;
            document.getElementById('addCourseModal').style.display = 'block';
        }

        function closeAddCourseModal() {
            document.getElementById('addCourseModal').style.display = 'none';
            document.getElementById('addCourseForm').reset();
        }

        function submitNewCourse(event) {
            event.preventDefault();
            const form = document.getElementById('addCourseForm');
            const formData = new FormData(form);
            formData.append('action', 'add_nibm_course');
            
            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                if (data.success) {
                    alert('Course added successfully to both database and dashboard!');
                    closeAddCourseModal();
                    
                    // Refresh the programs display to show the new course
                    loadProgramsByStudyLevel();
                    } else {
                    alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the course: ' + error.message);
            });
        }

        function loadProgramsByStudyLevel() {
            console.log('Loading NIBM programs...');
            fetch('get_nibm_programs.php')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Programs data:', data);
                    if (data.success) {
                        // Group programs by study level
                        const programsByLevel = {};
                        data.programs.forEach(program => {
                            if (!programsByLevel[program.level]) {
                                programsByLevel[program.level] = [];
                            }
                            programsByLevel[program.level].push(program);
                        });

                        // Update each study level container
                        const studyLevels = {
                            'Masters Programme': 'masters-programs',
                            'Degree (Undergraduate)': 'undergraduate-programs',
                            'Advanced Diploma / Diploma': 'advanced-diploma-programs',
                            'Certificate & Advanced Certificate': 'certificate-programs',
                            'Foundation Programme': 'foundation-programs'
                        };

                        Object.keys(studyLevels).forEach(level => {
                            const containerId = studyLevels[level];
                            const container = document.getElementById(containerId);
                            const programs = programsByLevel[level] || [];
                            
                            if (programs.length > 0) {
                                const programsHTML = programs.map(program => `
                                    <div class="program-card">
                                        <div class="program-header">
                                            <div class="program-info">
                                                <h4>${escapeHtml(program.program_name)}</h4>
                                                <div class="program-meta">
                                                    <span>${escapeHtml(program.program_code)}</span>
                                                    <span>${escapeHtml(program.duration || 'N/A')}</span>
                    </div>
                            </div>
                                            <div class="program-actions">
                                                <button class="btn-add-course" onclick="openAddCourseModal(${program.id}, '${escapeHtml(program.program_name)}')">
                                                    <i class="fas fa-plus"></i> Add Course
                            </button>
                                                <button class="btn-edit-program" onclick="editProgram(${program.id})">
                                                    <i class="fas fa-edit"></i> Edit
                            </button>
                                                <button class="btn-delete-program" onclick="deleteProgram(${program.id})">
                                                    <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                            </div>
                                        <div class="courses-list" id="courses-${program.id}">
                                            <div style="text-align: center; padding: 1rem; color: var(--secondary-color);">
                                                <i class="fas fa-spinner fa-spin" style="font-size: 1rem; margin-bottom: 0.5rem;"></i>
                                                <p>Loading courses...</p>
                            </div>
                        </div>
                            </div>
                                `).join('');
                                container.innerHTML = programsHTML;
                                
                                // Load courses for each program
                                programs.forEach(program => {
                                    loadCoursesForProgram(program.id);
                                });
                            } else {
                                container.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--secondary-color);"><i class="fas fa-folder-open" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i><p>No admin-added programs yet</p><p style="font-size: 0.9rem; margin-top: 0.5rem;">Click "Add Program" to create new programs</p></div>';
                            }
                        });

                        // Update course statistics
                        updateCourseStatistics(data.programs);
                    } else {
                        console.error('Failed to load programs:', data.message);
                        alert('Failed to load programs: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading programs by study level:', error);
                    alert('Error loading programs: ' + error.message);
                });
        }

        function updateCourseStatistics(programs) {
            // Update the statistics cards with real data
            const totalPrograms = programs.length;
            document.getElementById('totalPrograms').textContent = totalPrograms;
            
            // Calculate total courses from admin-added programs
            let totalCourses = 0;
            programs.forEach(program => {
                const coursesContainer = document.getElementById(`courses-${program.id}`);
                if (coursesContainer) {
                    const courseItems = coursesContainer.querySelectorAll('.course-item');
                    totalCourses += courseItems.length;
                }
            });
            
            document.getElementById('totalStudyLevels').textContent = '5'; // Fixed for NIBM
            document.getElementById('totalCourses').textContent = totalCourses;
            document.getElementById('activeStudents').textContent = '623'; // This should be fetched from student database
        }

        function exportCourseData() {
            // Export course data functionality
            alert('Export functionality will be implemented in future updates. This will allow you to download course and program data in various formats.');
        }

        function loadCoursesForProgram(programId) {
            // Fetch courses for a specific program
            fetch('get_nibm_courses.php')
            .then(response => response.json())
            .then(data => {
                    const coursesContainer = document.getElementById(`courses-${programId}`);
                    if (!coursesContainer) return;
                    
                if (data.success) {
                        const programCourses = data.courses.filter(course => course.program_id == programId);
                        
                        if (programCourses && programCourses.length > 0) {
                            const coursesHTML = programCourses.map(course => `
                                <div class="course-item">
                                    <div class="course-header">
                                        <div class="course-title">${escapeHtml(course.course_name)}</div>
                                        <div class="course-actions">
                                            <button class="btn-edit-course" onclick="editCourse(${course.id})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-delete-course" onclick="deleteCourse(${course.id})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    ${course.course_description ? `<div class="course-description">${escapeHtml(course.course_description)}</div>` : ''}
                                    <div class="course-meta">
                                        ${course.requirement ? `<span>Requirements: ${escapeHtml(course.requirement)}</span>` : ''}
                                        ${course.duration ? `<span>Duration: ${escapeHtml(course.duration)}</span>` : ''}
                                    </div>
                                </div>
                            `).join('');
                            coursesContainer.innerHTML = coursesHTML;
                } else {
                            coursesContainer.innerHTML = '<div style="text-align: center; padding: 1rem; color: var(--secondary-color);"><i class="fas fa-book-open" style="font-size: 1rem; margin-bottom: 0.5rem;"></i><p>No courses added yet</p><p style="font-size: 0.8rem; margin-top: 0.25rem;">Click "Add Course" to create new courses</p></div>';
                        }
                } else {
                        coursesContainer.innerHTML = '<div style="text-align: center; padding: 1rem; color: var(--secondary-color);"><i class="fas fa-book-open" style="font-size: 1rem; margin-bottom: 0.5rem;"></i><p>No courses added yet</p><p style="font-size: 0.8rem; margin-top: 0.25rem;">Click "Add Course" to create new courses</p></div>';
                }
            })
            .catch(error => {
                    console.error('Error loading courses for program:', error);
                    const coursesContainer = document.getElementById(`courses-${programId}`);
                    if (coursesContainer) {
                        coursesContainer.innerHTML = '<div style="text-align: center; padding: 1rem; color: var(--secondary-color);"><i class="fas fa-book-open" style="font-size: 1rem; margin-bottom: 0.5rem;"></i><p>No courses added yet</p><p style="font-size: 0.8rem; margin-top: 0.25rem;">Click "Add Course" to create new courses</p></div>';
                    }
                });
        }

        function editStudyLevel(studyLevel) {
            alert('Edit study level functionality will be implemented for: ' + studyLevel);
        }



        function openAddStudyLevelModal() {
            document.getElementById('addStudyLevelModal').style.display = 'block';
        }

        function closeAddStudyLevelModal() {
            document.getElementById('addStudyLevelModal').style.display = 'none';
            document.getElementById('addStudyLevelForm').reset();
        }

        function submitNewStudyLevel(event) {
            event.preventDefault();
            const form = document.getElementById('addStudyLevelForm');
            const formData = new FormData(form);
            
            // For now, just show a success message
            alert('Study level added successfully! This feature will be fully implemented in future updates.');
            closeAddStudyLevelModal();
        }

        function editProgram(programId) {
            // For now, show a placeholder message
            alert('Edit program functionality will be implemented in future updates for ID: ' + programId);
        }

        function deleteProgram(programId) {
            if (confirm('Are you sure you want to delete this program? This will also delete all associated courses.')) {
                const formData = new FormData();
                formData.append('program_id', programId);
                formData.append('action', 'delete_nibm_program');
                
                fetch('admin-course-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Program deleted successfully!');
                        loadProgramsByStudyLevel();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the program.');
                });
            }
        }

        function editCourse(courseId) {
            // For now, show a placeholder message
            alert('Edit course functionality will be implemented in future updates for ID: ' + courseId);
        }

        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course?')) {
                const formData = new FormData();
                formData.append('course_id', courseId);
                formData.append('action', 'delete_nibm_course');
                
                fetch('admin-course-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Course deleted successfully!');
                        loadProgramsByStudyLevel();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the course.');
                });
            }
        }

        // Course Registration Management Functions
        function approveRegistration(registrationId) {
            if (confirm('Are you sure you want to approve this registration?')) {
                updateRegistrationStatus(registrationId, 'approved');
            }
        }

        function rejectRegistration(registrationId) {
            const notes = prompt('Please provide a reason for rejection (optional):');
            if (notes !== null) {
                updateRegistrationStatus(registrationId, 'rejected', notes);
            }
        }

        function updateRegistrationStatus(registrationId, status, notes = '') {
            const formData = new FormData();
            formData.append('registration_id', registrationId);
            formData.append('status', status);
            formData.append('notes', notes);
            formData.append('action', 'update_status');

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Registration ${status} successfully!`);
                    location.reload(); // Refresh the page to show updated status
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the registration.');
            });
        }

        function filterRegistrations() {
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#enrollmentsTable tbody tr');
            
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (!statusFilter || status === statusFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function viewRegistration(registrationId) {
            // Implement view functionality if needed
            alert('View registration details for ID: ' + registrationId);
        }

        // Course Application Management Functions
        function viewApplication(applicationId) {
            alert('View application details for ID: ' + applicationId);
            // In a real application, you would fetch and display more details here
        }

        function updateApplicationStatus(applicationId, status) {
            if (confirm(`Are you sure you want to change the status of this application to "${status}"?`)) {
                const formData = new FormData();
                formData.append('application_id', applicationId);
                formData.append('status', status);
                formData.append('action', 'update_application_status');

                fetch('admin-course-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Application status updated to "${status}" successfully!`);
                        location.reload(); // Refresh the page to show updated status
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the application status.');
                });
            }
        }

        // Review Moderation Functions
        function loadReviews() {
            fetch('get_reviews.php?campus=NIBM Campus')
                .then(response => response.json())
                .then(data => {
                    updateReviewStats(data.stats);
                    displayReviews(data.reviews);
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    document.getElementById('reviewsList').innerHTML = '<div class="error">Error loading reviews</div>';
                });
        }

        function updateReviewStats(stats) {
            document.getElementById('totalReviews').textContent = stats.total || 0;
            document.getElementById('pendingReviews').textContent = stats.pending || 0;
            document.getElementById('approvedReviews').textContent = stats.approved || 0;
            document.getElementById('rejectedReviews').textContent = stats.rejected || 0;
        }

        function displayReviews(reviews) {
            const container = document.getElementById('reviewsList');
            if (!reviews || reviews.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-star"></i>
                        <h3>No reviews yet</h3>
                        <p>When students submit reviews, they will appear here for you to moderate.</p>
                    </div>
                `;
                return;
            }

            const reviewsHTML = reviews.map(review => `
                <div class="review-item" data-status="${review.status}">
                    <div class="review-header">
                        <div class="review-info">
                            <h3>${escapeHtml(review.course_name || 'General Course')}</h3>
                            <div class="review-meta">
                                <span><i class="fas fa-user"></i> ${escapeHtml(review.first_name + ' ' + review.last_name)}</span>
                                <span><i class="fas fa-envelope"></i> ${escapeHtml(review.email)}</span>
                                <span><i class="fas fa-calendar"></i> ${formatDate(review.created_at)}</span>
                                <span><i class="fas fa-university"></i> ${escapeHtml(review.university_name)}</span>
                            </div>
                        </div>
                        <div class="review-status status-${review.status}">
                            ${review.status.charAt(0).toUpperCase() + review.status.slice(1)}
                        </div>
                    </div>
                    
                    <div class="review-content">
                        <div class="review-rating">
                            <span>Rating:</span>
                            <div class="stars">
                                ${generateStars(review.rating)}
                            </div>
                            <span>(${review.rating}/5)</span>
                        </div>
                        
                        <div class="review-comment">
                            <strong>Review:</strong><br>
                            ${escapeHtml(review.comment).replace(/\n/g, '<br>')}
                        </div>
                        
                        ${review.moderation_notes ? `
                            <div class="review-comment" style="background: #f0f9ff; border-left: 4px solid var(--primary-color);">
                                <strong>Moderation Notes:</strong><br>
                                ${escapeHtml(review.moderation_notes).replace(/\n/g, '<br>')}
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="review-actions">
                        ${review.status === 'pending' ? `
                            <button class="btn btn-success" onclick="showModerationForm(${review.id}, 'approve')">
                                <i class="fas fa-check"></i>
                                Approve
                            </button>
                            <button class="btn btn-danger" onclick="showModerationForm(${review.id}, 'reject')">
                                <i class="fas fa-times"></i>
                                Reject
                            </button>
                        ` : ''}
                        
                        <button class="btn btn-outline" onclick="viewReviewDetails(${review.id})">
                            <i class="fas fa-eye"></i>
                            View Details
                        </button>
                    </div>
                    
                    <div class="moderation-form" id="moderation-form-${review.id}">
                        <form onsubmit="submitModeration(event, ${review.id})">
                            <div class="form-group">
                                <label for="notes-${review.id}">Moderation Notes *</label>
                                <textarea id="notes-${review.id}" class="form-control" required 
                                          placeholder="Provide notes about why you're approving or rejecting this review..."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane"></i>
                                    Submit Decision
                                </button>
                                <button type="button" class="btn btn-outline" onclick="hideModerationForm(${review.id})">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `).join('');

            container.innerHTML = reviewsHTML;
        }

        function showModerationForm(reviewId, action) {
            const form = document.getElementById(`moderation-form-${reviewId}`);
            form.dataset.action = action;
            form.classList.add('active');
        }

        function hideModerationForm(reviewId) {
            const form = document.getElementById(`moderation-form-${reviewId}`);
            form.classList.remove('active');
        }

        function submitModeration(event, reviewId) {
            event.preventDefault();
            const form = event.target;
            const action = form.parentElement.dataset.action;
            const notes = form.querySelector('textarea').value;

            if (!notes.trim()) {
                alert('Please provide moderation notes before submitting.');
                return;
            }

            const formData = new FormData();
            formData.append('review_id', reviewId);
            formData.append('action', action);
            formData.append('notes', notes);

            fetch('process_review_moderation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Review ${action}d successfully!`);
                    hideModerationForm(reviewId);
                    loadReviews(); // Reload reviews
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the review.');
            });
        }

        function filterReviews(status) {
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            const reviewItems = document.querySelectorAll('.review-item');
            reviewItems.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Inquiry Management Functions
        function loadInquiries() {
            fetch('get_inquiries.php?campus=NIBM')
                .then(response => response.json())
                .then(data => {
                    updateInquiryStats(data.stats);
                    displayInquiries(data.inquiries);
                })
                .catch(error => {
                    console.error('Error loading inquiries:', error);
                    document.getElementById('inquiriesList').innerHTML = '<div class="error">Error loading inquiries</div>';
                });
        }

        function updateInquiryStats(stats) {
            document.getElementById('totalInquiries').textContent = stats.total || 0;
            document.getElementById('pendingInquiries').textContent = stats.pending || 0;
            document.getElementById('answeredInquiries').textContent = stats.answered || 0;
            document.getElementById('closedInquiries').textContent = stats.closed || 0;
        }

        function displayInquiries(inquiries) {
            const container = document.getElementById('inquiriesList');
            if (!inquiries || inquiries.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>No inquiries yet</h3>
                        <p>When students send inquiries, they will appear here for you to respond to.</p>
                    </div>
                `;
                return;
            }

            const inquiriesHTML = inquiries.map(inquiry => `
                <div class="inquiry-item">
                    <div class="inquiry-header">
                        <div class="inquiry-info">
                            <h3>${escapeHtml(inquiry.subject)}</h3>
                            <div class="inquiry-meta">
                                <span><i class="fas fa-user"></i> ${escapeHtml(inquiry.first_name + ' ' + inquiry.last_name)}</span>
                                <span><i class="fas fa-envelope"></i> ${escapeHtml(inquiry.email)}</span>
                                <span><i class="fas fa-phone"></i> ${escapeHtml(inquiry.phone)}</span>
                                <span><i class="fas fa-calendar"></i> ${formatDate(inquiry.created_at)}</span>
                            </div>
                        </div>
                        <div class="inquiry-status status-${inquiry.response_status}">
                            ${inquiry.response_status.charAt(0).toUpperCase() + inquiry.response_status.slice(1)}
                        </div>
                    </div>
                    
                    <div class="inquiry-content">
                        <div class="inquiry-message">
                            <strong>Message:</strong><br>
                            ${escapeHtml(inquiry.message).replace(/\n/g, '<br>')}
                        </div>
                        
                        ${inquiry.response ? `
                            <div class="inquiry-response">
                                <strong>Your Response:</strong><br>
                                ${escapeHtml(inquiry.response).replace(/\n/g, '<br>')}
                                <div style="margin-top: 0.5rem; font-size: 0.875rem; color: var(--secondary-color);">
                                    Responded on: ${formatDateTime(inquiry.response_date)}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="inquiry-actions">
                        ${inquiry.response_status === 'pending' ? `
                            <button class="btn btn-primary" onclick="showResponseForm(${inquiry.id})">
                                <i class="fas fa-reply"></i>
                                Respond
                            </button>
                        ` : inquiry.response_status === 'answered' ? `
                            <button class="btn btn-outline" onclick="closeInquiry(${inquiry.id})">
                                <i class="fas fa-check"></i>
                                Close Inquiry
                            </button>
                        ` : ''}
                        
                        <button class="btn btn-outline" onclick="viewInquiryDetails(${inquiry.id})">
                            <i class="fas fa-eye"></i>
                            View Details
                        </button>
                    </div>
                    
                    <div class="response-form" id="response-form-${inquiry.id}">
                        <form onsubmit="submitResponse(event, ${inquiry.id})">
                            <div class="form-group">
                                <label for="response-${inquiry.id}">Your Response *</label>
                                <textarea id="response-${inquiry.id}" class="form-control" required 
                                          placeholder="Type your response to the student..."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Response
                                </button>
                                <button type="button" class="btn btn-outline" onclick="hideResponseForm(${inquiry.id})">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `).join('');

            container.innerHTML = inquiriesHTML;
        }

        function showResponseForm(inquiryId) {
            const form = document.getElementById(`response-form-${inquiryId}`);
            form.classList.add('active');
        }

        function hideResponseForm(inquiryId) {
            const form = document.getElementById(`response-form-${inquiryId}`);
            form.classList.remove('active');
        }

        function submitResponse(event, inquiryId) {
            event.preventDefault();
            const form = event.target;
            const response = form.querySelector('textarea').value;

            if (!response.trim()) {
                alert('Please provide a response before submitting.');
                return;
            }

            const formData = new FormData();
            formData.append('inquiry_id', inquiryId);
            formData.append('response', response);

            fetch('process_inquiry_response.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Response sent successfully!');
                    hideResponseForm(inquiryId);
                    loadInquiries(); // Reload inquiries
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the response.');
            });
        }

        function closeInquiry(inquiryId) {
            if (!confirm('Are you sure you want to close this inquiry?')) {
                return;
            }

            const formData = new FormData();
            formData.append('inquiry_id', inquiryId);
            formData.append('action', 'close');

            fetch('process_inquiry_response.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Inquiry closed successfully!');
                    loadInquiries(); // Reload inquiries
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while closing the inquiry.');
            });
        }

        function viewInquiryDetails(inquiryId) {
            // For now, just show an alert with the inquiry ID
            // This could be expanded to show a modal with detailed information
            alert('Viewing details for inquiry #' + inquiryId + '\n\nThis feature can be expanded to show a detailed modal with all inquiry information.');
        }

        // Settings Functions
        function loadSettings() {
            // Load saved settings from localStorage or server
            const savedSettings = localStorage.getItem('nibmAdminSettings');
            if (savedSettings) {
                const settings = JSON.parse(savedSettings);
                document.getElementById('emailNotifications').checked = settings.emailNotifications !== false;
                document.getElementById('dashboardAlerts').checked = settings.dashboardAlerts !== false;
                document.getElementById('themeSelect').value = settings.theme || 'light';
            }
        }

        function saveSettings() {
            const settings = {
                emailNotifications: document.getElementById('emailNotifications').checked,
                dashboardAlerts: document.getElementById('dashboardAlerts').checked,
                theme: document.getElementById('themeSelect').value
            };

            // Save to localStorage
            localStorage.setItem('nibmAdminSettings', JSON.stringify(settings));

            // Send to server
            const formData = new FormData();
            formData.append('action', 'save_settings');
            formData.append('settings', JSON.stringify(settings));

            fetch('process_admin_settings.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Settings saved successfully!');
                    applyTheme(settings.theme);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Settings saved locally, but there was an error saving to server.');
                applyTheme(settings.theme);
            });
        }

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
            }
        }

        // Content Management Functions
        function showContentTab(tabName) {
            document.querySelectorAll('.content-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.content-tab-content').forEach(content => content.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tabName + 'Tab').classList.add('active');
        }

        function showAnnouncementForm() {
            document.getElementById('announcementForm').style.display = 'block';
        }

        function hideAnnouncementForm() {
            document.getElementById('announcementForm').style.display = 'none';
            document.getElementById('newAnnouncementForm').reset();
        }

        function loadAnnouncements() {
            const announcementsList = document.getElementById('announcementsList');
            announcementsList.innerHTML = '<div style="text-align: center; padding: 3rem; color: var(--secondary-color);"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i><p>Loading announcements...</p></div>';
            
            // Properly encode the campus parameter to handle spaces
            const campus = encodeURIComponent('NIBM');
            fetch(`get_announcements.php?campus=${campus}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.announcement) {
                        const announcement = data.announcement;
                        announcementsList.innerHTML = `
                            <div class="announcement-item" style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: var(--shadow-sm); margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                    <h5 style="color: var(--primary-color); margin: 0;">${escapeHtml(announcement.title)}</h5>
                                    <span style="background: var(--success-color); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">Active</span>
                                </div>
                                <p style="color: var(--dark-color); margin-bottom: 1rem; line-height: 1.6;">${escapeHtml(announcement.body)}</p>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; color: var(--secondary-color);">
                                    <span><strong>Audience:</strong> ${announcement.audience}</span>
                                    <span><strong>Created:</strong> ${formatDateTime(announcement.created_at)}</span>
                                </div>
                                ${announcement.expires_at ? `<div style="margin-top: 0.5rem; font-size: 0.875rem; color: var(--warning-color);"><strong>Expires:</strong> ${formatDateTime(announcement.expires_at)}</div>` : ''}
                            </div>
                        `;
                    } else {
                        announcementsList.innerHTML = '<div style="text-align: center; padding: 3rem; color: var(--secondary-color);"><i class="fas fa-bell-slash" style="font-size: 2rem; margin-bottom: 1rem;"></i><p>No active announcements</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading announcements:', error);
                    announcementsList.innerHTML = '<div style="text-align: center; padding: 3rem; color: var(--danger-color);"><i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i><p>Error loading announcements</p></div>';
                });
        }

        function showArticleForm() {
            document.getElementById('articleForm').style.display = 'block';
        }

        function hideArticleForm() {
            document.getElementById('articleForm').style.display = 'none';
            document.getElementById('newArticleForm').reset();
        }

        function submitAnnouncement(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch('process_announcement.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Announcement published successfully!');
                    hideAnnouncementForm();
                    loadAnnouncements(); // Reload announcements list
                } else {
                    alert('Error: ' + (data.message || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while publishing the announcement.');
            });
        }

        function submitArticle(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch('process_article.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    alert('Article published successfully!');
                    hideArticleForm();
                    // Optionally reload articles list
                } else {
                    alert('Error publishing article. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while publishing the article.');
            });
        }

        // Utility Functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<i class="fas fa-star${i <= rating ? '' : '-o'}"></i>`;
            }
            return stars;
        }

        // Analytics Charts Variables
        let enrollmentChart = null;
        let programChart = null;
        let campusChart = null;
        let accreditationChart = null;

        // Initialize Analytics Charts
        function initializeAnalyticsCharts() {
            console.log('Initializing NIBM analytics charts...');
            
            // Check if canvas elements exist
            const enrollmentCanvas = document.getElementById('enrollmentCanvas');
            const programCanvas = document.getElementById('programCanvas');
            const campusCanvas = document.getElementById('campusCanvas');
            const accreditationCanvas = document.getElementById('accreditationCanvas');
            
            if (!enrollmentCanvas || !programCanvas || !campusCanvas || !accreditationCanvas) {
                console.error('Canvas elements not found. Available elements:', {
                    enrollmentCanvas: !!enrollmentCanvas,
                    programCanvas: !!programCanvas,
                    campusCanvas: !!campusCanvas,
                    accreditationCanvas: !!accreditationCanvas
                });
                return;
            }

            try {
                // Initialize Student Enrollment Trends Chart
                const enrollmentCtx = enrollmentCanvas.getContext('2d');
                enrollmentChart = new Chart(enrollmentCtx, {
                    type: 'line',
                    data: {
                        labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
                        datasets: [{
                            label: 'Total Enrollment',
                            data: [580, 620, 650, 680, 700, 700],
                            borderColor: '#1e3a8a',
                            backgroundColor: 'rgba(30, 58, 138, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'NIBM Student Enrollment Growth'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 800
                            }
                        }
                    }
                });

                // Initialize Program Demand Chart
                const programCtx = programCanvas.getContext('2d');
                programChart = new Chart(programCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Management', 'Information Technology', 'Languages'],
                        datasets: [{
                            data: [315, 245, 140],
                            backgroundColor: ['#1e3a8a', '#059669', '#f59e0b'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: 'Program Enrollment Distribution'
                            }
                        }
                    }
                });

                // Initialize Campus Performance Chart
                const campusCtx = campusCanvas.getContext('2d');
                campusChart = new Chart(campusCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Kandy', 'Kurunegala', 'Galle', 'Matara'],
                        datasets: [{
                            label: 'Student Count',
                            data: [700, 450, 320, 280],
                            backgroundColor: ['#1e3a8a', '#059669', '#f59e0b', '#ef4444'],
                            borderColor: ['#1e3a8a', '#059669', '#f59e0b', '#ef4444'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Campus Student Distribution'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Initialize Accreditation Status Chart
                const accreditationCtx = accreditationCanvas.getContext('2d');
                accreditationChart = new Chart(accreditationCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Fully Accredited', 'Under Review', 'Pending Renewal'],
                        datasets: [{
                            data: [85, 12, 3],
                            backgroundColor: ['#059669', '#f59e0b', '#ef4444'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: 'Accreditation Status Overview'
                            }
                        }
                    }
                });

                console.log('NIBM analytics charts initialized successfully');
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        }

        // Chart Update Functions
        function updateEnrollmentChart(period) {
            if (!enrollmentChart) return;
            
            // Update active button
            document.querySelectorAll('#enrollmentChart .chart-control-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const data = {
                yearly: {
                    labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
                    data: [580, 620, 650, 680, 700, 700]
                },
                monthly: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    data: [45, 52, 48, 38, 42, 55, 48, 52, 58, 45, 38, 42]
                },
                semester: {
                    labels: ['Sem 1 2023', 'Sem 2 2023', 'Sem 1 2024', 'Sem 2 2024'],
                    data: [680, 700, 700, 720]
                }
            };
            
            enrollmentChart.data.labels = data[period].labels;
            enrollmentChart.data.datasets[0].data = data[period].data;
            enrollmentChart.update();
        }

        function updateProgramChart(type) {
            if (!programChart) return;
            
            // Update active button
            document.querySelectorAll('#programChart .chart-control-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const data = {
                enrollment: {
                    labels: ['Management', 'Information Technology', 'Languages'],
                    data: [315, 245, 140]
                },
                applications: {
                    labels: ['Management', 'Information Technology', 'Languages'],
                    data: [450, 320, 180]
                },
                completion: {
                    labels: ['Management', 'Information Technology', 'Languages'],
                    data: [92, 88, 95]
                }
            };
            
            programChart.data.labels = data[type].labels;
            programChart.data.datasets[0].data = data[type].data;
            programChart.update();
        }

        function updateCampusChart(metric) {
            if (!campusChart) return;
            
            // Update active button
            document.querySelectorAll('#campusChart .chart-control-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const data = {
                students: {
                    label: 'Student Count',
                    data: [700, 450, 320, 280]
                },
                programs: {
                    label: 'Program Count',
                    data: [12, 8, 6, 5]
                },
                satisfaction: {
                    label: 'Satisfaction Rate (%)',
                    data: [95, 92, 88, 90]
                }
            };
            
            campusChart.data.datasets[0].label = data[metric].label;
            campusChart.data.datasets[0].data = data[metric].data;
            campusChart.update();
        }

        function updateAccreditationChart(view) {
            if (!accreditationChart) return;
            
            // Update active button
            document.querySelectorAll('#accreditationChart .chart-control-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const data = {
                status: {
                    labels: ['Fully Accredited', 'Under Review', 'Pending Renewal'],
                    data: [85, 12, 3]
                },
                renewal: {
                    labels: ['Renewed 2024', 'Due 2025', 'Due 2026'],
                    data: [60, 25, 15]
                },
                compliance: {
                    labels: ['Fully Compliant', 'Minor Issues', 'Major Issues'],
                    data: [90, 8, 2]
                }
            };
            
            accreditationChart.data.labels = data[view].labels;
            accreditationChart.data.datasets[0].data = data[view].data;
            accreditationChart.update();
        }

        // Global function to manually initialize charts (for debugging)
        window.initializeNIBMCharts = function() {
            console.log('Manually initializing NIBM charts...');
            initializeAnalyticsCharts();
        };

        // Function to force chart initialization (for troubleshooting)
        window.forceChartInit = function() {
            console.log('Force initializing charts...');
            // Destroy existing charts if they exist
            if (enrollmentChart) enrollmentChart.destroy();
            if (programChart) programChart.destroy();
            if (campusChart) campusChart.destroy();
            if (accreditationChart) accreditationChart.destroy();
            
            // Reset chart variables
            enrollmentChart = null;
            programChart = null;
            campusChart = null;
            accreditationChart = null;
            
            // Reinitialize
            setTimeout(initializeAnalyticsCharts, 100);
        };

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new NIBMAdminDashboard();
            
            // Load announcements when page loads
            loadAnnouncements();
            
            // Load programs when courses section is shown
            const coursesLink = document.querySelector('[data-section="courses"]');
            if (coursesLink) {
                coursesLink.addEventListener('click', () => {
                    setTimeout(loadProgramsByStudyLevel, 100);
                });
            }
            
            // Load reviews when reviews section is shown
            const reviewsLink = document.querySelector('[data-section="reviews"]');
            if (reviewsLink) {
                reviewsLink.addEventListener('click', () => {
                    setTimeout(loadReviews, 100);
                });
            }

            // Load inquiries when inquiries section is shown
            const inquiriesLink = document.querySelector('[data-section="inquiries"]');
            if (inquiriesLink) {
                inquiriesLink.addEventListener('click', () => {
                    setTimeout(loadInquiries, 100);
                });
            }

            // Initialize analytics charts when analytics section is shown
            const analyticsLink = document.querySelector('[data-section="analytics"]');
            if (analyticsLink) {
                analyticsLink.addEventListener('click', () => {
                    setTimeout(() => {
                        if (!enrollmentChart) {
                            initializeAnalyticsCharts();
                        }
                    }, 200);
                });
            }

            // Also try to initialize charts immediately if analytics section is already visible
            setTimeout(() => {
                const analyticsSection = document.getElementById('analytics');
                if (analyticsSection && analyticsSection.style.display !== 'none') {
                    if (!enrollmentChart) {
                        initializeAnalyticsCharts();
                    }
                }
            }, 500);
        });
    </script>

    <!-- Add Study Level Modal -->
    <div id="addStudyLevelModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Study Level</h3>
                <span class="close" onclick="closeAddStudyLevelModal()">&times;</span>
            </div>
            <form id="addStudyLevelForm" onsubmit="submitNewStudyLevel(event)">
                <div class="form-group">
                    <label for="studyLevelName">Study Level Name *</label>
                    <input type="text" id="studyLevelName" name="studyLevelName" required placeholder="e.g., Postgraduate, Undergraduate">
                </div>
                <div class="form-group">
                    <label for="studyLevelDescription">Description</label>
                    <textarea id="studyLevelDescription" name="studyLevelDescription" rows="3" placeholder="Brief description of the study level..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddStudyLevelModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Study Level</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Program Modal -->
    <div id="addProgramModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New NIBM Program</h3>
                <button class="close-modal" onclick="closeAddProgramModal()">
                    <i class="fas fa-times"></i>
                            </button>
                        </div>
            <form id="addProgramForm" onsubmit="submitNewProgram(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="programName">Program Name *</label>
                        <input type="text" id="programName" name="program_name" required placeholder="e.g., MBA in Global Business">
                    </div>
                    </div>
                <div class="modal-actions">
                    <button type="button" class="btn-back" onclick="closeAddProgramModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-apply">
                        <i class="fas fa-plus"></i> Add Program
                    </button>
                        </div>
            </form>
                    </div>
                </div>

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Course</h3>
                <button class="close-modal" onclick="closeAddCourseModal()">
                    <i class="fas fa-times"></i>
                            </button>
                        </div>
            <form id="addCourseForm" onsubmit="submitNewCourse(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="courseName">Course Name *</label>
                        <input type="text" id="courseName" name="course_name" required placeholder="e.g., Introduction to Programming">
                    </div>
                    <div class="form-group">
                        <label for="courseDescription">Course Description</label>
                        <textarea id="courseDescription" name="course_description" rows="3" placeholder="Brief description of the course..."></textarea>
                        </div>
                    <div class="form-group">
                        <label for="courseRequirement">Requirements</label>
                        <textarea id="courseRequirement" name="requirement" rows="3" placeholder="Course requirements or prerequisites..."></textarea>
                        </div>
                    <input type="hidden" id="courseProgramId" name="program_id" value="">
                    <input type="hidden" id="courseStudyLevel" name="study_level" value="">
                        </div>
                <div class="modal-actions">
                    <button type="button" class="btn-back" onclick="closeAddCourseModal()">
                        <i class="fas fa-times"></i> Cancel
                            </button>
                    <button type="submit" class="btn-apply">
                        <i class="fas fa-plus"></i> Add Course
                    </button>
                        </div>
            </form>
                    </div>
                </div>
</body>
</html>
