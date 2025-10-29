<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? 'Not provided');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        http_response_code(400);
        echo "Please fill all fields.";
        exit;
    }

    $mail = new PHPMailer(true);
    try {
        // ======================================================
        // GMAIL SMTP CONFIGURATION (✅ fixed version)
        // ======================================================
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bhaihassan278@gmail.com'; // your Gmail
        $mail->Password   = 'syjdciuelkwzxpqa';        // Gmail App Password (no spaces)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL (fixes timeout issue)
        $mail->Port       = 465;                        // SSL port

        // Optional: increase timeout just in case
        $mail->Timeout = 20;
        $mail->SMTPKeepAlive = true;

        // Sender & recipient
        $mail->setFrom('bhaihassan278@gmail.com', 'Portfolio Contact');
        $mail->addAddress('bhaihassan278@gmail.com');
        $mail->addReplyTo($email, $name);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "New message from $name via Portfolio Contact Form";
        $mail->Body = "
            <div style='font-family:Arial, sans-serif; background:#f9f9f9; padding:20px;'>
                <div style='max-width:600px; margin:auto; background:#fff; border-radius:8px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.1);'>
                    <h2 style='color:#333;'>📩 New Contact Message</h2>
                    <p><strong>Name:</strong> {$name}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Phone:</strong> {$phone}</p>
                    <hr style='border:none; border-top:1px solid #ddd; margin:20px 0;'>
                    <p><strong>Message:</strong></p>
                    <p style='white-space:pre-wrap; color:#555;'>{$message}</p>
                    <hr style='border:none; border-top:1px solid #ddd; margin:20px 0;'>
                    <p style='font-size:12px; color:#999;'>This message was sent from your portfolio contact form.</p>
                </div>
            </div>
        ";
        $mail->AltBody = "New message from $name\n\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";

        // ======================================================
        // SEND EMAIL
        // ======================================================
        $mail->send();
        echo 'OK';

    } catch (Exception $e) {
        http_response_code(500);
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(405);
    echo "Invalid request.";
}
?>
