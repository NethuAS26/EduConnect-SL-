// Global variables to track current state
let currentUniversity = null;
let currentStudyLevel = null;
let currentProgram = null;
let currentCourse = null;

// DOM elements
const universitiesSection = document.getElementById('universitiesSection');
const studyLevelsSection = document.getElementById('studyLevelsSection');
const programsSection = document.getElementById('programsSection');
const coursesSection = document.getElementById('coursesSection');
const courseModal = document.getElementById('courseModal');

// Breadcrumb elements
const breadcrumbStudyLevel = document.getElementById('breadcrumb-study-level');
const breadcrumbProgram = document.getElementById('breadcrumb-program');
const breadcrumbCourses = document.getElementById('breadcrumb-courses');
const separator1 = document.getElementById('separator1');
const separator2 = document.getElementById('separator2');
const separator3 = document.getElementById('separator3');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Courses page initialized');
    initializeEventListeners();
    showUniversities();
    
    // Add loading animation to cards
    addCardAnimations();
});

// Set up event listeners
function initializeEventListeners() {
    // University card clicks (excluding explore button)
    document.querySelectorAll('.university-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on the explore button
            if (e.target.closest('.explore-btn')) {
                return;
            }
            const universityKey = this.dataset.university;
            selectUniversity(universityKey);
        });
    });

    // Back button event listeners
    document.getElementById('backToUniversities').addEventListener('click', showUniversities);
    document.getElementById('backToStudyLevels').addEventListener('click', showStudyLevels);
    document.getElementById('backToPrograms').addEventListener('click', showPrograms);

    // Modal event listeners
    document.getElementById('closeModal').addEventListener('click', closeModal);
    document.getElementById('modalBackBtn').addEventListener('click', handleModalBack);
    document.getElementById('showApplicationFormBtn').addEventListener('click', showApplicationForm);
    document.getElementById('submitApplicationBtn').addEventListener('click', submitApplication);

    // Breadcrumb navigation
    breadcrumbStudyLevel.addEventListener('click', () => navigateToStep('study-levels'));
    breadcrumbProgram.addEventListener('click', () => navigateToStep('programs'));
    breadcrumbCourses.addEventListener('click', () => navigateToStep('courses'));
}

// Handle modal back button
function handleModalBack() {
    const applicationForm = document.getElementById('applicationForm');
    const courseContent = document.getElementById('modalCourseContent');
    const showApplicationBtn = document.getElementById('showApplicationFormBtn');
    const submitApplicationBtn = document.getElementById('submitApplicationBtn');
    
    if (applicationForm.style.display !== 'none') {
        // If application form is showing, go back to course details
        applicationForm.style.display = 'none';
        courseContent.style.display = 'block';
        showApplicationBtn.style.display = 'inline-flex';
        submitApplicationBtn.style.display = 'none';
    } else {
        // If course details are showing, close modal
        closeModal();
    }
}

// Show application form
function showApplicationForm() {
    const applicationForm = document.getElementById('applicationForm');
    const courseContent = document.getElementById('modalCourseContent');
    const showApplicationBtn = document.getElementById('showApplicationFormBtn');
    const submitApplicationBtn = document.getElementById('submitApplicationBtn');
    
    // Hide course content and show application form
    courseContent.style.display = 'none';
    applicationForm.style.display = 'block';
    
    // Update button visibility
    showApplicationBtn.style.display = 'none';
    submitApplicationBtn.style.display = 'inline-flex';
    
    // Pre-fill some form fields with course information
    prefillApplicationForm();
    
    // Scroll to top of form
    applicationForm.scrollIntoView({ behavior: 'smooth' });
}

