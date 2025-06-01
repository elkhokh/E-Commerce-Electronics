<?php

namespace App\controller\product;

use App\Product;    
use App\Wishlist;
use App\Massage;
use PDO;
class ProductController {



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
              
                break;
            case 'remove':
                break;
            case 'search':

        }
    }
  
   
} 
    
    ProductController::handler($db);
