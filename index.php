<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduConnect SL - Find Your Perfect University Course in Sri Lanka</title>
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
                        <a href="index.php" class="nav-link active">Home</a>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span>🎓 Your Gateway to Quality Education</span>
                </div>
                <h1 class="hero-title">Find Your Perfect University Course in Sri Lanka</h1>
                <p class="hero-subtitle">Discover thousands of courses from top universities across Sri Lanka. Compare programs, read student reviews, and make informed decisions about your education journey.</p>
                
                <div class="search-container">
                    <div class="search-box">
                        <div class="search-input-group">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search for courses, universities, or subjects..." id="searchInput">
                        </div>
                        <button class="btn btn-primary search-btn" id="searchBtn">Search</button>
                    </div>
                    
                    <div class="search-filters">
                        <a href="compare-courses.php" class="btn btn-outline compare-courses-btn">
                            <i class="fas fa-table"></i>
                            Compare Courses
                        </a>
                        <a href="universities.php" class="btn btn-outline">
                            <i class="fas fa-university"></i>
                            Compare Universities
                        </a>
                        <a href="courses.php" class="btn btn-outline">Browse All Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Announcements Section -->
    <section class="announcements-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </div>
                <h2>Latest Updates</h2>
                <p>Stay updated with important news and updates from our partner institutions</p>
            </div>
            
            <div id="announcementsContainer" class="announcements-grid">
                <div class="loading-announcements">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading announcements...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Universities Section -->
    <section class="featured-universities">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-university"></i>
                    <span>Universities</span>
                </div>
                <h2>Featured Institutions</h2>
                <p>Discover top-ranked universities and institutions across Sri Lanka</p>
            </div>
            
            <div class="universities-grid">
                <div class="university-card">
                    <div class="university-image">
                        <img src="ICBT_Campus.png" alt="ICBT Campus">
                        <div class="university-badge private">Private</div>
                    </div>
                    <div class="university-content">
                        <h3>ICBT Campus</h3>
                        <p>One of Sri Lanka's leading private higher education institutions offering diverse programs in Business, IT, Engineering, and more.</p>
                        <div class="university-stats">
                            <span><i class="fas fa-map-marker-alt"></i> Colombo, Kandy, Kurunegala</span>
                            <span><i class="fas fa-star"></i> 4.7</span>
                        </div>
                        <a href="universities.php#icbt" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
                
                <div class="university-card">
                    <div class="university-image">
                        <img src="NIBM_Campus.png" alt="NIBM">
                        <div class="university-badge private">Private</div>
                    </div>
                    <div class="university-content">
                        <h3>NIBM</h3>
                        <p>National Institute of Business Management offering quality education in business, management, and technology fields.</p>
                        <div class="university-stats">
                            <span><i class="fas fa-map-marker-alt"></i> Colombo</span>
                            <span><i class="fas fa-star"></i> 4.6</span>
                        </div>
                        <a href="universities.php#nibm" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
                
                <div class="university-card">
                    <div class="university-image">
                        <img src="Peradeniya_Campus.png" alt="University of Peradeniya">
                        <div class="university-badge government">Government</div>
                    </div>
                    <div class="university-content">
                        <h3>University of Peradeniya</h3>
                        <p>One of the most prestigious government universities in Sri Lanka, known for its academic excellence and research.</p>
                        <div class="university-stats">
                            <span><i class="fas fa-map-marker-alt"></i> Peradeniya</span>
                            <span><i class="fas fa-star"></i> 4.8</span>
                        </div>
                        <a href="universities.php#peradeniya" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
                
                <div class="university-card">
                    <div class="university-image">
                        <img src="Moratuwa_Campus.png" alt="University of Moratuwa">
                        <div class="university-badge government">Government</div>
                    </div>
                    <div class="university-content">
                        <h3>University of Moratuwa</h3>
                        <p>Premier engineering and technology university in Sri Lanka, offering world-class education in engineering and architecture.</p>
                        <div class="university-stats">
                            <span><i class="fas fa-map-marker-alt"></i> Moratuwa</span>
                            <span><i class="fas fa-star"></i> 4.9</span>
                        </div>
                        <a href="universities.php#moratuwa" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Quick Links Section -->
    <section class="quick-links">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-rocket"></i>
                    <span>Quick Access</span>
                </div>
                <h2>Get Started</h2>
                <p>Everything you need to start your educational journey</p>
            </div>
            
            <div class="quick-links-grid">
                <div class="quick-link-card">
                    <div class="card-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Course Finder</h3>
                    <p>Search and filter courses by university, level, duration, and more</p>
                    <a href="courses.php" class="btn btn-outline">Find Courses</a>
                </div>
                
                <div class="quick-link-card">
                    <div class="card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Student Reviews</h3>
                    <p>Read authentic reviews from students who have experienced these programs</p>
                    <a href="reviews.php" class="btn btn-outline">Read Reviews</a>
                </div>
                
                <div class="quick-link-card">
                    <div class="card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Send Inquiries</h3>
                    <p>Get in touch with universities directly through our inquiry system</p>
                    <a href="contact.php" class="btn btn-outline">Contact Universities</a>
                </div>
                
                <div class="quick-link-card">
                    <div class="card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Student Portal</h3>
                    <p>Create an account to save courses, track inquiries, and manage your profile</p>
                    <a href="signup.php" class="btn btn-outline">Join Now</a>
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
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            
            // Search data - mapping search terms to page sections
            const searchData = {
                // Universities
                'icbt': { page: 'universities.php', section: 'icbt' },
                'icbt campus': { page: 'universities.php', section: 'icbt' },
                'international college of business and technology': { page: 'universities.php', section: 'icbt' },
                'icbt campus sri lanka': { page: 'universities.php', section: 'icbt' },
                
                'nibm': { page: 'universities.php', section: 'nibm' },
                'national institute of business management': { page: 'universities.php', section: 'nibm' },
                'nibm sri lanka': { page: 'universities.php', section: 'nibm' },
                
                'peradeniya': { page: 'universities.php', section: 'peradeniya' },
                'university of peradeniya': { page: 'universities.php', section: 'peradeniya' },
                'peradeniya university': { page: 'universities.php', section: 'peradeniya' },
                
                'moratuwa': { page: 'universities.php', section: 'moratuwa' },
                'university of moratuwa': { page: 'universities.php', section: 'moratuwa' },
                'moratuwa university': { page: 'universities.php', section: 'moratuwa' },
                
                // Course categories
                'business': { page: 'courses.php', section: 'universitiesSection' },
                'management': { page: 'courses.php', section: 'universitiesSection' },
                'engineering': { page: 'courses.php', section: 'universitiesSection' },
                'it': { page: 'courses.php', section: 'universitiesSection' },
                'information technology': { page: 'courses.php', section: 'universitiesSection' },
                'computer science': { page: 'courses.php', section: 'universitiesSection' },
                'psychology': { page: 'courses.php', section: 'universitiesSection' },
                'law': { page: 'courses.php', section: 'universitiesSection' },
                'english': { page: 'courses.php', section: 'universitiesSection' },
                'science': { page: 'courses.php', section: 'universitiesSection' },
                'construction': { page: 'courses.php', section: 'universitiesSection' },
                'quantity surveying': { page: 'courses.php', section: 'universitiesSection' },
                'marketing': { page: 'courses.php', section: 'universitiesSection' },
                'digital marketing': { page: 'courses.php', section: 'universitiesSection' },
                'logistics': { page: 'courses.php', section: 'universitiesSection' },
                'supply chain': { page: 'courses.php', section: 'universitiesSection' },
                'tourism': { page: 'courses.php', section: 'universitiesSection' },
                'hospitality': { page: 'courses.php', section: 'universitiesSection' },
                'robotics': { page: 'courses.php', section: 'universitiesSection' },
                'drones': { page: 'courses.php', section: 'universitiesSection' },
                'biomedical': { page: 'courses.php', section: 'universitiesSection' },
                'data science': { page: 'courses.php', section: 'universitiesSection' },
                'civil engineering': { page: 'courses.php', section: 'universitiesSection' },
                'automotive engineering': { page: 'courses.php', section: 'universitiesSection' },
                'network technology': { page: 'courses.php', section: 'universitiesSection' },
                'cyber security': { page: 'courses.php', section: 'universitiesSection' },
                'software engineering': { page: 'courses.php', section: 'universitiesSection' },
                'computing': { page: 'courses.php', section: 'universitiesSection' },
                'project management': { page: 'courses.php', section: 'universitiesSection' },
                'accounting': { page: 'courses.php', section: 'universitiesSection' },
                'financial': { page: 'courses.php', section: 'universitiesSection' },
                'applied psychology': { page: 'courses.php', section: 'universitiesSection' },
                'international relations': { page: 'courses.php', section: 'universitiesSection' },
                'international business': { page: 'courses.php', section: 'universitiesSection' },
                'human resource': { page: 'courses.php', section: 'universitiesSection' },
                'hr': { page: 'courses.php', section: 'universitiesSection' },
                'strategic management': { page: 'courses.php', section: 'universitiesSection' },
                'leadership': { page: 'courses.php', section: 'universitiesSection' },
                'logistics management': { page: 'courses.php', section: 'universitiesSection' },
                'supply chain management': { page: 'courses.php', section: 'universitiesSection' },
                'tourism management': { page: 'courses.php', section: 'universitiesSection' },
                'hospitality management': { page: 'courses.php', section: 'universitiesSection' },
                'mechanical engineering': { page: 'courses.php', section: 'universitiesSection' },
                'electrical engineering': { page: 'courses.php', section: 'universitiesSection' },
                'civil engineering': { page: 'courses.php', section: 'universitiesSection' },
                'automotive engineering': { page: 'courses.php', section: 'universitiesSection' },
                'network engineering': { page: 'courses.php', section: 'universitiesSection' },
                'cybersecurity': { page: 'courses.php', section: 'universitiesSection' },
                'data analytics': { page: 'courses.php', section: 'universitiesSection' },
                'artificial intelligence': { page: 'courses.php', section: 'universitiesSection' },
                'ai': { page: 'courses.php', section: 'universitiesSection' },
                'machine learning': { page: 'courses.php', section: 'universitiesSection' },
                'web development': { page: 'courses.php', section: 'universitiesSection' },
                'mobile development': { page: 'courses.php', section: 'universitiesSection' },
                'game development': { page: 'courses.php', section: 'universitiesSection' },
                'graphic design': { page: 'courses.php', section: 'universitiesSection' },
                'multimedia': { page: 'courses.php', section: 'universitiesSection' },
                'animation': { page: 'courses.php', section: 'universitiesSection' },
                'film production': { page: 'courses.php', section: 'universitiesSection' },
                'journalism': { page: 'courses.php', section: 'universitiesSection' },
                'media studies': { page: 'courses.php', section: 'universitiesSection' },
                'communication': { page: 'courses.php', section: 'universitiesSection' },
                'public relations': { page: 'courses.php', section: 'universitiesSection' },
                'pr': { page: 'courses.php', section: 'universitiesSection' },
                
                // Study levels
                'undergraduate': { page: 'courses.php', section: 'universitiesSection' },
                'postgraduate': { page: 'courses.php', section: 'universitiesSection' },
                'masters': { page: 'courses.php', section: 'universitiesSection' },
                'master': { page: 'courses.php', section: 'universitiesSection' },
                'mba': { page: 'courses.php', section: 'universitiesSection' },
                'diploma': { page: 'courses.php', section: 'universitiesSection' },
                'certificate': { page: 'courses.php', section: 'universitiesSection' },
                'foundation': { page: 'courses.php', section: 'universitiesSection' },
                'advanced diploma': { page: 'courses.php', section: 'universitiesSection' },
                'degree': { page: 'courses.php', section: 'universitiesSection' },
                'bachelor': { page: 'courses.php', section: 'universitiesSection' },
                'bachelors': { page: 'courses.php', section: 'universitiesSection' },
                'llb': { page: 'courses.php', section: 'universitiesSection' },
                'llm': { page: 'courses.php', section: 'universitiesSection' },
                'msc': { page: 'courses.php', section: 'universitiesSection' },
                'bsc': { page: 'courses.php', section: 'universitiesSection' },
                'ba': { page: 'courses.php', section: 'universitiesSection' },
                'phd': { page: 'courses.php', section: 'universitiesSection' },
                'doctorate': { page: 'courses.php', section: 'universitiesSection' },
                'doctor of philosophy': { page: 'courses.php', section: 'universitiesSection' },
                'higher diploma': { page: 'courses.php', section: 'universitiesSection' },
                'professional diploma': { page: 'courses.php', section: 'universitiesSection' },
                'short course': { page: 'courses.php', section: 'universitiesSection' },
                'short courses': { page: 'courses.php', section: 'universitiesSection' },
                'workshop': { page: 'courses.php', section: 'universitiesSection' },
                'workshops': { page: 'courses.php', section: 'universitiesSection' },
                'training': { page: 'courses.php', section: 'universitiesSection' },
                'course': { page: 'courses.php', section: 'universitiesSection' },
                'courses': { page: 'courses.php', section: 'universitiesSection' },
                'program': { page: 'courses.php', section: 'universitiesSection' },
                'programs': { page: 'courses.php', section: 'universitiesSection' },
                'programme': { page: 'courses.php', section: 'universitiesSection' },
                'programmes': { page: 'courses.php', section: 'universitiesSection' }
            };
            
            function performSearch() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                
                if (!searchTerm) {
                    alert('Please enter a search term');
                    return;
                }
                
                // Show loading state
                const originalText = searchBtn.innerHTML;
                searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                searchBtn.disabled = true;
                
                // Find the best match
                let bestMatch = null;
                let bestScore = 0;
                
                for (const [key, value] of Object.entries(searchData)) {
                    const keyLower = key.toLowerCase();
                    let score = 0;
                    
                    // Exact match gets highest score
                    if (keyLower === searchTerm) {
                        score = 100;
                    }
                    // Contains the search term
                    else if (keyLower.includes(searchTerm) || searchTerm.includes(keyLower)) {
                        score = 50;
                    }
                    // Partial match
                    else if (keyLower.split(' ').some(word => searchTerm.includes(word)) || 
                             searchTerm.split(' ').some(word => keyLower.includes(word))) {
                        score = 25;
                    }
                    
                    if (score > bestScore) {
                        bestScore = score;
                        bestMatch = value;
                    }
                }
                
                // Small delay to show loading state
                setTimeout(() => {
                    if (bestMatch && bestScore > 0) {
                        // Navigate to the appropriate page and scroll to section
                        const url = bestMatch.page + (bestMatch.section ? '#' + bestMatch.section : '');
                        window.location.href = url;
                    } else {
                        // If no specific match, go to courses page for general search
                        window.location.href = 'courses.php';
                    }
                }, 500);
            }
            
            // Event listeners
            searchBtn.addEventListener('click', performSearch);
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        });
        
        // Announcement functionality
        function loadAnnouncements() {
            const container = document.getElementById('announcementsContainer');
            
            fetch('get_index_announcements.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.announcements && data.announcements.length > 0) {
                        displayAnnouncements(data.announcements);
                    } else {
                        showNoAnnouncements();
                    }
                })
                .catch(error => {
                    console.error('Error loading announcements:', error);
                    showNoAnnouncements();
                });
        }
        
        function displayAnnouncements(announcements) {
            const container = document.getElementById('announcementsContainer');
            
            const announcementsHTML = announcements.map(announcement => `
                <div class="announcement-card">
                    <div class="announcement-header">
                        <div class="announcement-badge ${getCampusClass(announcement.campus)}">
                            ${announcement.campus}
                        </div>
                        <div class="announcement-date">
                            ${formatDate(announcement.created_at)}
                        </div>
                    </div>
                    <div class="announcement-content">
                        <h3 class="announcement-title">${announcement.title}</h3>
                        <p class="announcement-body">${announcement.body}</p>
                        <div class="announcement-audience">
                            <i class="fas fa-users"></i>
                            <span>${getAudienceText(announcement.audience)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
            
            container.innerHTML = announcementsHTML;
        }
        
        function showNoAnnouncements() {
            const container = document.getElementById('announcementsContainer');
            container.innerHTML = `
                <div class="no-announcements">
                    <i class="fas fa-bell-slash"></i>
                    <p>No announcements at the moment</p>
                    <small>Check back later for updates</small>
                </div>
            `;
        }
        
        function getCampusClass(campus) {
            const campusLower = campus.toLowerCase();
            if (campusLower.includes('icbt')) return 'icbt';
            if (campusLower.includes('nibm')) return 'nibm';
            if (campusLower.includes('peradeniya')) return 'peradeniya';
            if (campusLower.includes('moratuwa')) return 'moratuwa';
            return 'other';
        }
        
        function getAudienceText(audience) {
            switch(audience) {
                case 'all': return 'All Students';
                case 'students': return 'Current Students';
                case 'admins': return 'Administrators';
                default: return audience;
            }
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 1) return 'Today';
            if (diffDays === 2) return 'Yesterday';
            if (diffDays <= 7) return `${diffDays - 1} days ago`;
            
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }
        
        // Load announcements when page loads
        document.addEventListener('DOMContentLoaded', loadAnnouncements);
    </script>
</body>
</html>
