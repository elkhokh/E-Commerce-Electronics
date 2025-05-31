<?php

namespace App\controller;

use App\Wishlist;
use App\Massage;
use PDO;

class wishlist_controller
{
    

    public static function handler(PDO $db)
    {
        
        if (!isset($_SESSION['user'])) {
            Massage::set_Massages("error", "Please login first");
            header('Location: index.php?page=Login');
            exit;
        }

        $user_id = (int) $_SESSION['user']['id'];
        $product_id = htmlspecialchars(trim($_GET['id']));
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'add':
                if (Wishlist::add($db, $user_id, $product_id)) {
                    Massage::set_Massages("success", "Product added to wishlist successfully");
                    header('Location: index.php?page=product_details&id='.$product_id);
                } else {
                    Massage::set_Massages("danger", "product is already wishlist");
                    header('Location: index.php?page=product_details&id='.$product_id);
                }
                break;
            case 'remove':
                if (Wishlist::remove($db, $user_id, $product_id)) {
                    Massage::set_Massages("success", "Product removed from wishlist successfully");
                    header('Location: index.php?page=product_details&id='.$product_id);
                } else {
                    Massage::set_Massages("danger", "Failed to remove product from wishlist");
                    header('Location: index.php?page=product_details&id='.$product_id);
                }
                break;
        }
    }
}


wishlist_controller::handler($db);
