<?php

use App\Massage;
use App\User;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $code = htmlspecialchars(trim($_POST['verification_code']));

    if (empty($email) || empty($code)) {
        Massage::set_Massages("danger", "Please enter the verification code");
        header('Location: index.php?page=verify_email&email=' . urlencode($email));
        exit;
    }

    if (User::verify_email($db, $email, $code)) {
        Massage::set_Massages("success", "Email verified successfully! You can now login.");
        header('Location: index.php?page=Login');
        exit;
    } else {
        Massage::set_Massages("danger", "Invalid or expired verification code");
        header('Location: index.php?page=verify_email&email=' . urlencode($email));
        exit;
    }
} 