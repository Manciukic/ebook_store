<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'includes/vendor/PHPMailer/src/Exception.php';
require 'includes/vendor/PHPMailer/src/PHPMailer.php';
require 'includes/vendor/PHPMailer/src/SMTP.php';

require_once "includes/settings.php";

function sendmail($to, $subject, $message){
    global $SMTP_HOST, $SMTP_USER, $SMTP_PWD, $MAIL_FROM_NAME;

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $SMTP_HOST;                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $SMTP_USER;                     // SMTP username
        $mail->Password   = $SMTP_PWD;                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom($SMTP_USER, $MAIL_FROM_NAME);
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>