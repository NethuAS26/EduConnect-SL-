<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once 'config.php';
} catch (Exception $e) {
    // Log error but continue loading the page
    error_log("Config error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                        <a href="contact.php" class="nav-link active">Contact</a>
                    </li>
                </ul>
                
                <div class="nav-auth">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline">Login</a>
                        <a href="signup.php" class="btn btn-primary">Sign Up</a>
                    <?php endif; ?>
                </div>
                
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <div class="header-text">
                    <h1>Let's Connect</h1>
                    <p>Ready to start your educational journey? We're here to guide you every step of the way.</p>
                </div>
                <div class="header-visual">
                    <div class="floating-elements">
                        <div class="floating-icon" style="--delay: 0s">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="floating-icon" style="--delay: 1s">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="floating-icon" style="--delay: 2s">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="floating-icon" style="--delay: 3s">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <!-- Main Contact Section -->
    <section class="main-contact">
        <div class="container">
            <div class="contact-layout">
                <!-- Contact Benefits Section (Left Side) -->
                <div class="contact-benefits-sidebar">
                    <div class="benefits-header">
                        <h2>Why Contact EduConnect SL?</h2>
                        <p>Your trusted partner in navigating Sri Lanka's educational landscape</p>
                    </div>
                    
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="benefit-content">
                                <h3>Expert Guidance</h3>
                                <p>Get personalized advice from education professionals who understand Sri Lanka's university system.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="benefit-content">
                                <h3>University Partnerships</h3>
                                <p>Direct access to top universities including ICBT, NIBM, Peradeniya, and Moratuwa.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="benefit-content">
                                <h3>Pathway Planning</h3>
                                <p>We help you navigate complex admission requirements and career pathways.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="benefit-content">
                                <h3>Timely Support</h3>
                                <p>Quick responses and ongoing support throughout your educational journey.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form (Right Side) -->
                <div class="contact-form-section">
                    <div class="form-header">
                        <h2>Send us a Message</h2>
                        <p>We'd love to hear from you. Fill out the form below and we'll get back to you within 24 hours.</p>
                    </div>
                    
                    <?php if(isset($_GET['success']) && $_GET['success'] === 'inquiry_sent'): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <div class="message-content">
                                <h3>Message Sent Successfully!</h3>
                                <p>Thank you for reaching out to us! We have received your message and will get back to you within 24 hours.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['error'])): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <div class="message-content">
                                <h3>Error</h3>
                                <p>
                                    <?php 
                                    switch($_GET['error']) {
                                        case 'login_required':
                                            echo 'You must be logged in as a student to send messages.';
                                            break;
                                        case 'missing_fields':
                                            echo 'Please fill in all required fields.';
                                            break;
                                        case 'invalid_email':
                                            echo 'Please enter a valid email address.';
                                            break;
                                        case 'invalid_student':
                                            echo 'Student account not found.';
                                            break;
                                        case 'email_mismatch':
                                            echo 'Email address does not match your logged-in account.';
                                            break;
                                        case 'database_error':
                                            echo 'A database error occurred. Please try again.';
                                            break;
                                        default:
                                            echo 'An error occurred. Please try again.';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'student'): ?>
                        <form id="contactForm" class="contact-form" method="POST" action="process_contact.php">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">First Name *</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user"></i>
                                        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name *</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user"></i>
                                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($_SESSION['user_last_name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-phone"></i>
                                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="campus">Select Campus *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-university"></i>
                                    <select id="campus" name="campus" required aria-describedby="campus-help">
                                        <option value="">Select a campus</option>
                                        <option value="icbt">ICBT Campus</option>
                                        <option value="nibm">NIBM Campus</option>
                                        <option value="peradeniya">Peradeniya Campus</option>
                                        <option value="moratuwa">Moratuwa Campus</option>
                                    </select>
                                </div>
                                <small id="campus-help" class="form-help">Choose the campus you'd like to contact for your inquiry</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-tag"></i>
                                    <select id="subject" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="course-inquiry">Course Inquiry</option>
                                        <option value="university-info">University Information</option>
                                        <option value="application-help">Application Help</option>
                                        <option value="general">General Question</option>
                                        <option value="feedback">Feedback</option>
                                        <option value="partnership">Partnership Inquiry</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-comment"></i>
                                    <textarea id="message" name="message" placeholder="Tell us how we can help you..." required></textarea>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary submit-btn">
                                    <span class="btn-text">Send Message</span>
                                    <span class="btn-icon"><i class="fas fa-paper-plane"></i></span>
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="login-required-message">
                            <div class="message-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3>Login Required</h3>
                            <p>You need to be logged in as a student to send messages. Please log in or sign up to continue.</p>
                            <div class="auth-buttons">
                                <a href="login.php" class="btn btn-primary">Login</a>
                                <a href="signup.php" class="btn btn-outline">Sign Up</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Campus Contact Information Section -->
    <section class="campus-contacts-section">
        <div class="container">
            <div class="section-header">
                <h2>Direct Campus Contacts</h2>
                <p>Connect directly with our partner campuses for immediate assistance</p>
            </div>
            
            <div class="campus-grid">
                <!-- ICBT Campus -->
            <div class="campus-section icbt-campus">
                <div class="campus-hero">
                    <div class="campus-logo">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="campus-info">
                        <h3>ICBT Campus</h3>
                        <p class="campus-description">International College of Business and Technology</p>
                        <div class="campus-badge">Premier Business & Technology Institute</div>
                    </div>
                </div>
                <div class="contact-grid">
                    <div class="contact-item primary">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">Main Campus Hotline</span>
                            <a href="tel:+94114777888" class="contact-number">+94 11 477 7888</a>
                            <span class="contact-status">Available 24/7</span>
                        </div>
                    </div>
                    <div class="contact-item secondary">
                        <div class="contact-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">Help Desk</span>
                            <a href="tel:+94114777800" class="contact-number">+94 11 477 7800</a>
                            <span class="contact-status">Mon-Fri: 8:00 AM - 6:00 PM</span>
                            </div>
                    </div>
                </div>
            </div>

            <!-- NIBM Campus -->
            <div class="campus-section nibm-campus">
                <div class="campus-hero">
                    <div class="campus-logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="campus-info">
                        <h3>NIBM Campus</h3>
                        <p class="campus-description">National Institute of Business Management</p>
                        <div class="campus-badge">Excellence in Business Education</div>
                    </div>
                </div>
                <div class="contact-grid">
                    <div class="contact-item primary">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">General Contact</span>
                            <a href="tel:+94117321000" class="contact-number">+94 11 732 1000</a>
                            <span class="contact-status">Mon-Fri: 8:30 AM - 5:30 PM</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peradeniya Campus -->
            <div class="campus-section peradeniya-campus">
                <div class="campus-hero">
                    <div class="campus-logo">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="campus-info">
                        <h3>Peradeniya Campus</h3>
                        <p class="campus-description">University of Peradeniya</p>
                        <div class="campus-badge">Sri Lanka's Premier University</div>
                    </div>
                </div>
                <div class="contact-grid">
                    <div class="contact-item primary">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">General Numbers</span>
                            <a href="tel:+94812388301" class="contact-number">+94 81 238 8301, 302, 303, 304, 305</a>
                            <span class="contact-status">Main Switchboard</span>
                        </div>
                    </div>
                    <div class="contact-item secondary">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">General Numbers</span>
                            <a href="tel:+94812390000" class="contact-number">+94 81 239 0000, 239 2000, 239 2001</a>
                            <span class="contact-status">Administrative Office</span>
                        </div>
                    </div>
                    <div class="contact-item executive">
                        <div class="contact-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">Vice Chancellor's Office</span>
                            <a href="tel:+94812392300" class="contact-number">+94 81 239 2300</a>
                            <span class="contact-status">Direct Line</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Moratuwa Campus -->
            <div class="campus-section moratuwa-campus">
                <div class="campus-hero">
                    <div class="campus-logo">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="campus-info">
                        <h3>Moratuwa Campus</h3>
                        <p class="campus-description">University of Moratuwa</p>
                        <div class="campus-badge">Engineering & Technology Excellence</div>
                    </div>
                </div>
                <div class="contact-grid">
                    <div class="contact-item primary">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">General Contact</span>
                            <a href="tel:+94112640051" class="contact-number">+94 112 640 051</a>
                            <span class="contact-status">Main Office</span>
                        </div>
                    </div>
                    <div class="contact-item secondary">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">General Contact</span>
                            <a href="tel:+94112650301" class="contact-number">+94 112 650 301</a>
                            <span class="contact-status">Administrative Office</span>
                        </div>
                    </div>
                    <div class="contact-item alternative">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <span class="contact-label">Alternative Numbers</span>
                            <a href="tel:+94112650301" class="contact-number">+94 11 265 0301, +94 11 264 0051</a>
                            <span class="contact-status">Directory Listing</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>


            






    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-graduation-cap"></i>
                        <span>EduConnect SL</span>
                    </div>
                    <p>Your trusted partner in finding the perfect educational path. We connect students with the best courses and universities across Sri Lanka.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="courses.php">Browse Courses</a></li>
                        <li><a href="universities.php">Universities</a></li>
                        <li><a href="reviews.php">Student Reviews</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="privacy-policy.php">Privacy Policy</a></li>
                        <li><a href="terms-of-service.php">Terms of Service</a></li>
                        <li><a href="cookie-policy.php">Cookie Policy</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <div class="campus-emails">
                        <div class="email-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@icbtcampus.edu.lk">info@icbtcampus.edu.lk</a>
                        </div>
                        <div class="email-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@nibm.lk">info@nibm.lk</a>
                        </div>
                        <div class="email-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:generalservice@gs.pdn.ac.lk">generalservice@gs.pdn.ac.lk</a>
                        </div>
                        <div class="email-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@uom.lk">info@uom.lk</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 EduConnect SL. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-header success">
                <i class="fas fa-check-circle"></i>
                <h3>Message Sent Successfully!</h3>
            </div>
            <div class="modal-body">
                <p>Thank you for reaching out to us! We have received your message and will get back to you within 24 hours.</p>
                <p>In the meantime, feel free to browse our courses or check out our university partners.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeSuccessModal()">Continue</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="contact.js"></script>
</body>
</html>
