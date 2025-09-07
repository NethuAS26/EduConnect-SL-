// Universities Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeUniversitiesPage();
    setupEventListeners();
    loadURLParameters();
    initializeAnimations();
});

// Initialize the universities page
function initializeUniversitiesPage() {
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

// Set up all event listeners
function setupEventListeners() {
    // Tab filtering
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedType = button.getAttribute('data-type');
            filterUniversities(selectedType);
            updateActiveTab(button);
        });
    });
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const targetId = link.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                e.preventDefault();
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Update URL without page reload
                history.pushState(null, null, `#${targetId}`);
            }
        });
    });
    
    // University card interactions
    const universityCards = document.querySelectorAll('.university-card');
    universityCards.forEach(card => {
        card.addEventListener('click', (e) => {
            // Don't trigger if clicking on buttons or links
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || 
                e.target.closest('.btn') || e.target.closest('a')) {
                return;
            }
            
            // Add click effect
            card.style.transform = 'scale(0.98)';
            setTimeout(() => {
                card.style.transform = '';
            }, 150);
        });
    });
}

// Filter universities based on selected type
function filterUniversities(type) {
    const universityCards = document.querySelectorAll('.university-card');
    
    universityCards.forEach(card => {
        const cardType = card.getAttribute('data-type');
        
        if (type === 'all' || cardType === type) {
            card.classList.remove('hidden');
            card.style.display = 'block';
        } else {
            card.classList.add('hidden');
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
    
    // Update comparison table visibility
    updateComparisonTable(type);
    
    // Animate visible cards
    animateVisibleCards();
}

// Update active tab styling
function updateActiveTab(activeButton) {
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });
    activeButton.classList.add('active');
}

// Update comparison table based on filter
function updateComparisonTable(type) {
    const comparisonTable = document.querySelector('.comparison-table');
    if (!comparisonTable) return;
    
    const rows = comparisonTable.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let shouldShow = false;
        
        if (type === 'all') {
            shouldShow = true;
        } else if (type === 'private') {
            // Show rows for private universities (ICBT, NIBM)
            const firstCell = cells[0].textContent.trim();
            if (firstCell === 'Type' || 
                firstCell === 'Established' || 
                firstCell === 'Main Location' || 
                firstCell === 'Programs Offered' || 
                firstCell === 'Student Population' || 
                firstCell === 'Average Rating' || 
                firstCell === 'Fee Range') {
                shouldShow = true;
            } else {
                // Check if this row has private university data
                const hasPrivateData = Array.from(cells).some(cell => 
                    cell.textContent.includes('Private') || 
                    cell.textContent.includes('1999') || 
                    cell.textContent.includes('1994') ||
                    cell.textContent.includes('Colombo') ||
                    cell.textContent.includes('50+') ||
                    cell.textContent.includes('40+') ||
                    cell.textContent.includes('15,000+') ||
                    cell.textContent.includes('12,000+') ||
                    cell.textContent.includes('4.7') ||
                    cell.textContent.includes('4.6') ||
                    cell.textContent.includes('200K') ||
                    cell.textContent.includes('150K')
                );
                shouldShow = hasPrivateData;
            }
        } else if (type === 'government') {
            // Show rows for government universities (Peradeniya, Moratuwa)
            const firstCell = cells[0].textContent.trim();
            if (firstCell === 'Type' || 
                firstCell === 'Established' || 
                firstCell === 'Main Location' || 
                firstCell === 'Programs Offered' || 
                firstCell === 'Student Population' || 
                firstCell === 'Average Rating' || 
                firstCell === 'Fee Range') {
                shouldShow = true;
            } else {
                // Check if this row has government university data
                const hasGovernmentData = Array.from(cells).some(cell => 
                    cell.textContent.includes('Government') || 
                    cell.textContent.includes('1942') || 
                    cell.textContent.includes('1972') ||
                    cell.textContent.includes('Peradeniya') ||
                    cell.textContent.includes('Moratuwa') ||
                    cell.textContent.includes('80+') ||
                    cell.textContent.includes('60+') ||
                    cell.textContent.includes('25,000+') ||
                    cell.textContent.includes('20,000+') ||
                    cell.textContent.includes('4.8') ||
                    cell.textContent.includes('4.9') ||
                    cell.textContent.includes('50K') ||
                    cell.textContent.includes('80K')
                );
                shouldShow = hasGovernmentData;
            }
        }
        
        if (shouldShow) {
            row.style.display = '';
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
            setTimeout(() => {
                row.style.display = 'none';
            }, 300);
        }
    });
}

// Animate visible cards
function animateVisibleCards() {
    const visibleCards = document.querySelectorAll('.university-card:not(.hidden)');
    
    visibleCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.remove('fadeInUp');
        void card.offsetWidth; // Trigger reflow
        card.classList.add('fadeInUp');
    });
}

// Load URL parameters for direct linking
function loadURLParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const university = urlParams.get('university');
    
    if (university) {
        // Filter to show specific university type
        let type = 'all';
        if (['icbt', 'nibm'].includes(university)) {
            type = 'private';
        } else if (['peradeniya', 'moratuwa'].includes(university)) {
            type = 'government';
        }
        
        // Update active tab
        const tabButton = document.querySelector(`[data-type="${type}"]`);
        if (tabButton) {
            updateActiveTab(tabButton);
            filterUniversities(type);
        }
        
        // Scroll to specific university if hash exists
        const hash = window.location.hash.substring(1);
        if (hash) {
            setTimeout(() => {
                const targetElement = document.getElementById(hash);
                if (targetElement) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }, 500);
        }
    }
}

// Initialize scroll animations
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
    const animatedElements = document.querySelectorAll('.university-card, .stat-item, .section-header');
    animatedElements.forEach(el => observer.observe(el));
    
    // Add scroll effect to header
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

// Utility function to show notifications
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
            document.body.removeChild(notification);
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

// Utility function to format numbers
function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export functions to global scope for debugging
window.UniversitiesPage = {
    filterUniversities,
    showNotification,
    formatNumber,
    debounce
};

// Add CSS for notification styles
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    
    .notification-close:hover {
        opacity: 0.8;
    }
    
    .animate-in {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    .header.scrolled {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
`;
document.head.appendChild(notificationStyles);
