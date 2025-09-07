<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Cookie Policy Specific Styles */
        
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
        
        /* Cookie Settings Panel */
        .cookie-settings {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 20px;
            width: 350px;
            z-index: 1000;
            transform: translateY(150%);
            transition: transform 0.4s ease;
        }
        
        .cookie-settings.active {
            transform: translateY(0);
        }
        
        .cookie-settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .cookie-settings-header h3 {
            margin: 0;
            color: #1e293b;
        }
        
        .close-settings {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #64748b;
        }
        
        .cookie-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .toggle-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .toggle-icon {
            width: 36px;
            height: 36px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f46e5;
        }
        
        .toggle-text h4 {
            margin: 0;
            font-size: 0.95rem;
            color: #1e293b;
        }
        
        .toggle-text p {
            margin: 3px 0 0;
            font-size: 0.8rem;
            color: #64748b;
        }
        
        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #4f46e5;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        .cookie-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-save {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            flex: 1;
            font-weight: 500;
        }
        
        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            flex: 1;
        }
        
        /* Floating Cookie Button */
        .cookie-float-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #4f46e5;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            cursor: pointer;
            z-index: 999;
            transition: transform 0.3s ease;
        }
        
        .cookie-float-btn:hover {
            transform: scale(1.1);
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
            
            .cookie-settings {
                width: 90%;
                right: 5%;
                left: 5%;
                bottom: 10px;
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

    <!-- Cookie Policy Hero Section -->
    <section class="policy-hero">
        <div class="container">
            <div class="policy-hero-content">
                <h1>Cookie Policy</h1>
                <p>This policy explains how we use cookies and similar technologies to enhance your browsing experience and provide personalized content on our educational platform.</p>
                <div class="policy-meta">
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        Last updated: December 2024
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-clock"></i>
                        Reading time: 4 minutes
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Cookie Overview -->
    <section class="policy-overview">
        <div class="container">
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-cookie"></i>
                    </div>
                    <h3>What Are Cookies?</h3>
                    <p>Small text files that help websites remember your preferences and improve functionality</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>How We Use Them</h3>
                    <p>We use cookies to enhance your experience, remember preferences, and analyze site usage</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h3>Your Control</h3>
                    <p>You can control cookie settings through your browser or our cookie consent banner</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Privacy First</h3>
                    <p>We only use cookies that are necessary and respect your privacy preferences</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cookie Policy Content -->
    <section class="policy-content">
        <div class="container">
            <div class="policy-sections">
                <div class="policy-section">
                    <h2>1. What Are Cookies?</h2>
                    <div class="policy-text">
                        <p>Cookies are small text files that are stored on your device (computer, tablet, or mobile phone) when you visit a website. They help websites remember information about your visit, such as your preferred language and other settings, which can make your next visit easier and the site more useful to you.</p>
                        <p>Cookies play an important role in making websites work efficiently and providing information to website owners.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>2. How We Use Cookies</h2>
                    <div class="policy-text">
                        <p>We use cookies for several purposes:</p>
                        <ul>
                            <li><strong>Essential Cookies:</strong> These are necessary for the website to function properly</li>
                            <li><strong>Performance Cookies:</strong> These help us understand how visitors interact with our website</li>
                            <li><strong>Functionality Cookies:</strong> These allow the website to remember choices you make</li>
                            <li><strong>Analytics Cookies:</strong> These help us improve our website by collecting information about how visitors use it</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>3. Types of Cookies We Use</h2>
                    <div class="policy-text">
                        <h3>Session Cookies</h3>
                        <p>These cookies are temporary and are deleted when you close your browser. They help us maintain your session and remember your preferences during your visit.</p>
                        
                        <h3>Persistent Cookies</h3>
                        <p>These cookies remain on your device for a set period or until you delete them. They help us remember your preferences for future visits.</p>
                        
                        <h3>Third-Party Cookies</h3>
                        <p>Some cookies are placed by third-party services that appear on our pages, such as Google Analytics, social media plugins, and advertising networks.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>4. Specific Cookies We Use</h2>
                    <div class="policy-text">
                        <h3>Essential Cookies</h3>
                        <ul>
                            <li><strong>Session Management:</strong> To maintain your login session and security</li>
                            <li><strong>Security:</strong> To protect against fraud and ensure secure browsing</li>
                            <li><strong>Functionality:</strong> To remember your language preferences and display settings</li>
                        </ul>
                        
                        <h3>Analytics Cookies</h3>
                        <ul>
                            <li><strong>Google Analytics:</strong> To understand how visitors use our website</li>
                            <li><strong>Performance Monitoring:</strong> To monitor website performance and identify issues</li>
                        </ul>
                        
                        <h3>Preference Cookies</h3>
                        <ul>
                            <li><strong>User Preferences:</strong> To remember your search history and course preferences</li>
                            <li><strong>Display Settings:</strong> To remember your preferred layout and theme</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>5. Third-Party Cookies</h2>
                    <div class="policy-text">
                        <p>We may use third-party services that place cookies on your device:</p>
                        <ul>
                            <li><strong>Google Analytics:</strong> For website analytics and performance monitoring</li>
                            <li><strong>Social Media:</strong> For social media integration and sharing features</li>
                            <li><strong>Advertising:</strong> For relevant educational content and offers</li>
                            <li><strong>Payment Processors:</strong> For secure payment processing (if applicable)</li>
                        </ul>
                        <p>These third parties have their own privacy policies and cookie practices.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>6. Managing Your Cookie Preferences</h2>
                    <div class="policy-text">
                        <h3>Browser Settings</h3>
                        <p>You can control and manage cookies through your browser settings:</p>
                        <ul>
                            <li><strong>Chrome:</strong> Settings > Privacy and security > Cookies and other site data</li>
                            <li><strong>Firefox:</strong> Options > Privacy & Security > Cookies and Site Data</li>
                            <li><strong>Safari:</strong> Preferences > Privacy > Manage Website Data</li>
                            <li><strong>Edge:</strong> Settings > Cookies and site permissions > Cookies and site data</li>
                        </ul>
                        
                        <h3>Cookie Consent</h3>
                        <p>When you first visit our website, you'll see a cookie consent banner. You can:</p>
                        <ul>
                            <li>Accept all cookies</li>
                            <li>Reject non-essential cookies</li>
                            <li>Customize your preferences</li>
                        </ul>
                        
                        <h3>Opting Out</h3>
                        <p>You can opt out of certain types of cookies:</p>
                        <ul>
                            <li><strong>Analytics:</strong> Use browser add-ons to block analytics cookies</li>
                            <li><strong>Advertising:</strong> Opt out through advertising industry websites</li>
                            <li><strong>Social Media:</strong> Adjust privacy settings on social media platforms</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>7. Impact of Disabling Cookies</h2>
                    <div class="policy-text">
                        <p>If you choose to disable cookies, some features of our website may not function properly:</p>
                        <ul>
                            <li>You may need to log in repeatedly</li>
                            <li>Your preferences may not be saved</li>
                            <li>Some features may be unavailable</li>
                            <li>Website performance may be affected</li>
                        </ul>
                        <p>We recommend keeping essential cookies enabled for the best user experience.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>8. Updates to This Policy</h2>
                    <div class="policy-text">
                        <p>We may update this Cookie Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons.</p>
                        <p>We will notify you of any material changes by:</p>
                        <ul>
                            <li>Posting the updated policy on our website</li>
                            <li>Sending email notifications to registered users</li>
                            <li>Displaying a notice on our platform</li>
                        </ul>
                        <p>Your continued use of our website after such changes constitutes acceptance of the updated policy.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>9. Contact Us</h2>
                    <div class="policy-text">
                        <p>If you have any questions about our use of cookies or this Cookie Policy, please contact us:</p>
                        <div class="contact-info">
                            <p><strong>Email:</strong> <a href="mailto:privacy@educonnectsl.lk">privacy@educonnectsl.lk</a></p>
                            <p><strong>Phone:</strong> +94 11 234 5678</p>
                            <p><strong>Address:</strong> EduConnect SL, Colombo, Sri Lanka</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cookie Settings Button -->
    <div class="cookie-float-btn" id="cookieSettingsBtn">
        <i class="fas fa-cookie-bite"></i>
    </div>

    <!-- Cookie Settings Panel -->
    <div class="cookie-settings" id="cookieSettingsPanel">
        <div class="cookie-settings-header">
            <h3>Cookie Preferences</h3>
            <button class="close-settings" id="closeSettings">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p>Manage your cookie preferences. You can enable or disable different types of cookies below.</p>
        
        <div class="cookie-toggle">
            <div class="toggle-label">
                <div class="toggle-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="toggle-text">
                    <h4>Essential Cookies</h4>
                    <p>Required for basic site functionality</p>
                </div>
            </div>
            <label class="switch">
                <input type="checkbox" checked disabled>
                <span class="slider"></span>
            </label>
        </div>
        
        <div class="cookie-toggle">
            <div class="toggle-label">
                <div class="toggle-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="toggle-text">
                    <h4>Analytics Cookies</h4>
                    <p>Help us improve our website</p>
                </div>
            </div>
            <label class="switch">
                <input type="checkbox" checked id="analyticsToggle">
                <span class="slider"></span>
            </label>
        </div>
        
        <div class="cookie-toggle">
            <div class="toggle-label">
                <div class="toggle-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="toggle-text">
                    <h4>Functional Cookies</h4>
                    <p>Remember your preferences</p>
                </div>
            </div>
            <label class="switch">
                <input type="checkbox" checked id="functionalToggle">
                <span class="slider"></span>
            </label>
        </div>
        
        <div class="cookie-toggle">
            <div class="toggle-label">
                <div class="toggle-icon">
                    <i class="fas fa-ad"></i>
                </div>
                <div class="toggle-text">
                    <h4>Advertising Cookies</h4>
                    <p>Show relevant content and ads</p>
                </div>
            </div>
            <label class="switch">
                <input type="checkbox" id="advertisingToggle">
                <span class="slider"></span>
            </label>
        </div>
        
        <div class="cookie-actions">
            <button class="btn-save" id="saveSettings">Save Preferences</button>
            <button class="btn-cancel" id="cancelSettings">Cancel</button>
        </div>
    </div>
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
    <script>
        // Cookie Settings Panel Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const cookieSettingsBtn = document.getElementById('cookieSettingsBtn');
            const cookieSettingsPanel = document.getElementById('cookieSettingsPanel');
            const closeSettingsBtn = document.getElementById('closeSettings');
            const cancelSettingsBtn = document.getElementById('cancelSettings');
            const saveSettingsBtn = document.getElementById('saveSettings');
            
            // Show cookie settings panel
            cookieSettingsBtn.addEventListener('click', function() {
                cookieSettingsPanel.classList.add('active');
            });
            
            // Hide cookie settings panel
            function hideSettingsPanel() {
                cookieSettingsPanel.classList.remove('active');
            }
            
            closeSettingsBtn.addEventListener('click', hideSettingsPanel);
            cancelSettingsBtn.addEventListener('click', hideSettingsPanel);
            
            // Save cookie preferences
            saveSettingsBtn.addEventListener('click', function() {
                const analyticsEnabled = document.getElementById('analyticsToggle').checked;
                const functionalEnabled = document.getElementById('functionalToggle').checked;
                const advertisingEnabled = document.getElementById('advertisingToggle').checked;
                
                // In a real implementation, you would set cookies based on these preferences
                // For this example, we'll just show an alert
                alert(`Cookie preferences saved!\nAnalytics: ${analyticsEnabled ? 'Enabled' : 'Disabled'}\nFunctional: ${functionalEnabled ? 'Enabled' : 'Disabled'}\nAdvertising: ${advertisingEnabled ? 'Enabled' : 'Disabled'}`);
                
                hideSettingsPanel();
            });
            
            // Close panel when clicking outside
            document.addEventListener('click', function(event) {
                if (cookieSettingsPanel.classList.contains('active') && 
                    !cookieSettingsPanel.contains(event.target) && 
                    !cookieSettingsBtn.contains(event.target)) {
                    hideSettingsPanel();
                }
            });
        });
    </script>
</body>
</html>