// Pre-fill application form with user data
function prefillApplicationForm() {
    // Set minimum date for date of birth (16 years ago)
    const today = new Date();
    const minDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
    const dateOfBirthField = document.getElementById('dateOfBirth');
    
    if (dateOfBirthField) {
        dateOfBirthField.min = minDate.toISOString().split('T')[0];
        
        // Set a default date (18 years ago) if field is empty
        if (!dateOfBirthField.value) {
            const defaultDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            dateOfBirthField.value = defaultDate.toISOString().split('T')[0];
        }
    }
    
    // Pre-fill with current user data if available
    if (typeof currentUserData !== 'undefined' && currentUserData) {
        const emailField = document.getElementById('email');
        if (emailField && currentUserData.email) {
            emailField.value = currentUserData.email;
        }
        
        const firstNameField = document.getElementById('firstName');
        if (firstNameField && currentUserData.first_name) {
            firstNameField.value = currentUserData.first_name;
        }
        
        const lastNameField = document.getElementById('lastName');
        if (lastNameField && currentUserData.last_name) {
            lastNameField.value = currentUserData.last_name;
        }
    }
    
    // Add form validation event listeners
    addFormValidationListeners();
}

// Add form validation event listeners
function addFormValidationListeners() {
    const form = document.getElementById('courseApplicationForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove error styling when user starts typing
            if (this.style.borderColor === '#e74c3c') {
                this.style.borderColor = '';
            }
        });
        
        input.addEventListener('blur', function() {
            // Validate on blur
            validateField(this);
        });
    });
}

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    switch (field.id) {
        case 'firstName':
        case 'lastName':
            if (!value) {
                isValid = false;
                errorMessage = 'This field is required';
            } else if (value.length < 2) {
                isValid = false;
                errorMessage = 'Must be at least 2 characters';
            }
            break;
            
        case 'email':
            if (!value) {
                isValid = false;
                errorMessage = 'Email is required';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email';
            }
            break;
            
        case 'phone':
            if (!value) {
                isValid = false;
                errorMessage = 'Phone number is required';
            } else if (!/^[\+]?[0-9\s\-\(\)]{8,}$/.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number';
            }
            break;
            
        case 'dateOfBirth':
            if (!value) {
                isValid = false;
                errorMessage = 'Date of birth is required';
            } else {
                const dob = new Date(value);
                const today = new Date();
                const age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                
                if (age < 16 || (age === 16 && monthDiff < 0)) {
                    isValid = false;
                    errorMessage = 'You must be at least 16 years old';
                }
            }
            break;
            
        case 'highestQualification':
            if (!value) {
                isValid = false;
                errorMessage = 'Please select your highest qualification';
            }
            break;
    }
    
    // Apply validation styling
    if (!isValid) {
        field.style.borderColor = '#e74c3c';
        field.title = errorMessage;
    } else {
        field.style.borderColor = '';
        field.title = '';
    }
    
    return isValid;
}

// Submit application form
function submitApplication() {
    const form = document.getElementById('courseApplicationForm');
    const submitBtn = document.getElementById('submitApplicationBtn');
    
    // Enhanced validation - check specific required fields
    const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'dateOfBirth', 'highestQualification'];
    const missingFields = [];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element || !element.value.trim()) {
            missingFields.push(field);
            if (element) {
                element.style.borderColor = '#e74c3c';
                element.focus();
            }
        }
    });
    
    // Check checkboxes
    const declaration = document.getElementById('declaration');
    const termsConditions = document.getElementById('termsConditions');
    
    if (!declaration.checked) {
        missingFields.push('declaration');
        declaration.style.borderColor = '#e74c3c';
    }
    
    if (!termsConditions.checked) {
        missingFields.push('termsConditions');
        termsConditions.style.borderColor = '#e74c3c';
    }
    
    if (missingFields.length > 0) {
        showApplicationMessage(`Please fill in all required fields: ${missingFields.join(', ')}`, 'error');
        return;
    }
    
    // Validate date of birth specifically
    const dateOfBirth = document.getElementById('dateOfBirth').value;
    if (!dateOfBirth) {
        showApplicationMessage('Please select your date of birth', 'error');
        document.getElementById('dateOfBirth').focus();
        return;
    }
    
    // Check if user is at least 16 years old
    const dob = new Date(dateOfBirth);
    const today = new Date();
    const age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    
    if (age < 16 || (age === 16 && monthDiff < 0)) {
        showApplicationMessage('You must be at least 16 years old to apply', 'error');
        document.getElementById('dateOfBirth').focus();
        return;
    }
    
    // Show loading state
    submitBtn.classList.add('btn-loading');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    
    // Collect form data
    const formData = new FormData(form);
    const applicationData = {
        course: currentCourse,
        university: currentUniversity,
        studyLevel: currentStudyLevel,
        program: currentProgram,
        timestamp: new Date().toISOString(),
        ...Object.fromEntries(formData)
    };
    
    console.log('Submitting application data:', applicationData);
    
    // Submit form data to server
    fetch('submit-application.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(applicationData)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Remove loading state
        submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
        
        if (data.success) {
            // Show success message
            showApplicationMessage(data.message, 'success');
            
            // Reset form
            form.reset();
            
            // Hide application form and show course details after 2 seconds
            setTimeout(() => {
                handleModalBack();
            }, 2000);
            
            // Log successful submission
            console.log('Application submitted successfully:', data);
        } else {
            // Show error message
            showApplicationMessage(data.message || 'Application submission failed. Please try again.', 'error');
            console.error('Application submission failed:', data);
        }
    })
    .catch(error => {
        // Remove loading state
        submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
        
        // Show error message
        showApplicationMessage(`Submission failed: ${error.message}. Please try again.`, 'error');
        console.error('Network error:', error);
    });
}

