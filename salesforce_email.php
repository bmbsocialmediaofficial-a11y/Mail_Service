<?php
// salesforce_email.php
header('Content-Type: application/json');

// Get JSON data from Salesforce
 $input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($input['to']) || !isset($input['body'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields (to/body)']);
    exit;
}

// Extract data
 $toEmail = $input['to'];
 $subject = $input['subject'] ?? 'No Subject';
 $htmlBody = $input['body'];
 $fromName = $input['fromName'] ?? 'OMS Support';

// Send email using PHPMailer
try {
    // Include your existing PHPMailer files
   require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
    require_once __DIR__ . '/mail_cred.php'; 
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    // --- FIX: Force UTF-8 Encoding ---
    $mail->CharSet = 'UTF-8';
    // -------------------------------
    
    // Use your SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtpout.secureserver.net';
    $mail->SMTPAuth = true;
    $mail->Username = $mail_username;
    $mail->Password = $mail_password;
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Set email details
    $mail->setFrom('support@buymeabook.co.in', $fromName);
    $mail->addAddress($toEmail);
    
    $mail->Subject = $subject;
    $mail->Body = $htmlBody;
    $mail->isHTML(true); 
    
    if ($mail->send()) {
        echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'PHPMailer send failed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>