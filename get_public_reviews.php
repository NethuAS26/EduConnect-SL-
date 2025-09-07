<?php
session_start();
require_once 'config.php';

// Set proper headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Connect to database
    $pdo = getDBConnection();
    
    // Get query parameters
    $university = $_GET['university'] ?? '';
    $rating = $_GET['rating'] ?? '';
    $sort = $_GET['sort'] ?? 'recent';
    $page = intval($_GET['page'] ?? 1);
    $limit = intval($_GET['limit'] ?? 10);
    $offset = ($page - 1) * $limit;
    
    // Build the base query
    $query = "
        SELECT r.*, s.first_name, s.last_name 
        FROM reviews r 
        LEFT JOIN students s ON r.student_id = s.id 
        WHERE r.status = 'approved'
    ";
    $params = [];
    
    // If user is logged in, also show their pending reviews
    if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'student') {
        $query = "
            SELECT r.*, s.first_name, s.last_name 
            FROM reviews r 
            LEFT JOIN students s ON r.student_id = s.id 
            WHERE r.status = 'approved' OR (r.student_id = ? AND r.status = 'pending')
        ";
        $params[] = $_SESSION['user_id'];
    }
    
    // Add university filter
    if (!empty($university) && $university !== 'all') {
        $query .= " AND r.university_name = ?";
        $params[] = $university;
    }
    
    // Add rating filter
    if (!empty($rating) && $rating !== 'all') {
        $query .= " AND r.rating >= ?";
        $params[] = intval($rating);
    }
    
    // Add sorting
    switch ($sort) {
        case 'rating':
            $query .= " ORDER BY r.rating DESC, r.created_at DESC";
            break;
        case 'oldest':
            $query .= " ORDER BY r.created_at ASC";
            break;
        case 'recent':
        default:
            $query .= " ORDER BY r.created_at DESC";
            break;
    }
    
    // Add pagination
    $query .= " LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    // Execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total count for pagination
    $countQuery = "
        SELECT COUNT(*) as total 
        FROM reviews r 
        WHERE r.status = 'approved'
    ";
    $countParams = [];
    
    // If user is logged in, also count their pending reviews
    if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'student') {
        $countQuery = "
            SELECT COUNT(*) as total 
            FROM reviews r 
            WHERE r.status = 'approved' OR (r.student_id = ? AND r.status = 'pending')
        ";
        $countParams[] = $_SESSION['user_id'];
    }
    
    if (!empty($university) && $university !== 'all') {
        $countQuery .= " AND r.university_name = ?";
        $countParams[] = $university;
    }
    
    if (!empty($rating) && $rating !== 'all') {
        $countQuery .= " AND r.rating >= ?";
        $countParams[] = intval($rating);
    }
    
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute($countParams);
    $totalCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get overall statistics
    $statsQuery = "
        SELECT 
            COUNT(*) as total_reviews,
            AVG(rating) as average_rating,
            SUM(CASE WHEN recommend_or_not_recommended = 'yes' THEN 1 ELSE 0 END) as recommended_count,
            COUNT(DISTINCT university_name) as university_count
        FROM reviews 
        WHERE status = 'approved'
    ";
    
    // If user is logged in, include their pending reviews in stats
    if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'student') {
        $statsQuery = "
            SELECT 
                COUNT(*) as total_reviews,
                AVG(rating) as average_rating,
                SUM(CASE WHEN recommend_or_not_recommended = 'yes' THEN 1 ELSE 0 END) as recommended_count,
                COUNT(DISTINCT university_name) as university_count
            FROM reviews 
            WHERE status = 'approved' OR (student_id = ? AND status = 'pending')
        ";
        $stmt = $pdo->prepare($statsQuery);
        $stmt->execute([$_SESSION['user_id']]);
    } else {
        $stmt = $pdo->prepare($statsQuery);
        $stmt->execute();
    }
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Format the response
    $response = [
        'success' => true,
        'reviews' => $reviews,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => ceil($totalCount / $limit),
            'total_count' => $totalCount,
            'has_more' => ($page * $limit) < $totalCount
        ],
        'stats' => [
            'total_reviews' => (int)$stats['total_reviews'],
            'average_rating' => round((float)$stats['average_rating'], 1),
            'recommend_percentage' => $stats['total_reviews'] > 0 ? 
                round(($stats['recommended_count'] / $stats['total_reviews']) * 100) : 0,
            'university_count' => (int)$stats['university_count']
        ]
    ];
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    error_log("Database error in get_public_reviews.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    error_log("General error in get_public_reviews.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred']);
    exit;
}
?>
