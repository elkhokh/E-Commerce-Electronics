<?php

use App\Massage;
use App\User;
use App\Validate;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $verification_code = htmlspecialchars(trim($_POST['verification_code']));
    $new_password = htmlspecialchars(trim($_POST['new_password']));

    $error = Validate::validate_change_password($email, $new_password);
    if (!empty($error)) {
        Massage::set_Massages("danger", $error);
        header('Location:./index.php?page=forget_password');
        exit;
    }
    // var_dump(User::reset_password($db, $email, $verification_code, $new_password));
    // exit;
    
    if (User::reset_password($db, $email, $verification_code, $new_password)) {
        Massage::set_Massages("success", "Password changed successfully. You can now login with your new password.");
        header('Location:./index.php?page=Login');
        exit;
    } else {
        Massage::set_Massages("danger", "Invalid or expired verification code. Please try again.");
        header('Location:./index.php?page=forget_password');
        exit;
    }
}