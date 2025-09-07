// Reviews Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Starting initialization');
    initializeReviewsPage();
    loadReviews();
    setupEventListeners();
    initializeAnimations();
    console.log('Initialization complete');
});

// Sample review data
const sampleReviews = [
    {
        id: 1,
        university_id: "icbt",
        course_name: "BSc Computer Science",
        rating: 5,
        recommendation: "yes",
        created_at: "2024-01-15"
    },
    {
        id: 2,
        university_id: "nibm",
        course_name: "MBA in Business Management",
        rating: 4,
        recommendation: "yes",
        created_at: "2024-01-10"
    },
    {
        id: 3,
        university_id: "peradeniya",
        course_name: "BSc Engineering",
        rating: 5,
        recommendation: "yes",
        created_at: "2024-01-08"
    },
    {
        id: 4,
        university_id: "moratuwa",
        course_name: "BSc Architecture",
        rating: 5,
        recommendation: "yes",
        created_at: "2024-01-05"
    },
    {
        id: 5,
        university_id: "icbt",
        course_name: "Diploma in Digital Marketing",
        rating: 4,
        recommendation: "yes",
        created_at: "2024-01-02"
    },
    {
        id: 6,
        university_id: "nibm",
        course_name: "BSc Information Technology",
        rating: 4,
        recommendation: "yes",
        created_at: "2023-12-28"
    }
];

// Global variables
let allReviews = [];
let filteredReviews = [];
let currentPage = 1;
const reviewsPerPage = 6;

// Initialize the reviews page
function initializeReviewsPage() {
    // Mobile menu toggle (redundant with main.js but included for self-containment)
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on nav links
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
}

// Load reviews from database
async function loadReviews() {
    console.log('Loading reviews from database...');
    try {
        const response = await fetch('get_public_reviews.php');
        const data = await response.json();
        
        if (data.success) {
            allReviews = data.reviews;
            filteredReviews = [...allReviews];
            console.log('Loaded from database:', allReviews.length, 'reviews');
            
            // Update stats with real data
            updateStatsWithRealData(data.stats);
        } else {
            console.error('Failed to load reviews:', data.error);
            // Fallback to sample data if database fails
            allReviews = sampleReviews;
            filteredReviews = [...allReviews];
        }
    } catch (error) {
        console.error('Error loading reviews:', error);
        // Fallback to sample data if network fails
        allReviews = sampleReviews;
        filteredReviews = [...allReviews];
    }
    
    displayReviews();
    updateStats();
}

// Set up all event listeners
function setupEventListeners() {
    console.log('Setting up event listeners...');
    
    // Filter and sort controls
    const universityFilter = document.getElementById('universityFilter');
    const ratingFilter = document.getElementById('ratingFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    console.log('Filter elements:', { universityFilter, ratingFilter, sortFilter });
    
    if (universityFilter) universityFilter.addEventListener('change', applyFilters);
    if (ratingFilter) ratingFilter.addEventListener('change', applyFilters);
    if (sortFilter) sortFilter.addEventListener('change', applyFilters);
    
    // Add review button
    const addReviewBtn = document.getElementById('addReviewBtn');
    const ctaAddReview = document.getElementById('ctaAddReview');
    
    console.log('Add Review Button:', addReviewBtn);
    console.log('CTA Add Review Button:', ctaAddReview);
    
    if (addReviewBtn) {
        addReviewBtn.addEventListener('click', openReviewModal);
        console.log('Event listener added to Add Review Button');
    } else {
        console.error('Add Review Button not found!');
    }
    
    if (ctaAddReview) {
        ctaAddReview.addEventListener('click', openReviewModal);
        console.log('Event listener added to CTA Add Review Button');
    } else {
        console.error('CTA Add Review Button not found!');
    }
    
    // Modal controls
    const closeReviewModalBtn = document.getElementById('closeReviewModal');
    const closeDetailModalBtn = document.getElementById('closeDetailModal');
    const cancelReviewBtn = document.getElementById('cancelReview');
    
    if (closeReviewModalBtn) closeReviewModalBtn.addEventListener('click', closeReviewModal);
    if (closeDetailModalBtn) closeDetailModalBtn.addEventListener('click', closeReviewDetailModal);
    if (cancelReviewBtn) cancelReviewBtn.addEventListener('click', closeReviewModal);
    
    // Review form submission
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', handleReviewSubmission);
    }
    
    // Load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMoreReviews);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal')) {
            closeReviewModal();
        }
    });
}

