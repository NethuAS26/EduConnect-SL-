<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_type = $_SESSION['user_type'];

// Get database connection
$pdo = getDBConnection();

// Fetch NIBM programs from database
try {
    $stmt = $pdo->prepare("
        SELECT 
            level,
            COUNT(*) as count,
            GROUP_CONCAT(DISTINCT program_name) as programs
        FROM nibm_programs 
        WHERE campus = 'NIBM' AND status = 'Active'
        GROUP BY level
        ORDER BY 
            CASE level
                WHEN 'Masters Programme' THEN 1
                WHEN 'Degree (Undergraduate)' THEN 2
                WHEN 'Advanced Diploma / Diploma' THEN 3
                WHEN 'Certificate & Advanced Certificate' THEN 4
                WHEN 'Foundation Programme' THEN 5
                ELSE 6
            END
    ");
    $stmt->execute();
    $programs_by_level = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Build study levels data from database
    $study_levels = [];
    foreach ($programs_by_level as $level_data) {
        $study_levels[$level_data['level']] = [
            'count' => (int)$level_data['count'],
            'description' => getLevelDescription($level_data['level']),
            'programs' => explode(',', $level_data['programs'])
        ];
    }
    
    // Add default levels if no data exists
    $default_levels = [
        'Masters Programme' => [
            'count' => 0,
            'description' => 'Advanced postgraduate programs for specialized expertise',
            'programs' => []
        ],
        'Degree (Undergraduate)' => [
            'count' => 0,
            'description' => 'Bachelor degree programs for academic excellence',
            'programs' => []
        ],
        'Advanced Diploma / Diploma' => [
            'count' => 0,
            'description' => 'Professional diploma programs for career advancement',
            'programs' => []
        ],
        'Certificate & Advanced Certificate' => [
            'count' => 0,
            'description' => 'Certificate programs for skill development',
            'programs' => []
        ],
        'Foundation Programme' => [
            'count' => 0,
            'description' => 'Foundation programs for academic preparation',
            'programs' => []
        ]
    ];
    
    // Merge database data with defaults
    foreach ($default_levels as $level => $default_data) {
        if (!isset($study_levels[$level])) {
            $study_levels[$level] = $default_data;
        }
    }
    
} catch (Exception $e) {
    error_log("Error fetching NIBM programs: " . $e->getMessage());
    // Fallback to default data if database error occurs
$study_levels = [
        'Masters Programme' => [
            'count' => 0,
            'description' => 'Advanced postgraduate programs for specialized expertise',
            'programs' => []
    ],
    'Degree (Undergraduate)' => [
            'count' => 0,
            'description' => 'Bachelor degree programs for academic excellence',
            'programs' => []
    ],
    'Advanced Diploma / Diploma' => [
            'count' => 0,
            'description' => 'Professional diploma programs for career advancement',
            'programs' => []
    ],
    'Certificate & Advanced Certificate' => [
            'count' => 0,
            'description' => 'Certificate programs for skill development',
            'programs' => []
    ],
    'Foundation Programme' => [
            'count' => 0,
            'description' => 'Foundation programs for academic preparation',
            'programs' => []
        ]
    ];
}

function getLevelDescription($level) {
    $descriptions = [
        'Masters Programme' => 'Advanced postgraduate programs for specialized expertise',
        'Degree (Undergraduate)' => 'Bachelor degree programs for academic excellence',
        'Advanced Diploma / Diploma' => 'Professional diploma programs for career advancement',
        'Certificate & Advanced Certificate' => 'Certificate programs for skill development',
        'Foundation Programme' => 'Foundation programs for academic preparation'
    ];
    
    return $descriptions[$level] ?? 'Academic programs for professional development';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIBM - Study Levels - EduConnectSL</title>
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

        .study-levels-content {
            padding: 80px 0;
            background: var(--bg-secondary);
            min-height: 60vh;
        }
        
        .study-levels-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .study-level-card {
            background: var(--bg-primary);
            border-radius: var(--border-radius-lg);
            padding: 0;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 550px;
            max-width: 350px;
            margin: 0 auto;
        }
        
        .study-level-card .card-image {
            width: 100%;
            height: 280px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            position: relative;
        }
        
        .study-level-card .card-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
            pointer-events: none;
        }
        
        .study-level-card .card-content {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }
        
        .study-level-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-primary);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }
        
        .study-level-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }
        
        .study-level-card::after {
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
        
        .study-level-card:hover::after {
            opacity: 1;
        }
        
        .study-level-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
            line-height: 1.3;
            position: relative;
        }
        
        .study-level-card h3::after {
            content: '';
            position: absolute;
            bottom: -0.75rem;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }
        
        .study-level-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            text-align: center;
            flex-grow: 1;
        }
        
        .study-level-card .program-count {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            background: var(--primary-light);
            border: 2px solid var(--primary-color);
            border-radius: var(--border-radius);
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }
        
        .study-level-card .program-count::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition);
        }
        
        .study-level-card:hover .program-count {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .study-level-card:hover .program-count::before {
            left: 100%;
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
        
        .page-title {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }
        
        .page-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        .page-title .university-name {
            color: var(--primary-color);
        }
        
        .page-title .study-levels-text {
            color: var(--accent-color);
        }
        
        .page-title p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        @media (max-width: 1024px) {
            .study-levels-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
                max-width: 100%;
            }
            
            .study-level-card {
                max-width: none;
                min-height: 500px;
            }
        }
        
        @media (max-width: 768px) {
            .study-levels-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                max-width: 400px;
            }
            
            .page-title h2 {
                font-size: 2rem;
            }
            
            .page-title p {
                font-size: 1rem;
            }
            
            .study-levels-content {
                padding: 60px 0;
            }
            
            .study-level-card {
                min-height: auto;
                max-width: 350px;
            }
            
            .study-level-card .card-image {
                height: 200px;
            }
            
            .study-level-card h3 {
                font-size: 1.5rem;
            }
            
            .study-level-description {
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }
            
            .study-level-card .card-content {
                padding: 1.5rem;
            }
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

    <!-- Study Levels Content -->
    <section class="study-levels-content">
        <div class="container">
            <div class="back-button">
                <a href="courses.php" class="btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Universities
                </a>
            </div>
            
            <div class="page-title">
                <h2>
                    <span class="university-name">NIBM</span> - <span class="study-levels-text">Study Levels</span>
                </h2>
                <p>Choose from our comprehensive range of study levels and programs</p>
            </div>
            
            <div class="study-levels-grid">
                <?php 
                $card_images = [
                    'Masters Programme' => 'img/NIBM_Program/masters_program.jpg',
                    'Degree (Undergraduate)' => 'img/NIBM_Program/Degree (Undergraduate).jpg',
                    'Advanced Diploma / Diploma' => 'img/NIBM_Program/Advanced Diploma.jpg',
                    'Certificate & Advanced Certificate' => 'img/NIBM_Program/Certificate & Advanced Certificate.jpg',
                    'Foundation Programme' => 'img/NIBM_Program/Foundation Programme.jpg'
                ];
                foreach ($study_levels as $level => $data): ?>
                <div class="study-level-card" onclick="selectStudyLevel('<?php echo $level; ?>')">
                    <div class="card-image" style="background-image: url('<?php echo isset($card_images[$level]) ? $card_images[$level] : 'img/NIBM_Program/masters_program.jpg'; ?>');"></div>
                    <div class="card-content">
                        <h3><?php echo $level; ?></h3>
                        <p class="study-level-description"><?php echo $data['description']; ?></p>
                        <div class="program-count">
                            <i class="fas fa-book-open"></i>
                            <?php echo $data['count']; ?> Programs Available
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
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

    <script>
        function selectStudyLevel(studyLevel) {
            // For now, redirect to a programs page for the selected study level
            // You can create individual program pages for each study level
            window.location.href = `NIBM_programs.php?level=${encodeURIComponent(studyLevel)}`;
        }
        
        // Add card animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.study-level-card');
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
    </script>
</body>
</html>
