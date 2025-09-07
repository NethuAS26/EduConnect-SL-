// Login System JavaScript - EduConnect SL
// This file handles all login form functionality including admin and student login

// Get DOM elements
const userTypeRadios = document.querySelectorAll('input[name="userType"]');
const campusSelectGroup = document.getElementById('campusSelectGroup');
const campusSelect = document.getElementById('campusSelect');
const loginForm = document.getElementById('loginForm');

// Function to show/hide campus select based on user type
function toggleCampusSelection() {
    const selectedUserType = document.querySelector('input[name="userType"]:checked').value;
    
    if (selectedUserType === 'admin') {
        campusSelectGroup.style.display = 'block';
        campusSelect.required = true; // Make campus required for admin
    } else {
        campusSelectGroup.style.display = 'none';
        campusSelect.value = ''; // Reset campus selection for students
        campusSelect.required = false; // Not required for students
    }
}

// Event listeners for user type selection
userTypeRadios.forEach(radio => {
    radio.addEventListener('change', toggleCampusSelection);
});

// Initialize campus selection visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCampusSelection();
});

// Form submission handler
loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    
    let selectedUserType = '';
    for (const radio of userTypeRadios) {
        if (radio.checked) {
            selectedUserType = radio.value;
            break;
        }
    }

    const selectedCampus = campusSelect.value;

    // Basic validation
    if (!email || !password) {
        showNotification('Please fill in your email and password', 'error');
        return;
    }
    
    if (!selectedUserType) { 
        showNotification('Please select your user type (Student or Admin)', 'error');
        return;
    }

    // Admin-specific validation
    if (selectedUserType === 'admin') {
        if (!selectedCampus) {
            showNotification('Please select your campus', 'error');
            return;
        }
    }

    // Show loading state
    const submitBtn = document.querySelector('.auth-btn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Signing In...';
    submitBtn.classList.add('loading');

    // Create FormData object
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('userType', selectedUserType);
    
    if (selectedUserType === 'admin') {
        formData.append('campus', selectedCampus);
    }
    
    // Send data to PHP backend
    fetch('login_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.classList.remove('loading');
        
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Redirect based on response
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.textContent = originalText;
        submitBtn.classList.remove('loading');
        showNotification('An error occurred. Please try again.', 'error');
    });
});

// Notification system function
function showNotification(message, type = 'info') {
    // Remove existing notifications to prevent overlap
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add floating labels effect for input fields
document.querySelectorAll('.form-group input').forEach(input => {
    input.addEventListener('focus', function() {
        const group = this.closest('.form-group');
        if (group) group.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
        const group = this.closest('.form-group');
        if (group && !this.value) {
            group.classList.remove('focused');
        }
    });
});

// Password visibility toggle function
function setupPasswordToggle(inputElementId) {
    const input = document.getElementById(inputElementId);
    const icon = input.parentElement.querySelector('i');
    
    icon.addEventListener('click', function() {
        if (input.type === 'password') {
            input.type = 'text';
            this.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            this.className = 'fas fa-lock';
        }
    });
}

// Setup password visibility toggle for the password input
setupPasswordToggle('password');

// Console log for debugging
console.log('Login.js loaded successfully!');