// Apply filters and sorting
async function applyFilters() {
    const universityFilter = document.getElementById('universityFilter').value;
    const ratingFilter = document.getElementById('ratingFilter').value;
    const sortFilter = document.getElementById('sortFilter').value;
    
    console.log('Applying filters:', { universityFilter, ratingFilter, sortFilter });
    
    try {
        // Build query parameters
        const params = new URLSearchParams();
        if (universityFilter !== 'all') {
            params.append('university', universityFilter);
        }
        if (ratingFilter !== 'all') {
            params.append('rating', ratingFilter);
        }
        params.append('sort', sortFilter);
        params.append('page', '1');
        params.append('limit', '50'); // Load more reviews for filtering
        
        const response = await fetch(`get_public_reviews.php?${params.toString()}`);
        const data = await response.json();
        
        if (data.success) {
            filteredReviews = data.reviews;
            allReviews = data.reviews; // Update all reviews with filtered results
            currentPage = 1;
            
            // Update stats with filtered data
            updateStatsWithRealData(data.stats);
        } else {
            console.error('Failed to apply filters:', data.error);
        }
    } catch (error) {
        console.error('Error applying filters:', error);
        // Fallback to client-side filtering
        filteredReviews = allReviews.filter(review => {
            let matchesUniversity = true;
            let matchesRating = true;
            
            if (universityFilter !== 'all') {
                matchesUniversity = review.university_id === universityFilter;
            }
            
            if (ratingFilter !== 'all') {
                matchesRating = review.rating >= parseInt(ratingFilter);
            }
            
            return matchesUniversity && matchesRating;
        });
        
        // Sort reviews
        sortReviews(sortFilter);
    }
    
    // Reset pagination
    currentPage = 1;
    
    // Display filtered results
    displayReviews();
    updateStats();
}

// Sort reviews based on selected option
function sortReviews(sortBy) {
    switch (sortBy) {
        case 'recent':
            filteredReviews.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            break;
        case 'rating':
            filteredReviews.sort((a, b) => b.rating - a.rating);
            break;
        case 'helpful':
            // Since we don't have helpful field, sort by rating instead
            filteredReviews.sort((a, b) => b.rating - a.rating);
            break;
        case 'oldest':
            filteredReviews.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            break;
        default:
            filteredReviews.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }
}

