<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - EduConnect SL</title>
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

    <!-- Help Center Hero Section -->
    <section class="help-hero">
        <div class="container">
            <div class="help-hero-content">
                <h1>Help Center</h1>
                <p>Find answers to your questions and get the support you need to make the most of EduConnect SL</p>
                
                <div class="help-search">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search for help topics..." id="helpSearch">
                        <button class="search-btn">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Help Stats -->
    <section class="help-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Help Articles</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support Available</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">< 2hrs</div>
                    <div class="stat-label">Response Time</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Help Categories -->
    <section class="help-categories">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-th-large"></i>
                    <span>Categories</span>
                </div>
                <h2>How Can We Help You?</h2>
                <p>Choose a category to find the information you need quickly</p>
            </div>
            
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Student Guide</h3>
                    <p>Learn how to navigate our platform and find the right courses for your educational journey</p>
                    <div class="category-features">
                        <span class="feature-tag">Getting Started</span>
                        <span class="feature-tag">Platform Navigation</span>
                        <span class="feature-tag">Course Discovery</span>
                    </div>
                    <a href="#student-guide" class="btn btn-outline">Learn More</a>
                </div>
                
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3>University Information</h3>
                    <p>Everything about our partner universities and how to connect with them directly</p>
                    <div class="category-features">
                        <span class="feature-tag">Partner Universities</span>
                        <span class="feature-tag">Contact Information</span>
                        <span class="feature-tag">Application Process</span>
                    </div>
                    <a href="#university-info" class="btn btn-outline">Learn More</a>
                </div>
                
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>Account & Billing</h3>
                    <p>Manage your account, profile settings, and understand our billing policies</p>
                    <div class="category-features">
                        <span class="feature-tag">Account Management</span>
                        <span class="feature-tag">Profile Settings</span>
                        <span class="feature-tag">Billing Support</span>
                    </div>
                    <a href="#account-billing" class="btn btn-outline">Learn More</a>
                </div>
                
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Contact Support</h3>
                    <p>Get in touch with our dedicated support team for personalized assistance</p>
                    <div class="category-features">
                        <span class="feature-tag">Email Support</span>
                        <span class="feature-tag">Phone Support</span>
                        <span class="feature-tag">Live Chat</span>
                    </div>
                    <a href="#contact-support" class="btn btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section" id="faqs">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Find quick answers to common questions</p>
            </div>
            
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
                        <h3>How do I contact universities?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>You can contact universities directly through our inquiry system. Visit the university's page and click on "Send Inquiry" or "Contact University". You can also find contact information in the footer of our website.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Are the course reviews authentic?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, all reviews on our platform are from verified students who have taken these courses. We have a strict verification process to ensure authenticity and prevent fake reviews.</p>
                    </div>
                </div>
                
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
                        <h3>Can I compare different courses?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes! We have a course comparison feature. You can select multiple courses and compare their details side by side, including duration, fees, curriculum, and student reviews.</p>
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
            </div>
        </div>
    </section>

    <!-- Contact Support Section -->
    <section class="contact-support" id="contact-support">
        <div class="container">
            <div class="section-header">
                <h2>Still Need Help?</h2>
                <p>Our support team is here to help you</p>
            </div>
            
            <div class="support-options">
                <div class="support-card">
                    <div class="support-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Support</h3>
                    <p>Send us an email and we'll get back to you within 24 hours</p>
                    <a href="mailto:support@educonnectsl.lk" class="btn btn-primary">Send Email</a>
                </div>
                
                <div class="support-card">
                    <div class="support-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Phone Support</h3>
                    <p>Call us during business hours for immediate assistance</p>
                    <a href="tel:+94112345678" class="btn btn-primary">Call Now</a>
                </div>
                
                <div class="support-card">
                    <div class="support-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Live Chat</h3>
                    <p>Chat with our support team in real-time</p>
                    <button class="btn btn-primary" onclick="openLiveChat()">Start Chat</button>
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
                        <li><a href="help-center.php">Help Center</a></li>
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
            
            // Help Search Functionality
            const helpSearch = document.getElementById('helpSearch');
            const faqItems = document.querySelectorAll('.faq-item');
            
            helpSearch.addEventListener('input', function() {
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
        
        // Live Chat Function (placeholder)
        function openLiveChat() {
            alert('Live chat feature coming soon! Please use email or phone support for now.');
        }
    </script>
</body>
</html>
