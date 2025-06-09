<?php

namespace App\controller;

use App\Contact_us;
use App\Massage;
use App\Validate;
use PDO;

class Contact_us_controller {


    public static function handler(PDO $db)
    {
        if (!isset($_SESSION['user'])) {
            Massage::set_Massages("danger","Please login to add a Reply" );
            header("Location: index.php?page=Login");
            exit;
        }
        if (!$_GET['action']) {
            Massage::set_Massages("danger","NO action" );
            header("Location: index.php?page=home");
            exit;
        }


        $user_id =(int) $_SESSION['user']['id'];
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'add':
                self::addMassage($db);
               break;
 
       }
    }

    static public function addMassage($db): void {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $message = htmlspecialchars(trim($_POST['message']));

            if ($error=Validate::validate_massage($name,$email,$message)) {
                Massage::set_Massages("danger",$error );
                header("Location: index.php?page=contact_us");
                exit;
            }

            if (Contact_us::create( $db,  $name,  $email,  $message)) {
                Massage::set_Massages("success","Message added successfully" );
            } else {
                Massage::set_Massages("danger","Failed to add Message" );
            }
        }

        header("Location: index.php?page=contact_us");
        exit;
    }


   
}



Contact_us_controller::handler($db);