// Display reviews with pagination
function displayReviews() {
    console.log('Displaying reviews...');
    const reviewsGrid = document.getElementById('reviewsGrid');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    
    console.log('Reviews grid element:', reviewsGrid);
    console.log('Load more button:', loadMoreBtn);
    
    if (!reviewsGrid) {
        console.error('Reviews grid not found!');
        return;
    }
    
    const startIndex = (currentPage - 1) * reviewsPerPage;
    const endIndex = startIndex + reviewsPerPage;
    const reviewsToShow = filteredReviews.slice(startIndex, endIndex);
    
    if (currentPage === 1) {
        reviewsGrid.innerHTML = '';
    }
    
    if (reviewsToShow.length === 0 && currentPage === 1) {
        reviewsGrid.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-comments"></i>
                <h3>No Reviews Found</h3>
                <p>No reviews match your current filters. Try adjusting your search criteria or be the first to write a review!</p>
                <button class="btn btn-primary" onclick="openReviewModal()">Write First Review</button>
            </div>
        `;
        if (loadMoreBtn) loadMoreBtn.style.display = 'none';
        return;
    }
    
    console.log('Reviews to show:', reviewsToShow.length);
    reviewsToShow.forEach(review => {
        console.log('Creating review card for:', review);
        const reviewCard = createReviewCard(review);
        reviewsGrid.appendChild(reviewCard);
    });
    
    // Show/hide load more button
    if (loadMoreBtn) {
        if (endIndex >= filteredReviews.length) {
            loadMoreBtn.style.display = 'none';
        } else {
            loadMoreBtn.style.display = 'inline-flex';
        }
    }
}

// Create a review card element
function createReviewCard(review) {
    console.log('Creating review card for:', review);
    
    const reviewCard = document.createElement('div');
    reviewCard.className = 'review-card';
    
    // Handle both database and sample data structures
    const universityName = review.university_name || review.university_id || getUniversityName(review.university_id);
    const courseName = review.courses || review.course_name || 'Course not specified';
    const rating = review.rating || 0;
    const recommendation = review.recommend_or_not_recommended || review.recommendation || 'yes';
    const reviewId = review.id || 0;
    
    reviewCard.innerHTML = `
        <div class="review-header">
            <div class="reviewer-info">
                <div class="reviewer-avatar">
                    A
                </div>
                <div class="reviewer-details">
                    <h4>Anonymous Student</h4>
                    <p>${universityName} • ${courseName}</p>
                </div>
            </div>
            <div class="review-rating">
                <div class="rating-value">${rating}/5</div>
                <div class="rating-stars-small">
                    ${generateStarRating(rating)}
                </div>
            </div>
        </div>
        
        <div class="review-content">
            <p><strong>Recommendation:</strong> ${recommendation === 'yes' ? 'Yes, I would recommend this university' : 'No, I would not recommend this university'}</p>
        </div>
        
        <div class="review-footer">
            <div class="review-meta">
                <div class="recommendation-badge ${recommendation === 'yes' ? 'recommended' : 'not-recommended'}">
                    ${recommendation === 'yes' ? 'Recommended' : 'Not Recommended'}
                </div>
            </div>
            <div class="review-actions">
                <button onclick="viewReviewDetail(${reviewId})" class="btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    `;
    
    return reviewCard;
}

// Generate star rating HTML
function generateStarRating(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        } else {
            stars += '<i class="far fa-star"></i>';
        }
    }
    return stars;
}

// Truncate text to specified length
function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

