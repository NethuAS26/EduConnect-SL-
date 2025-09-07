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
    
    // Get inquiries for this admin's campus
    $stmt = $pdo->prepare("SELECT i.*, s.first_name, s.last_name, s.email, s.phone, u.name as university_name 
                          FROM inquiries i 
                          JOIN students s ON i.student_id = s.id 
                          JOIN universities u ON i.university_id = u.id 
                          WHERE u.name LIKE ? 
                          ORDER BY i.created_at DESC");
    $stmt->execute(["%$campus%"]);
    $inquiries = $stmt->fetchAll();
    
    // Get inquiry statistics
    $stmt = $pdo->prepare("SELECT 
                              COUNT(*) as total,
                              COUNT(CASE WHEN response_status = 'pending' THEN 1 END) as pending,
                              COUNT(CASE WHEN response_status = 'answered' THEN 1 END) as answered,
                              COUNT(CASE WHEN response_status = 'closed' THEN 1 END) as closed
                          FROM inquiries i 
                          JOIN universities u ON i.university_id = u.id 
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
    <title>Inquiry Management - EduConnect SL</title>
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

        .inquiries-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .inquiries-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--light-color);
        }

        .inquiries-header h2 {
            color: var(--dark-color);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .inquiry-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .inquiry-item:hover {
            background: var(--light-color);
        }

        .inquiry-item:last-child {
            border-bottom: none;
        }

        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .inquiry-info h3 {
            color: var(--dark-color);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .inquiry-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--secondary-color);
        }

        .inquiry-status {
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

        .status-answered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .inquiry-content {
            margin-bottom: 1rem;
        }

        .inquiry-message {
            background: var(--light-color);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }

        .inquiry-response {
            background: #f0f9ff;
            padding: 1rem;
            border-radius: 0.375rem;
            border-left: 4px solid var(--primary-color);
        }

        .inquiry-actions {
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
            background: #059669;
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

        .response-form {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 0.375rem;
        }

        .response-form.active {
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
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <h1>Inquiry Management</h1>
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
                    case 'inquiry_responded':
                        echo 'Inquiry responded to successfully!';
                        break;
                    case 'inquiry_closed':
                        echo 'Inquiry closed successfully!';
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
                    case 'missing_response':
                        echo 'Please provide a response before submitting.';
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
                <div class="stat-label">Total Inquiries</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['answered'] ?? 0; ?></div>
                <div class="stat-label">Answered</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['closed'] ?? 0; ?></div>
                <div class="stat-label">Closed</div>
            </div>
        </div>

        <!-- Inquiries List -->
        <div class="inquiries-container">
            <div class="inquiries-header">
                <h2>Student Inquiries</h2>
            </div>
            
            <?php if (empty($inquiries)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No inquiries yet</h3>
                    <p>When students send inquiries, they will appear here for you to respond to.</p>
                </div>
            <?php else: ?>
                <?php foreach ($inquiries as $inquiry): ?>
                    <div class="inquiry-item" id="inquiry-<?php echo $inquiry['id']; ?>">
                        <div class="inquiry-header">
                            <div class="inquiry-info">
                                <h3><?php echo htmlspecialchars($inquiry['subject']); ?></h3>
                                <div class="inquiry-meta">
                                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($inquiry['first_name'] . ' ' . $inquiry['last_name']); ?></span>
                                    <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($inquiry['email']); ?></span>
                                    <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($inquiry['phone']); ?></span>
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($inquiry['created_at'])); ?></span>
                                </div>
                            </div>
                            <div class="inquiry-status status-<?php echo $inquiry['response_status']; ?>">
                                <?php echo ucfirst($inquiry['response_status']); ?>
                            </div>
                        </div>
                        
                        <div class="inquiry-content">
                            <div class="inquiry-message">
                                <strong>Message:</strong><br>
                                <?php echo nl2br(htmlspecialchars($inquiry['message'])); ?>
                            </div>
                            
                            <?php if ($inquiry['response']): ?>
                                <div class="inquiry-response">
                                    <strong>Your Response:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($inquiry['response'])); ?>
                                    <div style="margin-top: 0.5rem; font-size: 0.875rem; color: var(--secondary-color);">
                                        Responded on: <?php echo date('M j, Y g:i A', strtotime($inquiry['response_date'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="inquiry-actions">
                            <?php if ($inquiry['response_status'] === 'pending'): ?>
                                <button class="btn btn-primary" onclick="showResponseForm(<?php echo $inquiry['id']; ?>)">
                                    <i class="fas fa-reply"></i>
                                    Respond
                                </button>
                            <?php elseif ($inquiry['response_status'] === 'answered'): ?>
                                <button class="btn btn-outline" onclick="closeInquiry(<?php echo $inquiry['id']; ?>)">
                                    <i class="fas fa-check"></i>
                                    Close Inquiry
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn btn-outline" onclick="viewInquiryDetails(<?php echo $inquiry['id']; ?>)">
                                <i class="fas fa-eye"></i>
                                View Details
                            </button>
                        </div>
                        
                        <!-- Response Form -->
                        <div class="response-form" id="response-form-<?php echo $inquiry['id']; ?>">
                            <form action="process_inquiry_response.php" method="POST">
                                <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                                
                                <div class="form-group">
                                    <label for="response-<?php echo $inquiry['id']; ?>">Your Response *</label>
                                    <textarea id="response-<?php echo $inquiry['id']; ?>" name="response" class="form-control" required 
                                              placeholder="Type your response to the student..."></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane"></i>
                                        Send Response
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="hideResponseForm(<?php echo $inquiry['id']; ?>)">
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
        function showResponseForm(inquiryId) {
            const form = document.getElementById(`response-form-${inquiryId}`);
            form.classList.add('active');
        }

        function hideResponseForm(inquiryId) {
            const form = document.getElementById(`response-form-${inquiryId}`);
            form.classList.remove('active');
        }

        function closeInquiry(inquiryId) {
            if (confirm('Are you sure you want to close this inquiry? This action cannot be undone.')) {
                window.location.href = `close_inquiry.php?id=${inquiryId}`;
            }
        }

        function viewInquiryDetails(inquiryId) {
            // This could open a modal or redirect to a detailed view
            alert('Inquiry details view functionality can be implemented here.');
        }
    </script>
</body>
</html>
