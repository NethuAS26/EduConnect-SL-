<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get user's notifications
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND user_type = 'student' ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll();
    
    // Get notification statistics
    $stmt = $pdo->prepare("SELECT 
                              COUNT(*) as total,
                              COUNT(CASE WHEN is_read = 0 THEN 1 END) as unread
                          FROM notifications 
                          WHERE user_id = ? AND user_type = 'student'");
    $stmt->execute([$user_id]);
    $stats = $stmt->fetch();
    
    // Mark notifications as read if requested
    if (isset($_GET['mark_read']) && $_GET['mark_read'] === 'all') {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1, read_at = CURRENT_TIMESTAMP WHERE user_id = ? AND user_type = 'student' AND is_read = 0");
        $stmt->execute([$user_id]);
        header('Location: notifications.php');
        exit;
    }
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "Database connection failed";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .notifications-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .notifications-title {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
        }

        .mark-all-read {
            background: var(--success-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mark-all-read:hover {
            background: #059669;
        }

        .stats-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
        }

        .notification-item {
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .notification-item:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .notification-item.unread {
            border-left-color: var(--primary-color);
            background: #f8fafc;
        }

        .notification-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .notification-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .notification-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--secondary-color);
        }

        .notification-type {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .type-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .type-success {
            background: #d1fae5;
            color: #065f46;
        }

        .type-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .type-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .type-announcement {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .notification-content {
            padding: 1.5rem;
        }

        .notification-message {
            color: var(--dark-color);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .notification-actions {
            display: flex;
            gap: 0.5rem;
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

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
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
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="index.php">
                        <i class="fas fa-graduation-cap"></i>
                        <span>EduConnect SL</span>
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="courses.php" class="nav-link">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a href="universities.php" class="nav-link">Universities</a>
                    </li>
                    <li class="nav-item">
                        <a href="reviews.php" class="nav-link">Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.php" class="nav-link">Contact</a>
                    </li>
                </ul>
                
                <div class="nav-auth">
                    <a href="student-dashboard.php" class="btn btn-outline">Dashboard</a>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </div>
                
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <div class="notifications-container">
        <div class="notifications-header">
            <h1 class="notifications-title">Notifications</h1>
            <?php if ($stats['unread'] > 0): ?>
                <a href="?mark_read=all" class="mark-all-read">
                    <i class="fas fa-check-double"></i>
                    Mark All as Read
                </a>
            <?php endif; ?>
        </div>

        <!-- Statistics -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
                <div class="stat-label">Total Notifications</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $stats['unread'] ?? 0; ?></div>
                <div class="stat-label">Unread</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterNotifications('all')">All</button>
            <button class="filter-tab" onclick="filterNotifications('unread')">Unread</button>
            <button class="filter-tab" onclick="filterNotifications('read')">Read</button>
        </div>

        <!-- Notifications List -->
        <?php if (empty($notifications)): ?>
            <div class="empty-state">
                <i class="fas fa-bell"></i>
                <h3>No notifications yet</h3>
                <p>You'll see important updates, announcements, and responses to your inquiries here.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>" 
                     data-status="<?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                    <div class="notification-header">
                        <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
                        <div class="notification-meta">
                            <span><i class="fas fa-calendar"></i> <?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?></span>
                            <span class="notification-type type-<?php echo $notification['type']; ?>">
                                <?php echo ucfirst($notification['type']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="notification-content">
                        <div class="notification-message">
                            <?php echo htmlspecialchars($notification['message']); ?>
                        </div>
                        
                        <?php if ($notification['related_url']): ?>
                            <div class="notification-actions">
                                <a href="<?php echo $notification['related_url']; ?>" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt"></i>
                                    View Details
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
    <script>
        function filterNotifications(status) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter notification items
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