// Format date for display
function formatDate(dateString) {
    if (!dateString) return 'Date not available';
    
    const date = new Date(dateString);
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
        return 'Date not available';
    }
    
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) return 'Yesterday';
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
    if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`;
    return date.toLocaleDateString();
}

// Load more reviews
function loadMoreReviews() {
    currentPage++;
    displayReviews();
}

// Update review statistics with real data from API
function updateStatsWithRealData(stats) {
    // Update stats display if elements exist
    const statsElements = document.querySelectorAll('.review-stats .stat-number');
    if (statsElements.length >= 4) {
        statsElements[0].textContent = stats.average_rating || '0';
        statsElements[1].textContent = (stats.total_reviews || 0).toLocaleString();
        statsElements[2].textContent = stats.university_count || '0';
        statsElements[3].textContent = (stats.recommend_percentage || 0) + '%';
    }
}

// Update review statistics
function updateStats() {
    const totalReviews = filteredReviews.length;
    const averageRating = totalReviews > 0 ? 
        (filteredReviews.reduce((sum, review) => sum + review.rating, 0) / totalReviews).toFixed(1) : 0;
    const recommendPercentage = totalReviews > 0 ? 
        Math.round((filteredReviews.filter(review => review.recommendation === 'yes').length / totalReviews) * 100) : 0;
    
    // Update stats display if elements exist
    const statsElements = document.querySelectorAll('.review-stats .stat-number');
    if (statsElements.length >= 4) {
        statsElements[0].textContent = averageRating;
        statsElements[1].textContent = totalReviews.toLocaleString();
        statsElements[3].textContent = recommendPercentage + '%';
    }
}

// Open review modal
function openReviewModal() {
    console.log('openReviewModal called');
    const modal = document.getElementById('reviewModal');
    console.log('Modal element:', modal);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        console.log('Modal opened successfully');
    } else {
        console.error('Modal element not found!');
    }
}

// Close review modal
function closeReviewModal() {
    const modal = document.getElementById('reviewModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        // Reset form
        const form = document.getElementById('reviewForm');
        if (form) form.reset();
    }
}

// Handle review form submission
async function handleReviewSubmission(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        // Submit to server
        const response = await fetch('process_review.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Close modal
            closeReviewModal();
            
            // Show success message
            showNotification(data.message || 'Review submitted successfully! Thank you for sharing your experience.', 'success');
            
            // Reload reviews from database to show the new review
            await loadReviews();
        } else {
            // Handle server error
            console.error('Server error:', data.error);
            showNotification(data.error || 'Failed to submit review. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Error submitting review:', error);
        showNotification('Failed to submit review. Please check your connection and try again.', 'error');
    }
}

// Get university name from university code
function getUniversityName(code) {
    const universities = {
        'icbt': 'ICBT Campus',
        'nibm': 'NIBM',
        'peradeniya': 'University of Peradeniya',
        'moratuwa': 'University of Moratuwa'
    };
    return universities[code] || 'Unknown University';
}

// View review details
function viewReviewDetail(reviewId) {
    const review = allReviews.find(r => r.id === reviewId);
    if (!review) return;
    
    const modal = document.getElementById('reviewDetailModal');
    const title = document.getElementById('detailReviewTitle');
    const content = document.getElementById('reviewDetailContent');
    
    if (modal && title && content) {
        // Handle both database and sample data structures
        const universityName = review.university_name || review.university_id || getUniversityName(review.university_id);
        const courseName = review.courses || review.course_name || 'Course not specified';
        const rating = review.rating || 0;
        const recommendation = review.recommend_or_not_recommended || review.recommendation || 'yes';
        const createdAt = review.created_at || new Date().toISOString().split('T')[0];
        
        title.textContent = 'Review Details';
        content.innerHTML = `
            <div class="review-detail-header">
                <div class="review-detail-info">
                    <h3>Anonymous Student</h3>
                    <p>${universityName} • ${courseName}</p>
                </div>
                <div class="review-detail-rating">
                    <div class="rating-value">${rating}/5</div>
                    <div class="rating-stars-small">
                        ${generateStarRating(rating)}
                    </div>
                </div>
            </div>
            
            <div class="review-content">
                <h4>Review Summary</h4>
                <p><strong>Rating:</strong> ${rating}/5 stars</p>
                <p><strong>Recommendation:</strong> ${recommendation === 'yes' ? 'Yes, I would recommend this university' : 'No, I would not recommend this university'}</p>
                <p><strong>University:</strong> ${universityName}</p>
                <p><strong>Course:</strong> ${courseName}</p>
                <p><strong>Date:</strong> ${formatDate(createdAt)}</p>
            </div>
            
            <div class="review-detail-footer">
                <div class="review-meta">
                    <span><i class="fas fa-calendar"></i> ${formatDate(createdAt)}</span>
                </div>
            </div>
        `;
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

// Close review detail modal
function closeReviewDetailModal() {
    const modal = document.getElementById('reviewDetailModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Toggle helpful status for a review (simplified)
function toggleHelpful(reviewId) {
    showNotification('Helpful feature is not available in this simplified version.', 'info');
}

// Initialize animations
function initializeAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.review-card, .stat-item');
    animatedElements.forEach(el => observer.observe(el));
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span>${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    });
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (document.body.contains(notification)) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }
    }, 5000);
}

// Export functions to global scope for debugging
window.ReviewsPage = {
    openReviewModal,
    closeReviewModal,
    viewReviewDetail,
    toggleHelpful,
    showNotification
};
