<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_type = $_SESSION['user_type'];

// Course data structure
$universities = [
    'ICBT' => [
        'name' => 'ICBT Campus',
        'logo' => 'LogoICBT.png',
        'description' => 'International College of Business and Technology',
        'study_levels' => [
            'Postgraduate' => [
                'Business' => [
                    'Diploma in Strategic Management and Leadership (OTHM Level 7)',
                    'Diploma in Human Resource Management (OTHM Level 7)',
                    'MSc in International Relations'
                ],
                'Engineering & Construction' => [
                    'MSc in Civil Engineering',
                    'MSc Quantity Surveying and Commercial Management',
                    'MSc Construction Project Management'
                ],
                'Information Technology' => [
                    'Master of Science in Information Technology (MSc IT)',
                    'MSc in Data Science'
                ],
                'Science' => [
                    'MSc Applied Psychology & Behavior Change'
                ],
                'Law' => [
                    'Master of Laws in International Business (LLM)'
                ]
            ],
            'Undergraduate' => [
                'Business' => [
                    'Extended Diploma (Higher Diploma) in Logistics and Supply Chain Management (Level 5)',
                    'Higher Diploma in Digital Marketing',
                    'Higher Diploma in Business Management'
                ],
                'Engineering & Construction' => [
                    'Professional Diploma in Quantity Surveying',
                    'Higher Diploma in Automotive Engineering',
                    'Higher Diploma in Quantity Surveying'
                ],
                'Information Technology' => [
                    'Higher Diploma in Computing and Software Engineering',
                    'Higher Diploma in Network Technology & Cyber Security',
                    'BSc (Hons) Data Science'
                ],
                'English' => [
                    'Higher Diploma in English',
                    'BA (Hons) in English'
                ],
                'Law' => [
                    'LLB (Hons) Law'
                ]
            ],
            'After O/L & A/L' => [
                'Business' => [
                    'Higher Diploma in Digital Marketing',
                    'British Foundation Diploma for Higher Education Studies',
                    'Diploma in Tourism and Hospitality Management (OTHM Level 7)'
                ],
                'Engineering & Construction' => [
                    'Certificate Course in Robotics',
                    'Certificate Course in Aerial Vehicle (DRONES)',
                    'Foundation in Engineering'
                ],
                'Information Technology' => [
                    'International Diploma in Information & Communication Technology',
                    'Higher Diploma in Computing and Software Engineering'
                ],
                'Science' => [
                    'Foundation in Biomedical Science',
                    'International Diploma in Psychology',
                    'Higher Diploma in Psychology'
                ]
            ]
        ]
    ],
    'NIBM' => [
        'name' => 'NIBM',
        'logo' => 'LogoNibm.png',
        'description' => 'National Institute of Business Management',
        'study_levels' => [
            'Master Programmes' => [
                'Applied Psychology' => [
                    'MSc in Applied Psychology'
                ],
                'Data Science' => [
                    'MSc in Data Science'
                ],
                'Business' => [
                    'MBA in Global Business'
                ]
            ],
            'Degree (Undergraduate)' => [
                'Management' => [
                    'BA (Hons) in Management and Leadership'
                ],
                'Psychology' => [
                    'BSc (Hons) in Psychology'
                ]
            ],
            'Advanced Diploma / Diploma' => [
                'Business Management' => [
                    'Advanced Diploma in Business Management (for After A/L Students)'
                ],
                'Project Management' => [
                    'Advanced Diploma in Project Management'
                ]
            ],
            'Certificate & Advanced Certificate' => [
                'Business Management' => [
                    'Certificate in Business Management'
                ],
                'Accounting' => [
                    'Advanced Certificate in Financial and Management Accounting'
                ]
            ],
            'Foundation Programme' => [
                'General' => [
                    'Foundation Programme for Bachelor\'s Degree'
                ]
            ]
        ]
    ],
    'Peradeniya' => [
        'name' => 'University of Peradeniya',
        'logo' => 'LogoPeradeniya.png',
        'description' => 'Premier University of Sri Lanka',
        'study_levels' => [
            'Certificate & Advanced Certificate' => [
                'CDCE' => [
                    'CDCE (Lab Handling, Basic Tamil)'
                ],
                'CES' => [
                    'CES (Environmental Awareness, GIS)'
                ],
                'CEIT' => [
                    'CEIT (Basic ICT Skills)'
                ],
                'AgBC' => [
                    'AgBC (Biotech certificate training)'
                ],
                'PGIS' => [
                    'PGIS (Short academic workshops)'
                ]
            ],
            'Diploma' => [
                'CEIT' => [
                    'CEIT (Diploma in IT – DITUP)'
                ],
                'Allied Health Sciences' => [
                    'Allied Health Sciences (Diploma in Movement Science & Injury Prevention)'
                ]
            ],
            'Professional Short Courses' => [
                'CEIT' => [
                    'CEIT (CCNA Routing & Switching)'
                ],
                'College of IT' => [
                    'College of IT (Photography & Video Production)'
                ],
                'AgBC' => [
                    'AgBC (Intensive biotech workshops)'
                ]
            ]
        ]
    ],
    'Moratuwa' => [
        'name' => 'University of Moratuwa',
        'logo' => 'LogoMoratuwa.png',
        'description' => 'Leading Engineering and Technology University',
        'study_levels' => [
            'Faculty of Information Technology' => [
                'Certificate Courses & Short Programs' => [
                    'Computer Hardware & Networking',
                    'Software Development in Java',
                    'Web Development with PHP & MySQL'
                ]
            ],
            'Institute of Technology (ITUM)' => [
                'Short Courses' => [
                    'Certificate Course in Spoken English',
                    'Diploma in English',
                    'Engineering Drafting with AutoCAD'
                ]
            ],
            'Department of Mechanical Engineering' => [
                'Training Courses & Workshops' => [
                    'Computer Aided Drafting using AutoCAD (2D) – 12 Saturdays, LKR 30,000',
                    'Computer Aided 3D Modelling using AutoCAD – 12 Saturdays, LKR 35,000'
                ]
            ],
            'Department of Decision Sciences' => [
                'Short & Certificate Courses' => [
                    'Business Data Analysis with SQL',
                    'Data Visualization & Analytics with Power BI'
                ]
            ]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - EduConnectSL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="courses.css">
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
                        <a href="courses.php" class="nav-link active">Courses</a>
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
                <h1>Explore Our Courses</h1>
                <p>Discover programs from leading universities across Sri Lanka</p>
            </div>
        </div>
    </section>

    <!-- Universities Section -->
    <section id="universitiesSection" class="universities-section">
        <div class="container">
            <h2>Choose Your University</h2>
            <p>Select from our partner institutions to explore their programs</p>
            
            <div class="universities-grid">
                    <?php foreach ($universities as $key => $university): ?>
                    <div class="university-card" data-university="<?php echo $key; ?>">
                        <div class="university-logo">
                            <img src="<?php echo $university['logo']; ?>" alt="<?php echo $university['name']; ?> Logo">
                        </div>
                        <div class="university-info">
                            <h3><?php echo $university['name']; ?></h3>
                            <p><?php echo $university['description']; ?></p>
                            <div class="university-stats">
                                <span class="stat">
                                    <i class="fas fa-graduation-cap"></i>
                                    <div class="stat-number"><?php echo count($university['study_levels']); ?></div>
                                    <div class="stat-label">Study Levels</div>
                                </span>
                                <span class="stat">
                                    <i class="fas fa-book"></i>
                                    <div class="stat-number">
                                        <?php 
                                        $totalPrograms = 0;
                                        foreach ($university['study_levels'] as $level) {
                                            foreach ($level as $programs) {
                                                $totalPrograms += count($programs);
                                            }
                                        }
                                        echo $totalPrograms;
                                        ?>
                                    </div>
                                    <div class="stat-label">Programs</div>
                                </span>
                            </div>
                        </div>
                        <div class="university-action">
                            <a href="<?php echo $key; ?>_study_levels.php" class="btn btn-primary explore-btn">
                                <i class="fas fa-arrow-right"></i>
                                Explore
                            </a>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="card-decoration">
                            <div class="decoration-dot"></div>
                            <div class="decoration-line"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Study Levels Section -->
        <section id="studyLevelsSection" class="study-levels-section" style="display: none;">
            <div class="container">
                <div class="section-header">
                    <button id="backToUniversities" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Back to Universities
                    </button>
                    <h2 id="selectedUniversityName">Study Levels</h2>
                </div>
                
                <div id="studyLevelsGrid" class="study-levels-grid">
                    <!-- Study levels will be loaded here -->
                </div>
            </div>
        </section>

        <!-- Programs Section -->
        <section id="programsSection" class="programs-section" style="display: none;">
            <div class="container">
                <div class="section-header">
                    <button id="backToStudyLevels" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Back to Study Levels
                    </button>
                    <h2 id="selectedStudyLevelName">Programs</h2>
                </div>
                
                <div id="programsGrid" class="programs-grid">
                    <!-- Programs will be loaded here -->
                </div>
            </div>
        </section>

        <!-- Courses Section -->
        <section id="coursesSection" class="courses-section" style="display: none;">
            <div class="container">
                <div class="section-header">
                    <button id="backToPrograms" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Back to Programs
                    </button>
                    <h2 id="selectedProgramName">Courses</h2>
                </div>
                
                <div id="coursesGrid" class="courses-grid">
                    <!-- Courses will be loaded here -->
                </div>
            </div>
        </section>

        <!-- Course Details Modal -->
        <div id="courseModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalCourseTitle">Course Details</h3>
                    <button class="modal-close" id="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalCourseContent">
                        <!-- Course details will be loaded here -->
                    </div>
                    
                    <!-- Application Form -->
                    <div id="applicationForm" class="application-form" style="display: none;">
                        <h4>Course Application Form</h4>
                        <form id="courseApplicationForm">
                            <div class="form-section">
                                <h5>Personal Information</h5>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="firstName">First Name *</label>
                                        <input type="text" id="firstName" name="firstName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Last Name *</label>
                                        <input type="text" id="lastName" name="lastName" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="email">Email Address *</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number *</label>
                                        <input type="tel" id="phone" name="phone" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dateOfBirth">Date of Birth *</label>
                                    <input type="date" id="dateOfBirth" name="dateOfBirth" required>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5>Educational Background</h5>
                                <div class="form-group">
                                    <label for="highestQualification">Highest Qualification *</label>
                                    <select id="highestQualification" name="highestQualification" required>
                                        <option value="">Select Qualification</option>
                                        <option value="O/L">O/L (Ordinary Level)</option>
                                        <option value="A/L">A/L (Advanced Level)</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="Bachelor">Bachelor's Degree</option>
                                        <option value="Master">Master's Degree</option>
                                        <option value="PhD">PhD</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="institution">Institution Name</label>
                                    <input type="text" id="institution" name="institution" placeholder="Where did you complete your qualification?">
                                </div>
                                <div class="form-group">
                                    <label for="graduationYear">Year of Graduation</label>
                                    <input type="number" id="graduationYear" name="graduationYear" min="1950" max="2030" placeholder="e.g., 2020">
                                </div>
                            </div>

                            <div class="form-section">
                                <h5>Declaration</h5>
                                <div class="form-group checkbox-group">
                                    <input type="checkbox" id="declaration" name="declaration" required>
                                    <label for="declaration">I declare that all information provided in this application is true and accurate to the best of my knowledge. I understand that providing false information may result in the rejection of my application. *</label>
                                </div>
                                <div class="form-group checkbox-group">
                                    <input type="checkbox" id="termsConditions" name="termsConditions" required>
                                    <label for="termsConditions">I agree to the terms and conditions of the course application process. *</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" id="modalBackBtn">Back</button>
                    <button class="btn btn-primary" id="showApplicationFormBtn">
                        <i class="fas fa-file-alt"></i>
                        Apply for Course
                    </button>
                    <button class="btn btn-success" id="submitApplicationBtn" style="display: none;">
                        <i class="fas fa-paper-plane"></i>
                        Submit Application
                    </button>
                </div>
            </div>
        </div>

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



    <script>
        // Pass PHP data to JavaScript
        const universitiesData = <?php echo json_encode($universities); ?>;
    </script>
    <script src="courses.js"></script>
</body>
</html>
