<?php

use App\Massage;
use App\User;
use App\Validate;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   $email=htmlspecialchars( trim($_POST['email']));
   $password=htmlspecialchars(trim($_POST['password'])) ;
   $role=$_POST['role'];


    $error = Validate::validate_Login( $email,$password);
    if (!empty($error)) {
        Massage::set_Massages("danger", $error);
        if ($role==='user') {
        header('Location:./index.php?page=Login'); 
        exit;   
        }
        header('Location:./index.php?page=admin'); 
        exit; 
        
    }


    if (User::login_user( $db,$email, $password)) {
        Massage::set_Massages("success", "Login user successfully");
        header('Location:./index.php?page=home ');
        exit;
    } else {

        Massage::set_Massages("danger", "invalid Email or Password ");
        if ($role==='user') {
        header('Location:./index.php?page=Login'); 
        exit;   
        }
        header('Location:./index.php?page=admin'); 
        exit; 
        
    }
        
      

}