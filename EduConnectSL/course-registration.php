<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit();
}

// Check if course_id is provided
if (!isset($_GET['course_id'])) {
    header('Location: courses.php');
    exit();
}

$course_id = $_GET['course_id'];
$student_id = $_SESSION['user_id'];
$message = '';
$error = '';

try {
    $pdo = getDBConnection();
    
    // Get course details
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();
    
    if (!$course) {
        header('Location: courses.php');
        exit();
    }
    
    // Get student details
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    // Check if already registered
    $stmt = $pdo->prepare("SELECT * FROM course_registrations WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$student_id, $course_id]);
    $existing_registration = $stmt->fetch();
    
    if ($existing_registration) {
        $error = 'You are already registered for this course.';
    }
    
} catch (Exception $e) {
    $error = 'Database error: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existing_registration) {
    try {
        // Check if still available
        $stmt = $pdo->prepare("SELECT seats_available FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        $course_check = $stmt->fetch();
        
        if ($course_check['seats_available'] <= 0) {
            throw new Exception('Sorry, this course is no longer available.');
        }
        
        // Begin transaction
        $pdo->beginTransaction();
        
        // Insert registration with campus information
        $stmt = $pdo->prepare("INSERT INTO course_registrations (student_id, course_id, course_name, university_name, department_name, program_name, campus, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $student_id, 
            $course_id, 
            $course['title'], 
            $course['campus'], // Store campus name
            $course['department'], 
            $course['department'], // Using department as program for now
            $course['campus'], // Store campus again for easy access
            'pending'
        ]);
        
        // Update available seats
        $stmt = $pdo->prepare("UPDATE courses SET seats_available = seats_available - 1 WHERE id = ?");
        $stmt->execute([$course_id]);
        
        // Commit transaction
        $pdo->commit();
        
        // Send confirmation email
        $to = $student['email'];
        $subject = "Course Registration Confirmation - " . $course['title'];
        $message_body = "
        Dear " . $student['first_name'] . " " . $student['last_name'] . ",
        
        Thank you for registering for the course: " . $course['title'] . "
        Campus: " . $course['campus'] . "
        Department: " . $course['department'] . "
        Duration: " . $course['duration'] . "
        Fee: Rs. " . number_format($course['fee'], 2) . "
        
        Your registration is currently pending approval from the campus administration. 
        You will receive an email notification once your registration is approved or rejected.
        
        Registration ID: " . $pdo->lastInsertId() . "
        Registration Date: " . date('Y-m-d H:i:s') . "
        
        Best regards,
        EduConnect SL Team
        ";
        
        $headers = "From: noreply@educonnectsl.com\r\n";
        $headers .= "Reply-To: support@educonnectsl.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        mail($to, $subject, $message_body, $headers);
        
        $message = 'Course registration successful! You will receive a confirmation email shortly.';
        
        // Redirect to profile page after successful registration
        header('Location: profile.php?registration_success=1');
        exit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .registration-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .registration-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .registration-header h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .course-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid var(--primary-color);
        }
        
        .course-summary h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .course-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .course-detail {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .course-detail i {
            color: var(--primary-color);
            width: 20px;
        }
        
        .registration-form {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section h3 {
            color: var(--dark-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-group input[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
        
        .btn-register {
            background: var(--primary-color);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .btn-register:hover {
            background: var(--primary-color-dark);
        }
        
        .btn-register:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
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
            </div>
        </nav>
    </header>

    <div class="registration-container">
        <a href="courses.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
        
        <div class="registration-header">
            <h1>Course Registration</h1>
            <p>Complete your course registration by reviewing the information below</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="course-summary">
            <h3><i class="fas fa-book"></i> Course Information</h3>
            <div class="course-details">
                <div class="course-detail">
                    <i class="fas fa-graduation-cap"></i>
                    <span><strong>Course:</strong> <?php echo htmlspecialchars($course['title']); ?></span>
                </div>
                <div class="course-detail">
                    <i class="fas fa-university"></i>
                    <span><strong>Campus:</strong> <?php echo htmlspecialchars($course['campus']); ?></span>
                </div>
                <div class="course-detail">
                    <i class="fas fa-building"></i>
                    <span><strong>Department:</strong> <?php echo htmlspecialchars($course['department']); ?></span>
                </div>
                <div class="course-detail">
                    <i class="fas fa-clock"></i>
                    <span><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></span>
                </div>
                <div class="course-detail">
                    <i class="fas fa-money-bill"></i>
                    <span><strong>Fee:</strong> Rs. <?php echo number_format($course['fee'], 2); ?></span>
                </div>
                <div class="course-detail">
                    <i class="fas fa-users"></i>
                    <span><strong>Available Seats:</strong> <?php echo $course['seats_available']; ?></span>
                </div>
            </div>
        </div>
        
        <?php if (!$existing_registration): ?>
            <div class="registration-form">
                <form method="POST">
                    <div class="form-section">
                        <h3><i class="fas fa-user"></i> Student Information</h3>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Additional Information</h3>
                        <div class="form-group">
                            <label for="notes">Additional Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="4" placeholder="Any additional information you'd like to share..."></textarea>
                        </div>
                    </div>
                    
                    <div class="btn-container">
                        <button type="submit" class="btn-register" <?php echo ($course['seats_available'] <= 0) ? 'disabled' : ''; ?>>
                            <?php echo ($course['seats_available'] <= 0) ? 'Course Full' : 'Register for this Course'; ?>
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-error">
                <i class="fas fa-info-circle"></i> 
                You are already registered for this course. 
                <a href="profile.php">View your registrations</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Add any additional JavaScript functionality here
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation or other interactive features can be added here
        });
    </script>
</body>
</html>
