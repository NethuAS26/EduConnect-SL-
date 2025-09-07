<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    error_log('Admin authentication failed. Session data: ' . json_encode($_SESSION));
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access - Admin not logged in']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'update_application_status':
            handleUpdateApplicationStatus();
            break;
        case 'add_program':
            handleAddProgram();
            break;
        case 'add_course':
            handleAddCourse();
            break;
        case 'edit_course':
            handleEditCourse();
            break;
        case 'delete_course':
            handleDeleteCourse();
            break;
        case 'get_course_details':
            handleGetCourseDetails();
            break;
        case 'add_program_to_db':
            handleAddProgramToDatabase();
            break;
        case 'add_faculty':
            handleAddFaculty();
            break;
        case 'edit_faculty':
            handleEditFaculty();
            break;
        case 'delete_faculty':
            handleDeleteFaculty();
            break;
        case 'clear_all_courses':
            handleClearAllCourses();
            break;
        case 'delete_program':
            handleDeleteProgram();
            break;
        case 'add_nibm_program':
            handleAddNIBMProgram();
            break;
        case 'add_nibm_course':
            handleAddNIBMCourse();
            break;
        case 'delete_nibm_program':
            handleDeleteNIBMProgram();
            break;
        case 'delete_nibm_course':
            handleDeleteNIBMCourse();
            break;
        case 'add_peradeniya_program':
            handleAddPeradeniyaProgram();
            break;
        case 'add_peradeniya_course':
            handleAddPeradeniyaCourse();
            break;
        case 'delete_peradeniya_program':
            handleDeletePeradeniyaProgram();
            break;
        case 'delete_peradeniya_course':
            handleDeletePeradeniyaCourse();
            break;
        case 'add_moratuwa_program':
            handleAddMoratuwaProgram();
            break;
        case 'add_moratuwa_course':
            handleAddMoratuwaCourse();
            break;
        case 'delete_moratuwa_program':
            handleDeleteMoratuwaProgram();
            break;
        case 'delete_moratuwa_course':
            handleDeleteMoratuwaCourse();
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    error_log('Admin course action error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error']);
}

