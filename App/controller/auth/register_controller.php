<?php

use App\Massage;
use App\User;
use App\Validate;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    $error = Validate::validateRegister($name, $email, $password);
    if (!empty($error)) {
        Massage::set_Massages("danger", $error);
        header('Location:./index.php?page=register');
        exit;
    }   
    
    if (User::find_by_email($db, $email)) {
        Massage::set_Massages("danger", "Email already exists");
        header("Location: index.php?page=register");
        exit;
    }

    $user = User::register($db, $name, $email, $password);
    if ($user !== null) {
        Massage::set_Massages("success", "Registration successful! Please check your email to verify your account.");
        header('Location:./index.php?page=verify_email&email=' . urlencode($email));
        exit;
    } else {
        Massage::set_Massages("danger", "Registration failed. Please try again.");
        header('Location:./index.php?page=register');
        exit;
    }
}
