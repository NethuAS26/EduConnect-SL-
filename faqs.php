<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
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

    <!-- FAQ Hero Section -->
    <section class="faq-hero">
        <div class="container">
            <div class="faq-hero-content">
                <h1>Find Quick Answers</h1>
                <p>Browse through our most common questions and get instant answers to help you navigate EduConnect SL</p>
                
                <div class="faq-search">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search FAQs..." id="faqSearch">
                        <button class="search-btn">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Stats -->
    <section class="faq-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Common Questions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Categories</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Coverage Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">Instant</div>
                    <div class="stat-label">Answers</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Categories -->
    <section class="faq-categories">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-th-large"></i>
                    <span>Categories</span>
                </div>
                <h2>Browse by Category</h2>
                <p>Select a category to find relevant questions and answers</p>
            </div>
            
            <div class="faq-nav">
                <button class="faq-nav-btn active" data-category="general">
                    <i class="fas fa-info-circle"></i>
                    <span>General</span>
                </button>
                <button class="faq-nav-btn" data-category="courses">
                    <i class="fas fa-search"></i>
                    <span>Courses</span>
                </button>
                <button class="faq-nav-btn" data-category="account">
                    <i class="fas fa-user"></i>
                    <span>Account</span>
                </button>
                <button class="faq-nav-btn" data-category="universities">
                    <i class="fas fa-university"></i>
                    <span>Universities</span>
                </button>
                <button class="faq-nav-btn" data-category="technical">
                    <i class="fas fa-tools"></i>
                    <span>Technical</span>
                </button>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="faq-content">
        <div class="container">
            <!-- General FAQs -->
            <div class="faq-section active" id="general">
                <h2>General Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What is EduConnect SL?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>EduConnect SL is a comprehensive educational platform that connects students with the best universities and courses across Sri Lanka. We help students find, compare, and apply to educational programs that match their career goals.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Is EduConnect SL free to use?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, our platform is completely free to use. You can browse courses, read reviews, and contact universities without any charges. We only charge fees for premium features or direct course registrations.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I get started?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Getting started is easy! Simply create an account, browse our course catalog, and start exploring universities. You can search by subject, location, or university to find programs that interest you.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course FAQs -->
            <div class="faq-section" id="courses">
                <h2>Course Related Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I search for courses?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>You can search for courses using our search bar on the homepage. Enter keywords like course names, subjects, or university names. You can also browse courses by university or use our advanced filters to narrow down your search.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Can I compare different courses?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes! We have a course comparison feature. You can select multiple courses and compare their details side by side, including duration, fees, curriculum, and student reviews.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Are course fees accurate?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We strive to keep all course fees up to date, but we recommend contacting the university directly for the most current pricing information, as fees may change.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account FAQs -->
            <div class="faq-section" id="account">
                <h2>Account & Profile Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I create an account?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Creating an account is simple. Click on "Sign Up" in the top navigation, fill in your details including name, email, and password. Verify your email address and you're ready to start exploring courses!</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I reset my password?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>If you've forgotten your password, click on "Login" and then "Forgot Password". Enter your email address and we'll send you a link to reset your password.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Can I save courses to my profile?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes! Once you're logged in, you can save courses to your profile for later reference. Simply click the "Save" button on any course page.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- University FAQs -->
            <div class="faq-section" id="universities">
                <h2>University Related Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I contact universities?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>You can contact universities directly through our inquiry system. Visit the university's page and click on "Send Inquiry" or "Contact University". You can also find contact information in the footer of our website.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Are all universities verified?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, all universities on our platform are verified and accredited institutions. We maintain strict quality standards and regularly verify the credentials of all partner institutions.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Can I apply directly through EduConnect SL?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Currently, we facilitate the initial contact and inquiry process. For actual applications, you'll need to follow the university's specific application procedures, which we provide information about.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical FAQs -->
            <div class="faq-section" id="technical">
                <h2>Technical Support Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What browsers are supported?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Our platform works best with modern browsers including Chrome, Firefox, Safari, and Edge. We recommend using the latest version of your preferred browser for the best experience.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Is the website mobile-friendly?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes! Our website is fully responsive and optimized for mobile devices. You can browse courses, read reviews, and contact universities from your smartphone or tablet.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I report a technical issue?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>If you encounter any technical issues, please contact our support team via email at support@educonnectsl.lk or call us at +94 11 234 5678. Please include details about the issue and your device/browser information.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2>Still Have Questions?</h2>
                <p>Our support team is here to help you</p>
            </div>
            
            <div class="contact-options">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Support</h3>
                    <p>Get detailed responses within 24 hours</p>
                    <a href="mailto:support@educonnectsl.lk" class="btn btn-primary">Send Email</a>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Phone Support</h3>
                    <p>Speak with our team directly</p>
                    <a href="tel:+94112345678" class="btn btn-primary">Call Now</a>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Help Center</h3>
                    <p>Browse our comprehensive help articles</p>
                    <a href="help-center.php" class="btn btn-primary">Visit Help Center</a>
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
    <script>
        // FAQ Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const icon = this.querySelector('i');
                    
                    // Toggle answer visibility
                    answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
                    
                    // Toggle icon rotation
                    icon.style.transform = icon.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            });
            
            // FAQ Category Navigation
            const faqNavBtns = document.querySelectorAll('.faq-nav-btn');
            const faqSections = document.querySelectorAll('.faq-section');
            
            faqNavBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    
                    // Remove active class from all buttons and sections
                    faqNavBtns.forEach(b => b.classList.remove('active'));
                    faqSections.forEach(s => s.classList.remove('active'));
                    
                    // Add active class to clicked button and corresponding section
                    this.classList.add('active');
                    document.getElementById(category).classList.add('active');
                });
            });
            
            // FAQ Search Functionality
            const faqSearch = document.getElementById('faqSearch');
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                faqItems.forEach(item => {
                    const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
                    const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
                    
                    if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
