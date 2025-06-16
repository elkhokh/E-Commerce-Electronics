<?php

use App\Massage;
use App\User;
use App\Validate;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));

    if (empty($email)) {
        Massage::set_Massages("danger", "Please enter your email address");
        header('Location:./index.php?page=forget_password');
        exit;
    }

    if (!User::find_by_email($db, $email)) {
        Massage::set_Massages("danger", "Email not found");
        header('Location:./index.php?page=forget_password');
        exit;
    }

    if (User::request_password_reset($db, $email)) {
        Massage::set_Massages("success", "Verification code has been sent to your email");
        header('Location:./index.php?page=reset_password&email=' . urlencode($email));
        exit;
    } else {
        Massage::set_Massages("danger", "Failed to send verification code. Please try again.");
        header('Location:./index.php?page=forget_password');
        exit;
    }
} 