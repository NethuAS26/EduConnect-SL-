<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Terms of Service Specific Styles */
        
        :root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
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
    --gradient-primary: linear-gradient(135deg, #2563eb, #1d4ed8);
    --gradient-secondary: linear-gradient(135deg, #10b981, #059669);
    --gradient-hero: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(16, 185, 129, 0.9));
    --gradient-subtle: linear-gradient(135deg, #f8fafc, #f1f5f9);
}
        
        .policy-hero {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(16, 185, 129, 0.9));
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .policy-hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        
        .policy-hero-content p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        .policy-meta {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }
        
        .policy-overview {
            padding: 80px 0;
            background: #f8fafc;
        }
        
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .overview-card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .overview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .overview-icon {
            width: 70px;
            height: 70px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #4f46e5;
            font-size: 1.8rem;
        }
        
        .overview-card h3 {
            margin-bottom: 15px;
            color: #1e293b;
        }
        
        .overview-card p {
            color: #64748b;
            line-height: 1.6;
        }
        
        .policy-content {
            padding: 80px 0;
        }
        
        .policy-sections {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .policy-section {
            margin-bottom: 60px;
            padding: 30px;
            border-radius: 16px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .policy-section h2 {
            color: #4f46e5;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
            position: relative;
        }
        
        .policy-section h2:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #4f46e5;
        }
        
        .policy-section h3 {
            color: #1e293b;
            margin: 25px 0 15px;
        }
        
        .policy-text p {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 15px;
        }
        
        .policy-text ul {
            margin: 20px 0;
            padding-left: 20px;
        }
        
        .policy-text li {
            margin-bottom: 10px;
            color: #475569;
            line-height: 1.6;
        }
        
        .policy-text strong {
            color: #4f46e5;
        }
        
        .policy-text ol {
            margin: 20px 0;
            padding-left: 20px;
        }
        
        .policy-text ol li {
            margin-bottom: 10px;
            color: #475569;
            line-height: 1.6;
        }
        
        .contact-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }
        
        .contact-info p {
            margin-bottom: 10px;
        }
        
        .contact-info a {
            color: #4f46e5;
            text-decoration: none;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .policy-hero-content h1 {
                font-size: 2.2rem;
            }
            
            .policy-hero-content p {
                font-size: 1rem;
            }
            
            .policy-meta {
                flex-direction: column;
                align-items: center;
            }
            
            .policy-section {
                padding: 20px;
            }
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
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="btn btn-primary user-profile-btn">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        </a>
                        <a href="logout.php" class="btn btn-outline">Logout</a>
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

    <!-- Terms of Service Hero Section -->
    <section class="policy-hero">
        <div class="container">
            <div class="policy-hero-content">
                <h1>Terms of Service</h1>
                <p>Please read these terms carefully before using our educational platform. These terms govern your use of EduConnect SL and outline your rights and responsibilities.</p>
                <div class="policy-meta">
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        Last updated: December 2024
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-clock"></i>
                        Reading time: 8 minutes
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms Overview -->
    <section class="policy-overview">
        <div class="container">
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Acceptance</h3>
                    <p>By using our platform, you agree to these terms and our privacy policy</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Educational Services</h3>
                    <p>We connect students with universities but don't provide educational services directly</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>User Conduct</h3>
                    <p>Users must follow our guidelines and use the platform responsibly</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3>Legal Rights</h3>
                    <p>We protect our intellectual property while respecting user rights</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms of Service Content -->
    <section class="policy-content">
        <div class="container">
            <div class="policy-sections">
                <div class="policy-section">
                    <h2>1. Acceptance of Terms</h2>
                    <div class="policy-text">
                        <p>By accessing and using EduConnect SL ("the Platform"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                        <p>These Terms of Service ("Terms") govern your use of our website and services. By using our platform, you agree to these terms and our Privacy Policy.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>2. Description of Service</h2>
                    <div class="policy-text">
                        <p>EduConnect SL is an educational platform that:</p>
                        <ul>
                            <li>Connects students with universities and educational institutions in Sri Lanka</li>
                            <li>Provides course information, reviews, and comparison tools</li>
                            <li>Facilitates communication between students and educational institutions</li>
                            <li>Offers educational resources and guidance</li>
                        </ul>
                        <p>We act as an intermediary platform and do not directly provide educational services or courses.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>3. User Accounts and Registration</h2>
                    <div class="policy-text">
                        <h3>Account Creation</h3>
                        <p>To access certain features of our platform, you must create an account. You agree to:</p>
                        <ul>
                            <li>Provide accurate, current, and complete information</li>
                            <li>Maintain and update your account information</li>
                            <li>Keep your password secure and confidential</li>
                            <li>Notify us immediately of any unauthorized use</li>
                            <li>Accept responsibility for all activities under your account</li>
                        </ul>
                        
                        <h3>Account Termination</h3>
                        <p>We reserve the right to terminate or suspend accounts that violate these terms or engage in fraudulent activities.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>4. User Conduct and Responsibilities</h2>
                    <div class="policy-text">
                        <p>You agree not to:</p>
                        <ul>
                            <li>Use the platform for any unlawful purpose</li>
                            <li>Post false, misleading, or fraudulent information</li>
                            <li>Harass, abuse, or harm other users</li>
                            <li>Attempt to gain unauthorized access to our systems</li>
                            <li>Interfere with the platform's functionality</li>
                            <li>Use automated systems to access the platform</li>
                            <li>Violate any applicable laws or regulations</li>
                        </ul>
                        
                        <p>You are responsible for all content you submit and the consequences of your actions on the platform.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>5. Content and Intellectual Property</h2>
                    <div class="policy-text">
                        <h3>Our Content</h3>
                        <p>The platform and its original content, features, and functionality are owned by EduConnect SL and are protected by international copyright, trademark, and other intellectual property laws.</p>
                        
                        <h3>User-Generated Content</h3>
                        <p>By submitting content to our platform, you grant us a non-exclusive, worldwide, royalty-free license to use, display, and distribute your content in connection with our services.</p>
                        
                        <h3>Third-Party Content</h3>
                        <p>We may display content from universities and other third parties. We do not endorse or verify the accuracy of such content and are not responsible for third-party content.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>6. Privacy and Data Protection</h2>
                    <div class="policy-text">
                        <p>Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these Terms by reference.</p>
                        <p>By using our platform, you consent to the collection and use of your information as described in our Privacy Policy.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>7. Disclaimers and Limitations</h2>
                    <div class="policy-text">
                        <h3>Service Availability</h3>
                        <p>We strive to maintain platform availability but do not guarantee uninterrupted access. We may temporarily suspend services for maintenance or updates.</p>
                        
                        <h3>Information Accuracy</h3>
                        <p>While we strive to provide accurate information, we cannot guarantee the completeness, accuracy, or timeliness of all content on our platform.</p>
                        
                        <h3>Third-Party Services</h3>
                        <p>We are not responsible for the quality, accuracy, or availability of educational services provided by universities or other institutions listed on our platform.</p>
                        
                        <h3>Limitation of Liability</h3>
                        <p>To the maximum extent permitted by law, EduConnect SL shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of our platform.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>8. Indemnification</h2>
                    <div class="policy-text">
                        <p>You agree to indemnify and hold harmless EduConnect SL, its officers, directors, employees, and agents from any claims, damages, losses, or expenses arising from:</p>
                        <ul>
                            <li>Your use of the platform</li>
                            <li>Your violation of these Terms</li>
                            <li>Your violation of any rights of another party</li>
                            <li>Your violation of applicable laws or regulations</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>9. Termination</h2>
                    <div class="policy-text">
                        <p>We may terminate or suspend your access to our platform at any time, with or without cause, with or without notice.</p>
                        <p>Upon termination, your right to use the platform will cease immediately, and we may delete your account and associated data.</p>
                        <p>You may terminate your account at any time by contacting us or using the account deletion feature in your profile settings.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>10. Governing Law and Dispute Resolution</h2>
                    <div class="policy-text">
                        <p>These Terms shall be governed by and construed in accordance with the laws of Sri Lanka.</p>
                        <p>Any disputes arising from these Terms or your use of the platform shall be resolved through:</p>
                        <ol>
                            <li>Good faith negotiations between the parties</li>
                            <li>Mediation, if negotiations fail</li>
                            <li>Arbitration in Colombo, Sri Lanka, if mediation fails</li>
                        </ol>
                        <p>The language of any dispute resolution proceedings shall be English.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>11. Changes to Terms</h2>
                    <div class="policy-text">
                        <p>We reserve the right to modify these Terms at any time. We will notify users of significant changes by:</p>
                        <ul>
                            <li>Posting the updated terms on our website</li>
                            <li>Sending email notifications to registered users</li>
                            <li>Displaying prominent notices on our platform</li>
                        </ul>
                        <p>Your continued use of the platform after such changes constitutes acceptance of the updated Terms.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>12. Contact Information</h2>
                    <div class="policy-text">
                        <p>If you have any questions about these Terms of Service, please contact us:</p>
                        <div class="contact-info">
                            <p><strong>Email:</strong> <a href="mailto:legal@educonnectsl.lk">legal@educonnectsl.lk</a></p>
                            <p><strong>Phone:</strong> +94 11 234 5678</p>
                            <p><strong>Address:</strong> EduConnect SL, Colombo, Sri Lanka</p>
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

    <script src="script.js"></script>
</body>
</html>
