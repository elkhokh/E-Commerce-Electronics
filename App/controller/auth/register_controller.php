<?php

use App\Massage;
use App\User;
use App\Validate;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   $name=htmlspecialchars(trim($_POST['name'])) ;
   $email=htmlspecialchars( trim($_POST['email']));
   $password=htmlspecialchars(trim($_POST['password'])) ;


    $error = Validate::validateRegister($name, $email,$password);
    if (!empty($error)) {
        Massage::set_Massages("danger", $error);
        header('Location:./index.php?page=register');
        exit;
    }   
    if (User::find_by_email($db, $email)) {
        Massage::set_Massages("danger", "Email already exists");
        header("Location: index.php?page=create_user");
        exit;
    }

    if (User::register( $db,$name,$email, $password)!==null) {
        Massage::set_Massages("success", "Register user successfully");
        header('Location:./index.php?page=home ');
        exit;
    } else {
        Massage::set_Massages("danger", "Register user Fail ");
        header('Location:./index.php?page=register ');
        exit;
    }

}
