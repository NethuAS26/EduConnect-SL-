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
$admin_id = $_SESSION['admin_id'];

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get reviews for this admin's campus
    $stmt = $pdo->prepare("SELECT r.*, s.first_name, s.last_name, s.email, c.title as course_title, u.name as university_name 
                          FROM reviews r 
                          JOIN students s ON r.student_id = s.id 
                          JOIN courses c ON r.course_id = c.id 
                          JOIN programs p ON c.program_id = p.id 
                          JOIN departments d ON p.department_id = d.id 
                          JOIN universities u ON d.university_id = u.id 
                          WHERE u.name LIKE ? 
                          ORDER BY r.created_at DESC");
    $stmt->execute(["%$campus%"]);
    $reviews = $stmt->fetchAll();
    
    // Get review statistics
    $stmt = $pdo->prepare("SELECT 
                              COUNT(*) as total,
                              COUNT(CASE WHEN r.status = 'pending' THEN 1 END) as pending,
                              COUNT(CASE WHEN r.status = 'approved' THEN 1 END) as approved,
                              COUNT(CASE WHEN r.status = 'rejected' THEN 1 END) as rejected
                          FROM reviews r 
                          JOIN courses c ON r.course_id = c.id 
                          JOIN programs p ON c.program_id = p.id 
                          JOIN departments d ON p.department_id = d.id 
                          JOIN universities u ON d.university_id = u.id 
                          WHERE u.name LIKE ?");
    $stmt->execute(["%$campus%"]);
    $stats = $stmt->fetch();
    
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
    <title>Review Moderation - EduConnect SL</title>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            background: white;
            padding: 2rem 0;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
        }

        .back-btn {
            background: var(--secondary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background: #475569;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
            text-transform: uppercase;
            font-weight: 500;
        }

        .reviews-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .reviews-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--light-color);
        }

        .reviews-header h2 {
            color: var(--dark-color);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .review-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .review-item:hover {
            background: var(--light-color);
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .review-info h3 {
            color: var(--dark-color);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .review-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--secondary-color);
        }

        .review-status {
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

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .review-content {
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

        .review-comment {
            background: var(--light-color);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }

        .review-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
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

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
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

        .moderation-form {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 0.375rem;
        }

        .moderation-form.active {
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

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
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
    <div class="header">
        <div class="container">
            <div class="header-content">
                <h1>Review Moderation</h1>
                <a href="admin-dashboard.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php 
                switch($_GET['success']) {
                    case 'review_approved':
                        echo 'Review approved successfully!';
                        break;
                    case 'review_rejected':
                        echo 'Review rejected successfully!';
                        break;
                    default:
                        echo 'Operation completed successfully!';
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?php 
                switch($_GET['error']) {
                    case 'database_error':
                        echo 'Database error occurred. Please try again.';
                        break;
                    case 'missing_notes':
                        echo 'Please provide moderation notes before submitting.';
                        break;
                    default:
                        echo 'An error occurred. Please try again.';
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
                <div class="stat-label">Total Reviews</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['approved'] ?? 0; ?></div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['rejected'] ?? 0; ?></div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterReviews('all')">All Reviews</button>
            <button class="filter-tab" onclick="filterReviews('pending')">Pending</button>
            <button class="filter-tab" onclick="filterReviews('approved')">Approved</button>
            <button class="filter-tab" onclick="filterReviews('rejected')">Rejected</button>
        </div>

        <!-- Reviews List -->
        <div class="reviews-container">
            <div class="reviews-header">
                <h2>Student Reviews</h2>
            </div>
            
            <?php if (empty($reviews)): ?>
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <h3>No reviews yet</h3>
                    <p>When students submit reviews, they will appear here for you to moderate.</p>
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item" data-status="<?php echo $review['status']; ?>">
                        <div class="review-header">
                            <div class="review-info">
                                <h3><?php echo htmlspecialchars($review['course_title']); ?></h3>
                                <div class="review-meta">
                                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></span>
                                    <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($review['email']); ?></span>
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($review['created_at'])); ?></span>
                                    <span><i class="fas fa-university"></i> <?php echo htmlspecialchars($review['university_name']); ?></span>
                                </div>
                            </div>
                            <div class="review-status status-<?php echo $review['status']; ?>">
                                <?php echo ucfirst($review['status']); ?>
                            </div>
                        </div>
                        
                        <div class="review-content">
                            <div class="review-rating">
                                <span>Rating:</span>
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-o'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span>(<?php echo $review['rating']; ?>/5)</span>
                            </div>
                            
                            <div class="review-comment">
                                <strong>Review:</strong><br>
                                <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                            </div>
                            
                            <?php if ($review['moderation_notes']): ?>
                                <div class="review-comment" style="background: #f0f9ff; border-left: 4px solid var(--primary-color);">
                                    <strong>Moderation Notes:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($review['moderation_notes'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="review-actions">
                            <?php if ($review['status'] === 'pending'): ?>
                                <button class="btn btn-success" onclick="showModerationForm(<?php echo $review['id']; ?>, 'approve')">
                                    <i class="fas fa-check"></i>
                                    Approve
                                </button>
                                <button class="btn btn-danger" onclick="showModerationForm(<?php echo $review['id']; ?>, 'reject')">
                                    <i class="fas fa-times"></i>
                                    Reject
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn btn-outline" onclick="viewReviewDetails(<?php echo $review['id']; ?>)">
                                <i class="fas fa-eye"></i>
                                View Details
                            </button>
                        </div>
                        
                        <!-- Moderation Form -->
                        <div class="moderation-form" id="moderation-form-<?php echo $review['id']; ?>">
                            <form action="process_review_moderation.php" method="POST">
                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                <input type="hidden" name="action" id="action-<?php echo $review['id']; ?>" value="">
                                
                                <div class="form-group">
                                    <label for="notes-<?php echo $review['id']; ?>">Moderation Notes *</label>
                                    <textarea id="notes-<?php echo $review['id']; ?>" name="notes" class="form-control" required 
                                              placeholder="Provide notes about why you're approving or rejecting this review..."></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane"></i>
                                        Submit Decision
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="hideModerationForm(<?php echo $review['id']; ?>)">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showModerationForm(reviewId, action) {
            const form = document.getElementById(`moderation-form-${reviewId}`);
            const actionInput = document.getElementById(`action-${reviewId}`);
            actionInput.value = action;
            form.classList.add('active');
        }

        function hideModerationForm(reviewId) {
            const form = document.getElementById(`moderation-form-${reviewId}`);
            form.classList.remove('active');
        }

        function filterReviews(status) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter review items
            const reviewItems = document.querySelectorAll('.review-item');
            reviewItems.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function viewReviewDetails(reviewId) {
            // This could open a modal or redirect to a detailed view
            alert('Review details view functionality can be implemented here.');
        }
    </script>
</body>
</html>
