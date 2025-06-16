<?php
namespace App\Traits;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

trait Mailer {
    protected function sendVerificationEmail($email, $verificationCode, $type = 'register') {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'oe98515@gmail.com';
            $mail->Password = 'icne nsqu hmyt rswd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('oe98515@gmail.com', 'ElectroWorld App');
            $mail->addAddress($email);

            $mail->isHTML(true);
            
            if ($type === 'register') {
                $mail->Subject = 'Verify Your New Account';
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif;'>
                        <h2>Welcome to Our Store!</h2>
                        <p>Thank you for registering with us. Please use the following verification code to confirm your account:</p>
                        <h3 style='background-color: #f4f4f4; padding: 10px; text-align: center;'>{$verificationCode}</h3>
                        <p>This code will expire in 24 hours.</p>
                        <p>If you didn't create this account, please ignore this email.</p>
                    </div>
                ";
            } else {
                $mail->Subject = 'Password Reset Verification';
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif;'>
                        <h2>Password Reset Request</h2>
                        <p>We received a request to reset your password. Please use the following verification code:</p>
                        <h3 style='background-color: #f4f4f4; padding: 10px; text-align: center;'>{$verificationCode}</h3>
                        <p>This code will expire in 15 minutes.</p>
                        <p>If you didn't request a password reset, please ignore this email.</p>
                    </div>
                ";
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function generateVerificationCode() {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
} 