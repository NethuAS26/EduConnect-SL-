<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universities - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="universities.css">
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
                        <a href="universities.php" class="nav-link active">Universities</a>
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
                <h1>Universities in Sri Lanka</h1>
                <p>Discover top-ranked universities and institutions offering world-class education</p>
            </div>
        </div>
    </section>

    <!-- University Type Tabs -->
    <section class="university-tabs">
        <div class="container">
            <div class="tabs-container">
                <button class="tab-btn active" data-type="all">All Universities</button>
                <button class="tab-btn" data-type="private">Private Universities</button>
                <button class="tab-btn" data-type="government">Government Universities</button>
            </div>
        </div>
    </section>

    <!-- Universities Grid -->
    <section class="universities-section">
        <div class="container">
            <div class="universities-grid">
                <!-- ICBT Campus -->
                <div class="university-card" id="icbt" data-type="private">
                    <div class="university-header">
                        <div class="university-logo">
                            <img src="LogoICBT.png" alt="ICBT Campus Logo">
                        </div>
                        <div class="university-badge private">Private</div>
                    </div>
                    
                    <div class="university-content">
                        <h2>ICBT Campus</h2>
                        <p class="university-description">One of Sri Lanka's leading private higher education institutions offering diverse programs in Business, IT, Engineering, and more.</p>
                        
                        <div class="university-stats">
                            <div class="stat-item">
                                <div class="stat-number">25+</div>
                                <div class="stat-label">Years of Excellence</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50+</div>
                                <div class="stat-label">Programs Offered</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">15,000+</div>
                                <div class="stat-label">Alumni</div>
                            </div>
                        </div>
                        
                        <div class="university-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Multiple campuses: Colombo, Kandy, Kurunegala, Galle, Jaffna</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-star"></i>
                                <span>4.7/5 Rating</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Certificate to PhD levels</span>
                            </div>
                        </div>
                        
                        <div class="university-actions">
                            <a href="courses.php?university=icbt" class="btn btn-primary">View Courses</a>
                            <a href="contact.php?university=icbt" class="btn btn-outline">Contact</a>
                        </div>
                    </div>
                </div>

                <!-- NIBM -->
                <div class="university-card" id="nibm" data-type="private">
                    <div class="university-header">
                        <div class="university-logo">
                            <img src="LogoNibm.png" alt="NIBM Logo">
                        </div>
                        <div class="university-badge private">Private</div>
                    </div>
                    
                    <div class="university-content">
                        <h2>National Institute of Business Management (NIBM)</h2>
                        <p class="university-description">Premier business and management education institution offering quality programs in business, technology, and management fields.</p>
                        
                        <div class="university-stats">
                            <div class="stat-item">
                                <div class="stat-number">30+</div>
                                <div class="stat-label">Years of Excellence</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">40+</div>
                                <div class="stat-label">Programs Offered</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">12,000+</div>
                                <div class="stat-label">Alumni</div>
                            </div>
                        </div>
                        
                        <div class="university-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Main Campus: Colombo</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-star"></i>
                                <span>4.6/5 Rating</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Diploma to Masters levels</span>
                            </div>
                        </div>
                        
                        <div class="university-actions">
                            <a href="courses.php?university=nibm" class="btn btn-primary">View Courses</a>
                            <a href="contact.php?university=nibm" class="btn btn-outline">Contact</a>
                        </div>
                    </div>
                </div>

                <!-- University of Peradeniya -->
                <div class="university-card" id="peradeniya" data-type="government">
                    <div class="university-header">
                        <div class="university-logo">
                            <img src="LogoPeradeniya.png" alt="University of Peradeniya Logo">
                        </div>
                        <div class="university-badge government">Government</div>
                    </div>
                    
                    <div class="university-content">
                        <h2>University of Peradeniya</h2>
                        <p class="university-description">One of the most prestigious government universities in Sri Lanka, known for its academic excellence, research, and beautiful campus.</p>
                        
                        <div class="university-stats">
                            <div class="stat-item">
                                <div class="stat-number">70+</div>
                                <div class="stat-label">Years of Excellence</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">80+</div>
                                <div class="stat-label">Programs Offered</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">25,000+</div>
                                <div class="stat-label">Alumni</div>
                            </div>
                        </div>
                        
                        <div class="university-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Main Campus: Peradeniya, Kandy</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-star"></i>
                                <span>4.8/5 Rating</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Undergraduate to PhD levels</span>
                            </div>
                        </div>
                        
                        <div class="university-actions">
                            <a href="courses.php?university=peradeniya" class="btn btn-primary">View Courses</a>
                            <a href="contact.php?university=peradeniya" class="btn btn-outline">Contact</a>
                        </div>
                    </div>
                </div>

                <!-- University of Moratuwa -->
                <div class="university-card" id="moratuwa" data-type="government">
                    <div class="university-header">
                        <div class="university-logo">
                            <img src="LogoMoratuwa.png" alt="University of Moratuwa Logo">
                        </div>
                        <div class="university-badge government">Government</div>
                    </div>
                    
                    <div class="university-content">
                        <h2>University of Moratuwa</h2>
                        <p class="university-description">Premier engineering and technology university in Sri Lanka, offering world-class education in engineering, architecture, and technology.</p>
                        
                        <div class="university-stats">
                            <div class="stat-item">
                                <div class="stat-number">50+</div>
                                <div class="stat-label">Years of Excellence</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">60+</div>
                                <div class="stat-label">Programs Offered</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">20,000+</div>
                                <div class="stat-label">Alumni</div>
                            </div>
                        </div>
                        
                        <div class="university-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Main Campus: Moratuwa, Colombo</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-star"></i>
                                <span>4.9/5 Rating</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Undergraduate to PhD levels</span>
                            </div>
                        </div>
                        
                        <div class="university-actions">
                            <a href="courses.php?university=moratuwa" class="btn btn-primary">View Courses</a>
                            <a href="contact.php?university=moratuwa" class="btn btn-outline">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Section -->
    <section class="comparison-section">
        <div class="container">
            <div class="section-header">
                <h2>University Comparison</h2>
                <p>Compare key aspects of our featured universities</p>
            </div>
            
            <div class="comparison-table-container">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>ICBT Campus</th>
                            <th>NIBM</th>
                            <th>University of Peradeniya</th>
                            <th>University of Moratuwa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Type</td>
                            <td><span class="badge private">Private</span></td>
                            <td><span class="badge private">Private</span></td>
                            <td><span class="badge government">Government</span></td>
                            <td><span class="badge government">Government</span></td>
                        </tr>
                        <tr>
                            <td>Established</td>
                            <td>1999</td>
                            <td>1994</td>
                            <td>1942</td>
                            <td>1972</td>
                        </tr>
                        <tr>
                            <td>Main Location</td>
                            <td>Colombo</td>
                            <td>Colombo</td>
                            <td>Peradeniya</td>
                            <td>Moratuwa</td>
                        </tr>
                        <tr>
                            <td>Programs Offered</td>
                            <td>50+</td>
                            <td>40+</td>
                            <td>80+</td>
                            <td>60+</td>
                        </tr>
                        <tr>
                            <td>Student Population</td>
                            <td>15,000+</td>
                            <td>12,000+</td>
                            <td>25,000+</td>
                            <td>20,000+</td>
                        </tr>
                        <tr>
                            <td>Average Rating</td>
                            <td>4.7/5</td>
                            <td>4.6/5</td>
                            <td>4.8/5</td>
                            <td>4.9/5</td>
                        </tr>
                        <tr>
                            <td>Fee Range</td>
                            <td>Rs. 200K - 800K</td>
                            <td>Rs. 150K - 750K</td>
                            <td>Rs. 50K - 200K</td>
                            <td>Rs. 80K - 250K</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="quick-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Top Universities</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">230+</div>
                    <div class="stat-label">Programs Combined</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">72,000+</div>
                    <div class="stat-label">Total Alumni</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4.8</div>
                    <div class="stat-label">Average Rating</div>
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
    <script src="universities.js"></script>
    
    <script>
        // Tab functionality for university types with smooth transitions
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const universityCards = document.querySelectorAll('.university-card');
            
            // Add entrance animations for university cards
            universityCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    const type = this.getAttribute('data-type');
                    
                    // Show/hide university cards based on type with animation
                    universityCards.forEach((card, index) => {
                        if (type === 'all' || card.getAttribute('data-type') === type) {
                            card.style.display = 'block';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';
                            
                            setTimeout(() => {
                                card.style.transition = 'all 0.4s ease-out';
                                card.style.opacity = '1';
                                card.style.transform = 'scale(1)';
                            }, index * 100);
                        } else {
                            card.style.transition = 'all 0.3s ease-in';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';
                            
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
