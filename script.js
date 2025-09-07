document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
    
    // University type selection
    const universityButtons = document.querySelectorAll('.btn-university');
    const privateList = document.querySelector('.private-list');
    const governmentList = document.querySelector('.government-list');
    
    universityButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            universityButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show the corresponding university list
            const type = this.getAttribute('data-type');
            if (type === 'private') {
                privateList.style.display = 'block';
                governmentList.style.display = 'none';
            } else {
                privateList.style.display = 'none';
                governmentList.style.display = 'block';
            }
        });
    });
    
    // Course category tabs
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show the corresponding tab content
            const tabId = this.getAttribute('data-tab');
            document.querySelector(`.${tabId}`).classList.add('active');
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                navLinks.classList.remove('active');
            }
        });
    });
    
    // Initialize active tab
    const activeTabButton = document.querySelector('.tab-btn.active');
    if (activeTabButton) {
        activeTabButton.click();
    }
});

// Compare Courses functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Compare Courses functionality initializing...');
    
    // Campus tabs functionality for Compare Courses page
    const campusTabs = document.querySelectorAll('.campus-tab');
    const campusSections = document.querySelectorAll('.campus-courses-section');
    
    if (campusTabs.length > 0 && campusSections.length > 0) {
        console.log('Campus tabs found, adding functionality');
        
        campusTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetCampus = this.getAttribute('data-campus');
                console.log('Campus tab clicked:', targetCampus);
                
                // Remove active class from all tabs and sections
                campusTabs.forEach(t => t.classList.remove('active'));
                campusSections.forEach(s => s.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding section
                const targetSection = document.getElementById(`${targetCampus}-courses`);
                if (targetSection) {
                    targetSection.classList.add('active');
                    console.log('Showing section:', targetSection.id);
                }
            });
        });
        
        // Set default active tab (ICBT)
        const defaultTab = document.querySelector('.campus-tab[data-campus="icbt"]');
        if (defaultTab) {
            defaultTab.click();
        }
    }
});


//ICBT Page//

// Ensure the DOM is fully loaded before running scripts
document.addEventListener('DOMContentLoaded', function() {

  // --- Tab Functionality ---
  const tabButtons = document.querySelectorAll('.tab-button');
  const programLists = document.querySelectorAll('.program-list');

  tabButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Remove 'active' class from all buttons and content sections
      tabButtons.forEach(btn => btn.classList.remove('active'));
      programLists.forEach(list => list.classList.remove('active'));

      // Add 'active' class to the clicked button and its corresponding content section
      this.classList.add('active');
      document.getElementById(this.getAttribute('data-tab')).classList.add('active');
    });
  });

  // Set the default active tab to 'business' on page load
  const defaultTabButton = document.querySelector('.tab-button[data-tab="business"]');
  if (defaultTabButton) {
    defaultTabButton.click();
  }

  // --- Mobile Menu Toggle ---
  const menuToggle = document.querySelector('.menu-toggle');
  const navLinks = document.querySelector('.nav-links');

  if (menuToggle && navLinks) {
    menuToggle.addEventListener('click', function() {
      navLinks.classList.toggle('active');
    });
  }

  // --- Dropdown Toggle (for mobile) ---
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      // Prevent default if it's a link that shouldn't navigate
      if (this.getAttribute('href') === '#') {
        e.preventDefault();
      }
      // Toggle the parent list item's class to show/hide the dropdown
      this.parentElement.classList.toggle('show-dropdown');
    });
  });

  // --- Student Life Section Functionality ---
  
  const studentLifeCategoryItems = document.querySelectorAll('.category-item');
  const studentLifeCategoryContents = document.querySelectorAll('.category-content');

  if (studentLifeCategoryItems.length > 0 && studentLifeCategoryContents.length > 0) {
    studentLifeCategoryItems.forEach(item => {
      item.addEventListener('click', function() {
        // Remove 'active' class from all category items
        studentLifeCategoryItems.forEach(i => i.classList.remove('active'));
        // Add 'active' class to the clicked item
        this.classList.add('active');

        // Get the category name from data-category attribute
        const categoryToShow = this.getAttribute('data-category');

        // Hide all category contents
        studentLifeCategoryContents.forEach(content => content.classList.remove('active'));

        // Show the selected category content
        const contentElement = document.getElementById(`${categoryToShow}-content`);
        if (contentElement) {
          contentElement.classList.add('active');
        }
      });
    });
  }

  // Initialize active tab with null check
  const activeTabButton = document.querySelector('.tab-btn.active');
  if (activeTabButton) {
    activeTabButton.click();
  }

});