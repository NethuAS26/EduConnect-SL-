<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Courses - EduConnect SL</title>
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

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Compare Courses</h1>
                <p>Compare courses across different universities and find the perfect match for your educational goals</p>
                <nav class="breadcrumb">
                    <a href="index.php">Home</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Compare Courses</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Campus Navigation Tabs -->
    <section class="campus-tabs-section">
        <div class="container">
            <div class="campus-tabs">
                <button class="campus-tab active private" data-campus="icbt">
                    <span class="campus-label">Private</span>
                    <img src="ICBT_Campus.png" alt="ICBT Campus">
                    <span>ICBT Campus</span>
                </button>
                <button class="campus-tab private" data-campus="nibm">
                    <span class="campus-label">Private</span>
                    <img src="NIBM_Campus.png" alt="NIBM">
                    <span>NIBM</span>
                </button>
                <button class="campus-tab government" data-campus="peradeniya">
                    <span class="campus-label">Government</span>
                    <img src="Peradeniya_Campus.png" alt="University of Peradeniya">
                    <span>University of Peradeniya</span>
                </button>
                <button class="campus-tab government" data-campus="moratuwa">
                    <span class="campus-label">Government</span>
                    <img src="Moratuwa_Campus.png" alt="University of Moratuwa">
                    <span>University of Moratuwa</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ICBT Campus Courses Table -->
    <section class="campus-courses-section active" id="icbt-courses">
        <div class="container">
            <div class="section-header">
                <h2>ICBT Campus - Courses Comparison</h2>
                <p>Comprehensive overview of all courses offered by ICBT Campus with detailed information about skills acquired and career opportunities.</p>
            </div>
            
            <!-- Postgraduate Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Postgraduate</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Skills Acquired</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Postgraduate Business Courses -->
                                <tr>
                                    <td>Business</td>
                                    <td>Diploma in Strategic Management and Leadership (OTHM Level 7)</td>
                                    <td>Ability to lead teams, plan strategically, and make managerial decisions</td>
                                    <td>Business Manager, Project Manager, Team Leader</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>Diploma in Human Resource Management (OTHM Level 7)</td>
                                    <td>Competence in recruitment, employee relations, and HR processes</td>
                                    <td>HR Manager, Recruitment Specialist, HR Consultant</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>MSc in International Relations</td>
                                    <td>Skills in policy analysis, diplomacy, and international research</td>
                                    <td>Diplomat, Policy Analyst, International NGO Officer</td>
                                </tr>
                                
                                <!-- Postgraduate Engineering & Construction Courses -->
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>MSc in Civil Engineering</td>
                                    <td>Advanced technical knowledge in civil engineering, project design, and problem-solving</td>
                                    <td>Civil Engineer, Project Manager, Structural Engineer</td>
                                </tr>
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>MSc Quantity Surveying and Commercial Management</td>
                                    <td>Expertise in cost management, contract administration, and project evaluation</td>
                                    <td>Quantity Surveyor, Cost Consultant, Project Manager</td>
                                </tr>
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>MSc Construction Project Management</td>
                                    <td>Proficiency in construction planning, risk assessment, and project coordination</td>
                                    <td>Construction Manager, Project Planner, Site Manager</td>
                                </tr>
                                
                                <!-- Postgraduate IT Courses -->
                                <tr>
                                    <td>Information Technology</td>
                                    <td>Master of Science in Information Technology (MSc IT)</td>
                                    <td>Knowledge in IT systems, software development, and network management</td>
                                    <td>IT Manager, Systems Analyst, Software Developer</td>
                                </tr>
                                
                                <!-- Postgraduate Science Courses -->
                                <tr>
                                    <td>Science</td>
                                    <td>MSc Applied Psychology & Behavior Change</td>
                                    <td>Understanding of human behavior, counseling techniques, and psychological research</td>
                                    <td>Clinical Psychologist, Counselor, Researcher</td>
                                </tr>
                                
                                <!-- Postgraduate Law Courses -->
                                <tr>
                                    <td>Law</td>
                                    <td>Master of Laws in International Business (LLM)</td>
                                    <td>Expertise in international business law, contracts, and legal research</td>
                                    <td>Corporate Lawyer, Legal Advisor, Compliance Officer</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Undergraduate Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Undergraduate</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Skills Acquired</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Undergraduate Business Courses -->
                                <tr>
                                    <td>Business</td>
                                    <td>Extended Diploma (Higher Diploma) in Logistics and Supply Chain Management (Level 5)</td>
                                    <td>Ability to manage logistics operations, plan supply chains, and optimize processes</td>
                                    <td>Logistics Manager, Supply Chain Analyst, Operations Executive</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>Higher Diploma in Digital Marketing</td>
                                    <td>Competence in digital campaigns, social media, and marketing analytics</td>
                                    <td>Digital Marketer, Social Media Manager, Marketing Executive</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>Higher Diploma in Business Management</td>
                                    <td>Skills in business operations, leadership, and decision-making</td>
                                    <td>Business Manager, Operations Executive, Entrepreneur</td>
                                </tr>
                                
                                <!-- Undergraduate IT Courses -->
                                <tr>
                                    <td>Information Technology</td>
                                    <td>BSc (Hons) Data Science</td>
                                    <td>Skills in data processing, analytics, and statistical modeling</td>
                                    <td>Data Analyst, Data Scientist, Business Intelligence Developer</td>
                                </tr>
                                <tr>
                                    <td>Information Technology</td>
                                    <td>Higher Diploma in Computing and Software Engineering</td>
                                    <td>Ability to develop software, design systems, and solve computing problems</td>
                                    <td>Software Developer, Web Developer, Systems Analyst</td>
                                </tr>
                                <tr>
                                    <td>Information Technology</td>
                                    <td>Higher Diploma in Network Technology & Cyber Security</td>
                                    <td>Knowledge in networking, cybersecurity, and IT administration</td>
                                    <td>Network Engineer, Cybersecurity Analyst, IT Support</td>
                                </tr>
                                
                                <!-- Undergraduate Engineering & Construction Courses -->
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>Professional Diploma in Quantity Surveying</td>
                                    <td>Expertise in cost estimation, project documentation, and site management</td>
                                    <td>Quantity Surveyor, Site Engineer, Project Coordinator</td>
                                </tr>
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>Higher Diploma in Automotive Engineering</td>
                                    <td>Understanding of automotive systems, maintenance, and repair</td>
                                    <td>Automotive Engineer, Vehicle Technician, Service Manager</td>
                                </tr>
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>Higher Diploma in Quantity Surveying</td>
                                    <td>Skills in project planning, cost management, and construction regulations</td>
                                    <td>Quantity Surveyor, Construction Planner, Cost Analyst</td>
                                </tr>
                                
                                <!-- Undergraduate English Courses -->
                                <tr>
                                    <td>English</td>
                                    <td>Higher Diploma in English</td>
                                    <td>Proficiency in advanced English communication, writing, and analysis</td>
                                    <td>English Teacher, Content Writer, Translator</td>
                                </tr>
                                <tr>
                                    <td>English</td>
                                    <td>BA (Hons) in English</td>
                                    <td>Ability to analyze literature, think critically, and communicate effectively</td>
                                    <td>Lecturer, Editor, Copywriter</td>
                                </tr>
                                
                                <!-- Undergraduate Law Courses -->
                                <tr>
                                    <td>Law</td>
                                    <td>LLB (Hons) Law</td>
                                    <td>Knowledge of legal systems, analytical thinking, and reasoning</td>
                                    <td>Lawyer, Legal Advisor, Corporate Counsel</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- After O/L & A/L Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">After O/L & A/L</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Skills Acquired</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Foundation Business Courses -->
                                <tr>
                                    <td>Business</td>
                                    <td>British Foundation Diploma for Higher Education Studies</td>
                                    <td>Academic foundation, study skills, and readiness for higher education</td>
                                    <td>Entry to Higher Education programs, Academic pathways</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>Diploma in Tourism and Hospitality Management (OTHM Level 7)</td>
                                    <td>Competence in hospitality operations, customer service, and tourism management</td>
                                    <td>Hotel Manager, Tourism Officer, Event Coordinator</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>Higher Diploma in Digital Marketing</td>
                                    <td>Skills in creating digital campaigns, social media management, and analytics</td>
                                    <td>Digital Marketer, Social Media Executive, Marketing Coordinator</td>
                                </tr>
                                
                                <!-- Foundation Engineering & Construction Courses -->
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>Certificate Course in Robotics</td>
                                    <td>Knowledge of robotics fundamentals, programming, and automation</td>
                                    <td>Robotics Technician, Automation Engineer, Research Assistant</td>
                                </tr>
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>Certificate Course in Aerial Vehicle (DRONES)</td>
                                    <td>Skills in drone operation, aerial technology, and safety compliance</td>
                                    <td>Drone Operator, Survey Technician, Aerial Photographer</td>
                                </tr>
                                <tr>
                                    <td>Engineering & Construction</td>
                                    <td>Foundation in Engineering</td>
                                    <td>Basic engineering concepts, problem-solving, and technical skills</td>
                                    <td>Entry-level Engineer, Technician, Trainee Engineer</td>
                                </tr>
                                
                                <!-- Foundation IT Courses -->
                                <tr>
                                    <td>Information Technology</td>
                                    <td>International Diploma in Information & Communication Technology</td>
                                    <td>ICT fundamentals, computing, and networking skills</td>
                                    <td>IT Support, Junior Programmer, Network Assistant</td>
                                </tr>
                                <tr>
                                    <td>Information Technology</td>
                                    <td>Higher Diploma in Computing and Software Engineering</td>
                                    <td>Competence in programming, software development, and system design</td>
                                    <td>Software Developer, Web Developer, Systems Analyst</td>
                                </tr>
                                
                                <!-- Foundation Science Courses -->
                                <tr>
                                    <td>Science</td>
                                    <td>Foundation in Biomedical Science</td>
                                    <td>Knowledge of basic biomedical concepts and lab skills</td>
                                    <td>Lab Assistant, Research Assistant, Entry-level Healthcare Roles</td>
                                </tr>
                                <tr>
                                    <td>Science</td>
                                    <td>International Diploma in Psychology</td>
                                    <td>Understanding of human behavior, research basics, and psychology principles</td>
                                    <td>Counselor, Research Assistant, Psychology Support Roles</td>
                                </tr>
                                <tr>
                                    <td>Science</td>
                                    <td>Higher Diploma in Psychology</td>
                                    <td>Skills in applied psychology, counseling, and behavioral analysis</td>
                                    <td>Counselor, Behavioral Therapist, HR Specialist</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NIBM Campus Courses Table -->
    <section class="campus-courses-section" id="nibm-courses">
        <div class="container">
            <div class="section-header">
                <h2>NIBM - Courses Comparison</h2>
                <p>Comprehensive overview of all courses offered by NIBM with detailed information about skills acquired and career opportunities.</p>
            </div>
            
            <!-- Master's Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Master's Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Master's Business Courses -->
                                <tr>
                                    <td>Business</td>
                                    <td>MBA in Global Business</td>
                                    <td>Leadership, strategic thinking, management skills</td>
                                    <td>Business Manager, Project Manager, Consultant</td>
                                </tr>
                                
                                <!-- Master's Psychology Courses -->
                                <tr>
                                    <td>Psychology</td>
                                    <td>MSc in Applied Psychology</td>
                                    <td>Applied psychology, research, counseling skills</td>
                                    <td>Clinical Psychologist, Counselor, Researcher</td>
                                </tr>
                                
                                <!-- Master's Computing/Data Courses -->
                                <tr>
                                    <td>Computing / Data</td>
                                    <td>MSc in Data Science</td>
                                    <td>Data analysis, machine learning, statistical modeling</td>
                                    <td>Data Scientist, Business Analyst, Data Engineer</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Undergraduate Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Undergraduate Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Undergraduate Business & Management Courses -->
                                <tr>
                                    <td>Business & Management</td>
                                    <td>BA (Hons) in Management & Leadership</td>
                                    <td>Management principles, leadership, business strategy</td>
                                    <td>Business Manager, Team Leader, Entrepreneur</td>
                                </tr>
                                
                                <!-- Undergraduate Psychology Courses -->
                                <tr>
                                    <td>Psychology</td>
                                    <td>BSc (Hons) in Psychology</td>
                                    <td>Understanding human behavior, research, analysis</td>
                                    <td>Psychologist, Counselor, Research Assistant</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Advanced Diploma / Diploma Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Advanced Diploma / Diploma Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Advanced Diploma Business Courses -->
                                <tr>
                                    <td>Business</td>
                                    <td>Advanced Diploma in Business Management (After A/L)</td>
                                    <td>Business operations, management skills</td>
                                    <td>Business Executive, Manager, Entrepreneur</td>
                                </tr>
                                
                                <!-- Advanced Diploma Management Courses -->
                                <tr>
                                    <td>Management</td>
                                    <td>Advanced Diploma in Project Management</td>
                                    <td>Project planning, execution, and leadership skills</td>
                                    <td>Project Manager, Coordinator, Consultant</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Certificate Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Certificate Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Certificate Business Courses -->
                                <tr>
                                    <td>Business</td>
                                    <td>Certificate in Business Management</td>
                                    <td>Basic management skills, business operations knowledge</td>
                                    <td>Junior Manager, Assistant, Administrator</td>
                                </tr>
                                
                                <!-- Certificate Accounting Courses -->
                                <tr>
                                    <td>Accounting</td>
                                    <td>Advanced Certificate in Financial & Management</td>
                                    <td>Accounting principles, financial reporting, budgeting</td>
                                    <td>Accountant, Finance Executive, Auditor</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Foundation Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Foundation Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Foundation General Courses -->
                                <tr>
                                    <td>Foundation</td>
                                    <td>Accounting Foundation Programme for Bachelor's Degree</td>
                                    <td>Academic preparation, study skills, foundational knowledge</td>
                                    <td>Entry to Undergraduate Degree programs</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- University of Peradeniya Courses Table -->
    <section class="campus-courses-section" id="peradeniya-courses">
        <div class="container">
            <div class="section-header">
                <h2>University of Peradeniya - Courses Comparison</h2>
                <p>Comprehensive overview of all courses offered by University of Peradeniya with detailed information about skills acquired and career opportunities.</p>
            </div>
            
            <!-- Certificate & Advanced Certificate Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Certificate & Advanced Certificate Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- CDCE Department Courses -->
                                <tr>
                                    <td>CDCE</td>
                                    <td>Certificate in Basic Tamil</td>
                                    <td>Basic Tamil language skills</td>
                                    <td>Language Assistant, Translator, Academic Assistant</td>
                                </tr>
                                <tr>
                                    <td>CDCE</td>
                                    <td>Advanced Certificate in Lab Handling</td>
                                    <td>Laboratory techniques and safety</td>
                                    <td>Lab Assistant, Research Technician</td>
                                </tr>
                                
                                <!-- CES Department Courses -->
                                <tr>
                                    <td>CES</td>
                                    <td>Environmental Awareness (Online)</td>
                                    <td>Environmental knowledge and awareness</td>
                                    <td>Environmental Officer, NGO Assistant</td>
                                </tr>
                                <tr>
                                    <td>CES</td>
                                    <td>GIS for Environmental Management</td>
                                    <td>GIS mapping and environmental analysis skills</td>
                                    <td>GIS Analyst, Environmental Consultant</td>
                                </tr>
                                
                                <!-- CEIT Department Courses -->
                                <tr>
                                    <td>CEIT</td>
                                    <td>Certificate in Basic ICT Skills</td>
                                    <td>Basic computer and ICT skills</td>
                                    <td>IT Support, Junior Technician</td>
                                </tr>
                                
                                <!-- AgBC Department Courses -->
                                <tr>
                                    <td>AgBC</td>
                                    <td>Biotech Certificate Training</td>
                                    <td>Biotechnology lab skills and understanding</td>
                                    <td>Research Assistant, Lab Technician</td>
                                </tr>
                                
                                <!-- PGIS Department Courses -->
                                <tr>
                                    <td>PGIS</td>
                                    <td>Short Academic Workshops</td>
                                    <td>Specific academic skill enhancement</td>
                                    <td>Entry-level academic/technical roles</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Diploma Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Diploma Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- CEIT Department Diploma Courses -->
                                <tr>
                                    <td>CEIT</td>
                                    <td>Diploma in IT (DITUP)</td>
                                    <td>Intermediate IT and computing skills</td>
                                    <td>IT Technician, Junior Developer</td>
                                </tr>
                                
                                <!-- Allied Health Sciences Department Courses -->
                                <tr>
                                    <td>Allied Health Sciences</td>
                                    <td>Diploma in Movement Science & Injury Prevention</td>
                                    <td>Knowledge of movement science and injury prevention</td>
                                    <td>Physiotherapy Assistant, Rehabilitation Technician</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Professional Short Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Professional Short Courses</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- CEIT Department Professional Courses -->
                                <tr>
                                    <td>CEIT</td>
                                    <td>CCNA Routing & Switching</td>
                                    <td>Networking and IT infrastructure skills</td>
                                    <td>Network Engineer, IT Support</td>
                                </tr>
                                
                                <!-- College of IT Department Courses -->
                                <tr>
                                    <td>College of IT</td>
                                    <td>Photography & Video Production</td>
                                    <td>Photography and video production skills</td>
                                    <td>Photographer, Videographer, Content Creator</td>
                                </tr>
                                
                                <!-- AgBC Department Professional Courses -->
                                <tr>
                                    <td>AgBC</td>
                                    <td>Intensive Biotechnology Workshops</td>
                                    <td>Advanced biotechnology lab techniques</td>
                                    <td>Research Assistant, Lab Technician</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- University of Moratuwa Courses Table -->
    <section class="campus-courses-section" id="moratuwa-courses">
        <div class="container">
            <div class="section-header">
                <h2>University of Moratuwa - Courses Comparison</h2>
                <p>Comprehensive overview of all courses offered by University of Moratuwa with detailed information about skills acquired and career opportunities.</p>
            </div>
            
            <!-- Certificate Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Certificate Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- IT Faculty Department Courses -->
                                <tr>
                                    <td>IT Faculty</td>
                                    <td>Computer Hardware & Networking</td>
                                    <td>Computer hardware understanding, network setup skills</td>
                                    <td>IT Support, Network Technician</td>
                                </tr>
                                <tr>
                                    <td>IT Faculty</td>
                                    <td>Software Development in Java</td>
                                    <td>Java programming and software development skills</td>
                                    <td>Software Developer, Programmer</td>
                                </tr>
                                <tr>
                                    <td>IT Faculty</td>
                                    <td>Web Development with PHP & MySQL</td>
                                    <td>Web programming and database management skills</td>
                                    <td>Web Developer, Database Assistant</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Certificate / Diploma Level Courses Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Certificate / Diploma Level</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- ITUM Department Courses -->
                                <tr>
                                    <td>ITUM</td>
                                    <td>Certificate in Spoken English</td>
                                    <td>English communication skills</td>
                                    <td>Customer Support, Academic Assistant</td>
                                </tr>
                                <tr>
                                    <td>ITUM</td>
                                    <td>Diploma in English</td>
                                    <td>Advanced English reading, writing and speaking</td>
                                    <td>English Teacher, Translator</td>
                                </tr>
                                <tr>
                                    <td>ITUM</td>
                                    <td>Engineering Drafting with AutoCAD</td>
                                    <td>CAD drafting and engineering drawing skills</td>
                                    <td>Draftsman, CAD Technician</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Workshops Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Workshops</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Mechanical Engineering Department Workshops -->
                                <tr>
                                    <td>Mechanical Engineering</td>
                                    <td>AutoCAD 2D Drafting (12 Saturdays)</td>
                                    <td>2D CAD drafting and technical drawing skills</td>
                                    <td>CAD Technician, Junior Engineer</td>
                                </tr>
                                <tr>
                                    <td>Mechanical Engineering</td>
                                    <td>AutoCAD 3D Modelling (12 Saturdays)</td>
                                    <td>3D CAD modeling skills</td>
                                    <td>3D Modeler, CAD Technician</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Short Courses / Professional Development Section -->
            <div class="course-level-section">
                <h3 class="level-heading">Short Courses / Professional Development</h3>
                <div class="comparison-table-container">
                    <div class="comparison-table-wrapper">
                        <table class="courses-comparison-table">
                            <thead>
                                <tr>
                                    <th>Programs</th>
                                    <th>Courses</th>
                                    <th>Expected Outcomes</th>
                                    <th>Career Opportunities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Business Data Department Courses -->
                                <tr>
                                    <td>Business Data</td>
                                    <td>SQL data</td>
                                    <td>SQL data analysis and reporting skills</td>
                                    <td>Data Analyst, Business Analyst</td>
                                </tr>
                                
                                <!-- Sciences Department Courses -->
                                <tr>
                                    <td>Sciences</td>
                                    <td>Analysis with SQL</td>
                                    <td>Analysis and reporting skills</td>
                                    <td>Business Analyst</td>
                                </tr>
                                
                                <!-- Decision Sciences Department Courses -->
                                <tr>
                                    <td>Decision Sciences</td>
                                    <td>Data Visualization & Analytics with Power BI</td>
                                    <td>Data visualization and analytics skills</td>
                                    <td>Data Analyst, Business Intelligence Developer</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
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
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <div class="contact-info">
                        <p><i class="fas fa-phone"></i> +94 11 234 5678</p>
                        <p><i class="fas fa-envelope"></i> info@educonnectsl.lk</p>
                        <p><i class="fas fa-map-marker-alt"></i> Colombo, Sri Lanka</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 EduConnect SL. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
