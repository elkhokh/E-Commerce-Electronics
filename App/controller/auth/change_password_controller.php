<?php

use App\Massage;
use App\User;
use App\Validate;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   $email=htmlspecialchars( trim($_POST['email']));
   $new_password=htmlspecialchars(trim($_POST['new_password'])) ;


    $error = Validate::validate_change_password( $email,$new_password);
    if (!empty($error)) {
        Massage::set_Massages("danger", $error);
        header('Location:./index.php?page=forget_password');
        exit;
    }


    if (User::change_password( $db,$email, $new_password)) {
        Massage::set_Massages("success", "Change Password successfully");
        header('Location:./index.php?page=Login ');
        exit;
    } else {
        Massage::set_Massages("danger", "Change Password Fail ");
        header('Location:./index.php?page=forget_password ');
        exit;
    }

}