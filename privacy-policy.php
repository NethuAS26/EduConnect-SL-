<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Privacy Policy Specific Styles */
        
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

    <!-- Privacy Policy Hero Section -->
    <section class="policy-hero">
        <div class="container">
            <div class="policy-hero-content">
                <h1>Privacy Policy</h1>
                <p>Your privacy is important to us. This policy explains how we collect, use, and protect your information to ensure your data remains secure and private.</p>
                <div class="policy-meta">
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        Last updated: December 2024
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-clock"></i>
                        Reading time: 5 minutes
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Policy Overview -->
    <section class="policy-overview">
        <div class="container">
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Data Collection</h3>
                    <p>We collect only necessary information to provide you with the best educational experience</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Data Security</h3>
                    <p>Your information is protected with industry-standard encryption and security measures</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3>Your Rights</h3>
                    <p>You have full control over your data with rights to access, modify, or delete your information</p>
                </div>
                <div class="overview-card">
                    <div class="overview-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Transparency</h3>
                    <p>We're transparent about how we use your data and will notify you of any changes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="policy-content">
        <div class="container">
            <div class="policy-sections">
                <div class="policy-section">
                    <h2>1. Information We Collect</h2>
                    <div class="policy-text">
                        <h3>Personal Information</h3>
                        <p>We collect information you provide directly to us, such as:</p>
                        <ul>
                            <li>Name and contact information (email, phone number)</li>
                            <li>Educational background and interests</li>
                            <li>Account credentials and profile information</li>
                            <li>Communications with universities and our support team</li>
                        </ul>
                        
                        <h3>Automatically Collected Information</h3>
                        <p>When you visit our website, we automatically collect:</p>
                        <ul>
                            <li>Device information (IP address, browser type, operating system)</li>
                            <li>Usage data (pages visited, time spent, search queries)</li>
                            <li>Cookies and similar tracking technologies</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>2. How We Use Your Information</h2>
                    <div class="policy-text">
                        <p>We use the information we collect to:</p>
                        <ul>
                            <li>Provide and improve our educational platform services</li>
                            <li>Connect you with universities and educational institutions</li>
                            <li>Personalize your experience and show relevant content</li>
                            <li>Send you important updates and notifications</li>
                            <li>Respond to your inquiries and provide customer support</li>
                            <li>Analyze usage patterns to improve our services</li>
                            <li>Ensure platform security and prevent fraud</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>3. Information Sharing</h2>
                    <div class="policy-text">
                        <p>We do not sell, trade, or rent your personal information to third parties. We may share your information in the following circumstances:</p>
                        <ul>
                            <li><strong>With Universities:</strong> When you express interest in a course or university, we may share relevant information to facilitate your inquiry</li>
                            <li><strong>Service Providers:</strong> We work with trusted third-party service providers who assist us in operating our platform</li>
                            <li><strong>Legal Requirements:</strong> We may disclose information if required by law or to protect our rights and safety</li>
                            <li><strong>Business Transfers:</strong> In the event of a merger or acquisition, your information may be transferred as part of the business assets</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>4. Data Security</h2>
                    <div class="policy-text">
                        <p>We implement appropriate technical and organizational measures to protect your personal information:</p>
                        <ul>
                            <li>Encryption of data in transit and at rest</li>
                            <li>Regular security assessments and updates</li>
                            <li>Access controls and authentication measures</li>
                            <li>Employee training on data protection</li>
                            <li>Incident response procedures</li>
                        </ul>
                        <p>However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>5. Your Rights and Choices</h2>
                    <div class="policy-text">
                        <p>You have the following rights regarding your personal information:</p>
                        <ul>
                            <li><strong>Access:</strong> Request a copy of the personal information we hold about you</li>
                            <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                            <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                            <li><strong>Portability:</strong> Request a copy of your data in a portable format</li>
                            <li><strong>Objection:</strong> Object to certain types of processing</li>
                            <li><strong>Withdrawal:</strong> Withdraw consent for processing based on consent</li>
                        </ul>
                        <p>To exercise these rights, please contact us at privacy@educonnectsl.lk</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>6. Cookies and Tracking Technologies</h2>
                    <div class="policy-text">
                        <p>We use cookies and similar technologies to:</p>
                        <ul>
                            <li>Remember your preferences and settings</li>
                            <li>Analyze website traffic and usage patterns</li>
                            <li>Provide personalized content and recommendations</li>
                            <li>Improve website functionality and performance</li>
                        </ul>
                        <p>You can control cookie settings through your browser preferences. However, disabling certain cookies may affect website functionality.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>7. Data Retention</h2>
                    <div class="policy-text">
                        <p>We retain your personal information for as long as necessary to:</p>
                        <ul>
                            <li>Provide our services to you</li>
                            <li>Comply with legal obligations</li>
                            <li>Resolve disputes and enforce agreements</li>
                            <li>Improve our services</li>
                        </ul>
                        <p>When we no longer need your information, we will securely delete or anonymize it.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>8. International Data Transfers</h2>
                    <div class="policy-text">
                        <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information during such transfers, including:</p>
                        <ul>
                            <li>Standard contractual clauses</li>
                            <li>Adequacy decisions</li>
                            <li>Other appropriate safeguards</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>9. Children's Privacy</h2>
                    <div class="policy-text">
                        <p>Our services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If we become aware that we have collected such information, we will take steps to delete it promptly.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>10. Changes to This Policy</h2>
                    <div class="policy-text">
                        <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by:</p>
                        <ul>
                            <li>Posting the updated policy on our website</li>
                            <li>Sending you an email notification</li>
                            <li>Displaying a notice on our platform</li>
                        </ul>
                        <p>Your continued use of our services after such changes constitutes acceptance of the updated policy.</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>11. Contact Us</h2>
                    <div class="policy-text">
                        <p>If you have any questions about this Privacy Policy or our data practices, please contact us:</p>
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
