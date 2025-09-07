<?php
session_start();
require_once 'config.php';

// Debug: Log session information
error_log("icbt-admin-dashboard.php - Session data: " . print_r($_SESSION, true));

// Check if admin is logged in and is from ICBT campus
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin' || $_SESSION['campus'] !== 'ICBT Campus') {
    error_log("icbt-admin-dashboard.php - Authentication failed. admin_id: " . ($_SESSION['admin_id'] ?? 'NOT SET') . ", user_type: " . ($_SESSION['user_type'] ?? 'NOT SET') . ", campus: " . ($_SESSION['campus'] ?? 'NOT SET'));
    header('Location: login.php');
    exit();
}

error_log("icbt-admin-dashboard.php - Authentication successful for admin_id: " . $_SESSION['admin_id'] . ", campus: " . $_SESSION['campus']);

$campus = $_SESSION['campus'];
$admin_email = $_SESSION['admin_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICBT Admin Dashboard - EduConnect SL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #0369a1;
            --primary-dark: #0284c7;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #0ea5e9;
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

        .card-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .card-actions select {
            padding: 0.25rem 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background: white;
            color: var(--dark-color);
        }

        .card-actions select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Enhanced Inquiry Statistics Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .stat-card:nth-child(1)::before {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
        }

        .stat-card:nth-child(2)::before {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .stat-card:nth-child(3)::before {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .stat-card:nth-child(4)::before {
            background: linear-gradient(90deg, #6b7280, #9ca3af);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--secondary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card:hover .stat-number {
            color: var(--primary-color);
        }

        .stat-card:hover .stat-label {
            color: var(--dark-color);
        }

                /* Responsive adjustments for stats */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .stat-label {
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Inquiry Details Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(4px);
        }
        
        .modal-overlay.active {
            opacity: 1;
        }
        
        .inquiry-details-modal {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .modal-overlay.active .inquiry-details-modal {
            transform: scale(1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 1rem 1rem 0 0;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background 0.2s ease;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .modal-body {
            padding: 2rem;
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .inquiry-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .detail-section {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
        }
        
        .detail-section.full-width {
            grid-column: 1 / -1;
        }
        
        .detail-section h4 {
            margin: 0 0 1rem 0;
            color: var(--dark-color);
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .detail-section h4 i {
            color: var(--primary-color);
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 500;
            color: var(--secondary-color);
            min-width: 100px;
        }
        
        .detail-value {
            color: var(--dark-color);
            font-weight: 500;
            text-align: right;
            flex: 1;
        }
        
        .detail-value.status-pending {
            color: #f59e0b;
            font-weight: 600;
        }
        
        .detail-value.status-answered {
            color: #10b981;
            font-weight: 600;
        }
        
        .detail-value.status-closed {
            color: #6b7280;
            font-weight: 600;
        }
        
        .message-content,
        .response-content {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            line-height: 1.6;
            color: var(--dark-color);
        }
        
        .response-content {
            background: #f0f9ff;
            border-color: #0ea5e9;
        }
        
        .modal-footer {
            padding: 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        @media (max-width: 768px) {
            .inquiry-details-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-header {
                padding: 1rem;
            }
            
            .modal-footer {
                padding: 1rem;
                flex-direction: column;
            }
            
            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
            
            .detail-value {
                text-align: left;
            }
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
            background: #0284c7;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .category-title {
                font-size: 1.25rem;
            }
            
            .faculty-title {
                font-size: 1rem;
            }
            
            .program-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .program-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        /* Header Actions Styles */
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Faculty Management Styles */
        .faculty-header {
            background: var(--secondary-color);
            padding: 1rem 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faculty-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .faculty-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit-faculty {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-edit-faculty:hover {
            background: #d97706;
        }

        .btn-delete-faculty {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-delete-faculty:hover {
            background: #dc2626;
        }

        /* Responsive adjustments for faculty management */
        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .faculty-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .faculty-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        /* Academic Structure Table Styles */
        .academic-structure {
            overflow-x: auto;
        }

        .structure-table {
            border-collapse: collapse;
            width: 100%;
            min-width: 800px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            table-layout: fixed;
        }
        
        .structure-table td {
            position: relative;
        }

        .structure-table th {
            background: #f8f9fa;
            color: #495057;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border: 1px solid #dee2e6;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .structure-table th:nth-child(1) {
            width: 20%;
        }
        
        .structure-table th:nth-child(2) {
            width: 25%;
        }
        
        .structure-table th:nth-child(3) {
            width: 55%;
        }
        
        .structure-table td:nth-child(1) {
            width: 20%;
        }
        
        .structure-table td:nth-child(2) {
            width: 25%;
        }
        
        .structure-table td:nth-child(3) {
            width: 55%;
        }

        .structure-table td {
            padding: 1rem;
            border: 1px solid #dee2e6;
            vertical-align: top;
            background: white;
        }

        .structure-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .structure-table tr:hover {
            background-color: #f1f3f4;
        }
        
        .structure-table tr {
            height: auto;
            min-height: 60px;
        }
        
        .structure-table tr td {
            border: 1px solid #dee2e6;
            padding: 1rem;
        }

        /* Study Level Cell Styles */
        .study-level-cell {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-align: center;
            min-width: 200px;
            border-right: 2px solid #dee2e6;
        }

        .study-level-info h4 {
            margin: 0 0 0.75rem 0;
            font-size: 1.125rem;
            color: #212529;
        }

        .study-level-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-edit-category,
        .btn-delete-category {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-category:hover,
        .btn-delete-category:hover {
            background: #5a6268;
        }

        .btn-add-program {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            width: 100%;
            max-width: 120px;
        }

        .btn-add-program:hover {
            background: #218838;
        }

        /* Programs Cell Styles */
        .programs-cell {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-align: center;
            min-width: 200px;
            border-right: 1px solid #dee2e6;
            vertical-align: top;
            position: relative;
        }

        .programs-info h5 {
            margin: 0 0 0.75rem 0;
            font-size: 1rem;
            color: #212529;
            text-align: center;
        }

        .programs-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .btn-edit-faculty,
        .btn-delete-faculty {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-faculty:hover,
        .btn-delete-faculty:hover {
            background: #5a6268;
        }

        /* Programs List Styles */
        .programs-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .program-item {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .program-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .program-name {
            font-weight: 600;
            color: #212529;
            flex: 1;
            font-size: 0.875rem;
            text-align: left;
        }

        .program-actions {
            display: flex;
            gap: 0.25rem;
        }

        .btn-edit-program,
        .btn-delete-program {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-program:hover,
        .btn-delete-program:hover {
            background: #5a6268;
        }

        .btn-add-course {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-course:hover {
            background: #0056b3;
        }

        /* Courses Cell Styles */
        .courses-cell {
            background: white;
            min-width: 400px;
            padding: 1rem;
            vertical-align: top;
            position: relative;
        }

        .course-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .course-name {
            font-weight: 600;
            color: #212529;
            flex: 1;
            font-size: 0.875rem;
        }

        /* Existing Courses Styles */
        .existing-courses {
            margin-bottom: 1rem;
        }

        .existing-courses h6 {
            margin: 0 0 0.5rem 0;
            color: #6c757d;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .existing-course-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .existing-course-item:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }

        .course-code {
            font-weight: 600;
            color: #495057;
            min-width: 60px;
            font-size: 0.75rem;
        }

        .course-title {
            flex: 1;
            color: #212529;
            font-size: 0.875rem;
        }

        .course-credits {
            color: #6c757d;
            font-size: 0.75rem;
            min-width: 80px;
        }

        .course-actions {
            display: flex;
            gap: 0.25rem;
        }

        .btn-edit-course,
        .btn-delete-course {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-course:hover {
            background: #5a6268;
        }

        .btn-delete-course {
            background: #dc3545;
        }

        .btn-delete-course:hover {
            background: #c82333;
        }

        .btn-edit-existing-course,
        .btn-delete-existing-course {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-edit-existing-course:hover {
            background: #5a6268;
        }

        .btn-delete-existing-course {
            background: #dc3545;
        }

        .btn-delete-existing-course:hover {
            background: #c82333;
        }

        .no-courses {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 0.75rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
            border-radius: 0.25rem;
            border: 1px dashed #dee2e6;
        }

        .no-courses p {
            margin: 0;
            font-size: 0.875rem;
        }

        /* No Program in DB Styles */
        .no-program-db {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 0.75rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
            border-radius: 0.25rem;
            border: 1px dashed #dee2e6;
        }

        .program-not-db {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .btn-add-program-small {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: background-color 0.2s ease;
        }

        .btn-add-program-small:hover {
            background: #218838;
        }

        /* Actions Cell Styles */
        .actions-cell {
            background: white;
            text-align: center;
        }

        .row-actions {
            display: flex;
            justify-content: center;
        }

        .btn-view-details {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-view-details:hover {
            background: #5a6268;
        }

        /* No Data Styles */
        .no-data {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .no-data-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .no-data-message i {
            font-size: 2rem;
            opacity: 0.5;
        }

        .error {
            color: #dc3545;
            text-align: center;
            padding: 1rem;
        }

        /* Responsive adjustments for the new table */
        @media (max-width: 1200px) {
            .structure-table {
                min-width: 600px;
            }
            
            .courses-cell {
                min-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .academic-structure {
                margin: 0 -1rem;
            }
            
            .structure-table {
                min-width: 500px;
            }
            
            .program-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .course-item {
                flex-direction: column;
                align-items: flex-start;
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
            background: #0284c7;
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

        /* Enhanced Courses Section Styles */
        .courses-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .courses-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .courses-title-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .courses-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }

        .courses-header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-primary-action {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary-action:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-secondary-action {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary-action:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Course Statistics Grid */
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
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .course-stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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
            margin: 0 0 0.25rem 0;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary-color);
            margin: 0;
            font-weight: 500;
        }

        /* Enhanced Study Levels Container */
        .study-levels-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .study-level-card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .study-level-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .study-level-header {
            background: var(--light-color);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .study-level-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .study-level-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .study-level-details h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0 0 0.25rem 0;
        }

        .study-level-description {
            font-size: 0.875rem;
            color: var(--secondary-color);
            margin: 0;
        }

        .study-level-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .btn-edit-study-level {
            background: var(--secondary-color);
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
            transition: all 0.3s ease;
        }

        .btn-edit-study-level:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .btn-add-program-primary {
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
            transition: all 0.3s ease;
        }

        .btn-add-program-primary:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        /* Programs Grid */
        .programs-grid {
            padding: 1.5rem;
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
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .program-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .program-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .program-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit-program {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-edit-program:hover {
            background: #d97706;
        }

        .btn-delete-program {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-delete-program:hover {
            background: #dc2626;
        }

        .btn-add-course {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-add-course:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .program-description {
            font-size: 0.875rem;
            color: var(--secondary-color);
            margin: 0.75rem 0;
            line-height: 1.5;
        }

        .program-meta {
            display: flex;
            gap: 1rem;
            margin: 0.75rem 0;
            flex-wrap: wrap;
        }

        .program-code {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .program-duration {
            background: var(--success-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Courses List */
        .courses-list {
            margin-top: 1rem;
        }

        .courses-list h6 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0 0 1rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .course-item {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .course-item:hover {
            box-shadow: var(--shadow-sm);
            border-color: var(--primary-color);
        }

        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .course-code {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.875rem;
        }

        .course-actions {
            display: flex;
            gap: 0.25rem;
        }

        .btn-edit-course {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-edit-course:hover {
            background: #5a6268;
        }

        .btn-delete-course {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-delete-course:hover {
            background: #dc2626;
        }

        .course-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--secondary-color);
        }

        .course-detail {
            display: flex;
            align-items: flex-start;
            gap: 0.25rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .course-detail span {
            flex: 1;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .no-courses {
            text-align: center;
            padding: 2rem;
            color: var(--secondary-color);
            background: var(--light-color);
            border-radius: 0.5rem;
            border: 2px dashed var(--border-color);
        }

        .no-courses i {
            font-size: 2rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-courses p {
            margin: 0;
            font-size: 0.875rem;
        }

        /* Responsive adjustments for new sections */
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

            .courses-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .courses-header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .course-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .study-level-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .study-level-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .programs-grid {
                grid-template-columns: 1fr;
            }

            .program-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .course-details {
                grid-template-columns: 1fr;
            }
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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>ICBT Campus</h2>
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
                        ICBT Courses
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
                    <h1>ICBT Campus Admin Dashboard</h1>
                </div>
                
                <div class="top-bar-right">
                    <button class="mobile-toggle" id="mobileToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="admin-info">
                        <div class="admin-avatar">
                            I
                        </div>
                        <div class="admin-details">
                            <h4>ICBT Admin</h4>
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
                                <h3 class="card-title">ICBT Students</h3>
                                <div class="card-icon primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="card-value">90,000+</div>
                            <p class="card-description">Foreign degrees obtained locally</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">ICBT Programs</h3>
                                <div class="card-icon success">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="card-value">6</div>
                            <p class="card-description">Certificate to Master's levels</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Campus Locations</h3>
                                <div class="card-icon warning">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            <div class="card-value">10</div>
                            <p class="card-description">Branches across Sri Lanka</p>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Established</h3>
                                <div class="card-icon info">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="card-value">2000</div>
                            <p class="card-description">International partnerships</p>
                        </div>
                    </div>

                    <!-- ICBT Campus Info -->
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">ICBT Campus Information</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Locations</h4>
                                <p>Bambalapitiya, Colombo-04 (Main), with branches in Gampaha, Nugegoda, Kurunegala, Kandy, Matara, Galle, Jaffna, Batticaloa, and Anuradhapura</p>
                            </div>
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--success-color); margin-bottom: 0.5rem;">Established</h4>
                                <p>2000</p>
                            </div>
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--warning-color); margin-bottom: 0.5rem;">Accreditation</h4>
                                <p>UGC Approved</p>
                            </div>
                            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                                <h4 style="color: var(--info-color); margin-bottom: 0.5rem;">Specialization</h4>
                                <p>IT, Business Management, Quantity Surveying, Engineering, and Psychology</p>
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
                                    ICBT Course Management
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
                                <button class="btn-danger-action" onclick="clearAllCourses()" style="background-color: #ef4444; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: background-color 0.3s ease;">
                                    <i class="fas fa-trash"></i>
                                    Clear All Courses
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
                                <h3 class="stat-number" id="totalStudyLevels">3</h3>
                                <p class="stat-label">Study Levels</p>
                            </div>
                        </div>
                        <div class="course-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number" id="totalPrograms">18</h3>
                                <p class="stat-label">Programs</p>
                            </div>
                        </div>
                        <div class="course-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number" id="totalCourses">156</h3>
                                <p class="stat-label">Courses</p>
                            </div>
                        </div>
                        <div class="course-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number" id="activeStudents">847</h3>
                                <p class="stat-label">Active Students</p>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Study Levels Container -->
                    <div class="study-levels-container">
                        <!-- Postgraduate Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">Postgraduate</h3>
                                        <p class="study-level-description">Advanced degree programs for graduates</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('Postgraduate')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('Postgraduate')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                    </button>
                                </div>
                            </div>
                            
                            <div class="programs-grid" id="postgraduate-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>

                        <!-- Undergraduate Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">Undergraduate</h3>
                                        <p class="study-level-description">Bachelor's degree programs</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('Undergraduate')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('Undergraduate')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                    </button>
                                </div>
                            </div>
                            
                            <div class="programs-grid" id="undergraduate-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>

                        <!-- After A/L & O/L Level -->
                        <div class="study-level-card">
                            <div class="study-level-header">
                                <div class="study-level-info">
                                    <div class="study-level-icon">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                    <div class="study-level-details">
                                        <h3 class="study-level-title">After A/L & O/L</h3>
                                        <p class="study-level-description">Foundation and certificate programs</p>
                                    </div>
                                </div>
                                <div class="study-level-actions">
                                    <button class="btn-edit-study-level" onclick="editStudyLevel('After A/L & O/L')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-add-program-primary" onclick="openAddProgramModal('After A/L & O/L')">
                                        <i class="fas fa-plus"></i>
                                        Add Program
                                    </button>
                                </div>
                            </div>
                            
                            <div class="programs-grid" id="after-al-ol-programs">
                                <!-- Programs will be loaded here -->
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Students Section -->
                <section id="students" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">ICBT Student Directory</h3>
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
                                        <td>ICBT001</td>
                                        <td>John Doe</td>
                                        <td>BIT</td>
                                        <td>john.doe@icbt.edu.lk</td>
                                        <td>+94 71 123 4567</td>
                                        <td>2024-01-15</td>
                                        <td><span style="color: var(--success-color);">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>ICBT002</td>
                                        <td>Jane Smith</td>
                                        <td>BBA</td>
                                        <td>jane.smith@icbt.edu.lk</td>
                                        <td>+94 77 234 5678</td>
                                        <td>2024-01-20</td>
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
                            <h3 class="table-title">ICBT Course Registration Requests</h3>
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
                                    WHERE cr.campus = 'ICBT'
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
                                        <p>No course registrations found for ICBT Campus</p>
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
                            <h3 class="table-title">Course Applications - ICBT Campus</h3>
                            <p style="margin-top: 0.5rem; color: var(--secondary-color);">Review and manage student course applications</p>
                        </div>
                        <div id="applicationsContent">
                            <?php
                            try {
                                // Fetch course applications for ICBT campus
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
                                    WHERE ca.university = 'ICBT Campus'
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
                                        <p>No course applications found for ICBT Campus</p>
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

                        <!-- Program Popularity Chart -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Program Popularity Analysis</h3>
                                <div class="chart-controls">
                                    <button class="chart-control-btn active" onclick="updateProgramChart('enrollment')">Enrollment</button>
                                    <button class="chart-control-btn" onclick="updateProgramChart('demand')">Demand</button>
                                    <button class="chart-control-btn" onclick="updateProgramChart('completion')">Completion</button>
                                </div>
                            </div>
                            <div id="programChart" style="height: 400px; position: relative;">
                                <canvas id="programCanvas" width="400" height="400"></canvas>
                            </div>
                        </div>

                        <!-- Geographic Distribution Chart -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Geographic Distribution</h3>
                                <div class="chart-controls">
                                    <button class="chart-control-btn active" onclick="updateGeographicChart('students')">Students</button>
                                    <button class="chart-control-btn" onclick="updateGeographicChart('programs')">Programs</button>
                                    <button class="chart-control-btn" onclick="updateGeographicChart('capacity')">Capacity</button>
                                </div>
                            </div>
                            <div id="geographicChart" style="height: 400px; position: relative;">
                                <canvas id="geographicCanvas" width="400" height="400"></canvas>
                            </div>
                        </div>

                        <!-- Accreditation Status Chart -->
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
                            <h3 class="table-title">ICBT Analytics Insights</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-chart-line"></i>
                                    Enrollment Growth
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>Total Students:</strong> 90,000+ with foreign degrees</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Growth Rate:</strong> +15% annually</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Peak Season:</strong> January - March</p>
                                <p style="color: var(--success-color); font-weight: 600;">📈 Strong growth in international programs</p>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--success-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-trophy"></i>
                                    Program Performance
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>Top Program:</strong> IT (35% enrollment)</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Fastest Growing:</strong> Business Management (+22%)</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Completion Rate:</strong> 94% average</p>
                                <p style="color: var(--success-color); font-weight: 600;">🎯 Excellent program diversity</p>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--warning-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-map-marked-alt"></i>
                                    Geographic Spread
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>Main Campus:</strong> Bambalapitiya, Colombo-04</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Regional Coverage:</strong> 10 locations</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Coverage:</strong> All major cities</p>
                                <p style="color: var(--info-color); font-weight: 600;">🌍 Nationwide presence</p>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.75rem;">
                                <h4 style="color: var(--info-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-shield-alt"></i>
                                    Accreditation Health
                                </h4>
                                <p style="margin-bottom: 0.5rem;"><strong>UGC Recognition:</strong> 100% programs</p>
                                <p style="margin-bottom: 0.5rem;"><strong>International Partners:</strong> UK, Australia, Thailand, India, Sweden</p>
                                <p style="margin-bottom: 0.5rem;"><strong>Quality Assurance:</strong> Regular audits</p>
                                <p style="color: var(--success-color); font-weight: 600;">✅ Full accreditation compliance</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Settings Section -->
                <section id="settings" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">ICBT Dashboard Settings</h3>
                        </div>
                        <div id="settingsContent">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.5rem;">
                                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Notification Settings</h4>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" checked> Email notifications
                                        </label>
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" checked> Dashboard alerts
                                        </label>
                                    </div>
                                </div>
                                
                                <div style="padding: 1.5rem; background: var(--light-color); border-radius: 0.5rem;">
                                    <h4 style="color: var(--success-color); margin-bottom: 1rem;">Display Settings</h4>
                                    <div style="margin-bottom: 1rem;">
                                        <label>Theme:</label>
                                        <select style="margin-top: 0.5rem; width: 100%;">
                                            <option>Light</option>
                                            <option>Dark</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Review Moderation Section -->
                <section id="reviews" class="dashboard-section" style="display: none;">
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Review Moderation</h3>
                            <p class="table-subtitle">Moderate student reviews and ratings for ICBT courses</p>
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
                            <p class="table-subtitle">Manage and respond to student inquiries for ICBT courses</p>
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
                            <p class="table-subtitle">Create and manage articles, announcements, and content for ICBT students</p>
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
            </main>
        </div>
    </div>

    <!-- Add Program Modal -->
    <div id="addProgramModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New ICBT Program</h3>
                <span class="close" onclick="closeAddProgramModal()">&times;</span>
            </div>
            <form id="addProgramForm" onsubmit="submitNewProgram(event)">
                <div class="form-group">
                    <label for="programName">Program Name *</label>
                    <input type="text" id="programName" name="programName" required placeholder="e.g., Bachelor of Information Technology">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddProgramModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Course</h3>
                <span class="close" onclick="closeAddCourseModal()">&times;</span>
            </div>
            <form id="addCourseForm" onsubmit="submitNewCourse(event)">
                <input type="hidden" id="courseProgramId" name="programId">
                <div class="form-group">
                    <label for="courseTitle">Course Name *</label>
                    <input type="text" id="courseTitle" name="courseTitle" required placeholder="e.g., Introduction to Programming">
                </div>
                <div class="form-group">
                    <label for="courseDescription">Course Description</label>
                    <textarea id="courseDescription" name="courseDescription" rows="3" placeholder="Brief description of the course..."></textarea>
                </div>
                <div class="form-group">
                    <label for="courseRequirement">Requirement</label>
                    <textarea id="courseRequirement" name="courseRequirement" rows="3" placeholder="Course requirements or prerequisites..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddCourseModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editCourseModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Course</h3>
                <span class="close" onclick="closeEditCourseModal()">&times;</span>
            </div>
            <form id="editCourseForm" onsubmit="submitEditCourse(event)">
                <input type="hidden" id="editCourseId" name="courseId">
                <div class="form-group">
                    <label for="editCourseCode">Course Code *</label>
                    <input type="text" id="editCourseCode" name="courseCode" required>
                </div>
                <div class="form-group">
                    <label for="editCourseTitle">Course Title *</label>
                    <input type="text" id="editCourseTitle" name="courseTitle" required>
                </div>
                <div class="form-group">
                    <label for="editCourseCredits">Credits *</label>
                    <input type="number" id="editCourseCredits" name="courseCredits" min="1" max="6" required>
                </div>
                <div class="form-group">
                    <label for="editCourseSemester">Semester *</label>
                    <select id="editCourseSemester" name="courseSemester" required>
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                        <option value="3rd Semester">3rd Semester</option>
                        <option value="4th Semester">4th Semester</option>
                        <option value="5th Semester">5th Semester</option>
                        <option value="6th Semester">6th Semester</option>
                        <option value="7th Semester">7th Semester</option>
                        <option value="8th Semester">8th Semester</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editCourseInstructor">Instructor *</label>
                    <input type="text" id="editCourseInstructor" name="courseInstructor" required>
                </div>
                <div class="form-group">
                    <label for="editCourseDescription">Course Description</label>
                    <textarea id="editCourseDescription" name="courseDescription" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="editCourseStatus">Status</label>
                    <select id="editCourseStatus" name="courseStatus">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Draft">Draft</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditCourseModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update Course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Faculty Modal -->
    <div id="addFacultyModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Faculty</h3>
                <span class="close" onclick="closeAddFacultyModal()">&times;</span>
            </div>
            <form id="addFacultyForm" onsubmit="submitNewFaculty(event)">
                <div class="form-group">
                    <label for="facultyCategory">Category *</label>
                    <select id="facultyCategory" name="facultyCategory" required>
                        <option value="">Select Category</option>
                        <option value="Postgraduate">Postgraduate</option>
                        <option value="Undergraduate">Undergraduate</option>
                        <option value="After A/L">After A/L</option>
                        <option value="After O/L">After O/L</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="facultyName">Faculty Name *</label>
                    <input type="text" id="facultyName" name="facultyName" required placeholder="e.g., Business, Engineering & Construction">
                </div>
                <div class="form-group">
                    <label for="facultyDescription">Description</label>
                    <textarea id="facultyDescription" name="facultyDescription" rows="3" placeholder="Faculty description..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddFacultyModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Faculty</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Faculty Modal -->
    <div id="editFacultyModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Faculty</h3>
                <span class="close" onclick="closeEditFacultyModal()">&times;</span>
            </div>
            <form id="editFacultyForm" onsubmit="submitEditFaculty(event)">
                <input type="hidden" id="editFacultyOldName" name="oldFacultyName">
                <input type="hidden" id="editFacultyCategory" name="facultyCategory">
                <div class="form-group">
                    <label for="editFacultyName">Faculty Name *</label>
                    <input type="text" id="editFacultyName" name="facultyName" required>
                </div>
                <div class="form-group">
                    <label for="editFacultyDescription">Description</label>
                    <textarea id="editFacultyDescription" name="facultyDescription" rows="3"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditFacultyModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update Faculty</button>
                </div>
            </form>
        </div>
    </div>

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
                    <input type="text" id="studyLevelName" name="studyLevelName" required placeholder="e.g., Foundation, Certificate, Diploma">
                </div>
                <div class="form-group">
                    <label for="studyLevelDescription">Description</label>
                    <textarea id="studyLevelDescription" name="studyLevelDescription" rows="3" placeholder="Brief description of this study level..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddStudyLevelModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Study Level</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Add Program Modal -->
    <div id="enhancedAddProgramModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Program</h3>
                <span class="close" onclick="closeEnhancedAddProgramModal()">&times;</span>
            </div>
            <form id="enhancedAddProgramForm" onsubmit="submitEnhancedNewProgram(event)">
                <input type="hidden" id="programStudyLevel" name="studyLevel">
                <div class="form-group">
                    <label for="enhancedProgramName">Program Name *</label>
                    <input type="text" id="enhancedProgramName" name="programName" required placeholder="e.g., Bachelor of Information Technology">
                </div>
                <div class="form-group">
                    <label for="programCode">Program Code *</label>
                    <input type="text" id="programCode" name="programCode" required placeholder="e.g., BIT, BBA, MSc">
                </div>
                <div class="form-group">
                    <label for="programDuration">Duration *</label>
                    <select id="programDuration" name="programDuration" required>
                        <option value="">Select Duration</option>
                        <option value="6 months">6 months</option>
                        <option value="1 year">1 year</option>
                        <option value="2 years">2 years</option>
                        <option value="3 years">3 years</option>
                        <option value="4 years">4 years</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="programDescription">Description</label>
                    <textarea id="programDescription" name="programDescription" rows="3" placeholder="Brief description of the program..."></textarea>
                </div>
                <div class="form-group">
                    <label for="programRequirements">Requirements</label>
                    <textarea id="programRequirements" name="programRequirements" rows="3" placeholder="Program requirements or prerequisites..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEnhancedAddProgramModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Add Course Modal -->
    <div id="enhancedAddCourseModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Course</h3>
                <span class="close" onclick="closeEnhancedAddCourseModal()">&times;</span>
            </div>
            <form id="enhancedAddCourseForm" onsubmit="submitEnhancedNewCourse(event)">
                <input type="hidden" id="courseStudyLevel" name="studyLevel">
                <input type="hidden" id="courseProgramName" name="programName">
                <div class="form-group">
                    <label for="enhancedCourseName">Course Name *</label>
                    <input type="text" id="enhancedCourseName" name="courseName" required placeholder="e.g., Introduction to Programming">
                </div>
                <div class="form-group">
                    <label for="enhancedCourseDescription">Course Description</label>
                    <textarea id="enhancedCourseDescription" name="courseDescription" rows="3" placeholder="Brief description of the course..."></textarea>
                </div>
                <div class="form-group">
                    <label for="enhancedCourseRequirement">Requirement</label>
                    <textarea id="enhancedCourseRequirement" name="courseRequirement" rows="3" placeholder="Course requirements or prerequisites..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEnhancedAddCourseModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Course</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Dashboard functionality
        class ICBTAdminDashboard {
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

        function editStudyLevel(studyLevel) {
            alert(`Edit functionality for ${studyLevel} will be implemented in future updates.`);
        }

        function openEnhancedAddProgramModal(studyLevel) {
            document.getElementById('programStudyLevel').value = studyLevel;
            document.getElementById('enhancedAddProgramModal').style.display = 'block';
        }

        function closeEnhancedAddProgramModal() {
            document.getElementById('enhancedAddProgramModal').style.display = 'none';
            document.getElementById('enhancedAddProgramForm').reset();
        }

        function submitEnhancedNewProgram(event) {
            event.preventDefault();
            const form = document.getElementById('enhancedAddProgramForm');
            const formData = new FormData(form);
            const studyLevel = document.getElementById('programStudyLevel').value;
            const programName = document.getElementById('enhancedProgramName').value;
            const programCode = document.getElementById('programCode').value;
            const duration = document.getElementById('programDuration').value;
            const description = document.getElementById('programDescription').value;
            const requirements = document.getElementById('programRequirements').value;

            // Create program object
            const program = {
                name: programName,
                code: programCode,
                duration: duration,
                description: description,
                requirements: requirements,
                studyLevel: studyLevel
            };

            // Store program in localStorage for persistence
            storeProgram(studyLevel, program);

            // Add program to the appropriate container
            addProgramToContainer(studyLevel, program);
            
            // Save to database
            const dbFormData = new FormData();
            dbFormData.append('action', 'add_program');
            dbFormData.append('programCode', programCode);
            dbFormData.append('programName', programName);
            dbFormData.append('duration', duration);
            dbFormData.append('level', studyLevel);
            dbFormData.append('description', description);
            dbFormData.append('requirements', requirements);
            dbFormData.append('campus', 'ICBT');
            
            fetch('admin-course-actions.php', {
                method: 'POST',
                body: dbFormData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Program saved to database:', data);
                } else {
                    console.error('Failed to save program to database:', data.message);
                }
            })
            .catch(error => {
                console.error('Error saving program to database:', error);
            });
            
            // Close modal and show success message
            closeEnhancedAddProgramModal();
            alert('Program added successfully!');
        }

        function openEnhancedAddCourseModal(studyLevel, programName) {
            document.getElementById('courseStudyLevel').value = studyLevel;
            document.getElementById('courseProgramName').value = programName;
            document.getElementById('enhancedAddCourseModal').style.display = 'block';
        }

        function closeEnhancedAddCourseModal() {
            document.getElementById('enhancedAddCourseModal').style.display = 'none';
            document.getElementById('enhancedAddCourseForm').reset();
            
            // Reset editing state
            document.getElementById('enhancedAddCourseModal').removeAttribute('data-editing-course');
            
            // Reset modal title and button text
            const modalTitle = document.querySelector('#enhancedAddCourseModal .modal-header h3');
            modalTitle.textContent = 'Add New Course';
            const submitBtn = document.querySelector('#enhancedAddCourseForm .btn-submit');
            submitBtn.textContent = 'Add Course';
        }

        function submitEnhancedNewCourse(event) {
            event.preventDefault();
            const form = document.getElementById('enhancedAddCourseForm');
            const formData = new FormData(form);
            const studyLevel = document.getElementById('courseStudyLevel').value;
            const programName = document.getElementById('courseProgramName').value;
            const courseName = document.getElementById('enhancedCourseName').value;
            const courseDescription = document.getElementById('enhancedCourseDescription').value;
            const courseRequirement = document.getElementById('enhancedCourseRequirement').value;
            
            // Debug logging for form values
            console.log('Form values:');
            console.log('Course Name:', courseName);
            console.log('Course Description:', courseDescription);
            console.log('Course Requirement:', courseRequirement);

            // Check if we're editing an existing course
            const editingCourseCode = document.getElementById('enhancedAddCourseModal').getAttribute('data-editing-course');
            
            if (editingCourseCode) {
                // Update existing course logic here if needed
                // For now, we'll just handle new course creation
            }

            // Create new course object with simplified structure
            const course = {
                name: courseName,
                code: 'CS' + Date.now(), // Generate unique course code
                credits: '3', // Default credits
                semester: '1st Semester', // Default semester
                instructor: 'TBD', // Default instructor
                description: courseDescription,
                requirement: courseRequirement
            };
            
            // Debug logging
            console.log('Course object created:', course);
            console.log('Course description:', courseDescription);
            console.log('Course requirement:', courseRequirement);

            // First, save to database
            const dbFormData = new FormData();
            dbFormData.append('action', 'add_course');
            dbFormData.append('campus', 'ICBT');
            dbFormData.append('courseName', courseName);
            dbFormData.append('courseDescription', courseDescription);
            dbFormData.append('courseRequirement', courseRequirement);
            dbFormData.append('programName', programName);
            dbFormData.append('studyLevel', studyLevel);
            
            // Debug logging for FormData
            console.log('FormData being sent:');
            for (let [key, value] of dbFormData.entries()) {
                console.log(key + ': ' + value);
            }

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: dbFormData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // If database save successful, update the course object with the database ID
                    course.id = data.course_id;
                    course.code = data.course_code || course.code;
                    
                    // Store course in localStorage for persistence
                    storeCourse(studyLevel, programName, course);

                    // Add course to the appropriate program
                    addCourseToProgram(studyLevel, programName, course);
                    
                    // Close modal and show success message
                    closeEnhancedAddCourseModal();
                    alert('Course added successfully to both database and dashboard!');
                } else {
                    alert('Error saving to database: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving to database. Course will be saved locally only.');
                
                // Fallback: save to localStorage only
                storeCourse(studyLevel, programName, course);
                addCourseToProgram(studyLevel, programName, course);
                closeEnhancedAddCourseModal();
            });
        }

        function addProgramToContainer(studyLevel, program) {
            const containerId = getContainerId(studyLevel);
            const container = document.getElementById(containerId);
            
            if (!container) return;

            // Check if container has no-programs message
            const noProgramsMsg = container.querySelector('.no-programs');
            if (noProgramsMsg) {
                noProgramsMsg.remove();
            }

            // Create program HTML
            const programHTML = createProgramHTML(program);
            container.insertAdjacentHTML('beforeend', programHTML);
        }

        function addCourseToProgram(studyLevel, programName, course) {
            const containerId = getContainerId(studyLevel);
            const container = document.getElementById(containerId);
            
            if (!container) return;

            // Find the program
            const programElement = container.querySelector(`[data-program-name="${programName}"]`);
            if (!programElement) return;

            // Find or create courses list
            let coursesList = programElement.querySelector('.courses-list');
            if (!coursesList) {
                coursesList = document.createElement('div');
                coursesList.className = 'courses-list';
                coursesList.innerHTML = '<h6><i class="fas fa-book"></i> Courses</h6>';
                programElement.appendChild(coursesList);
            }

            // Remove no-courses message if it exists
            const noCoursesMsg = coursesList.querySelector('.no-courses');
            if (noCoursesMsg) {
                noCoursesMsg.remove();
            }

            // Create course HTML
            const courseHTML = createCourseHTML(course);
            coursesList.insertAdjacentHTML('beforeend', courseHTML);
        }

        function loadCoursesForProgram(studyLevel, programName) {
            const key = `icbt_courses_${studyLevel.replace(/\s+/g, '_')}_${programName.replace(/\s+/g, '_')}`;
            const courses = JSON.parse(localStorage.getItem(key) || '[]');
            
            // Debug logging
            console.log('Loading courses for program:', programName);
            console.log('Storage key:', key);
            console.log('Loaded courses:', courses);
            
            if (courses.length > 0) {
                const containerId = getContainerId(studyLevel);
                const container = document.getElementById(containerId);
                const programElement = container.querySelector(`[data-program-name="${programName}"]`);
                
                if (programElement) {
                    let coursesList = programElement.querySelector('.courses-list');
                    if (!coursesList) {
                        coursesList = document.createElement('div');
                        coursesList.className = 'courses-list';
                        coursesList.innerHTML = '<h6><i class="fas fa-book"></i> Courses</h6>';
                        programElement.appendChild(coursesList);
                    }

                    // Remove no-courses message if it exists
                    const noCoursesMsg = coursesList.querySelector('.no-courses');
                    if (noCoursesMsg) {
                        noCoursesMsg.remove();
                    }

                    // Add each course
                    courses.forEach(course => {
                        const courseHTML = createCourseHTML(course);
                        coursesList.insertAdjacentHTML('beforeend', courseHTML);
                    });
                }
            }
        }

        function createProgramHTML(program) {
            const programHTML = `
                <div class="program-card" data-program-name="${program.name}">
                    <div class="program-header">
                        <h4 class="program-title">${program.name}</h4>
                        <div class="program-actions">
                            <button class="btn-edit-program" onclick="editProgram('${program.name}', '${program.studyLevel}')" title="Edit Program">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-program" onclick="deleteProgram('${program.name}', '${program.studyLevel}')" title="Delete Program">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    ${program.description ? `<p class="program-description">${program.description}</p>` : ''}
                    <div class="program-meta">
                        <span class="program-code">${program.code}</span>
                        <span class="program-duration">${program.duration}</span>
                    </div>
                    <button class="btn-add-course" onclick="openEnhancedAddCourseModal('${program.studyLevel}', '${program.name}')">
                        <i class="fas fa-plus"></i> Add Course
                    </button>
                    <div class="courses-list">
                        <div class="no-courses">
                            <i class="fas fa-book-open"></i>
                            <p>No courses added yet</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Load existing courses for this program after a short delay to ensure DOM is ready
            setTimeout(() => {
                loadCoursesForProgram(program.studyLevel, program.name);
            }, 100);
            
            return programHTML;
        }

        function createCourseHTML(course) {
            // Debug logging
            console.log('Creating HTML for course:', course);
            console.log('Course description:', course.description);
            console.log('Course requirement:', course.requirement);
            
            return `
                <div class="course-item" data-course-code="${course.code}">
                    <div class="course-header">
                        <span class="course-code">${course.code}</span>
                        <div class="course-actions">
                            <button class="btn-edit-course" onclick="editLocalCourse('${course.code}')" title="Edit Course">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-course" onclick="deleteLocalCourse('${course.code}')" title="Delete Course">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="course-details">
                        <div class="course-detail">
                            <i class="fas fa-file-alt"></i>
                            <span>${course.name}</span>
                        </div>
                        ${course.description && course.description.trim() ? `
                        <div class="course-detail">
                            <i class="fas fa-info-circle"></i>
                            <span>${course.description}</span>
                        </div>
                        ` : ''}
                        ${course.requirement && course.requirement.trim() ? `
                        <div class="course-detail">
                            <i class="fas fa-clipboard-list"></i>
                            <span>${course.requirement}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        function getContainerId(studyLevel) {
            switch(studyLevel) {
                case 'Postgraduate':
                    return 'postgraduate-programs';
                case 'Undergraduate':
                    return 'undergraduate-programs';
                case 'After A/L & O/L':
                    return 'after-al-ol-programs';
                default:
                    return 'postgraduate-programs';
            }
        }

        function editProgram(programName, studyLevel) {
            alert(`Edit functionality for program "${programName}" will be implemented in future updates.`);
        }

        function deleteProgram(programName, studyLevel) {
            if (confirm(`Are you sure you want to delete the program "${programName}"? This will remove all courses under this program.`)) {
                const containerId = getContainerId(studyLevel);
                const container = document.getElementById(containerId);
                const programElement = container.querySelector(`[data-program-name="${programName}"]`);
                
                if (programElement) {
                    // First, try to delete from database
                    const dbFormData = new FormData();
                    dbFormData.append('action', 'delete_program');
                    dbFormData.append('campus', 'ICBT');
                    dbFormData.append('program_name', programName);

                    fetch('admin-course-actions.php', {
                        method: 'POST',
                        body: dbFormData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove from localStorage
                            const key = `icbt_programs_${studyLevel.replace(/\s+/g, '_')}`;
                            let programs = JSON.parse(localStorage.getItem(key) || '[]');
                            programs = programs.filter(p => p.name !== programName);
                            localStorage.setItem(key, JSON.stringify(programs));
                            
                            // Remove courses for this program
                            const courseKey = `icbt_courses_${studyLevel.replace(/\s+/g, '_')}_${programName.replace(/\s+/g, '_')}`;
                            localStorage.removeItem(courseKey);
                            
                            // Remove the program element from DOM
                            programElement.remove();
                            
                            // Check if container is empty and show no-programs message
                            if (container.children.length === 0) {
                                container.innerHTML = `
                                    <div class="no-programs">
                                        <i class="fas fa-graduation-cap"></i>
                                        <p>No programs added yet</p>
                                    </div>
                                `;
                            }
                            
                            alert('Program deleted successfully from both database and dashboard!');
                        } else {
                            alert('Error deleting from database: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting from database. Program will be deleted locally only.');
                        
                        // Fallback: delete from localStorage only
                        const key = `icbt_programs_${studyLevel.replace(/\s+/g, '_')}`;
                        let programs = JSON.parse(localStorage.getItem(key) || '[]');
                        programs = programs.filter(p => p.name !== programName);
                        localStorage.setItem(key, JSON.stringify(programs));
                        
                        // Remove courses for this program
                        const courseKey = `icbt_courses_${studyLevel.replace(/\s+/g, '_')}_${programName.replace(/\s+/g, '_')}`;
                        localStorage.removeItem(courseKey);
                        
                        programElement.remove();
                        
                        // Check if container is empty and show no-programs message
                        if (container.children.length === 0) {
                            container.innerHTML = `
                                <div class="no-programs">
                                    <i class="fas fa-graduation-cap"></i>
                                    <p>No programs added yet</p>
                                </div>
                            `;
                        }
                    });
                } else {
                    alert('Program not found!');
                }
            }
        }

        function editLocalCourse(courseCode) {
            // Find the course element
            const courseElement = document.querySelector(`[data-course-code="${courseCode}"]`);
            if (!courseElement) {
                alert('Course not found!');
                return;
            }

            // Find the program element
            const programElement = courseElement.closest('.program-item');
            if (!programElement) {
                alert('Program not found!');
                return;
            }

            const programName = programElement.getAttribute('data-program-name');
            const studyLevel = programElement.querySelector('.btn-add-course').getAttribute('onclick').match(/'([^']+)'/)[1];
            
            // Get course data from localStorage
            const key = `icbt_courses_${studyLevel.replace(/\s+/g, '_')}_${programName.replace(/\s+/g, '_')}`;
            const courses = JSON.parse(localStorage.getItem(key) || '[]');
            const course = courses.find(c => c.code === courseCode);
            
            if (!course) {
                alert('Course data not found!');
                return;
            }

            // Populate the enhanced add course modal for editing
            document.getElementById('courseStudyLevel').value = studyLevel;
            document.getElementById('courseProgramName').value = programName;
            document.getElementById('enhancedCourseName').value = course.name;
            document.getElementById('courseDescription').value = course.description || '';
            document.getElementById('courseRequirement').value = course.requirement || '';
            
            // Change modal title and button text
            const modalTitle = document.querySelector('#enhancedAddCourseModal .modal-header h3');
            modalTitle.textContent = 'Edit Course';
            const submitBtn = document.querySelector('#enhancedAddCourseForm .btn-submit');
            submitBtn.textContent = 'Update Course';
            
            // Show the modal
            document.getElementById('enhancedAddCourseModal').style.display = 'block';
            
            // Store the course code for updating
            document.getElementById('enhancedAddCourseModal').setAttribute('data-editing-course', courseCode);
        }

        function deleteLocalCourse(courseCode) {
            if (confirm(`Are you sure you want to delete the course "${courseCode}"?`)) {
                const courseElement = document.querySelector(`[data-course-code="${courseCode}"]`);
                if (courseElement) {
                    // Find the program element
                    const programElement = courseElement.closest('.program-card');
                    if (programElement) {
                        const programName = programElement.getAttribute('data-program-name');
                        const studyLevel = programElement.querySelector('.btn-add-course').getAttribute('onclick').match(/'([^']+)'/)[1];
                        
                        // Get the course ID from the course element or find it in localStorage
                        const key = `icbt_courses_${studyLevel.replace(/\s+/g, '_')}_${programName.replace(/\s+/g, '_')}`;
                        let courses = JSON.parse(localStorage.getItem(key) || '[]');
                        const courseToDelete = courses.find(c => c.code === courseCode);
                        
                        if (courseToDelete) {
                            // First, try to delete from database using course ID if available
                            const dbFormData = new FormData();
                            dbFormData.append('action', 'delete_course');
                            dbFormData.append('campus', 'ICBT');
                            
                            // Use course ID if available, otherwise use course code
                            if (courseToDelete.id) {
                                dbFormData.append('course_id', courseToDelete.id);
                            } else {
                                dbFormData.append('course_code', courseCode);
                            }

                            fetch('admin-course-actions.php', {
                                method: 'POST',
                                body: dbFormData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove from localStorage
                                    courses = courses.filter(c => c.code !== courseCode);
                                    localStorage.setItem(key, JSON.stringify(courses));
                                    
                                    // Remove the course element
                                    courseElement.remove();
                                    
                                    // Check if this was the last course and show no-courses message
                                    const coursesList = courseElement.parentElement;
                                    if (coursesList && coursesList.children.length === 1) { // Only the header remains
                                        coursesList.innerHTML = `
                                            <h6><i class="fas fa-book"></i> Courses</h6>
                                            <div class="no-courses">
                                                <i class="fas fa-book-open"></i>
                                                <p>No courses added yet</p>
                                            </div>
                                        `;
                                    }
                                    
                                    alert('Course deleted successfully from both database and dashboard!');
                                } else {
                                    alert('Error deleting from database: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while deleting from database. Course will be deleted locally only.');
                                
                                // Fallback: delete from localStorage only
                                courses = courses.filter(c => c.code !== courseCode);
                                localStorage.setItem(key, JSON.stringify(courses));
                                
                                courseElement.remove();
                                
                                // Check if this was the last course and show no-courses message
                                const coursesList = courseElement.parentElement;
                                if (coursesList && coursesList.children.length === 1) { // Only the header remains
                                    coursesList.innerHTML = `
                                        <h6><i class="fas fa-book"></i> Courses</h6>
                                        <div class="no-courses">
                                            <i class="fas fa-book-open"></i>
                                            <p>No courses added yet</p>
                                        </div>
                                    `;
                                }
                            });
                        } else {
                            alert('Course not found in localStorage!');
                        }
                    } else {
                        alert('Program not found!');
                    }
                } else {
                    alert('Course not found!');
                }
            }
        }

        // Function to clear all courses from all programs
        function clearAllCourses() {
            if (confirm('Are you sure you want to delete ALL courses from ALL programs? This action cannot be undone.')) {
                // First, clear from localStorage and update UI
                const studyLevels = ['Postgraduate', 'Undergraduate', 'After A/L & O/L'];
                
                studyLevels.forEach(studyLevel => {
                    const containerId = getContainerId(studyLevel);
                    const container = document.getElementById(containerId);
                    
                    if (container) {
                        // Find all program elements
                        const programElements = container.querySelectorAll('.program-item');
                        
                        programElements.forEach(programElement => {
                            const programName = programElement.getAttribute('data-program-name');
                            
                            // Clear courses from localStorage
                            const key = `icbt_courses_${studyLevel.replace(/\s+/g, '_')}_${programName.replace(/\s+/g, '_')}`;
                            localStorage.removeItem(key);
                            
                            // Update the courses list in the DOM to show "No courses added yet"
                            const coursesList = programElement.querySelector('.courses-list');
                            if (coursesList) {
                                coursesList.innerHTML = `
                                    <h6><i class="fas fa-book"></i> Courses</h6>
                                    <div class="no-courses">
                                        <i class="fas fa-book-open"></i>
                                        <p>No courses added yet</p>
                                    </div>
                                `;
                            }
                        });
                    }
                });
                
                // Then, clear from database
                const formData = new FormData();
                formData.append('action', 'clear_all_courses');
                formData.append('campus', 'ICBT');

                fetch('admin-course-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('All courses have been deleted successfully from both localStorage and database!');
                    } else {
                        alert('Error clearing database: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while clearing courses from database, but localStorage has been cleared.');
                });
            }
        }

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new ICBTAdminDashboard();
            
            // Initialize empty state for programs containers
            initializeProgramsContainers();
        });

        function initializeProgramsContainers() {
            const containers = [
                'postgraduate-programs',
                'undergraduate-programs',
                'after-al-ol-programs'
            ];
            
            containers.forEach(containerId => {
                const container = document.getElementById(containerId);
                if (container && container.children.length === 0) {
                    container.innerHTML = `
                        <div class="no-programs">
                            <i class="fas fa-graduation-cap"></i>
                            <p>No programs added yet</p>
                        </div>
                    `;
                }
            });

            // Load existing programs and courses from localStorage
            loadStoredData();
        }

        function storeProgram(studyLevel, program) {
            const key = `icbt_programs_${studyLevel.replace(/\s+/g, '_')}`;
            let programs = JSON.parse(localStorage.getItem(key) || '[]');
            programs.push(program);
            localStorage.setItem(key, JSON.stringify(programs));
        }

        function storeCourse(studyLevel, programName, course) {
            const key = `icbt_courses_${studyLevel.replace(/\s+/g, '_')}_${programName.replace(/\s+/g, '_')}`;
            let courses = JSON.parse(localStorage.getItem(key) || '[]');
            courses.push(course);
            localStorage.setItem(key, JSON.stringify(courses));
            
            // Debug logging
            console.log('Storing course:', course);
            console.log('Storage key:', key);
            console.log('All courses in storage:', courses);
        }

        function loadStoredData() {
            const studyLevels = ['Postgraduate', 'Undergraduate', 'After A/L & O/L'];
            
            studyLevels.forEach(studyLevel => {
                const key = `icbt_programs_${studyLevel.replace(/\s+/g, '_')}`;
                const programs = JSON.parse(localStorage.getItem(key) || '[]');
                
                if (programs.length > 0) {
                    const containerId = getContainerId(studyLevel);
                    const container = document.getElementById(containerId);
                    
                    if (container) {
                        // Clear no-programs message
                        const noProgramsMsg = container.querySelector('.no-programs');
                        if (noProgramsMsg) {
                            noProgramsMsg.remove();
                        }
                        
                        // Add each program
                        programs.forEach(program => {
                            addProgramToContainer(studyLevel, program);
                        });
                    }
                }
            });
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

        // Course-related functions have been removed

        // Edit Course Modal Functions have been removed

        // Delete Course Function has been removed

        // Add Program to Database Function has been removed

        // Faculty Management Functions
        // openAddFacultyModal function removed

        // closeAddFacultyModal function removed

        // submitNewFaculty function removed

        function editFaculty(facultyName, categoryName) {
            document.getElementById('editFacultyOldName').value = facultyName;
            document.getElementById('editFacultyCategory').value = categoryName;
            document.getElementById('editFacultyName').value = facultyName;
            document.getElementById('editFacultyDescription').value = ''; // You can fetch this from database if needed
            document.getElementById('editFacultyModal').style.display = 'block';
        }

        function closeEditFacultyModal() {
            document.getElementById('editFacultyModal').style.display = 'none';
            document.getElementById('editFacultyForm').reset();
        }

        function submitEditFaculty(event) {
            event.preventDefault();
            const form = document.getElementById('editFacultyForm');
            const formData = new FormData(form);
            formData.append('action', 'edit_faculty');
            formData.append('campus', 'ICBT');

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Faculty updated successfully!');
                    location.reload();
                    closeEditFacultyModal();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the faculty.');
            });
        }

        function deleteFaculty(facultyName, categoryName) {
            if (confirm(`Are you sure you want to delete the faculty "${facultyName}"? This will also remove all programs and courses under this faculty.`)) {
                const formData = new FormData();
                formData.append('faculty_name', facultyName);
                formData.append('category_name', categoryName);
                formData.append('action', 'delete_faculty');
                formData.append('campus', 'ICBT');

                fetch('admin-course-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Faculty deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the faculty.');
                });
            }
        }

        // Category Management Functions
        function editCategory(categoryName) {
            alert('Edit category functionality for: ' + categoryName + '\n\nThis feature will be implemented in the next update.');
        }

        function deleteCategory(categoryName) {
            if (confirm(`Are you sure you want to delete the category "${categoryName}"? This will remove all faculties, programs, and courses under this category.`)) {
                alert('Delete category functionality for: ' + categoryName + '\n\nThis feature will be implemented in the next update.');
            }
        }

        // Program Management Functions
        function editProgram(programName, facultyName, categoryName) {
            alert('Edit program functionality for: ' + programName + '\n\nThis feature will be implemented in the next update.');
        }

        // Close faculty modals when clicking outside
        window.onclick = function(event) {
            const addProgramModal = document.getElementById('addProgramModal');
            const addCourseModal = document.getElementById('addCourseModal');
            const editCourseModal = document.getElementById('editCourseModal');
            const addFacultyModal = document.getElementById('addFacultyModal');
            const editFacultyModal = document.getElementById('editFacultyModal');
            
            if (event.target === addProgramModal) {
                closeAddProgramModal();
            }
            if (event.target === addCourseModal) {
                closeAddCourseModal();
            }
            if (event.target === editCourseModal) {
                closeEditCourseModal();
            }
            if (event.target === addFacultyModal) {
                closeAddFacultyModal();
            }
            if (event.target === editFacultyModal) {
                closeEditFacultyModal();
            }
        }

        // Helper functions for dynamic program and course management
        function getCurrentStudyLevel() {
            return window.currentStudyLevel || 'Postgraduate';
        }

        function getStudyLevelFromProgram(programName) {
            // Try to find the program in the DOM to determine its study level
            const programElement = document.querySelector(`[data-program-name="${programName}"]`);
            if (programElement) {
                const addCourseBtn = programElement.querySelector('.btn-add-course');
                if (addCourseBtn) {
                    const onclick = addCourseBtn.getAttribute('onclick');
                    const match = onclick.match(/'([^']+)'/);
                    if (match) {
                        return match[1];
                    }
                }
            }
            return 'Postgraduate'; // Default fallback
        }

        function addProgramToTable(programName, studyLevel) {
            // Store the program name for reference when adding courses
            window.lastAddedProgramName = programName;
            
            // Find the correct study level row in the table
            const studyLevelRows = document.querySelectorAll('.study-level-cell');
            let targetRow = null;
            let targetStudyLevelCell = null;
            
            for (let row of studyLevelRows) {
                const rowText = row.textContent;
                if (rowText.includes(studyLevel)) {
                    targetRow = row.closest('tr');
                    targetStudyLevelCell = row;
                    break;
                }
            }
            
            if (!targetRow) {
                // console.error('Study level row not found:', studyLevel);
                return;
            }
            
            // Find the last program row for this study level
            let lastProgramRow = targetRow;
            let currentRow = targetRow.nextElementSibling;
            
            // Look for the next row that has a study-level-cell (which means we've reached the next study level)
            while (currentRow && !currentRow.querySelector('.study-level-cell')) {
                lastProgramRow = currentRow;
                currentRow = currentRow.nextElementSibling;
            }
            
            // Create a new row for the program
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="programs-cell">
                    <div class="programs-info">
                        <h5>${programName}</h5>
                        <div class="programs-actions">
                            <button class="btn-edit-faculty" onclick="editFaculty('${programName}', '${studyLevel}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-faculty" onclick="deleteFaculty('${programName}', '${studyLevel}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </td>
                <td class="courses-cell">
                    <div class="existing-courses">
                        <div class="no-courses">
                            <p>No courses yet</p>
                        </div>
                    </div>
                    <button class="btn-add-course" onclick="openAddCourseModal(0, '${programName} - ${studyLevel}')">
                        <i class="fas fa-plus"></i> Add Course
                    </button>
                </td>
            `;
            
            // Insert the new row after the last program row for this study level
            lastProgramRow.parentNode.insertBefore(newRow, lastProgramRow.nextSibling);
            
            // Update the rowspan of the study level cell to accommodate the new program
            const currentRowspan = parseInt(targetStudyLevelCell.getAttribute('rowspan') || '1');
            targetStudyLevelCell.setAttribute('rowspan', currentRowspan + 1);
        }

        function openAddCourseModalForNewProgram(programName, studyLevel) {
            // Open the Add Course modal for the newly created program
            document.getElementById('courseProgramId').value = 'new';
            document.getElementById('addCourseModal').style.display = 'block';
            
            // Update the modal title to show it's for the new program
            const modalTitle = document.querySelector('#addCourseModal .modal-header h3');
            modalTitle.textContent = `Add Course for ${programName}`;
        }

        function addCourseToNewProgram(courseName, courseDescription) {
            // Find the newly added program row and add the course to it
            const programRows = document.querySelectorAll('.programs-cell');
            let targetProgramRow = null;
            
            // Look for the program that was just created (it should be the last one)
            for (let i = programRows.length - 1; i >= 0; i--) {
                const programNameElement = programRows[i].querySelector('h5');
                if (programNameElement && programNameElement.textContent === window.lastAddedProgramName) {
                    targetProgramRow = programRows[i].closest('tr');
                    break;
                }
            }
            
            if (targetProgramRow) {
                // Find the courses cell in the same row
                const coursesCell = targetProgramRow.querySelector('.courses-cell');
                if (coursesCell) {
                    // Replace "No courses yet" with the new course
                    const existingCourses = coursesCell.querySelector('.existing-courses');
                    if (existingCourses) {
                        // Clear the existing content and add the new course
                        existingCourses.innerHTML = `
                            <div class="existing-course-item">
                                <div class="course-info">
                                    <span class="course-name">${courseName}</span>
                                    <div class="course-actions">
                                        <button class="btn-edit-course" onclick="editCourse('new')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-delete-course" onclick="deleteCourse('new')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }
            }
        }

        // Course-related functions have been removed

        // Add Program Modal Functions
        function openAddProgramModal(categoryName = '') {
            // Store the current study level for reference
            window.currentStudyLevel = categoryName;
            document.getElementById('addProgramModal').style.display = 'block';
        }

        function closeAddProgramModal() {
            document.getElementById('addProgramModal').style.display = 'none';
            // Reset form
            document.getElementById('addProgramForm').reset();
        }

        function submitNewProgram(event) {
            event.preventDefault();
            const form = document.getElementById('addProgramForm');
            const formData = new FormData(form);
            formData.append('action', 'add_program');
            formData.append('campus', 'ICBT');
            
            // Add default values for required fields that were removed from the form
            formData.append('programCode', 'PGM' + Date.now()); // Generate unique program code
            formData.append('duration', '3 Years'); // Default duration
            formData.append('level', getCurrentStudyLevel()); // Use the correct study level
            formData.append('description', ''); // Empty description
            formData.append('maxCapacity', '100'); // Default capacity

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Program added successfully!');
                    
                    // Get the program name and study level
                    const programName = document.getElementById('programName').value;
                    const studyLevel = getCurrentStudyLevel();
                    
                    // Store the program name globally for course addition
                    window.lastAddedProgramName = programName;
                    
                    // Add the new program to the container dynamically
                    const program = {
                        name: programName,
                        code: 'PGM' + Date.now(),
                        duration: '3 Years',
                        description: '',
                        studyLevel: studyLevel
                    };
                    
                    // Store program in localStorage for persistence
                    storeProgram(studyLevel, program);
                    
                    // Add program to the appropriate container
                    addProgramToContainer(studyLevel, program);
                    
                    // Close the program modal
                    closeAddProgramModal();
                    
                    // Automatically open the Add Course modal for the new program
                    setTimeout(() => {
                        openEnhancedAddCourseModal(studyLevel, programName);
                    }, 500);
                    
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the program.');
            });
        }

        // Add Course Modal Functions
        function openAddCourseModal(programId, programName) {
            if (programId === 0) {
                // If no program is specified, show a program selection modal first
                alert('Please select a program first to add a course.');
                return;
            }
            
            document.getElementById('courseProgramId').value = programId;
            document.getElementById('addCourseModal').style.display = 'block';
        }

        function closeAddCourseModal() {
            document.getElementById('addCourseModal').style.display = 'none';
            // Reset form
            document.getElementById('addCourseForm').reset();
        }

        function submitNewCourse(event) {
            event.preventDefault();
            const form = document.getElementById('addCourseForm');
            const formData = new FormData(form);
            formData.append('action', 'add_course');
            formData.append('campus', 'ICBT');
            
            // Get the program ID and check if it's for a new program
            const programId = document.getElementById('courseProgramId').value;
            
            if (programId === 'new') {
                // For new programs, we need to add the course to the program first
                // Get the program name from the modal title
                const modalTitle = document.querySelector('#addCourseModal .modal-header h3');
                const programName = modalTitle.textContent.replace('Add Course for ', '');
                
                // Get the course details from the form
                const courseName = document.getElementById('courseTitle').value;
                const courseDescription = document.getElementById('courseDescription').value;
                const courseRequirement = document.getElementById('courseRequirement').value;
                
                // Add the course directly to the program without database submission
                const course = {
                    name: courseName,
                    code: 'CS' + Date.now(),
                    credits: '3',
                    semester: '1st Semester',
                    instructor: 'TBD',
                    description: courseDescription,
                    requirement: courseRequirement
                };
                
                // Find the study level from the program name
                const studyLevel = getStudyLevelFromProgram(programName);
                
                // Store course in localStorage for persistence
                storeCourse(studyLevel, programName, course);
                
                // Add course to the appropriate program
                addCourseToProgram(studyLevel, programName, course);
                closeAddCourseModal();
                
                // Reset modal title
                modalTitle.textContent = 'Add New Course';
                
                // Show success message
                alert('Course added successfully!');
                return;
            }
            
            // For existing programs, proceed with normal course submission
            // Add default values for required fields that were removed from the form
            formData.append('courseCode', 'CS' + Date.now()); // Generate unique course code
            formData.append('courseCredits', '3'); // Default credits
            formData.append('courseSemester', '1st Semester'); // Default semester
            formData.append('courseInstructor', 'TBD'); // Default instructor
            formData.append('courseStatus', 'Active'); // Default status

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Course added successfully!');
                    closeAddCourseModal();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the course.');
                closeAddCourseModal();
            });
        }

        // Edit Course Modal Functions
        function editCourse(courseId) {
            const editCourseModal = document.getElementById('editCourseModal');
            const editCourseForm = document.getElementById('editCourseForm');
            const editCourseIdInput = document.getElementById('editCourseId');

            // Fetch course details
            fetch(`admin-course-actions.php?action=get_course_details&course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        editCourseIdInput.value = courseId;
                        document.getElementById('editCourseCode').value = data.course_code;
                        document.getElementById('editCourseTitle').value = data.course_title;
                        document.getElementById('editCourseCredits').value = data.credits;
                        document.getElementById('editCourseSemester').value = data.semester;
                        document.getElementById('editCourseInstructor').value = data.instructor;
                        document.getElementById('editCourseDescription').value = data.description;
                        document.getElementById('editCourseStatus').value = data.status;
                        editCourseModal.style.display = 'block';
                    } else {
                        alert('Error fetching course details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching course details.');
                });
        }

        function closeEditCourseModal() {
            document.getElementById('editCourseModal').style.display = 'none';
            // Reset form
            document.getElementById('editCourseForm').reset();
        }

        function submitEditCourse(event) {
            event.preventDefault();
            const form = document.getElementById('editCourseForm');
            const formData = new FormData(form);
            formData.append('action', 'edit_course');
            formData.append('campus', 'ICBT');

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Course updated successfully!');
                    location.reload(); // Refresh the page to show updated course
                    closeEditCourseModal();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the course.');
            });
        }

        // Delete Course Function
        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course?')) {
                const formData = new FormData();
                formData.append('course_id', courseId);
                formData.append('action', 'delete_course');
                formData.append('campus', 'ICBT');

                fetch('admin-course-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Course deleted successfully!');
                        location.reload(); // Refresh the page to show updated list
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

        // Faculty Management Functions
        function openAddFacultyModal() {
            document.getElementById('addFacultyModal').style.display = 'block';
        }

        function closeAddFacultyModal() {
            document.getElementById('addFacultyModal').style.display = 'none';
            document.getElementById('addFacultyForm').reset();
        }

        function submitNewFaculty(event) {
            event.preventDefault();
            const form = document.getElementById('addFacultyForm');
            const formData = new FormData(form);
            formData.append('action', 'add_faculty');
            formData.append('campus', 'ICBT');

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Faculty added successfully!');
                    location.reload();
                    closeAddFacultyModal();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the faculty.');
            });
        }

        function editFaculty(facultyName, categoryName) {
            document.getElementById('editFacultyOldName').value = facultyName;
            document.getElementById('editFacultyCategory').value = categoryName;
            document.getElementById('editFacultyName').value = facultyName;
            document.getElementById('editFacultyDescription').value = ''; // You can fetch this from database if needed
            document.getElementById('editFacultyModal').style.display = 'block';
        }

        function closeEditFacultyModal() {
            document.getElementById('editFacultyModal').style.display = 'none';
            document.getElementById('editFacultyForm').reset();
        }

        function submitEditFaculty(event) {
            event.preventDefault();
            const form = document.getElementById('editFacultyForm');
            const formData = new FormData(form);
            formData.append('action', 'edit_faculty');
            formData.append('campus', 'ICBT');

            fetch('admin-course-actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Faculty updated successfully!');
                    location.reload();
                    closeEditFacultyModal();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the faculty.');
            });
        }

        function deleteFaculty(facultyName, categoryName) {
            if (confirm(`Are you sure you want to delete the faculty "${facultyName}"? This will also remove all programs and courses under this faculty.`)) {
                const formData = new FormData();
                formData.append('faculty_name', facultyName);
                formData.append('campus', 'ICBT');
                formData.append('category_name', categoryName);
                formData.append('action', 'delete_faculty');

                fetch('admin-course-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Faculty deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the faculty.');
                });
            }
        }

        // Category Management Functions
        function editCategory(categoryName) {
            alert('Edit category functionality for: ' + categoryName + '\n\nThis feature will be implemented in the next update.');
        }

        function deleteCategory(categoryName) {
            if (confirm(`Are you sure you want to delete the category "${categoryName}"? This will remove all faculties, programs, and courses under this category.`)) {
                alert('Delete category functionality for: ' + categoryName + '\n\nThis feature will be implemented in the next update.');
            }
        }

        // Program Management Functions
        function editProgram(programName, facultyName, categoryName) {
            alert('Edit program functionality for: ' + programName + '\n\nThis feature will be implemented in the next update.');
        }

        // Close faculty modals when clicking outside

        // Review Moderation Functions
        function loadReviews() {
            fetch('get_reviews.php?campus=ICBT Campus')
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
            fetch('get_inquiries.php?campus=ICBT')
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
                        <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                        </div>
                        <h3>No inquiries yet</h3>
                        <p>When students send inquiries, they will appear here for you to respond to.</p>
                    </div>
                `;
                return;
            }

            const inquiriesHTML = inquiries.map(inquiry => `
                <div class="inquiry-card" data-status="${inquiry.response_status}">
                    <div class="inquiry-card-header">
                        <div class="inquiry-main-info">
                            <div class="inquiry-subject">
                            <h3>${escapeHtml(inquiry.subject)}</h3>
                                <span class="inquiry-id">#${inquiry.id}</span>
                            </div>
                            <div class="inquiry-status-badge status-${inquiry.response_status}">
                                <i class="fas fa-${getStatusIcon(inquiry.response_status)}"></i>
                            ${inquiry.response_status.charAt(0).toUpperCase() + inquiry.response_status.slice(1)}
                            </div>
                        </div>
                        <div class="inquiry-meta-info">
                            <div class="student-info">
                                <div class="student-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="student-details">
                                    <span class="student-name">${escapeHtml(inquiry.first_name + ' ' + inquiry.last_name)}</span>
                                    <span class="student-email">${escapeHtml(inquiry.email)}</span>
                                </div>
                            </div>
                            <div class="inquiry-timeline">
                                <div class="timeline-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Submitted: ${formatDate(inquiry.created_at)}</span>
                                </div>
                                ${inquiry.response_date ? `
                                    <div class="timeline-item">
                                        <i class="fas fa-reply"></i>
                                        <span>Responded: ${formatDate(inquiry.response_date)}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    
                    <div class="inquiry-card-body">
                        <div class="message-section">
                            <div class="message-header">
                                <i class="fas fa-comment"></i>
                                <span>Student Message</span>
                            </div>
                            <div class="message-content">
                            ${escapeHtml(inquiry.message).replace(/\n/g, '<br>')}
                            </div>
                        </div>
                        
                        ${inquiry.response ? `
                            <div class="response-section">
                                <div class="response-header">
                                    <i class="fas fa-reply"></i>
                                    <span>Admin Response</span>
                                </div>
                                <div class="response-content">
                                ${escapeHtml(inquiry.response).replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="inquiry-card-footer">
                        <div class="action-buttons">
                        ${inquiry.response_status === 'pending' ? `
                                <button class="btn btn-primary btn-action" onclick="showResponseForm(${inquiry.id})">
                                <i class="fas fa-reply"></i>
                                    <span>Respond</span>
                            </button>
                        ` : inquiry.response_status === 'answered' ? `
                                <button class="btn btn-success btn-action" onclick="closeInquiry(${inquiry.id})">
                                <i class="fas fa-check"></i>
                                    <span>Close</span>
                            </button>
                        ` : ''}
                        
                            <button class="btn btn-info btn-action" onclick="viewInquiryDetails(${inquiry.id})">
                            <i class="fas fa-eye"></i>
                                <span>Details</span>
                            </button>
                            
                            <button class="btn btn-danger btn-action" onclick="deleteInquiry(${inquiry.id})">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                        </button>
                        </div>
                    </div>
                    
                    <div class="response-form" id="response-form-${inquiry.id}">
                        <div class="response-form-header">
                            <h4><i class="fas fa-reply"></i> Respond to Inquiry</h4>
                            <button class="close-form" onclick="hideResponseForm(${inquiry.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form onsubmit="submitResponse(event, ${inquiry.id})">
                            <div class="form-group">
                                <label for="response-${inquiry.id}">Your Response *</label>
                                <textarea id="response-${inquiry.id}" class="form-control" required 
                                          placeholder="Type your professional response to the student..."></textarea>
                            </div>
                            
                            <div class="form-actions">
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

        function getStatusIcon(status) {
            switch(status) {
                case 'pending': return 'clock';
                case 'answered': return 'check-circle';
                case 'closed': return 'times-circle';
                default: return 'question-circle';
            }
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
            // Fetch inquiry details and show in a modal
            fetch(`get_inquiry_details.php?id=${inquiryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showInquiryDetailsModal(data.inquiry);
                    } else {
                        alert('Error loading inquiry details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading inquiry details.');
                });
        }

        function showInquiryDetailsModal(inquiry) {
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal-content inquiry-details-modal">
                    <div class="modal-header">
                        <h3>Inquiry Details</h3>
                        <button class="modal-close" onclick="closeInquiryDetailsModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inquiry-details-grid">
                            <div class="detail-section">
                                <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                                <div class="detail-item">
                                    <span class="detail-label">Subject:</span>
                                    <span class="detail-value">${escapeHtml(inquiry.subject)}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status:</span>
                                    <span class="detail-value status-${inquiry.response_status}">
                                        ${inquiry.response_status.charAt(0).toUpperCase() + inquiry.response_status.slice(1)}
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Submitted:</span>
                                    <span class="detail-value">${formatDateTime(inquiry.created_at)}</span>
                                </div>
                                ${inquiry.response_date ? `
                                    <div class="detail-item">
                                        <span class="detail-label">Responded:</span>
                                        <span class="detail-value">${formatDateTime(inquiry.response_date)}</span>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <div class="detail-section">
                                <h4><i class="fas fa-user"></i> Student Information</h4>
                                <div class="detail-item">
                                    <span class="detail-label">Name:</span>
                                    <span class="detail-value">${escapeHtml(inquiry.first_name + ' ' + inquiry.last_name)}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email:</span>
                                    <span class="detail-value">${escapeHtml(inquiry.email)}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Phone:</span>
                                    <span class="detail-value">${escapeHtml(inquiry.phone)}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Campus:</span>
                                    <span class="detail-value">${escapeHtml(inquiry.university_id)}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section full-width">
                            <h4><i class="fas fa-comment"></i> Original Message</h4>
                            <div class="message-content">
                                ${escapeHtml(inquiry.message).replace(/\n/g, '<br>')}
                            </div>
                        </div>
                        
                        ${inquiry.response ? `
                            <div class="detail-section full-width">
                                <h4><i class="fas fa-reply"></i> Admin Response</h4>
                                <div class="response-content">
                                    ${escapeHtml(inquiry.response).replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline" onclick="closeInquiryDetailsModal()">
                            <i class="fas fa-times"></i>
                            Close
                        </button>
                        ${inquiry.response_status === 'pending' ? `
                            <button class="btn btn-primary" onclick="showResponseFormFromModal(${inquiry.id})">
                                <i class="fas fa-reply"></i>
                                Respond
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeInquiryDetailsModal() {
            const modal = document.querySelector('.modal-overlay');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => modal.remove(), 300);
            }
        }

        function showResponseFormFromModal(inquiryId) {
            closeInquiryDetailsModal();
            setTimeout(() => showResponseForm(inquiryId), 350);
        }

        function deleteInquiry(inquiryId) {
            if (!confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')) {
                return;
            }

            const formData = new FormData();
            formData.append('inquiry_id', inquiryId);
            formData.append('action', 'delete');

            fetch('process_inquiry_response.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Inquiry deleted successfully!');
                    loadInquiries(); // Reload inquiries
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the inquiry.');
            });
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
            const campus = encodeURIComponent('ICBT Campus');
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

        // Load data when sections are shown
        document.addEventListener('DOMContentLoaded', () => {
            // Load announcements when page loads
            loadAnnouncements();
            
            // Load reviews when reviews section is shown (if it exists)
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

            // Load analytics when analytics section is shown
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
        });

        // Analytics Functions
        let enrollmentChart = null;
        let programChart = null;
        let geographicChart = null;
        let accreditationChart = null;

        // Initialize Analytics Charts
        function initializeAnalyticsCharts() {
            console.log('Initializing ICBT analytics charts...');
            
            // Check if canvas elements exist
            const enrollmentCanvas = document.getElementById('enrollmentCanvas');
            const programCanvas = document.getElementById('programCanvas');
            const geographicCanvas = document.getElementById('geographicCanvas');
            const accreditationCanvas = document.getElementById('accreditationCanvas');
            
            if (!enrollmentCanvas || !programCanvas || !geographicCanvas || !accreditationCanvas) {
                console.error('Canvas elements not found. Available elements:', {
                    enrollmentCanvas: !!enrollmentCanvas,
                    programCanvas: !!programCanvas,
                    geographicCanvas: !!geographicCanvas,
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
                            data: [75000, 78000, 82000, 85000, 88000, 90000],
                            borderColor: '#0284c7',
                            backgroundColor: 'rgba(2, 132, 199, 0.1)',
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
                                text: 'ICBT Student Enrollment Growth'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100000
                            }
                        }
                    }
                });

                // Initialize Program Popularity Chart
                const programCtx = programCanvas.getContext('2d');
                programChart = new Chart(programCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['IT', 'Business Management', 'Quantity Surveying', 'Engineering', 'Psychology', 'Other'],
                        datasets: [{
                            data: [31500, 27000, 18000, 9000, 3600, 900],
                            backgroundColor: ['#0284c7', '#059669', '#f59e0b', '#ef4444', '#8b5cf6', '#64748b'],
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

                // Initialize Geographic Distribution Chart
                const geographicCtx = geographicCanvas.getContext('2d');
                geographicChart = new Chart(geographicCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Colombo-04', 'Gampaha', 'Nugegoda', 'Kurunegala', 'Kandy', 'Matara', 'Galle', 'Jaffna', 'Batticaloa', 'Anuradhapura'],
                        datasets: [{
                            label: 'Student Count',
                            data: [25000, 12000, 10000, 8000, 7000, 6000, 5500, 5000, 4500, 4000],
                            backgroundColor: ['#0284c7', '#059669', '#f59e0b', '#ef4444', '#8b5cf6', '#64748b', '#06b6d4', '#84cc16', '#f97316', '#ec4899'],
                            borderColor: ['#0284c7', '#059669', '#f59e0b', '#ef4444', '#8b5cf6', '#64748b', '#06b6d4', '#84cc16', '#f97316', '#ec4899'],
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
                            data: [90, 8, 2],
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

                console.log('ICBT analytics charts initialized successfully');
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
                    data: [75000, 78000, 82000, 85000, 88000, 90000]
                },
                monthly: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    data: [7500, 7800, 8200, 8500, 8800, 9000, 9200, 9500, 9800, 10000, 10200, 10500]
                },
                semester: {
                    labels: ['Sem 1 2023', 'Sem 2 2023', 'Sem 1 2024', 'Sem 2 2024'],
                    data: [88000, 90000, 92000, 95000]
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
                    labels: ['IT', 'Business Management', 'Quantity Surveying', 'Engineering', 'Psychology', 'Other'],
                    data: [31500, 27000, 18000, 9000, 3600, 900]
                },
                demand: {
                    labels: ['IT', 'Business Management', 'Quantity Surveying', 'Engineering', 'Psychology', 'Other'],
                    data: [45000, 38000, 25000, 12000, 5000, 1200]
                },
                completion: {
                    labels: ['IT', 'Business Management', 'Quantity Surveying', 'Engineering', 'Psychology', 'Other'],
                    data: [95, 94, 92, 90, 88, 85]
                }
            };
            
            programChart.data.labels = data[type].labels;
            programChart.data.datasets[0].data = data[type].data;
            programChart.update();
        }

        function updateGeographicChart(metric) {
            if (!geographicChart) return;
            
            // Update active button
            document.querySelectorAll('#geographicChart .chart-control-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const data = {
                students: {
                    label: 'Student Count',
                    data: [25000, 12000, 10000, 8000, 7000, 6000, 5500, 5000, 4500, 4000]
                },
                programs: {
                    label: 'Program Count',
                    data: [15, 12, 10, 8, 7, 6, 5, 4, 3, 2]
                },
                capacity: {
                    label: 'Capacity Utilization (%)',
                    data: [95, 88, 85, 82, 80, 78, 75, 72, 70, 68]
                }
            };
            
            geographicChart.data.datasets[0].label = data[metric].label;
            geographicChart.data.datasets[0].data = data[metric].data;
            geographicChart.update();
        }

        function updateAccreditationChart(view) {
            if (!accreditationChart) return;
            
            // Update active button
            document.querySelectorAll('#accreditationChart .chart-control-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const data = {
                status: {
                    labels: ['Fully Accredited', 'Under Review', 'Pending Renewal'],
                    data: [90, 8, 2]
                },
                renewal: {
                    labels: ['Renewed 2024', 'Due 2025', 'Due 2026'],
                    data: [70, 25, 5]
                },
                compliance: {
                    labels: ['Fully Compliant', 'Minor Issues', 'Major Issues'],
                    data: [95, 4, 1]
                }
            };
            
            accreditationChart.data.labels = data[view].labels;
            accreditationChart.data.datasets[0].data = data[view].data;
            accreditationChart.update();
        }

        // Global function to manually initialize charts (for debugging)
        window.initializeICBTCharts = function() {
            console.log('Manually initializing ICBT charts...');
            initializeAnalyticsCharts();
        };

        // Function to force chart initialization (for troubleshooting)
        window.forceICBTChartInit = function() {
            console.log('Force initializing ICBT charts...');
            // Destroy existing charts if they exist
            if (enrollmentChart) enrollmentChart.destroy();
            if (programChart) programChart.destroy();
            if (geographicChart) geographicChart.destroy();
            if (accreditationChart) accreditationChart.destroy();
            
            // Reset chart variables
            enrollmentChart = null;
            programChart = null;
            geographicChart = null;
            accreditationChart = null;
            
            // Reinitialize
            setTimeout(initializeAnalyticsCharts, 100);
        };

        function loadCampusDistribution() {
            fetch(`get_icbt_analytics.php?type=campus`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderCampusChart(data.data);
                    } else {
                        console.error('Error loading campus data:', data.message);
                        renderCampusChart(getSampleCampusData());
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    renderCampusChart(getSampleCampusData());
                });
        }

        function loadFinancialPerformance() {
            fetch(`get_icbt_analytics.php?type=financial`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderFinancialChart(data.data);
                    } else {
                        console.error('Error loading financial data:', data.message);
                        renderFinancialChart(getSampleFinancialData());
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    renderFinancialChart(getSampleFinancialData());
                });
        }

        function exportAnalyticsReport() {
            alert('Exporting comprehensive analytics report...\n\nThis feature will generate a detailed PDF report with all analytics data.');
        }

        function refreshAnalytics() {
            loadAnalytics();
            alert('Analytics data refreshed successfully!');
        }

        function renderEnrollmentChart(data) {
            const ctx = document.getElementById('enrollmentTrendsCanvas').getContext('2d');
            
            if (enrollmentChart) {
                enrollmentChart.destroy();
            }

            enrollmentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Enrollments',
                        data: data.values,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
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
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        function renderPopularProgramsChart(data) {
            const ctx = document.getElementById('popularProgramsCanvas').getContext('2d');
            
            if (popularProgramsChart) {
                popularProgramsChart.destroy();
            }

            popularProgramsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Students',
                        data: data.values,
                        backgroundColor: [
                            '#2563eb',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                            '#06b6d4'
                        ],
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        function renderDemographicsChart(data) {
            const ctx = document.getElementById('demographicsCanvas').getContext('2d');
            
            if (demographicsChart) {
                demographicsChart.destroy();
            }

            demographicsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.values,
                        backgroundColor: [
                            '#2563eb',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function renderCompletionChart(data) {
            const ctx = document.getElementById('completionCanvas').getContext('2d');
            
            if (completionChart) {
                completionChart.destroy();
            }

            completionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Completion Rate (%)',
                        data: data.values,
                        backgroundColor: '#10b981',
                        borderColor: '#059669',
                        borderWidth: 2,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Sample data functions for fallback
        function getSampleEnrollmentData(period) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const data = {
                6: { labels: months.slice(-6), values: [45, 52, 48, 61, 58, 67] },
                12: { labels: months, values: [42, 45, 52, 48, 61, 58, 67, 71, 65, 73, 68, 75] },
                24: { labels: ['2023', '2024'], values: [650, 720] }
            };
            return data[period] || data[6];
        }

        function getSampleProgramsData(period) {
            const data = {
                current: {
                    labels: ['Business Management', 'Computer Science', 'Engineering', 'Arts & Design', 'Health Sciences'],
                    values: [120, 95, 87, 65, 78]
                },
                last: {
                    labels: ['Business Management', 'Computer Science', 'Engineering', 'Arts & Design', 'Health Sciences'],
                    values: [110, 88, 82, 58, 72]
                },
                year: {
                    labels: ['Business Management', 'Computer Science', 'Engineering', 'Arts & Design', 'Health Sciences'],
                    values: [450, 380, 320, 280, 310]
                }
            };
            return data[period] || data.current;
        }

        function getSampleDemographicsData() {
            return {
                labels: ['Male', 'Female', 'International', 'Local', 'Mature Students'],
                values: [45, 55, 15, 85, 12]
            };
        }

        function getSampleCompletionData() {
            return {
                labels: ['Business', 'Computing', 'Engineering', 'Arts', 'Health', 'Foundation'],
                values: [92, 88, 85, 90, 87, 95]
            };
        }

        function getSampleCampusData() {
            return {
                labels: ['Colombo', 'Gampaha', 'Nugegoda', 'Kurunegala', 'Kandy', 'Matara', 'Galle', 'Jaffna', 'Batticaloa', 'Anuradhapura'],
                values: [25, 18, 15, 12, 10, 8, 7, 5, 4, 3]
            };
        }

        function getSampleFinancialData() {
            return {
                labels: ['Tuition Fees', 'Infrastructure', 'Faculty Salaries', 'Administrative', 'Technology', 'Marketing'],
                values: [65, 12, 15, 5, 2, 1]
            };
        }

        function renderCampusChart(data) {
            const ctx = document.getElementById('campusCanvas').getContext('2d');
            
            if (campusChart) {
                campusChart.destroy();
            }

            campusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.values,
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                            '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
                        ],
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
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });
        }

        function renderFinancialChart(data) {
            const ctx = document.getElementById('financialCanvas').getContext('2d');
            
            if (financialChart) {
                financialChart.destroy();
            }

            financialChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Percentage (%)',
                        data: data.values,
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'
                        ],
                        borderColor: [
                            '#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed', '#0891b2'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>

    <style>
        /* Course Registration Management Styles */
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

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-approve, .btn-reject, .btn-view {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-approve {
            background: var(--success-color);
            color: white;
        }

        .btn-approve:hover {
            background: #28a745;
        }

        .btn-reject {
            background: var(--danger-color);
            color: white;
        }

        .btn-reject:hover {
            background: #dc3545;
        }

        .btn-view {
            background: var(--secondary-color);
            color: white;
        }

        .btn-view:hover {
            background: #6c757d;
        }

        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: var(--secondary-color);
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .error {
            color: var(--danger-color);
            text-align: center;
            padding: 20px;
        }

        .btn-waitlist {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-right: 0.5rem;
        }

        .btn-waitlist:hover {
            background: #d97706;
        }

        .status-badge.status-waitlisted {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.status-pending {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.status-approved {
            background: #d1fae5;
            color: #065f46;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.status-rejected {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Course Applications specific styles */
        #applications .data-table td {
            vertical-align: top;
            padding: 1rem 0.75rem;
        }

        #applications .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        #applications .btn-view,
        #applications .btn-approve,
        #applications .btn-waitlist,
        #applications .btn-reject {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1001; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 600px;
            border-radius: 10px;
            position: relative;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            color: var(--dark-color);
            transition: border-color 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 1.5rem;
        }

        .btn-submit, .btn-cancel {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-submit {
            background: var(--primary-color);
            color: white;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        .btn-cancel {
            background: var(--secondary-color);
            color: white;
        }

        .btn-cancel:hover {
            background: #64748b;
        }

        /* Programs and Courses Styles */
        .programs-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .program-section {
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            overflow: hidden;
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .program-header {
            background: var(--light-color);
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .program-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .program-title i {
            color: var(--primary-color);
        }

        .program-code {
            font-size: 0.875rem;
            color: var(--secondary-color);
            font-weight: 500;
        }

        .program-info {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .program-level,
        .program-duration {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark-color);
            border: 1px solid var(--border-color);
        }

        .program-status {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-suspended {
            background: #fef3c7;
            color: #92400e;
        }

        .courses-table-wrapper {
            padding: 1.5rem;
        }

        .courses-table {
            margin-bottom: 1rem;
        }

        .courses-table th {
            background: var(--light-color);
            font-weight: 600;
            color: var(--dark-color);
            padding: 1rem 0.75rem;
        }

        .courses-table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .no-courses {
            text-align: center;
            padding: 2rem;
        }

        .no-courses-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            color: var(--secondary-color);
        }

        .no-courses-message i {
            font-size: 2rem;
            opacity: 0.5;
        }

        .no-courses-message p {
            margin: 0;
            font-size: 1rem;
        }

        .add-course-section {
            text-align: center;
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            background: var(--light-color);
        }

        .btn-add-course {
            background: var(--info-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-add-course:hover {
            background: #0ea5e9;
        }

        /* Course status badges */
        .status-badge.status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.status-draft {
            background: #f3f4f6;
            color: #374151;
        }

        /* New Hierarchical Structure Styles */
        .categories-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .category-section {
            border: 2px solid var(--primary-color);
            border-radius: 1rem;
            overflow: hidden;
            background: white;
            box-shadow: var(--shadow-md);
        }

        .category-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 1.5rem;
            color: white;
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .category-title i {
            color: white;
        }

        .faculties-container {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .faculty-section {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            overflow: hidden;
            background: var(--light-color);
        }

        .faculty-header {
            background: var(--secondary-color);
            padding: 1rem 1.5rem;
            color: white;
        }

        .faculty-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .faculty-title i {
            color: white;
        }

        .programs-container {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .program-item {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            overflow: hidden;
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .program-header {
            background: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .program-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .program-title i {
            color: var(--primary-color);
        }

        .program-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-add-program {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-add-program:hover {
            background: #0284c7;
        }

        .btn-add-course {
            background: var(--info-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-add-course:hover {
            background: #0ea5e9;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .category-title {
                font-size: 1.25rem;
            }
            
            .faculty-title {
                font-size: 1rem;
            }
            
            .program-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .program-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        /* Header Actions Styles */
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Faculty Management Styles */
        .faculty-header {
            background: var(--secondary-color);
            padding: 1rem 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faculty-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .faculty-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit-faculty {
            background: var(--warning-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-edit-faculty:hover {
            background: #d97706;
        }

        .btn-delete-faculty {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-delete-faculty:hover {
            background: #dc2626;
        }

        /* Responsive adjustments for faculty management */
        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .faculty-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .faculty-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        /* Academic Structure Table Styles */
        .academic-structure {
            overflow-x: auto;
        }

        .structure-table {
            border-collapse: collapse;
            width: 100%;
            min-width: 800px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .structure-table th {
            background: #f8f9fa;
            color: #495057;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border: 1px solid #dee2e6;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .structure-table td {
            padding: 1rem;
            border: 1px solid #dee2e6;
            vertical-align: top;
            background: white;
        }

        .structure-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .structure-table tr:hover {
            background-color: #f1f3f4;
        }

        /* Study Level Cell Styles */
        .study-level-cell {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-align: center;
            min-width: 200px;
            border-right: 2px solid #dee2e6;
        }

        .study-level-info h4 {
            margin: 0 0 0.75rem 0;
            font-size: 1.125rem;
            color: #212529;
        }

        .study-level-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-edit-category,
        .btn-delete-category {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-category:hover,
        .btn-delete-category:hover {
            background: #5a6268;
        }

        .btn-add-program {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            width: 100%;
            max-width: 120px;
        }

        .btn-add-program:hover {
            background: #218838;
        }

        /* Programs Cell Styles */
        .programs-cell {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-align: center;
            min-width: 200px;
            border-right: 1px solid #dee2e6;
        }

        .programs-info h5 {
            margin: 0 0 0.75rem 0;
            font-size: 1rem;
            color: #212529;
        }

        .programs-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .btn-edit-faculty,
        .btn-delete-faculty {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-faculty:hover,
        .btn-delete-faculty:hover {
            background: #5a6268;
        }

        /* Programs List Styles */
        .programs-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .program-item {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .program-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .program-name {
            font-weight: 600;
            color: #212529;
            flex: 1;
            font-size: 0.875rem;
            text-align: left;
        }

        .program-actions {
            display: flex;
            gap: 0.25rem;
        }

        .btn-edit-program,
        .btn-delete-program {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-program:hover,
        .btn-delete-program:hover {
            background: #5a6268;
        }

        .btn-add-course {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-course:hover {
            background: #0056b3;
        }

        /* Courses Cell Styles */
        .courses-cell {
            background: white;
            min-width: 400px;
            padding: 1rem;
        }

        .courses-list {
            margin-bottom: 1rem;
        }

        .course-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        .course-code {
            font-weight: 600;
            color: #495057;
            min-width: 60px;
            font-size: 0.75rem;
        }

        .course-title {
            flex: 1;
            color: #212529;
            font-size: 0.875rem;
        }

        .course-credits {
            color: #6c757d;
            font-size: 0.75rem;
            min-width: 80px;
        }

        .course-actions {
            display: flex;
            gap: 0.25rem;
        }

        .btn-edit-course,
        .btn-delete-course {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-edit-course:hover {
            background: #5a6268;
        }

        .btn-delete-course {
            background: #dc3545;
        }

        .btn-delete-course:hover {
            background: #c82333;
        }

        .no-courses,
        .no-program {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 1rem;
        }

        .btn-add-program-small {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        .btn-add-course {
            background: #374151;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-course:hover {
            background: #1f2937;
        }

        /* Actions Cell Styles */
        .actions-cell {
            background: white;
            text-align: center;
        }

        .row-actions {
            display: flex;
            justify-content: center;
        }

        .btn-view-details {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-view-details:hover {
            background: #5a6268;
        }

        /* No Data Styles */
        .no-data {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .no-data-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .no-data-message i {
            font-size: 2rem;
            opacity: 0.5;
        }

        .error {
            color: #dc3545;
            text-align: center;
            padding: 1rem;
        }

        /* Responsive adjustments for the new table */
        @media (max-width: 1200px) {
            .structure-table {
                min-width: 600px;
            }
            
            .courses-cell {
                min-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .academic-structure {
                margin: 0 -1rem;
            }
            
            .structure-table {
                min-width: 500px;
            }
            
            .program-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .course-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        /* Override width for add course buttons */
        .btn-add-course {
            width: auto !important;
            min-width: 120px !important;
        }

        /* Professional Course Management Styles */
        .study-level-section {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .study-level-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .study-level-header h4 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .study-level-header h4 i {
            color: #6b7280;
        }

        .study-level-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-edit {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-edit:hover {
            background: #4b5563;
        }

        .btn-add-program {
            background: #374151;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-add-program:hover {
            background: #1f2937;
        }

        .programs-container {
            padding: 1.5rem;
        }

        .program-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .program-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .program-info h5 {
            margin: 0 0 0.5rem 0;
            font-size: 1rem;
            color: #111827;
            font-weight: 600;
        }

        .program-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .program-meta span {
            background: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }

        .program-description {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .program-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-edit-program,
        .btn-delete-program {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.2s ease;
        }

        .btn-edit-program:hover {
            background: #4b5563;
        }

        .btn-delete-program {
            background: #dc2626;
        }

        .btn-delete-program:hover {
            background: #0284c7;
        }

        .courses-list {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .courses-list h6 {
            margin: 0 0 1rem 0;
            color: #374151;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .course-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.875rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
        }

        .course-item:hover {
            border-color: #d1d5db;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .course-details {
            flex: 1;
        }

        .course-title {
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .course-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .course-actions {
            display: flex;
            gap: 0.375rem;
        }

        .btn-edit-course,
        .btn-delete-course {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.2s ease;
        }

        .btn-edit-course:hover {
            background: #4b5563;
        }

        .btn-delete-course {
            background: #dc2626;
        }

        .btn-delete-course:hover {
            background: #0284c7;
        }

        .no-programs,
        .no-courses {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
            font-style: italic;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 0.5rem;
        }

        .no-programs i,
        .no-courses i {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.6;
            color: #9ca3af;
        }

        .no-programs p,
        .no-courses p {
            margin: 0;
            font-size: 0.875rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .study-level-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .study-level-actions {
                width: 100%;
                justify-content: flex-end;
            }
            
            .program-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .program-actions {
                width: 100%;
                justify-content: flex-end;
            }
            
            .course-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            
            .course-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        /* Professional Inquiry Management Styles */
        .inquiry-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .inquiry-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-color);
        }
        
        .inquiry-card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .inquiry-main-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .inquiry-subject h3 {
            margin: 0 0 0.5rem 0;
            color: var(--dark-color);
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .inquiry-id {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .inquiry-status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .inquiry-status-badge.status-pending {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fbbf24;
        }
        
        .inquiry-status-badge.status-answered {
            background: #d1fae5;
            color: #059669;
            border: 1px solid #10b981;
        }
        
        .inquiry-status-badge.status-closed {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #9ca3af;
        }
        
        .inquiry-meta-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .student-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .student-avatar {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .student-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .student-name {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 1rem;
        }
        
        .student-email {
            color: var(--secondary-color);
            font-size: 0.875rem;
        }
        
        .inquiry-timeline {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .timeline-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--secondary-color);
        }
        
        .timeline-item i {
            color: var(--primary-color);
            width: 1rem;
        }
        
        .inquiry-card-body {
            padding: 1.5rem;
        }
        
        .message-section,
        .response-section {
            margin-bottom: 1.5rem;
        }
        
        .message-section:last-child,
        .response-section:last-child {
            margin-bottom: 0;
        }
        
        .message-header,
        .response-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .message-header i {
            color: var(--primary-color);
        }
        
        .response-header i {
            color: var(--success-color);
        }
        
        .message-content,
        .response-content {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem;
            line-height: 1.6;
            color: var(--dark-color);
        }
        
        .response-content {
            background: #f0f9ff;
            border-color: #0ea5e9;
        }
        
        .inquiry-card-footer {
            background: #f8fafc;
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .btn-action {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-action.btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
        }
        
        .btn-action.btn-success {
            background: linear-gradient(135deg, var(--success-color), #10b981);
            color: white;
        }
        
        .btn-action.btn-info {
            background: linear-gradient(135deg, var(--info-color), #0ea5e9);
            color: white;
        }
        
        .btn-action.btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #ef4444);
            color: white;
        }
        
        .response-form {
            background: white;
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem;
            display: none;
        }
        
        .response-form.active {
            display: block;
            animation: slideDown 0.3s ease;
        }
        
        .response-form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .response-form-header h4 {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .close-form {
            background: none;
            border: none;
            color: var(--secondary-color);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .close-form:hover {
            background: #f1f5f9;
            color: var(--dark-color);
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .empty-icon {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .empty-state h3 {
            margin: 0 0 1rem 0;
            color: var(--dark-color);
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .empty-state p {
            color: var(--secondary-color);
            font-size: 1rem;
            line-height: 1.6;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .inquiry-meta-info {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .inquiry-main-info {
                flex-direction: column;
                gap: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-action {
                justify-content: center;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }

        /* Analytics Styles */
        .analytics-header {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .analytics-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .analytics-main-title {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .analytics-subtitle {
            margin: 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .analytics-header-actions {
            display: flex;
            gap: 1rem;
        }
        
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .kpi-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-color);
        }
        
        .kpi-icon {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .kpi-content {
            flex: 1;
        }
        
        .kpi-number {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .kpi-label {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .kpi-description {
            margin: 0;
            font-size: 0.875rem;
            color: var(--secondary-color);
        }
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }
        
        .metric-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .metric-item:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .metric-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--success-color), #10b981);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .metric-content h4 {
            margin: 0 0 0.25rem 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .metric-content p {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--success-color);
        }
        
        @media (max-width: 768px) {
            .analytics-header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .analytics-main-title {
                font-size: 1.5rem;
            }
            
            .kpi-grid {
                grid-template-columns: 1fr;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

         Export course data functionality
        function exportCourseData() {
            // Placeholder for export functionality
            alert('Export functionality will be implemented in future updates.');
        
        }

        // Update course statistics
        function updateCourseStats() {
            // Count study levels
            const studyLevels = ['Postgraduate', 'Undergraduate', 'After A/L & O/L'];
            document.getElementById('totalStudyLevels').textContent = studyLevels.length;

             Count programs
            let totalPrograms = 0;
            studyLevels.forEach(level => {
                const key = `icbt_programs_${level.replace(/\s+/g, '_')}`;
                const programs = JSON.parse(localStorage.getItem(key) || '[]');
                totalPrograms += programs.length;
            });
            document.getElementById('totalPrograms').textContent = totalPrograms;

            // Count courses
            let totalCourses = 0;
            studyLevels.forEach(level => {
                const programs = JSON.parse(localStorage.getItem(`icbt_programs_${level.replace(/\s+/g, '_')}`) || '[]');
                programs.forEach(program => {
                    const courseKey = `icbt_courses_${level.replace(/\s+/g, '_')}_${program.name.replace(/\s+/g, '_')}`;
                    const courses = JSON.parse(localStorage.getItem(courseKey) || '[]');
                    totalCourses += courses.length;
                });
            });
            document.getElementById('totalCourses').textContent = totalCourses;
        }

        // Initialize course statistics when the courses section is shown
        document.addEventListener('DOMContentLoaded', function() {
            // Update stats when courses section is shown
            const coursesLink = document.querySelector('[data-section="courses"]');
            if (coursesLink) {
                coursesLink.addEventListener('click', function() {
                    setTimeout(updateCourseStats, 100);
                });
            }
        });
    </style>
</body>
</html>
