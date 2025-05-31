<?php

namespace App\controller\Cart;

use App\Cart;
use App\Discount;
use App\Massage;

class discount_controller {
    public static function handler($db) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::apply_discount($db);
        }
    }

    private static function apply_discount($db) {
        if (!isset($_SESSION['user']['id'])) {
            Massage::set_Massages("error", "Please login first ");
            header('Location: index.php?page=Login');
            exit;
        }

        if (!isset($_POST['discount_code']) || empty($_POST['discount_code'])) {
            Massage::set_Massages("error", "Please set code");
            header('Location: index.php?page=Cart');
            exit;
        }

        $discount_code = trim($_POST['discount_code']);
        $discount = Discount::find_by_code($db, $discount_code);
        //   var_dump($discount->is_valid());
        //   exit;
        if (!$discount || !$discount->is_valid() ) {
            Massage::set_Massages("error", "Invalid code ");
            header('Location: index.php?page=Cart');
            exit;
        }
     
        $cart = new Cart($_SESSION['user']['id']);
        $cart->load($db);

        if ($cart->getItemsCount() == 0) {
            Massage::set_Massages("error", " The Cart Is Empty");
            header('Location: index.php?page=Cart');
            exit;
        }

        $_SESSION['discount_amount'] = $discount->calculate_discount($cart->getFinalTotal());
          $discount->delete($db);
        Massage::set_Massages("success", "Discount complete successfully");
        header('Location: index.php?page=Cart');
        exit;
    }

}


discount_controller::handler($db);
