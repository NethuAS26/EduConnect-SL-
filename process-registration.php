<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$action = $input['action'] ?? '';
$registration_id = $input['registration_id'] ?? '';
$admin_notes = $input['admin_notes'] ?? '';

// Validate input
if (!in_array($action, ['approve', 'reject']) || empty($registration_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid action or registration ID']);
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Get registration details
    $stmt = $pdo->prepare("
        SELECT cr.*, s.first_name, s.last_name, s.email, s.phone 
        FROM course_registrations cr 
        JOIN students s ON cr.student_id = s.id 
        WHERE cr.id = ?
    ");
    $stmt->execute([$registration_id]);
    $registration = $stmt->fetch();
    
    if (!$registration) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Registration not found']);
        exit();
    }
    
    // Check if admin has permission for this university
    $admin_campus = $_SESSION['campus'];
    if (strpos($registration['university_name'], $admin_campus) === false) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'You do not have permission to process this registration']);
        exit();
    }
    
    // Update registration status
    $new_status = ($action === 'approve') ? 'approved' : 'rejected';
    $stmt = $pdo->prepare("
        UPDATE course_registrations 
        SET status = ?, admin_notes = ?, admin_id = ?, admin_response_date = CURRENT_TIMESTAMP 
        WHERE id = ?
    ");
    $stmt->execute([$new_status, $admin_notes, $_SESSION['admin_id'], $registration_id]);
    
    // Send email notification to student
    $email_sent = sendEmailNotification($registration, $new_status, $admin_notes);
    
    // Prepare response
    $response = [
        'success' => true,
        'message' => "Application successfully " . $new_status,
        'email_sent' => $email_sent
    ];
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}

/**
 * Send email notification to student
 */
function sendEmailNotification($registration, $status, $admin_notes) {
    try {
        $to = $registration['email'];
        $subject = "Course Registration " . ucfirst($status) . " - EduConnect SL";
        
        $status_text = ($status === 'approved') ? 'approved' : 'rejected';
        $status_color = ($status === 'approved') ? '#10b981' : '#ef4444';
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
                .status-badge { 
                    display: inline-block; 
                    padding: 8px 16px; 
                    background: {$status_color}; 
                    color: white; 
                    border-radius: 20px; 
                    font-weight: bold; 
                    margin: 10px 0; 
                }
                .course-info { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>EduConnect SL</h1>
                    <p>Course Registration Update</p>
                </div>
                
                <div class='content'>
                    <h2>Hello {$registration['first_name']} {$registration['last_name']},</h2>
                    
                    <p>Your course registration has been <strong>{$status_text}</strong>.</p>
                    
                    <div class='status-badge'>
                        " . ucfirst($status) . "
                    </div>
                    
                    <div class='course-info'>
                        <h3>Course Details:</h3>
                        <p><strong>Course:</strong> {$registration['course_name']}</p>
                        <p><strong>University:</strong> {$registration['university_name']}</p>
                        <p><strong>Department:</strong> {$registration['department_name']}</p>
                        <p><strong>Program:</strong> {$registration['program_name']}</p>
                        <p><strong>Application Date:</strong> " . date('F j, Y', strtotime($registration['registration_date'])) . "</p>
                    </div>";
        
        if (!empty($admin_notes)) {
            $message .= "
                    <div class='course-info'>
                        <h3>Admin Notes:</h3>
                        <p>{$admin_notes}</p>
                    </div>";
        }
        
        if ($status === 'approved') {
            $message .= "
                    <p><strong>Next Steps:</strong></p>
                    <ul>
                        <li>You will receive further instructions from the university</li>
                        <li>Please check your email regularly for updates</li>
                        <li>Contact the university if you have any questions</li>
                    </ul>";
        } else {
            $message .= "
                    <p><strong>What This Means:</strong></p>
                    <ul>
                        <li>Your application was not approved at this time</li>
                        <li>You may be able to apply for other courses</li>
                        <li>Contact the university for more information</li>
                    </ul>";
        }
        
        $message .= "
                    
                    <p>If you have any questions, please don't hesitate to contact us.</p>
                    
                    <p>Best regards,<br>
                    <strong>The EduConnect SL Team</strong></p>
                </div>
                
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>&copy; 2024 EduConnect SL. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";
        
        // Email headers
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: EduConnect SL <noreply@educonnectsl.lk>',
            'Reply-To: info@educonnectsl.lk',
            'X-Mailer: PHP/' . phpversion()
        ];
        
        // Send email
        $mail_sent = mail($to, $subject, $message, implode("\r\n", $headers));
        
        if (!$mail_sent) {
            error_log("Failed to send email to: " . $to);
        }
        
        return $mail_sent;
        
    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}
?>
