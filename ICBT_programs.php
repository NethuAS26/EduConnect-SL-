<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_type = $_SESSION['user_type'];

// Get the study level from URL parameter
$study_level = isset($_GET['level']) ? $_GET['level'] : '';

// Normalize study level names to handle different naming conventions
function normalizeStudyLevel($study_level) {
    // Map different naming conventions to the standard format
    $level_mapping = [
        'After A/L & O/L' => 'After O/L & A/L',
        'After A/L' => 'After O/L & A/L',
        'After O/L' => 'After O/L & A/L'
    ];
    
    return $level_mapping[$study_level] ?? $study_level;
}

// Function to fetch admin-added programs and courses from database
function fetchAdminProgramsAndCourses($pdo, $study_level, $hardcoded_programs) {
    $admin_programs = [];
    
    try {
        // Normalize the study level
        $normalized_study_level = normalizeStudyLevel($study_level);
        
        // Get all hardcoded program names for this study level
        $hardcoded_program_names = [];
        if (isset($hardcoded_programs[$normalized_study_level])) {
            foreach ($hardcoded_programs[$normalized_study_level] as $category => $programs) {
                foreach ($programs as $program_name => $course_data) {
                    $hardcoded_program_names[] = $program_name;
                }
            }
        }
        
        // Fetch programs for the specific study level (try both original and normalized)
        // Also include the reverse mapping for admin dashboard compatibility
        $stmt = $pdo->prepare("
            SELECT 
                p.id as program_id,
                p.program_name,
                p.description as program_description,
                p.requirements as program_requirements,
                p.duration,
                c.id as course_id,
                c.course_name,
                c.course_description,
                c.requirement,
                c.campus
            FROM icbt_programs p
            LEFT JOIN icbt_courses c ON p.id = c.program_id
            WHERE (p.level = ? OR p.level = ? OR p.level = 'After A/L & O/L') AND p.campus = 'ICBT' AND p.status = 'Active'
            ORDER BY p.program_name, c.course_name
        ");
        $stmt->execute([$study_level, $normalized_study_level]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Group by program, but only include programs that are NOT in hardcoded data
        foreach ($results as $row) {
            $program_name = $row['program_name'];
            
            // Skip if this program already exists in hardcoded data
            if (in_array($program_name, $hardcoded_program_names)) {
                continue;
            }
            
            if (!isset($admin_programs[$program_name])) {
                $admin_programs[$program_name] = [];
            }
            
            if ($row['course_name']) {
                $admin_programs[$program_name][$row['course_name']] = [
                    'description' => $row['course_description'] ?: 'Course description not available.',
                    'requirements' => $row['requirement'] ?: 'Requirements not specified.',
                    'duration' => $row['duration'] ?: 'Duration not specified.',
                    'fees' => 'Contact for pricing',
                    'is_admin_added' => true,
                    'program_id' => $row['program_id'],
                    'course_id' => $row['course_id']
                ];
            } else {
                // If no courses, create a program entry with program-level data
                $admin_programs[$program_name]['Program Details'] = [
                    'description' => $row['program_description'] ?: 'Program description not available.',
                    'requirements' => $row['program_requirements'] ?: 'Requirements not specified.',
                    'duration' => $row['duration'] ?: 'Duration not specified.',
                    'fees' => 'Contact for pricing',
                    'is_admin_added' => true,
                    'program_id' => $row['program_id']
                ];
            }
        }
        
    } catch (Exception $e) {
        error_log("Error fetching admin programs: " . $e->getMessage());
    }
    
    return $admin_programs;
}

// ICBT Programs Data with Courses (Hardcoded)
$programs_data = [
    'Postgraduate' => [
        'Business' => [
            'Diploma in Strategic Management and Leadership (OTHM Level 7)' => [
                'description' => 'Advanced leadership and strategic management skills for senior professionals.',
                'requirements' => 'Bachelor degree or equivalent, 3+ years work experience',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ],
            'Diploma in Human Resource Management (OTHM Level 7)' => [
                'description' => 'Comprehensive HR management training for modern workplace challenges.',
                'requirements' => 'Bachelor degree or equivalent, HR experience preferred',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ],
            'MSc in International Relations' => [
                'description' => 'Global perspective on international politics, diplomacy, and foreign policy.',
                'requirements' => 'Bachelor degree in related field, English proficiency',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Engineering & Construction' => [
            'MSc in Civil Engineering' => [
                'description' => 'Advanced civil engineering principles and modern construction techniques.',
                'requirements' => 'BSc in Civil Engineering or related field',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'MSc Quantity Surveying and Commercial Management' => [
                'description' => 'Professional quantity surveying and commercial management expertise.',
                'requirements' => 'BSc in Quantity Surveying or related field',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'MSc Construction Project Management' => [
                'description' => 'Advanced project management skills for construction industry.',
                'requirements' => 'BSc in Construction or related field',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Information Technology' => [
            'Master of Science in Information Technology (MSc IT)' => [
                'description' => 'Advanced IT skills and knowledge for technology leadership roles.',
                'requirements' => 'BSc in IT or Computer Science',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'MSc in Data Science' => [
                'description' => 'Data analysis, machine learning, and statistical modeling expertise.',
                'requirements' => 'BSc in Mathematics, Statistics, or Computer Science',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Science' => [
            'MSc Applied Psychology & Behavior Change' => [
                'description' => 'Applied psychology principles for behavior modification and therapy.',
                'requirements' => 'BSc in Psychology or related field',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Law' => [
            'Master of Laws in International Business (LLM)' => [
                'description' => 'Advanced legal studies focusing on international business law, corporate governance, and cross-border transactions.',
                'requirements' => 'LLB degree or equivalent, minimum 2.1 GPA, English proficiency',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ]
        ]
    ],
    'Undergraduate' => [
        'Business' => [
            'Extended Diploma (Higher Diploma) in Logistics and Supply Chain Management (Level 5)' => [
                'description' => 'Comprehensive logistics and supply chain management training.',
                'requirements' => 'A/L qualification or equivalent',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'Higher Diploma in Digital Marketing' => [
                'description' => 'Modern digital marketing strategies and tools for business growth.',
                'requirements' => 'A/L qualification or equivalent',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'Higher Diploma in Business Management' => [
                'description' => 'Fundamental business management principles and practices.',
                'requirements' => 'A/L qualification or equivalent',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Engineering & Construction' => [
            'Professional Diploma in Quantity Surveying' => [
                'description' => 'Professional quantity surveying skills for construction industry.',
                'requirements' => 'A/L qualification with Mathematics',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'Higher Diploma in Automotive Engineering' => [
                'description' => 'Automotive engineering principles and modern vehicle technology.',
                'requirements' => 'A/L qualification with Physics and Mathematics',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'Higher Diploma in Quantity Surveying' => [
                'description' => 'Quantity surveying fundamentals and cost management.',
                'requirements' => 'A/L qualification with Mathematics',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Information Technology' => [
            'Higher Diploma in Computing and Software Engineering' => [
                'description' => 'Software development and computing fundamentals.',
                'requirements' => 'A/L qualification with Mathematics',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'Higher Diploma in Network Technology & Cyber Security' => [
                'description' => 'Network administration and cybersecurity expertise.',
                'requirements' => 'A/L qualification with Mathematics',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'BSc (Hons) Data Science' => [
                'description' => 'Data science fundamentals and analytical skills.',
                'requirements' => 'A/L qualification with Mathematics',
                'duration' => '3 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'English' => [
            'Higher Diploma in English' => [
                'description' => 'Advanced English language skills and literature studies.',
                'requirements' => 'A/L qualification with English',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ],
            'BA (Hons) in English' => [
                'description' => 'Comprehensive English literature and language studies.',
                'requirements' => 'A/L qualification with English',
                'duration' => '3 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Law' => [
            'LLB (Hons) Law' => [
                'description' => 'Comprehensive legal education and professional law training.',
                'requirements' => 'A/L qualification, English proficiency',
                'duration' => '3 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ]
    ],
    'After O/L & A/L' => [
        'Business' => [
            'Higher Diploma in Digital Marketing' => [
                'description' => 'Digital marketing strategies for modern business success.',
                'requirements' => 'O/L qualification or equivalent',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ],
            'British Foundation Diploma for Higher Education Studies' => [
                'description' => 'Foundation studies for higher education preparation.',
                'requirements' => 'O/L qualification or equivalent',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ],
            'Diploma in Tourism and Hospitality Management (OTHM Level 7)' => [
                'description' => 'Professional tourism and hospitality management training.',
                'requirements' => 'A/L qualification or equivalent',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Engineering & Construction' => [
            'Certificate Course in Robotics' => [
                'description' => 'Introduction to robotics and automation systems.',
                'requirements' => 'O/L qualification with Science',
                'duration' => '6 months full-time',
                'fees' => 'Contact for pricing'
            ],
            'Certificate Course in Aerial Vehicle (DRONES)' => [
                'description' => 'Drone technology and aerial vehicle operations.',
                'requirements' => 'O/L qualification or equivalent',
                'duration' => '6 months full-time',
                'fees' => 'Contact for pricing'
            ],
            'Foundation in Engineering' => [
                'description' => 'Foundation studies for engineering degree programs.',
                'requirements' => 'O/L qualification with Mathematics and Science',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Information Technology' => [
            'International Diploma in Information & Communication Technology' => [
                'description' => 'Comprehensive ICT skills and knowledge.',
                'requirements' => 'O/L qualification or equivalent',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ],
            'Higher Diploma in Computing and Software Engineering' => [
                'description' => 'Software engineering and computing fundamentals.',
                'requirements' => 'A/L qualification with Mathematics',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ],
        'Science' => [
            'Foundation in Biomedical Science' => [
                'description' => 'Foundation studies for biomedical science careers.',
                'requirements' => 'A/L qualification with Biology and Chemistry',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ],
            'International Diploma in Psychology' => [
                'description' => 'Introduction to psychology principles and practices.',
                'requirements' => 'A/L qualification or equivalent',
                'duration' => '1 year full-time',
                'fees' => 'Contact for pricing'
            ],
            'Higher Diploma in Psychology' => [
                'description' => 'Advanced psychology studies and research methods.',
                'requirements' => 'A/L qualification or equivalent',
                'duration' => '2 years full-time',
                'fees' => 'Contact for pricing'
            ]
        ]
    ]
];

// Get programs for the selected study level
$normalized_study_level = normalizeStudyLevel($study_level);
$programs = isset($programs_data[$normalized_study_level]) ? $programs_data[$normalized_study_level] : [];

// Fetch admin-added programs and courses from database
$admin_programs = [];
try {
    $pdo = getDBConnection();
    $admin_programs = fetchAdminProgramsAndCourses($pdo, $study_level, $programs_data);
} catch (Exception $e) {
    error_log("Error connecting to database: " . $e->getMessage());
}

// Merge hardcoded programs with admin-added programs
if (!empty($admin_programs)) {
    // Add each admin program as its own category (not under "Admin Programs")
    foreach ($admin_programs as $program_name => $courses) {
        $programs[$program_name] = $courses;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICBT Campus - <?php echo htmlspecialchars($study_level); ?> Programs - EduConnectSL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="courses.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables - Matching Website Theme */
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --secondary-color: #10b981;
            --secondary-dark: #059669;
            --accent-color: #f59e0b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --border-radius-lg: 12px;
            --transition: all 0.3s ease;
            --gradient-primary: linear-gradient(135deg, #2563eb, #1d4ed8);
            --gradient-secondary: linear-gradient(135deg, #10b981, #059669);
            --gradient-hero: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(16, 185, 129, 0.9));
        }

        .programs-content {
            padding: 80px 0;
            background: var(--bg-secondary);
            min-height: 60vh;
        }
        
        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2.5rem;
            margin-top: 3rem;
        }
        
        .program-card {
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 300px;
        }
        
        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-primary);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }
        
        .program-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }
        
        .program-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.02), rgba(16, 185, 129, 0.02));
            opacity: 0;
            transition: var(--transition);
            pointer-events: none;
        }
        
        .program-card:hover::after {
            opacity: 1;
        }
        
        .program-category {
            font-size: 1.25rem;
            color: var(--primary-color);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--primary-light);
        }
        
        .program-category::before {
            content: '';
            width: 12px;
            height: 12px;
            background: var(--accent-color);
            border-radius: 50%;
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        
        .program-count {
            margin-left: auto;
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
            background: var(--bg-tertiary);
            padding: 0.25rem 0.75rem;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
        }
        
        .program-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .program-list li {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 1.1rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            padding-left: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 0.5rem;
            background: var(--bg-secondary);
        }
        
        .program-list li::before {
            content: '→';
            position: absolute;
            left: 0.75rem;
            color: var(--primary-color);
            font-weight: bold;
            opacity: 0;
            transition: var(--transition);
            font-size: 1.2rem;
        }
        
        .program-list li:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .program-list li:hover {
            background: var(--primary-light);
            padding-left: 2.5rem;
            color: var(--primary-color);
            transform: translateX(4px);
            box-shadow: var(--shadow-sm);
        }
        
        .program-list li:hover::before {
            opacity: 1;
        }
        
        .back-button {
            margin-bottom: 2rem;
        }
        
        .back-button .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .back-button .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            position: relative;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 60%, var(--accent-color) 60%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .section-header .university-name {
            color: var(--primary-color);
        }
        
        .section-header .study-level-text {
            color: var(--accent-color);
        }
        
        @media (max-width: 768px) {
            .programs-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .section-header h2 {
                font-size: 2rem;
            }
            
            .programs-content {
                padding: 60px 0;
            }
            
            .program-card {
                padding: 2rem;
                min-height: auto;
            }
            
            .program-category {
                font-size: 1.1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .program-count {
                margin-left: 0;
                align-self: flex-start;
            }
            
            .program-list li {
                padding: 1rem;
                font-size: 1rem;
            }
        }
        
        /* Course Details Modal Styles */
        .course-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .course-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f3f4;
        }
        
        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }
        
        .close-modal:hover {
            background-color: #f1f3f4;
        }
        
        .course-details-table {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .course-details-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .course-details-cell {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .course-details-header {
            font-weight: 600;
            color: #667eea;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .course-details-content {
            color: #333;
            font-size: 1rem;
            line-height: 1.5;
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        
        .btn-apply {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-apply:hover {
            background: #5a6fd8;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-back:hover {
            background: #5a6268;
        }
        
        /* Application Form Styles */
        .application-form {
            display: none;
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h3 {
            color: #667eea;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f1f3f4;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group .required {
            color: #e74c3c;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        
        .form-message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .form-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
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

    <!-- Programs Content -->
    <section class="programs-content">
        <div class="container">
            <div class="section-header">
                <div class="back-button">
                    <a href="ICBT_study_levels.php" class="btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Study Levels
                    </a>
                </div>
                <h2>
                    <span class="university-name">ICBT Campus</span> - <span class="study-level-text"><?php echo htmlspecialchars($study_level); ?> Programs</span>
                </h2>
            </div>
            
            <?php if (empty($programs)): ?>
            <div style="text-align: center; padding: 3rem;">
                <h3>No programs found for this study level.</h3>
                <p>Please select a different study level.</p>
            </div>
            <?php else: ?>
            <div class="programs-grid">
                <?php foreach ($programs as $category => $program_list): ?>
                <div class="program-card">
                    <div class="program-category">
                        <i class="fas fa-graduation-cap"></i>
                        <?php echo htmlspecialchars($category); ?>
                        <span class="program-count"><?php echo count($program_list); ?> Programs</span>
                    </div>
                    <ul class="program-list">
                        <?php foreach ($program_list as $program_name => $course_data): ?>
                        <li onclick="showCourseDetails('<?php echo htmlspecialchars($program_name); ?>', '<?php echo htmlspecialchars($category); ?>', '<?php echo htmlspecialchars($study_level); ?>', 'ICBT Campus')">
                            <?php echo htmlspecialchars($program_name); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>EduConnectSL</h3>
                    <p>Connecting students with quality education opportunities across Sri Lanka.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="courses.php">Courses</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <p>Email: info@educonnectsl.com</p>
                    <p>Phone: +94 11 234 5678</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 EduConnectSL. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Course Details Modal -->
    <div id="courseModal" class="course-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalCourseTitle">Course Details</h2>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Course Details Content -->
            <div id="modalCourseContent">
                <div class="course-details-table">
                    <div class="course-details-row">
                        <div class="course-details-cell">
                            <div class="course-details-header">Program:</div>
                            <div class="course-details-content" id="modalProgramName"></div>
                        </div>
                        <div class="course-details-cell">
                            <div class="course-details-header">Course:</div>
                            <div class="course-details-content" id="modalCourseName"></div>
                        </div>
                        <div class="course-details-cell">
                            <div class="course-details-header">Description:</div>
                            <div class="course-details-content" id="modalDescription"></div>
                        </div>
                        <div class="course-details-cell">
                            <div class="course-details-header">Requirements:</div>
                            <div class="course-details-content" id="modalRequirements"></div>
                        </div>
                        <div class="course-details-cell">
                            <div class="course-details-header">Duration:</div>
                            <div class="course-details-content" id="modalDuration"></div>
                        </div>
                        <div class="course-details-cell">
                            <div class="course-details-header">Fees:</div>
                            <div class="course-details-content" id="modalFees"></div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button class="btn-back" onclick="closeModal()">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </button>
                    <button class="btn-apply" onclick="showApplicationForm()">
                        <i class="fas fa-paper-plane"></i>
                        Apply Now
                    </button>
                </div>
            </div>
            
            <!-- Application Form -->
            <div id="applicationForm" class="application-form">
                <form id="courseApplicationForm">
                    <div class="form-section">
                        <h3>Personal Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="dateOfBirth">Date of Birth <span class="required">*</span></label>
                                <input type="date" id="dateOfBirth" name="dateOfBirth" required>
                            </div>
                            <div class="form-group">
                                <label for="highestQualification">Highest Qualification <span class="required">*</span></label>
                                <select id="highestQualification" name="highestQualification" required>
                                    <option value="">Select Qualification</option>
                                    <option value="O/L">O/L</option>
                                    <option value="A/L">A/L</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Bachelor">Bachelor's Degree</option>
                                    <option value="Master">Master's Degree</option>
                                    <option value="PhD">PhD</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="institution">Institution (Optional)</label>
                                <input type="text" id="institution" name="institution">
                            </div>
                            <div class="form-group">
                                <label for="graduationYear">Graduation Year (Optional)</label>
                                <input type="number" id="graduationYear" name="graduationYear" min="1950" max="2024">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Course Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="courseName">Course Name</label>
                                <input type="text" id="courseName" name="courseName" readonly>
                            </div>
                            <div class="form-group">
                                <label for="universityName">University</label>
                                <input type="text" id="universityName" name="universityName" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="studyLevel">Study Level</label>
                                <input type="text" id="studyLevel" name="studyLevel" readonly>
                            </div>
                            <div class="form-group">
                                <label for="program">Program</label>
                                <input type="text" id="program" name="program" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Declaration</h3>
                        <div class="checkbox-group">
                            <input type="checkbox" id="declaration" name="declaration" required>
                            <label for="declaration">I declare that all information provided is true and accurate <span class="required">*</span></label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="termsConditions" name="termsConditions" required>
                            <label for="termsConditions">I agree to the terms and conditions <span class="required">*</span></label>
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn-back" onclick="handleModalBack()">
                            <i class="fas fa-arrow-left"></i>
                            Back to Course Details
                        </button>
                        <button type="button" class="btn-apply" id="submitApplicationBtn" onclick="submitApplication()">
                            <i class="fas fa-paper-plane"></i>
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Global variables to track current state
        let currentCourse = null;
        let currentProgram = null;
        let currentStudyLevel = null;
        let currentUniversity = null;
        
        // Course data from PHP (hardcoded)
        const courseData = <?php echo json_encode($programs_data); ?>;
        
        // Admin-added course data from PHP
        const adminCourseData = <?php echo json_encode($admin_programs); ?>;
        
        // Study level normalization function (matches PHP function)
        function normalizeStudyLevel(studyLevel) {
            const levelMapping = {
                'After A/L & O/L': 'After O/L & A/L',
                'After A/L': 'After O/L & A/L',
                'After O/L': 'After O/L & A/L'
            };
            return levelMapping[studyLevel] || studyLevel;
        }
        
        // Add card animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.program-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
        
        // Show course details modal
        function showCourseDetails(courseName, program, studyLevel, university) {
            currentCourse = courseName;
            currentProgram = program;
            currentStudyLevel = studyLevel;
            currentUniversity = university;
            
            // Normalize study level for data lookup
            const normalizedStudyLevel = normalizeStudyLevel(studyLevel);
            
            // Try to get course data from hardcoded data first
            let courseInfo = null;
            if (courseData[normalizedStudyLevel] && courseData[normalizedStudyLevel][program] && courseData[normalizedStudyLevel][program][courseName]) {
                courseInfo = courseData[normalizedStudyLevel][program][courseName];
            } else if (adminCourseData[program] && adminCourseData[program][courseName]) {
                // If not found in hardcoded data, try admin-added data
                courseInfo = adminCourseData[program][courseName];
            }
            
            // If course info is not found, show error
            if (!courseInfo) {
                alert('Course information not found. Please try again.');
                return;
            }
            
            // Update modal content
            document.getElementById('modalCourseTitle').textContent = courseName;
            document.getElementById('modalProgramName').textContent = program;
            document.getElementById('modalCourseName').textContent = courseName;
            document.getElementById('modalDescription').textContent = courseInfo.description;
            document.getElementById('modalRequirements').textContent = courseInfo.requirements;
            document.getElementById('modalDuration').textContent = courseInfo.duration;
            document.getElementById('modalFees').textContent = courseInfo.fees;
            
            // Pre-fill form fields
            document.getElementById('courseName').value = courseName;
            document.getElementById('universityName').value = university;
            document.getElementById('studyLevel').value = studyLevel;
            document.getElementById('program').value = program;
            
            // Show modal
            const modal = document.getElementById('courseModal');
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }
        
        // Show application form
        function showApplicationForm() {
            const applicationForm = document.getElementById('applicationForm');
            const courseContent = document.getElementById('modalCourseContent');
            
            // Hide course content and show application form
            courseContent.style.display = 'none';
            applicationForm.style.display = 'block';
            
            // Pre-fill form with user data if available
            prefillApplicationForm();
        }
        
        // Handle modal back button
        function handleModalBack() {
            const applicationForm = document.getElementById('applicationForm');
            const courseContent = document.getElementById('modalCourseContent');
            
            // If application form is showing, go back to course details
            applicationForm.style.display = 'none';
            courseContent.style.display = 'block';
        }
        
        // Pre-fill application form
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
        }
        
        // Submit application
        function submitApplication() {
            const form = document.getElementById('courseApplicationForm');
            const submitBtn = document.getElementById('submitApplicationBtn');
            
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
                    
                    // Close modal after 2 seconds
                    setTimeout(() => {
                        closeModal();
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
            const modal = document.getElementById('courseModal');
            const applicationForm = document.getElementById('applicationForm');
            const courseContent = document.getElementById('modalCourseContent');
            
            // Reset modal state
            applicationForm.style.display = 'none';
            courseContent.style.display = 'block';
            
            // Close modal
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('courseModal');
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('courseModal');
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });
    </script>
</body>
</html>
