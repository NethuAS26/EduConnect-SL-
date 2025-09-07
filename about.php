<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                        <a href="about.php" class="nav-link active">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.php" class="nav-link">Contact</a>
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



    <!-- Website Overview Section -->
    <section class="overview-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Platform Overview</div>
                <h2>Website Overview</h2>
                <p>Connecting students with the best higher education opportunities across Sri Lanka</p>
            </div>
            <div class="overview-content">
                <div class="overview-text">
                    <div class="text-block">
                        <h3>Bridging the Gap</h3>
                        <p>EduConnect SL is Sri Lanka's premier platform that bridges the gap between students and educational institutions. We understand that choosing the right course and university is one of the most important decisions in a student's life.</p>
                    </div>
                    <div class="text-block">
                        <h3>Simplified Selection Process</h3>
                        <p>Our comprehensive platform simplifies the complex process of course selection by providing detailed information, unbiased comparisons, and personalized guidance. Whether you're an A/L student looking for undergraduate programs or a working professional seeking career advancement, we're here to help you make informed decisions.</p>
                    </div>
                </div>
                <div class="overview-image">
                    <div class="image-container">
                        <img src="img/aboutpage.jpg" alt="Students celebrating graduation - Representing Higher Education Excellence in Sri Lanka">
                        <div class="image-overlay">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Universities Showcase -->
    <section class="universities-showcase">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Partner Institutions</div>
                <h2>Featured Universities</h2>
                <p>Discover the leading educational institutions we partner with</p>
            </div>
            <div class="universities-grid">
                <div class="university-card">
                    <div class="university-image">
                        <img src="ICBT_Campus.png" alt="ICBT Campus">
                        <div class="university-badge">Private University</div>
                    </div>
                    <div class="university-content">
                        <h3>ICBT Campus</h3>
                        <p>One of Sri Lanka's largest private higher education institutions, offering a wide range of local and international degree programs.</p>
                        <ul class="university-features">
                            <li><i class="fas fa-check-circle"></i> International degree pathways</li>
                            <li><i class="fas fa-check-circle"></i> Industry-focused curriculum</li>
                            <li><i class="fas fa-check-circle"></i> Multiple campus locations</li>
                        </ul>
                        <div class="university-cta">
                            <a href="universities.php" class="btn btn-outline">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="university-card">
                    <div class="university-image">
                        <img src="NIBM_Campus.png" alt="NIBM">
                        <div class="university-badge">Private University</div>
                    </div>
                    <div class="university-content">
                        <h3>NIBM</h3>
                        <p>National Institute of Business Management specializing in business and IT programs with industry partnerships.</p>
                        <ul class="university-features">
                            <li><i class="fas fa-check-circle"></i> Specialized in Business & IT</li>
                            <li><i class="fas fa-check-circle"></i> Professional certifications</li>
                            <li><i class="fas fa-check-circle"></i> Industry partnerships</li>
                        </ul>
                        <div class="university-cta">
                            <a href="universities.php" class="btn btn-outline">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="university-card">
                    <div class="university-image">
                        <img src="Peradeniya_Campus.png" alt="University of Peradeniya">
                        <div class="university-badge">Government University</div>
                    </div>
                    <div class="university-content">
                        <h3>University of Peradeniya</h3>
                        <p>One of Sri Lanka's most prestigious universities known for high academic standards across diverse disciplines.</p>
                        <ul class="university-features">
                            <li><i class="fas fa-check-circle"></i> Research-intensive programs</li>
                            <li><i class="fas fa-check-circle"></i> Historic prestigious institution</li>
                            <li><i class="fas fa-check-circle"></i> Comprehensive facilities</li>
                        </ul>
                        <div class="university-cta">
                            <a href="universities.php" class="btn btn-outline">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="university-card">
                    <div class="university-image">
                        <img src="Moratuwa_Campus.png" alt="University of Moratuwa">
                        <div class="university-badge">Government University</div>
                    </div>
                    <div class="university-content">
                        <h3>University of Moratuwa</h3>
                        <p>Center of excellence for engineering, architecture, and technology education in South Asia.</p>
                        <ul class="university-features">
                            <li><i class="fas fa-check-circle"></i> Engineering & Technology focus</li>
                            <li><i class="fas fa-check-circle"></i> Industry collaboration</li>
                            <li><i class="fas fa-check-circle"></i> Innovation and research</li>
                        </ul>
                        <div class="university-cta">
                            <a href="universities.php" class="btn btn-outline">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="vision-mission-section">
        <div class="container">
            <div class="vm-grid">
                <div class="vision-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>Our Vision</h3>
                    </div>
                    <p>To become Sri Lanka's most trusted and comprehensive educational platform, empowering every student to make informed decisions about their higher education journey and ultimately contribute to the nation's intellectual and economic growth.</p>
                    <div class="card-footer">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="mission-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Our Mission</h3>
                    </div>
                    <p>To provide students with accurate, up-to-date, and reliable course information through our comprehensive platform. We aim to simplify the course selection process by offering detailed comparisons, authentic student reviews, and expert guidance.</p>
                    <div class="card-footer">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Offer Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Our Services</div>
                <h2>What We Offer</h2>
                <p>Comprehensive resources designed to guide your educational journey</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Course Discovery</h3>
                    <p>Browse and search through our extensive database of courses from leading universities across Sri Lanka with advanced filtering options.</p>
                    <div class="feature-link">
                        <a href="courses.php">Explore Courses <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3>University Profiles</h3>
                    <p>Detailed information about universities including ICBT, NIBM, University of Peradeniya, and University of Moratuwa with campus details and programs.</p>
                    <div class="feature-link">
                        <a href="universities.php">View Universities <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Student Reviews</h3>
                    <p>Read authentic reviews and ratings from current and former students to make informed decisions about your education.</p>
                    <div class="feature-link">
                        <a href="reviews.php">Read Reviews <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Student Registration</h3>
                    <p>Create your student account to access personalized course recommendations and track your educational journey.</p>
                    <div class="feature-link">
                        <a href="signup.php">Get Started <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Course Applications</h3>
                    <p>Submit applications directly through our platform for courses at partner universities with streamlined processes.</p>
                    <div class="feature-link">
                        <a href="courses.php">Apply Now <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Inquiry System</h3>
                    <p>Get answers to your questions about courses, universities, and admission processes through our inquiry management system.</p>
                    <div class="feature-link">
                        <a href="contact.php">Ask Questions <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Why EduConnect SL</div>
                <h2>Why Choose Us</h2>
                <p>Discover the advantages of using EduConnect SL over individual university searches</p>
            </div>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Multiple University Access</h3>
                        <p>Access courses and information from ICBT, NIBM, University of Peradeniya, University of Moratuwa, and more in one centralized platform.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Authentic Student Reviews</h3>
                        <p>Read real reviews and ratings from current and former students to make informed decisions about your education.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Direct Application System</h3>
                        <p>Submit course applications directly through our platform without needing to visit individual university websites.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Inquiry Management</h3>
                        <p>Get quick answers to your questions through our integrated inquiry system and direct communication channels.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Target Audience Section -->
    <section class="audience-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">User Benefits</div>
                <h2>Who Can Benefit</h2>
                <p>Our platform is designed for anyone seeking higher education opportunities in Sri Lanka</p>
            </div>
            <div class="audience-grid">
                <div class="audience-card">
                    <div class="audience-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Easy Course Discovery</h3>
                    <p>Find and compare courses from multiple universities in one place with advanced search and filtering options.</p>
                    <div class="audience-cta">
                        <a href="courses.php" class="btn btn-outline">Start Searching</a>
                    </div>
                </div>
                <div class="audience-card">
                    <div class="audience-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Student Community Access</h3>
                    <p>Connect with current students and alumni through our review system to get authentic insights about courses and universities.</p>
                    <div class="audience-cta">
                        <a href="reviews.php" class="btn btn-outline">Join Community</a>
                    </div>
                </div>
                <div class="audience-card">
                    <div class="audience-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Streamlined Applications</h3>
                    <p>Submit course applications directly through our platform with simplified processes and tracking capabilities.</p>
                    <div class="audience-cta">
                        <a href="courses.php" class="btn btn-outline">Apply Now</a>
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
    <script src="about.js"></script>
</body>
</html>