// Show application message
function showApplicationMessage(message, type) {
    // Remove existing messages
    const existingMessage = document.querySelector('.form-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = `form-message ${type}`;
    messageDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    // Insert message at top of form
    const applicationForm = document.getElementById('applicationForm');
    applicationForm.insertBefore(messageDiv, applicationForm.firstChild);
    
    // Auto-remove message after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 5000);
}

// Close modal
function closeModal() {
    const applicationForm = document.getElementById('applicationForm');
    const courseContent = document.getElementById('modalCourseContent');
    const showApplicationBtn = document.getElementById('showApplicationFormBtn');
    const submitApplicationBtn = document.getElementById('submitApplicationBtn');
    
    // Reset modal state
    applicationForm.style.display = 'none';
    courseContent.style.display = 'block';
    showApplicationBtn.style.display = 'inline-flex';
    submitApplicationBtn.style.display = 'none';
    
    // Close modal
    courseModal.classList.remove('show');
    setTimeout(() => {
        courseModal.style.display = 'none';
    }, 300);
}

// Add card animations
function addCardAnimations() {
    const cards = document.querySelectorAll('.university-card, .study-level-card, .program-card, .course-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
}

// Show universities section
function showUniversities() {
    console.log('Showing universities section');
    hideAllSections();
    universitiesSection.style.display = 'block';
    updateBreadcrumb('universities');
    
    // Reset state
    currentUniversity = null;
    currentStudyLevel = null;
    currentProgram = null;
    currentCourse = null;
    
    // Add entrance animation
    setTimeout(() => {
        addCardAnimations();
    }, 100);
}

// Handle university selection
function selectUniversity(universityKey) {
    console.log('University selected:', universityKey);
    currentUniversity = universityKey;
    showStudyLevels();
}

// Show study levels for selected university
function showStudyLevels() {
    console.log('Showing study levels for:', currentUniversity);
    hideAllSections();
    studyLevelsSection.style.display = 'block';
    
    const university = universitiesData[currentUniversity];
    if (!university) {
        console.error('University data not found:', currentUniversity);
        return;
    }

    // Update section header
    document.getElementById('selectedUniversityName').textContent = `${university.name} - Study Levels`;
    
    // Populate study levels grid
    const studyLevelsGrid = document.getElementById('studyLevelsGrid');
    studyLevelsGrid.innerHTML = '';
    
    Object.keys(university.study_levels).forEach(studyLevel => {
        const programs = university.study_levels[studyLevel];
        const totalPrograms = Object.values(programs).reduce((sum, programCourses) => sum + programCourses.length, 0);
        
        const studyLevelCard = document.createElement('div');
        studyLevelCard.className = 'study-level-card';
        studyLevelCard.dataset.studyLevel = studyLevel;
        
        studyLevelCard.innerHTML = `
            <h3>${studyLevel}</h3>
            <div class="program-count">${totalPrograms} Programs Available</div>
        `;
        
        studyLevelCard.addEventListener('click', () => selectStudyLevel(studyLevel));
        studyLevelsGrid.appendChild(studyLevelCard);
    });
    
    updateBreadcrumb('study-levels');
    
    // Add entrance animation
    setTimeout(() => {
        addCardAnimations();
    }, 100);
}

// Handle study level selection
function selectStudyLevel(studyLevel) {
    console.log('Study level selected:', studyLevel);
    currentStudyLevel = studyLevel;
    showPrograms();
}

// Show programs for selected study level
function showPrograms() {
    console.log('Showing programs for:', currentStudyLevel);
    hideAllSections();
    programsSection.style.display = 'block';
    
    const university = universitiesData[currentUniversity];
    const studyLevelData = university.study_levels[currentStudyLevel];
    
    // Update section header
    document.getElementById('selectedStudyLevelName').textContent = `${currentStudyLevel} Programs`;
    
    // Populate programs grid
    const programsGrid = document.getElementById('programsGrid');
    programsGrid.innerHTML = '';
    
    Object.keys(studyLevelData).forEach(program => {
        const courses = studyLevelData[program];
        
        const programCard = document.createElement('div');
        programCard.className = 'program-card';
        programCard.dataset.program = program;
        
        programCard.innerHTML = `
            <h3>${program}</h3>
            <div class="course-count">${courses.length} Courses Available</div>
        `;
        
        programCard.addEventListener('click', () => selectProgram(program));
        programsGrid.appendChild(programCard);
    });
    
    updateBreadcrumb('programs');
    
    // Add entrance animation
    setTimeout(() => {
        addCardAnimations();
    }, 100);
}

// Handle program selection
function selectProgram(program) {
    console.log('Program selected:', program);
    currentProgram = program;
    showCourses();
}

// Show courses for selected program
function showCourses() {
    console.log('Showing courses for:', currentProgram);
    hideAllSections();
    coursesSection.style.display = 'block';
    
    const university = universitiesData[currentUniversity];
    const studyLevelData = university.study_levels[currentStudyLevel];
    const courses = studyLevelData[currentProgram];
    
    // Update section header
    document.getElementById('selectedProgramName').textContent = `${currentProgram} Courses`;
    
    // Populate courses grid
    const coursesGrid = document.getElementById('coursesGrid');
    coursesGrid.innerHTML = '';
    
    courses.forEach(course => {
        const courseCard = document.createElement('div');
        courseCard.className = 'course-card';
        
        courseCard.innerHTML = `
            <h3>${course}</h3>
            <div class="course-meta">
                <span class="course-level">${currentStudyLevel}</span>
                <span class="course-university">${university.name}</span>
            </div>
            <div class="course-description">${getCourseDescription(course)}</div>
        `;
        
        courseCard.addEventListener('click', () => selectCourse(course));
        coursesGrid.appendChild(courseCard);
    });
    
    updateBreadcrumb('courses');
    
    // Add entrance animation
    setTimeout(() => {
        addCardAnimations();
    }, 100);
}

// Handle course selection
function selectCourse(course) {
    console.log('Course selected:', course);
    currentCourse = course;
    showCourseModal();
}

// Show course details modal
function showCourseModal() {
    const university = universitiesData[currentUniversity];
    const studyLevelData = university.study_levels[currentStudyLevel];
    
    // Update modal content
    document.getElementById('modalCourseTitle').textContent = currentCourse;
    
    const modalContent = document.getElementById('modalCourseContent');
    modalContent.innerHTML = `
        <div class="course-details-table">
            <div class="course-details-row">
                <div class="course-details-cell">
                    <div class="course-details-header">Program:</div>
                    <div class="course-details-content">${currentProgram}</div>
                </div>
                <div class="course-details-cell">
                    <div class="course-details-header">Course:</div>
                    <div class="course-details-content">${currentCourse}</div>
                </div>
                <div class="course-details-cell">
                    <div class="course-details-header">Description:</div>
                    <div class="course-details-content">${getCourseDescription(currentCourse)}</div>
                </div>
                <div class="course-details-cell">
                    <div class="course-details-header">Requirement:</div>
                    <div class="course-details-content">${getCourseRequirements(currentCourse)}</div>
                </div>
            </div>
        </div>
    `;
    
    // Show modal with animation
    courseModal.style.display = 'flex';
    setTimeout(() => {
        courseModal.classList.add('show');
    }, 10);
}

// Hide all sections
function hideAllSections() {
    universitiesSection.style.display = 'none';
    studyLevelsSection.style.display = 'none';
    programsSection.style.display = 'none';
    coursesSection.style.display = 'none';
}

// Update breadcrumb navigation
function updateBreadcrumb(step) {
    // Reset all breadcrumb items
    breadcrumbStudyLevel.style.display = 'none';
    breadcrumbProgram.style.display = 'none';
    breadcrumbCourses.style.display = 'none';
    separator1.style.display = 'none';
    separator2.style.display = 'none';
    separator3.style.display = 'none';
    
    // Remove active class from all items
    document.querySelectorAll('.breadcrumb-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show and activate appropriate breadcrumb items
    switch (step) {
        case 'universities':
            document.querySelector('[data-step="universities"]').classList.add('active');
            break;
            
        case 'study-levels':
            document.querySelector('[data-step="universities"]').classList.add('active');
            breadcrumbStudyLevel.style.display = 'inline-block';
            separator1.style.display = 'inline-block';
            breadcrumbStudyLevel.classList.add('active');
            break;
            
        case 'programs':
            document.querySelector('[data-step="universities"]').classList.add('active');
            breadcrumbStudyLevel.style.display = 'inline-block';
            separator1.style.display = 'inline-block';
            breadcrumbStudyLevel.classList.add('active');
            breadcrumbProgram.style.display = 'inline-block';
            separator2.style.display = 'inline-block';
            breadcrumbProgram.classList.add('active');
            break;
            
        case 'courses':
            document.querySelector('[data-step="universities"]').classList.add('active');
            breadcrumbStudyLevel.style.display = 'inline-block';
            separator1.style.display = 'inline-block';
            breadcrumbStudyLevel.classList.add('active');
            breadcrumbProgram.style.display = 'inline-block';
            separator2.style.display = 'inline-block';
            breadcrumbProgram.classList.add('active');
            breadcrumbCourses.style.display = 'inline-block';
            separator3.style.display = 'inline-block';
            breadcrumbCourses.classList.add('active');
            break;
    }
}

// Navigate to specific step via breadcrumb
function navigateToStep(step) {
    switch (step) {
        case 'universities':
            showUniversities();
            break;
        case 'study-levels':
            if (currentUniversity) {
                showStudyLevels();
            }
            break;
        case 'programs':
            if (currentUniversity && currentStudyLevel) {
                showPrograms();
            }
            break;
        case 'courses':
            if (currentUniversity && currentStudyLevel && currentProgram) {
                showCourses();
            }
            break;
    }
}

// Utility functions for course data
function getCourseDescription(courseName) {
    // This would typically come from a database
    // For now, return a generic description
    return `This course provides comprehensive training in ${courseName.toLowerCase()}. Students will gain practical skills and theoretical knowledge essential for their chosen field. The curriculum is designed to meet industry standards and prepare graduates for successful careers.`;
}

function getCourseRequirements(courseName) {
    // This would typically come from a database
    // For now, return generic requirements
    return `Basic understanding of the subject area. Some courses may require specific prerequisites which will be reviewed during the application process. English language proficiency is required for all programs.`;
}

function getCourseDurationAndFees(courseName) {
    // This would typically come from a database
    // For now, return generic information
    return `Course duration varies by program level. Postgraduate programs typically take 1-2 years, undergraduate programs 3-4 years, and certificate programs 6 months to 1 year. Fees are competitive and vary by program. Contact the university for specific pricing.`;
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target === courseModal) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && courseModal.style.display === 'flex') {
        closeModal();
    }
});

// Add smooth scrolling for better UX
function smoothScrollTo(element) {
    element.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Add loading states for better UX
function showLoading(element) {
    element.classList.add('loading');
}

function hideLoading(element) {
    element.classList.remove('loading');
}