<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Reviews - EduConnect SL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reviews.css">
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
                        <a href="reviews.php" class="nav-link active">Reviews</a>
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
                <h1>Student Reviews & Ratings</h1>
                <p>Read authentic reviews from students and share your own experiences</p>
            </div>
        </div>
    </section>

    <!-- Review Stats -->
    <section class="review-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">4.8</div>
                    <div class="stat-label">Average Rating</div>
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1,247</div>
                    <div class="stat-label">Total Reviews</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Universities</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">89%</div>
                    <div class="stat-label">Would Recommend</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Filters -->
    <section class="review-filters">
        <div class="container">
            <div class="filters-container">
                <div class="filter-group">
                    <label for="universityFilter">University</label>
                    <select id="universityFilter">
                        <option value="all">All Universities</option>
                        <option value="icbt">ICBT Campus</option>
                        <option value="nibm">NIBM</option>
                        <option value="peradeniya">University of Peradeniya</option>
                        <option value="moratuwa">University of Moratuwa</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="ratingFilter">Rating</label>
                    <select id="ratingFilter">
                        <option value="all">All Ratings</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4+ Stars</option>
                        <option value="3">3+ Stars</option>
                        <option value="2">2+ Stars</option>
                        <option value="1">1+ Star</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sortFilter">Sort By</label>
                    <select id="sortFilter">
                        <option value="recent">Most Recent</option>
                        <option value="rating">Highest Rating</option>
                        <option value="helpful">Most Helpful</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>
                
                <button class="btn btn-primary" id="addReviewBtn">
                    <i class="fas fa-plus"></i>
                    Add Review
                </button>
            </div>
        </div>
    </section>

    <!-- Reviews Grid -->
    <section class="reviews-section">
        <div class="container">
            <div class="reviews-grid" id="reviewsGrid">
                <!-- Reviews will be dynamically loaded here -->
            </div>
            
            <div class="load-more-container">
                <button class="btn btn-outline" id="loadMoreBtn">
                    <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                    Load More Reviews
                </button>
            </div>
        </div>
    </section>

    <!-- Add Review Modal -->
    <div class="modal" id="reviewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Write a Review</h2>
                <button class="modal-close" id="closeReviewModal">&times;</button>
            </div>
            
            <form class="review-form" id="reviewForm" method="POST" action="process_review.php">
                <div class="form-group">
                    <label for="reviewUniversity">University *</label>
                    <select id="reviewUniversity" name="university" required>
                        <option value="">Select University</option>
                        <option value="icbt">ICBT Campus</option>
                        <option value="nibm">NIBM</option>
                        <option value="peradeniya">University of Peradeniya</option>
                        <option value="moratuwa">University of Moratuwa</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="reviewCourse">Course (Optional)</label>
                    <input type="text" id="reviewCourse" name="course" placeholder="e.g., BSc Computer Science">
                </div>
                
                <div class="form-group">
                    <label for="reviewRating">Rating *</label>
                    <div class="rating-input">
                        <input type="radio" name="rating" value="5" id="star5" required>
                        <label for="star5"><i class="fas fa-star"></i></label>
                        <input type="radio" name="rating" value="4" id="star4">
                        <label for="star4"><i class="fas fa-star"></i></label>
                        <input type="radio" name="rating" value="3" id="star3">
                        <label for="star3"><i class="fas fa-star"></i></label>
                        <input type="radio" name="rating" value="2" id="star2">
                        <label for="star2"><i class="fas fa-star"></i></label>
                        <input type="radio" name="rating" value="1" id="star1">
                        <label for="star1"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reviewRecommend">Would you recommend this university? *</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="recommend" value="yes" required>
                            <span>Yes, I would recommend</span>
                        </label>
                        <label>
                            <input type="radio" name="recommend" value="no">
                            <span>No, I would not recommend</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" id="cancelReview">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review Detail Modal -->
    <div class="modal" id="reviewDetailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="detailReviewTitle">Review Details</h2>
                <button class="modal-close" id="closeDetailModal">&times;</button>
            </div>
            
            <div class="review-detail-content" id="reviewDetailContent">
                <!-- Review details will be populated here -->
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <section class="quick-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">15,000+</div>
                    <div class="stat-label">Students Helped</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Verified Reviews</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support Available</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4.9</div>
                    <div class="stat-label">Platform Rating</div>
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
    <script src="reviews.js"></script>
</body>
</html>