function handleUpdateApplicationStatus() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $application_id = $_POST['application_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    // Validate inputs
    if (empty($application_id) || empty($status)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    // Validate status
    $valid_statuses = ['pending', 'approved', 'rejected', 'waitlisted'];
    if (!in_array($status, $valid_statuses)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        return;
    }
    
    try {
        // Get the old status before updating
        $oldStatusStmt = $pdo->prepare("SELECT status FROM course_applications WHERE id = ?");
        $oldStatusStmt->execute([$application_id]);
        $oldStatus = $oldStatusStmt->fetchColumn();
        
        // Update application status
        $updateStmt = $pdo->prepare("
            UPDATE course_applications 
            SET 
                status = ?,
                review_date = CURRENT_TIMESTAMP,
                reviewed_by = ?
            WHERE id = ?
        ");
        
        $updateStmt->execute([$status, $_SESSION['admin_id'], $application_id]);
        
        if ($updateStmt->rowCount() > 0) {
            // Log the status change with the correct old status
            $logStmt = $pdo->prepare("
                INSERT INTO application_status_log (application_id, old_status, new_status, reviewer_id)
                VALUES (?, ?, ?, ?)
            ");
            $logStmt->execute([$application_id, $oldStatus, $status, $_SESSION['admin_id']]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Application status updated successfully',
                'new_status' => $status
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Application not found']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in updateApplicationStatus: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    }
}

function handleAddProgram() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programCode = $_POST['programCode'] ?? '';
    $programName = $_POST['programName'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $level = $_POST['level'] ?? '';
    $description = $_POST['description'] ?? '';
    $requirements = $_POST['requirements'] ?? '';
    $maxCapacity = $_POST['maxCapacity'] ?? '';
    $campus = $_POST['campus'] ?? '';
    $facultyId = $_POST['faculty_id'] ?? null;
    
    // Validate required fields
    if (empty($programCode) || empty($programName) || empty($duration) || empty($level) || empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program code already exists
        $checkStmt = $pdo->prepare("SELECT id FROM icbt_programs WHERE program_code = ?");
        $checkStmt->execute([$programCode]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program code already exists']);
            return;
        }
        
        // Insert new program
        $insertStmt = $pdo->prepare("
            INSERT INTO icbt_programs (
                program_code, 
                program_name, 
                duration, 
                level, 
                description, 
                requirements,
                max_capacity, 
                campus, 
                faculty_id,
                status, 
                created_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', CURRENT_TIMESTAMP)
        ");
        
        $insertStmt->execute([
            $programCode,
            $programName,
            $duration,
            $level,
            $description,
            $requirements,
            $maxCapacity ?: null,
            $campus,
            $facultyId
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Program added successfully',
                'program_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add program']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleAddCourse() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseName = $_POST['courseName'] ?? '';
    $courseDescription = $_POST['courseDescription'] ?? '';
    $courseRequirement = $_POST['courseRequirement'] ?? '';
    $programName = $_POST['programName'] ?? '';
    $studyLevel = $_POST['studyLevel'] ?? '';
    $campus = $_POST['campus'] ?? '';
    
    // Debug logging
    error_log("handleAddCourse called with: " . json_encode($_POST));
    error_log("Course Description received: '" . $courseDescription . "'");
    error_log("Course Requirement received: '" . $courseRequirement . "'");
    
    // Validate required fields
    if (empty($courseName) || empty($campus)) {
        error_log("Validation failed: courseName=" . $courseName . ", campus=" . $campus);
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // First, find the program ID based on program name and campus
        $programStmt = $pdo->prepare("SELECT id FROM icbt_programs WHERE program_name = ? AND campus = ? LIMIT 1");
        $programStmt->execute([$programName, $campus]);
        
        error_log("Looking for program: " . $programName . " in campus: " . $campus);
        error_log("Program search result rows: " . $programStmt->rowCount());
        
        if ($programStmt->rowCount() === 0) {
            // If program doesn't exist, create it
            $programCode = 'PGM' . time();
            error_log("Creating new program with code: " . $programCode);
            
            $insertProgramStmt = $pdo->prepare("
                INSERT INTO icbt_programs (
                    program_code, 
                    program_name, 
                    duration, 
                    level, 
                    description, 
                    max_capacity, 
                    campus, 
                    status, 
                    created_date
                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'Active', CURRENT_TIMESTAMP)
            ");
            
            $insertProgramStmt->execute([
                $programCode,
                $programName,
                '3 Years',
                $studyLevel,
                'Program created automatically',
                100,
                $campus
            ]);
            
            $programId = $pdo->lastInsertId();
            error_log("New program created with ID: " . $programId);
        } else {
            $program = $programStmt->fetch(PDO::FETCH_ASSOC);
            $programId = $program['id'];
            error_log("Found existing program with ID: " . $programId);
        }
        
        // Check if course name already exists for this program
        $checkStmt = $pdo->prepare("SELECT id FROM icbt_courses WHERE course_name = ? AND program_id = ?");
        $checkStmt->execute([$courseName, $programId]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Course name already exists for this program']);
            return;
        }
        
        // Insert new course
        error_log("Inserting course with program_id: " . $programId . ", course_name: " . $courseName);
        
        $insertStmt = $pdo->prepare("
            INSERT INTO icbt_courses (
                program_id,
                course_name, 
                course_description, 
                requirement, 
                status, 
                campus, 
                created_date
            ) VALUES (?, ?, ?, ?, 'Active', ?, CURRENT_TIMESTAMP)
        ");
        
        error_log("About to insert course with values:");
        error_log("Program ID: " . $programId);
        error_log("Course Name: '" . $courseName . "'");
        error_log("Course Description: '" . $courseDescription . "'");
        error_log("Course Requirement: '" . $courseRequirement . "'");
        error_log("Campus: '" . $campus . "'");
        
        $insertStmt->execute([
            $programId,
            $courseName,
            $courseDescription,
            $courseRequirement,
            $campus
        ]);
        
        error_log("Course insert result: " . $insertStmt->rowCount() . " rows affected");
        
        if ($insertStmt->rowCount() > 0) {
            $courseId = $pdo->lastInsertId();
            error_log("Course inserted successfully with ID: " . $courseId);
            echo json_encode([
                'success' => true, 
                'message' => 'Course added successfully',
                'course_id' => $courseId,
                'course_name' => $courseName
            ]);
        } else {
            error_log("Failed to insert course");
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add course']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddCourse: ' . $e->getMessage());
        error_log('Error details: ' . $e->getTraceAsString());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleEditCourse() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseId = $_POST['courseId'] ?? '';
    $courseName = $_POST['courseName'] ?? '';
    $courseDescription = $_POST['courseDescription'] ?? '';
    $courseRequirement = $_POST['courseRequirement'] ?? '';
    $courseStatus = $_POST['courseStatus'] ?? 'Active';
    $campus = $_POST['campus'] ?? '';
    
    // Validate required fields
    if (empty($courseId) || empty($courseName) || empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if course name already exists in another course (excluding current course)
        $checkStmt = $pdo->prepare("SELECT id FROM icbt_courses WHERE course_name = ? AND id != ?");
        $checkStmt->execute([$courseName, $courseId]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Course name already exists']);
            return;
        }
        
        // Update course
        $updateStmt = $pdo->prepare("
            UPDATE icbt_courses 
            SET 
                course_name = ?,
                course_description = ?, 
                requirement = ?, 
                status = ?, 
                updated_date = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        $updateStmt->execute([
            $courseName,
            $courseDescription,
            $courseRequirement,
            $courseStatus,
            $courseId
        ]);
        
        if ($updateStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Course updated successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Course not found or no changes made']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleEditCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeleteCourse() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseId = $_POST['course_id'] ?? '';
    $courseCode = $_POST['course_code'] ?? '';
    $campus = $_POST['campus'] ?? '';
    
    // Validate required fields
    if (empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing campus information']);
        return;
    }
    
    if (empty($courseId) && empty($courseCode)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing course ID or course code']);
        return;
    }
    
    try {
        // Delete course by course ID or course code
        if (!empty($courseId)) {
            // Delete by course ID (new method)
            $deleteStmt = $pdo->prepare("DELETE FROM icbt_courses WHERE id = ? AND campus = ?");
            $deleteStmt->execute([$courseId, $campus]);
        } else {
            // Delete by course code (legacy method - but our new table doesn't have course_code)
            // So we'll try to find by course name instead
            $deleteStmt = $pdo->prepare("DELETE FROM icbt_courses WHERE course_name = ? AND campus = ?");
            $deleteStmt->execute([$courseCode, $campus]);
        }
        
        if ($deleteStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Course deleted successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Course not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleDeleteCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleGetCourseDetails() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseId = $_GET['course_id'] ?? '';
    
    // Validate required fields
    if (empty($courseId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing course ID']);
        return;
    }
    
    try {
        // Get course details
        $selectStmt = $pdo->prepare("
            SELECT 
                course_name,
                course_description, 
                requirement, 
                status
            FROM icbt_courses 
            WHERE id = ?
        ");
        $selectStmt->execute([$courseId]);
        
        if ($selectStmt->rowCount() > 0) {
            $course = $selectStmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true, 
                'course_name' => $course['course_name'],
                'course_description' => $course['course_description'],
                'requirement' => $course['requirement'],
                'status' => $course['status']
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Course not found']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleGetCourseDetails: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleAddProgramToDatabase() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programName = $_POST['program_name'] ?? '';
    $campus = $_POST['campus'] ?? '';
    $category = $_POST['category'] ?? '';
    
    // Validate required fields
    if (empty($programName) || empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program already exists
        $checkStmt = $pdo->prepare("SELECT id FROM icbt_programs WHERE program_name = ? AND campus = ?");
        $checkStmt->execute([$programName, $campus]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program already exists in database']);
            return;
        }
        
        // Generate a unique program code
        $programCode = generateProgramCode($programName, $category);
        
        // Determine level and duration based on category
        $level = determineLevel($category);
        $duration = determineDuration($category);
        
        // Insert new program
        $insertStmt = $pdo->prepare("
            INSERT INTO icbt_programs (
                program_code, 
                program_name, 
                duration, 
                level, 
                description, 
                max_capacity, 
                campus, 
                status, 
                created_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'Active', CURRENT_TIMESTAMP)
        ");
        
        $insertStmt->execute([
            $programCode,
            $programName,
            $duration,
            $level,
            'Program added from dashboard',
            100, // Default capacity
            $campus
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Program added to database successfully',
                'program_id' => $pdo->lastInsertId(),
                'program_code' => $programCode
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add program to database']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddProgramToDatabase: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function generateProgramCode($programName, $category) {
    // Generate a unique program code based on category and program name
    $prefix = '';
    switch ($category) {
        case 'Postgraduate':
            $prefix = 'PG';
            break;
        case 'Undergraduate':
            $prefix = 'UG';
            break;
        case 'After A/L':
            $prefix = 'AL';
            break;
        case 'After O/L':
            $prefix = 'OL';
            break;
        default:
            $prefix = 'PR';
    }
    
    // Extract first letters of key words
    $words = explode(' ', $programName);
    $code = $prefix;
    
    foreach ($words as $word) {
        if (strlen($word) > 2 && !in_array(strtolower($word), ['in', 'of', 'and', 'the', 'for', 'to', 'with'])) {
            $code .= strtoupper(substr($word, 0, 1));
        }
    }
    
    // Add a random number to ensure uniqueness
    $code .= rand(100, 999);
    
    return $code;
}

function determineLevel($category) {
    switch ($category) {
        case 'Postgraduate':
            return 'Postgraduate';
        case 'Undergraduate':
            return 'Undergraduate';
        case 'After A/L':
            return 'Advanced Level';
        case 'After O/L':
            return 'Ordinary Level';
        default:
            return 'Other';
    }
}

function determineDuration($category) {
    switch ($category) {
        case 'Postgraduate':
            return '1-2 Years';
        case 'Undergraduate':
            return '3-4 Years';
        case 'After A/L':
            return '1-2 Years';
        case 'After O/L':
            return '1 Year';
        default:
            return 'Variable';
    }
}

function handleAddFaculty() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $category = $_POST['facultyCategory'] ?? '';
    $facultyName = $_POST['facultyName'] ?? '';
    $description = $_POST['facultyDescription'] ?? '';
    $campus = $_POST['campus'] ?? '';
    
    // Validate required fields
    if (empty($category) || empty($facultyName) || empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if faculty already exists in this category
        $checkStmt = $pdo->prepare("SELECT id FROM icbt_faculties WHERE faculty_name = ? AND category = ? AND campus = ?");
        $checkStmt->execute([$facultyName, $category, $campus]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Faculty already exists in this category']);
            return;
        }
        
        // Insert new faculty
        $insertStmt = $pdo->prepare("
            INSERT INTO icbt_faculties (
                faculty_name, 
                category, 
                description, 
                campus, 
                status, 
                created_date
            ) VALUES (?, ?, ?, ?, 'Active', CURRENT_TIMESTAMP)
        ");
        
        $insertStmt->execute([
            $facultyName,
            $category,
            $description,
            $campus
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Faculty added successfully',
                'faculty_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add faculty']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddFaculty: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleEditFaculty() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $oldFacultyName = $_POST['oldFacultyName'] ?? '';
    $facultyName = $_POST['facultyName'] ?? '';
    $category = $_POST['facultyCategory'] ?? '';
    $description = $_POST['facultyDescription'] ?? '';
    $campus = $_POST['campus'] ?? '';
    
    // Validate required fields
    if (empty($oldFacultyName) || empty($facultyName) || empty($category) || empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if new faculty name already exists in this category (excluding current faculty)
        $checkStmt = $pdo->prepare("SELECT id FROM icbt_faculties WHERE faculty_name = ? AND category = ? AND campus = ? AND faculty_name != ?");
        $checkStmt->execute([$facultyName, $category, $campus, $oldFacultyName]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Faculty name already exists in this category']);
            return;
        }
        
        // Update faculty
        $updateStmt = $pdo->prepare("
            UPDATE icbt_faculties 
            SET faculty_name = ?, description = ?, updated_date = CURRENT_TIMESTAMP
            WHERE faculty_name = ? AND category = ? AND campus = ?
        ");
        
        $updateStmt->execute([
            $facultyName,
            $description,
            $oldFacultyName,
            $category,
            $campus
        ]);
        
        if ($updateStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Faculty updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update faculty']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleEditFaculty: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeleteFaculty() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $facultyName = $_POST['faculty_name'] ?? '';
    $categoryName = $_POST['category_name'] ?? '';
    $campus = $_POST['campus'] ?? '';
    
    // Validate required fields
    if (empty($facultyName) || empty($categoryName) || empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Delete faculty
        $deleteFacultyStmt = $pdo->prepare("DELETE FROM icbt_faculties WHERE faculty_name = ? AND category = ? AND campus = ?");
        $deleteFacultyStmt->execute([$facultyName, $categoryName, $campus]);
        
        // Note: In a real application, you might want to handle programs and courses differently
        // For now, we'll just delete the faculty and let the admin handle the cleanup
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Faculty deleted successfully'
        ]);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Database error in handleDeleteFaculty: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleClearAllCourses() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $campus = $_POST['campus'] ?? '';
    
    // Validate required fields
    if (empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing campus information']);
        return;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Delete all courses for the specified campus
        $deleteCoursesStmt = $pdo->prepare("DELETE FROM icbt_courses WHERE campus = ?");
        $deleteCoursesStmt->execute([$campus]);
        
        $deletedCount = $deleteCoursesStmt->rowCount();
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => "Successfully deleted {$deletedCount} courses from the database"
        ]);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Database error in handleClearAllCourses: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeleteProgram() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programName = $_POST['program_name'] ?? '';
    $campus = $_POST['campus'] ?? '';
    
    // Validate required fields
    if (empty($programName) || empty($campus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // First, get the program ID
        $programStmt = $pdo->prepare("SELECT id FROM icbt_programs WHERE program_name = ? AND campus = ?");
        $programStmt->execute([$programName, $campus]);
        
        if ($programStmt->rowCount() === 0) {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Program not found']);
            return;
        }
        
        $program = $programStmt->fetch(PDO::FETCH_ASSOC);
        $programId = $program['id'];
        
        // Delete all courses associated with this program
        $deleteCoursesStmt = $pdo->prepare("DELETE FROM icbt_courses WHERE program_id = ?");
        $deleteCoursesStmt->execute([$programId]);
        $deletedCoursesCount = $deleteCoursesStmt->rowCount();
        
        // Delete the program itself
        $deleteProgramStmt = $pdo->prepare("DELETE FROM icbt_programs WHERE id = ? AND campus = ?");
        $deleteProgramStmt->execute([$programId, $campus]);
        
        if ($deleteProgramStmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode([
                'success' => true, 
                'message' => "Program deleted successfully. {$deletedCoursesCount} courses were also removed."
            ]);
        } else {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Program not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Database error in handleDeleteProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

// NIBM Program and Course Management Functions

function handleAddNIBMProgram() {
    global $pdo;
    
    error_log('handleAddNIBMProgram called with POST data: ' . json_encode($_POST));
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programCode = $_POST['program_code'] ?? '';
    $programName = $_POST['program_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $level = $_POST['level'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Validate required fields
    if (empty($programCode) || empty($programName) || empty($category) || empty($level)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program code already exists
        $checkStmt = $pdo->prepare("SELECT id FROM nibm_programs WHERE program_code = ?");
        $checkStmt->execute([$programCode]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program code already exists']);
            return;
        }
        
        // Insert new program
        $insertStmt = $pdo->prepare("
            INSERT INTO nibm_programs (
                program_code, 
                program_name, 
                category,
                level, 
                duration,
                description,
                campus, 
                status
            ) VALUES (?, ?, ?, ?, ?, ?, 'NIBM', 'Active')
        ");
        
        $insertStmt->execute([
            $programCode,
            $programName,
            $category,
            $level,
            $duration,
            $description
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'NIBM Program added successfully',
                'program_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add program']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddNIBMProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleAddNIBMCourse() {
    global $pdo;
    
    error_log('handleAddNIBMCourse called with POST data: ' . json_encode($_POST));
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseName = $_POST['course_name'] ?? '';
    $programId = $_POST['program_id'] ?? '';
    $courseDescription = $_POST['course_description'] ?? '';
    $requirement = $_POST['requirement'] ?? '';
    $duration = $_POST['duration'] ?? '';
    
    // Validate required fields
    if (empty($courseName) || empty($programId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program exists
        $programStmt = $pdo->prepare("SELECT id FROM nibm_programs WHERE id = ? AND campus = 'NIBM'");
        $programStmt->execute([$programId]);
        
        if ($programStmt->rowCount() === 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program not found']);
            return;
        }
        
        // Insert new course
        $insertStmt = $pdo->prepare("
            INSERT INTO nibm_courses (
                program_id,
                course_name, 
                course_description,
                requirement,
                duration,
                campus, 
                status
            ) VALUES (?, ?, ?, ?, ?, 'NIBM', 'Active')
        ");
        
        $insertStmt->execute([
            $programId,
            $courseName,
            $courseDescription,
            $requirement,
            $duration
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'NIBM Course added successfully',
                'course_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add course']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddNIBMCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeleteNIBMProgram() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programId = $_POST['program_id'] ?? '';
    
    // Validate required fields
    if (empty($programId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing program ID']);
        return;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Delete all courses associated with this program
        $deleteCoursesStmt = $pdo->prepare("DELETE FROM nibm_courses WHERE program_id = ?");
        $deleteCoursesStmt->execute([$programId]);
        $deletedCoursesCount = $deleteCoursesStmt->rowCount();
        
        // Delete the program itself
        $deleteProgramStmt = $pdo->prepare("DELETE FROM nibm_programs WHERE id = ? AND campus = 'NIBM'");
        $deleteProgramStmt->execute([$programId]);
        
        if ($deleteProgramStmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode([
                'success' => true, 
                'message' => "NIBM Program deleted successfully. {$deletedCoursesCount} courses were also removed."
            ]);
        } else {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Program not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Database error in handleDeleteNIBMProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeleteNIBMCourse() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseId = $_POST['course_id'] ?? '';
    
    // Validate required fields
    if (empty($courseId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing course ID']);
        return;
    }
    
    try {
        // Delete the course
        $deleteCourseStmt = $pdo->prepare("DELETE FROM nibm_courses WHERE id = ? AND campus = 'NIBM'");
        $deleteCourseStmt->execute([$courseId]);
        
        if ($deleteCourseStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'NIBM Course deleted successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Course not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleDeleteNIBMCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleAddPeradeniyaProgram() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programCode = $_POST['program_code'] ?? '';
    $programName = $_POST['program_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $level = $_POST['level'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Validate required fields
    if (empty($programCode) || empty($programName) || empty($category) || empty($level)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program code already exists
        $checkStmt = $pdo->prepare("SELECT id FROM peradeniya_programs WHERE program_code = ?");
        $checkStmt->execute([$programCode]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program code already exists']);
            return;
        }
        
        // Insert new program
        $insertStmt = $pdo->prepare("
            INSERT INTO peradeniya_programs (
                program_code, 
                program_name, 
                category,
                level, 
                duration,
                description,
                campus, 
                status
            ) VALUES (?, ?, ?, ?, ?, ?, 'University of Peradeniya', 'Active')
        ");
        
        $insertStmt->execute([
            $programCode,
            $programName,
            $category,
            $level,
            $duration,
            $description
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Peradeniya Program added successfully',
                'program_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add program']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddPeradeniyaProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleAddPeradeniyaCourse() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseName = $_POST['course_name'] ?? '';
    $programId = $_POST['program_id'] ?? '';
    $courseDescription = $_POST['course_description'] ?? '';
    $requirement = $_POST['requirement'] ?? '';
    $duration = $_POST['duration'] ?? '';
    
    // Validate required fields
    if (empty($courseName) || empty($programId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program exists
        $programStmt = $pdo->prepare("SELECT id FROM peradeniya_programs WHERE id = ? AND campus = 'University of Peradeniya'");
        $programStmt->execute([$programId]);
        
        if ($programStmt->rowCount() === 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program not found']);
            return;
        }
        
        // Insert new course
        $insertStmt = $pdo->prepare("
            INSERT INTO peradeniya_courses (
                program_id,
                course_name, 
                course_description,
                requirement,
                duration,
                campus, 
                status
            ) VALUES (?, ?, ?, ?, ?, 'University of Peradeniya', 'Active')
        ");
        
        $insertStmt->execute([
            $programId,
            $courseName,
            $courseDescription,
            $requirement,
            $duration
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Peradeniya Course added successfully',
                'course_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add course']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddPeradeniyaCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeletePeradeniyaProgram() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programId = $_POST['program_id'] ?? '';
    
    // Validate required fields
    if (empty($programId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing program ID']);
        return;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Delete all courses associated with this program
        $deleteCoursesStmt = $pdo->prepare("DELETE FROM peradeniya_courses WHERE program_id = ?");
        $deleteCoursesStmt->execute([$programId]);
        $deletedCoursesCount = $deleteCoursesStmt->rowCount();
        
        // Delete the program itself
        $deleteProgramStmt = $pdo->prepare("DELETE FROM peradeniya_programs WHERE id = ? AND campus = 'University of Peradeniya'");
        $deleteProgramStmt->execute([$programId]);
        
        if ($deleteProgramStmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode([
                'success' => true, 
                'message' => "Peradeniya Program deleted successfully. {$deletedCoursesCount} courses were also removed."
            ]);
        } else {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Program not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Database error in handleDeletePeradeniyaProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeletePeradeniyaCourse() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseId = $_POST['course_id'] ?? '';
    
    // Validate required fields
    if (empty($courseId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing course ID']);
        return;
    }
    
    try {
        // Delete the course
        $deleteCourseStmt = $pdo->prepare("DELETE FROM peradeniya_courses WHERE id = ? AND campus = 'University of Peradeniya'");
        $deleteCourseStmt->execute([$courseId]);
        
        if ($deleteCourseStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Peradeniya Course deleted successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Course not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleDeletePeradeniyaCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

// Moratuwa Program and Course Management Functions

function handleAddMoratuwaProgram() {
    global $pdo;
    
    error_log('handleAddMoratuwaProgram called with POST data: ' . json_encode($_POST));
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programCode = $_POST['program_code'] ?? '';
    $programName = $_POST['program_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $level = $_POST['level'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Validate required fields
    if (empty($programCode) || empty($programName) || empty($category) || empty($level)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program code already exists
        $checkStmt = $pdo->prepare("SELECT id FROM moratuwa_programs WHERE program_code = ?");
        $checkStmt->execute([$programCode]);
        
        if ($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program code already exists']);
            return;
        }
        
        // Insert new program
        $insertStmt = $pdo->prepare("
            INSERT INTO moratuwa_programs (
                program_code, 
                program_name, 
                category,
                level, 
                duration,
                description,
                campus, 
                status
            ) VALUES (?, ?, ?, ?, ?, ?, 'Moratuwa', 'Active')
        ");
        
        $insertStmt->execute([
            $programCode,
            $programName,
            $category,
            $level,
            $duration,
            $description
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Moratuwa Program added successfully',
                'program_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add program']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddMoratuwaProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleAddMoratuwaCourse() {
    global $pdo;
    
    error_log('handleAddMoratuwaCourse called with POST data: ' . json_encode($_POST));
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseName = $_POST['course_name'] ?? '';
    $programId = $_POST['program_id'] ?? '';
    $courseDescription = $_POST['course_description'] ?? '';
    $requirement = $_POST['requirement'] ?? '';
    $duration = $_POST['duration'] ?? '';
    
    // Validate required fields
    if (empty($courseName) || empty($programId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    try {
        // Check if program exists
        $programStmt = $pdo->prepare("SELECT id FROM moratuwa_programs WHERE id = ? AND campus = 'Moratuwa'");
        $programStmt->execute([$programId]);
        
        if ($programStmt->rowCount() === 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Program not found']);
            return;
        }
        
        // Insert new course
        $insertStmt = $pdo->prepare("
            INSERT INTO moratuwa_courses (
                program_id,
                course_name, 
                course_description,
                requirement,
                duration,
                campus, 
                status
            ) VALUES (?, ?, ?, ?, ?, 'Moratuwa', 'Active')
        ");
        
        $insertStmt->execute([
            $programId,
            $courseName,
            $courseDescription,
            $requirement,
            $duration
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Moratuwa Course added successfully',
                'course_id' => $pdo->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add course']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleAddMoratuwaCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeleteMoratuwaProgram() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $programId = $_POST['program_id'] ?? '';
    
    // Validate required fields
    if (empty($programId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing program ID']);
        return;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Delete all courses associated with this program
        $deleteCoursesStmt = $pdo->prepare("DELETE FROM moratuwa_courses WHERE program_id = ?");
        $deleteCoursesStmt->execute([$programId]);
        $deletedCoursesCount = $deleteCoursesStmt->rowCount();
        
        // Delete the program itself
        $deleteProgramStmt = $pdo->prepare("DELETE FROM moratuwa_programs WHERE id = ? AND campus = 'Moratuwa'");
        $deleteProgramStmt->execute([$programId]);
        
        if ($deleteProgramStmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode([
                'success' => true, 
                'message' => "Moratuwa Program deleted successfully. {$deletedCoursesCount} courses were also removed."
            ]);
        } else {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Program not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Database error in handleDeleteMoratuwaProgram: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}

function handleDeleteMoratuwaCourse() {
    global $pdo;
    
    // Get database connection
    $pdo = getDBConnection();
    
    $courseId = $_POST['course_id'] ?? '';
    
    // Validate required fields
    if (empty($courseId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing course ID']);
        return;
    }
    
    try {
        // Delete the course
        $deleteCourseStmt = $pdo->prepare("DELETE FROM moratuwa_courses WHERE id = ? AND campus = 'Moratuwa'");
        $deleteCourseStmt->execute([$courseId]);
        
        if ($deleteCourseStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Moratuwa Course deleted successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Course not found or not authorized to delete']);
        }
        
    } catch (PDOException $e) {
        error_log('Database error in handleDeleteMoratuwaCourse: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    }
}
?>
